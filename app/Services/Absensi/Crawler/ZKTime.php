<?php

namespace App\Services\Absensi\Crawler;

use Illuminate\Support\Facades\DB;
use App\Jobs\TelegramJob;
use App\Jobs\GenerateAttSummaryJob;

class ZKTime {
	private $ip;
	private $port = 80;
	private $machineList;

	function __construct($ip) {
		$this->ip = $ip;
		$this->machineList = att_machine_list();
	}

	public function connect() {
		try {
			$conn = fsockopen($this->ip, $this->port, $errno, $errstr, 30);
			if(!$conn) {
				\Log::error($errstr);
				throw new \Exception("🚫 Koneksi ke mesin abensi {$this->ip} gagal");
				
			}
			$this->_checkErrorMesin($this->ip);
			\Log::info("✅ Koneksi ke mesin absensi {$this->ip} berhasil.");
			return $conn;
		} catch (\Exception $e) {

			$machineName = array_search ($this->ip, $this->machineList);
			$pesanError = "🚫 Koneksi ke mesin <b>$machineName (" . $this->ip . ")</b> gagal, silahkan menghubungi bagian IT.";
			
			$hasMachineError = DB::table('tr_log_koneksi_mesin_absensi')->where('status', 0)->where('ip_address', $this->ip)->where('date', date('Y-m-d'))->count() > 0;
			if(!$hasMachineError) {
				$this->_logToDB($this->ip);
				TelegramJob::dispatch($pesanError);
			}

			\Log::error($pesanError);
			return null;
		}
	}

	public function disconnect($conn) {
		if(!feof($conn))
			return fclose ($conn);
	}

	public function getUserInfo($pin = 'ALL') {
		$conn = $this->connect();
		if(!is_null($conn)) {
			$soap_request = "<GetUserInfo><ArgComKey xsi:type=\"xsd:integer\">0</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">{$pin}</PIN></Arg></GetUserInfo>";
			$buffer = $this->handle($conn, $soap_request);

			$buffer= $this->parse($buffer, "<GetUserInfoResponse>" , "</GetUserInfoResponse>");
			$buffer = explode("\r\n" , $buffer);
			
			$result = [];

			foreach($buffer as $buff) {
				if($buff) {
					array_push($result, xml_to_array($buff));
				}
			}
			$this->disconnect($conn);

			return  $result;
		}

		return null;
	}

	public function getAttLog($pin = 'ALL') {
		$conn = $this->connect();
		if(!is_null($conn)) {
			$soap_request="<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">0</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">{$pin}</PIN></Arg></GetAttLog>";
			$buffer = $this->handle($conn, $soap_request);

			$buffer= $this->parse($buffer, "<GetAttLogResponse>" , "</GetAttLogResponse>");
			$buffer = explode("\r\n" , $buffer);
			
			$result = [];

			foreach($buffer as $buff) {
				if($buff) {
					$_buff = xml_to_array($buff);
					$_buff['Machine'] = $this->ip;
					array_push($result, $_buff);
				}
			}
			$this->disconnect($conn);

			\Log::info("✅ Laporan absensi mesin {$this->ip} sebanyak : ". count($result) . " record");

			return  $result;
		}
		return null;
	}

	public function restart() {
		$conn = $this->connect();
		if(!is_null($conn)) {
			$soap_request="<Restart><ArgComKey xsi:type=\"xsd:integer\">ComKey</ArgComKey></Restart>";
			$buffer = $this->handle($conn, $soap_request);

			$buffer= $this->parse($buffer, "<RestartResponse>" , "</RestartResponse>");
			$buffer = explode("\r\n" , $buffer);
			
			$result = [];

			foreach($buffer as $buff) {
				if($buff) {
					array_push($result, xml_to_array($buff));
				}
			}
			$this->disconnect($conn);

			return  $result;
		}
		return null;
	}

	public function handle($conn, $soap_request) {
		$newLine ="\r\n";
		fputs($conn, "POST /iWsService HTTP/1.0" . $newLine);
		fputs($conn, "Content-Type: text/xml" . $newLine);
		fputs($conn, "Content-Length: " . strlen($soap_request) . $newLine . $newLine);
		fputs($conn, $soap_request . $newLine);

		$buffer = "";
		while($response = fgets($conn, 1024)){
			$buffer = $buffer . $response;
		}

		return $buffer;
	}

	public function parse($data, $p1, $p2) {
		$data =" " . $data;
		$hasil = "";
		$begin = strpos($data, $p1);
		if($begin != "") {
			$end = strpos(strstr($data, $p1), $p2);
			if($end != ""){
				$hasil = substr($data, $begin + strlen($p1) , $end - strlen($p1));
			}
		}
		return $hasil;	
	}

	public function saveUniqueUserInfo() {		
		$rows = collect($this->getUserInfo());

		if(!is_null($rows)) {
			$userInfo = DB::connection('N4_ZKTime')->table('UserInfo');
			$rows = $rows->map(function($row) {
				return collect($row)->only(['PIN','Name','Group','Privilege','Card','PIN2',	'TZ1',	'TZ2', 'TZ3']);
			});

			DB::connection('N4_ZKTime')->beginTransaction();
			try {
				foreach ($rows as $row) {
					$userInfo->insert($row->toArray());
				}
				DB::connection('N4_ZKTime')->commit();
			} catch (\Exception $e) {
				\Log::error($e->getMessage());
				DB::connection('N4_ZKTime')->rollBack();				
			}
		}
	}

	public function saveUniqueAttLog() {		
		$rows = collect($this->getAttLog());

		if(!is_null($rows)) {			
			DB::connection('N4_ZKTime')->beginTransaction();
			$userInfo = DB::connection('N4_ZKTime')->table('AttLog');
			try {
				foreach ($rows as $row) {
					$userInfo->insert($row);
				}
				DB::connection('N4_ZKTime')->commit();
			} catch (\Exception $e) {
				DB::connection('N4_ZKTime')->rollBack();
				\Log::error($e->getMessage());
			}
		}
	}

	private function _logToDB($ip_address) {
		try {
			DB::statement("exec sp_log_error_mesin_ke_db ?", [$ip_address]);			
		} catch (Exception $e) {
			\Log::error("ZKTime gagal menyimpan log error mesin.\n" . $e->getMessage());
		}
	}

	private function _checkErrorMesin($ip_address) {
		$isErrorExists = DB::table('tr_log_koneksi_mesin_absensi')
		->where('ip_address', $ip_address)
		->where('status', 0)
		->whereRaw("[date] = convert(date, getdate())")
		->count();

		if($isErrorExists > 0) {
			$dates = DB::table('tr_log_koneksi_mesin_absensi')->distinct()->select('date')->where('status', 0)->get();
			foreach ($dates as $errorMesin) {
				GenerateAttSummaryJob::dispatch(date('Y-m-d', strtotime($errorMesin->date)), 'ZKTimeDisconnectedMachineResolver');
				
				DB::table('tr_log_koneksi_mesin_absensi')
				->where('date', date('Y-m-d', strtotime($errorMesin->date)))
				->update([
					'status' => 1,
					'updated_at' => date('Y-m-d H:i:s')
				]);

				$machineName = array_search ($this->ip, $this->machineList);
				$pesanInfo = "Sistem E-Absensi mengenerate ulang summary absensi tgl:{$errorMesin->date} karena mesin <b>$machineName (" . $this->ip . ")</b> baru saja terkoneksi.";
				TelegramJob::dispatch($pesanInfo);
				
				\Log::info("ZKTimeDisconnectedMachineResolver >>> Menambah job 'GenerateAttSummaryJob', tgl:{$errorMesin->date} karena mesin:{$ip_address} baru saja terkoneksi.");
			}
		}
	}
	
}
