<?php

namespace App\Services\Absensi\Crawler;

use Illuminate\Database\QueryException;
use App\Services\IHCS\Foundation\Curly;
use Log;
use DB;

class GetAttLog {
	
	use Curly;

	private $body;

	function __construct()
	{
		ini_set('max_execution_time', 600);
	}
	

	public function saveAttLog()
	{
		$listUser = $this->get_user_info();

		foreach (att_machine_list() as $machine) {			
			// try {
				$fp = fsockopen($machine, 80, $errno, $errstr, 30);

				if (!$fp) {
					Log::error(__METHOD__ . " $errstr ($errno)<br />");
					// return;
				}

				foreach ($listUser as $pin) {
					Log::info("Get AttLog PIN:{$pin}");
					$this->get_att_log($machine, $pin);
				}
			/*} catch (\ErrorException $e) {
				Log::error(__METHOD__ . " >> ". $e->getMessage());
			} catch (\Exception $e) {
				Log::error(__METHOD__ . " >> ". $e->getMessage());				
			}*/
		}
	}

	private function get_att_log($machine, $pin) {
		$url = "http://{$machine}/iWsService";
		$input = "<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">0</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">{$pin}</PIN></Arg></GetAttLog>";
		$this->curl($url, 'xmlRequest=' . $input, '', [ "Content-Type: text/xml"]);		
		// dd(xml_to_array($this->body)['Row']);
		if(isset(xml_to_array($this->body)['Row'])) {
			return $this->store_att_logs($machine, xml_to_array($this->body)['Row']);
		}
	}

	private function store_att_logs($machine, $rows)
	{
		// Log::info("Before generate_att_logs_insert_query , ");
		// Log::info($rows);
		$query = $this->generate_att_logs_insert_query($machine, $rows);
		if(is_null($query) || empty($query)) {
			Log::error(__METHOD__ . " Gagal men-generate query penarikan data Absensi dari mesin");
		} else {
			DB::beginTransaction();
			try {
				DB::connection("att_service")->statement($query);
				DB::commit();			
				Log::info("penarikan data Absensi dari mesin berhasil dilakukan");
			} catch (QueryException $e) {
				DB::rollBack();			
			}
		}
	}

	private function generate_att_logs_insert_query($machine, $rows) {
		$query = '';
		$rowss = [];

		if(isset($rows['PIN']))
			$rows = [$rows];

		foreach($rows as $i => $att) {
			if((int) date('Ym', strtotime($att['DateTime'])) >= 201812) {
				// Log::info($att['PIN']);
				array_push($rowss, $att);
			}
		}

		foreach($rowss as $i => $att) {
			if($i > 0) {
				$query .= ' union all ';
			}
			$query .= "select '{$att['PIN']}' as PIN, '{$att['DateTime']}' as DateTime,'{$att['Verified']}' as Verified, '{$att['Status']}' as Status,'{$att['WorkCode']}' as WorkCode, '{$machine}' as machine,'".now()."' as created_at";
		}
		
		$queryInsert = "insert into AttLog (PIN, DateTime, Verified, Status, WorkCode, machine, created_at) ";
		$query = "select * from (" . $query .") a where not exists (select * from AttLog b where a.PIN = b.PIN and a.DateTime = b.DateTime and a.Verified = b.Verified and a.WorkCode = b.WorkCode)";
		return $queryInsert . $query;
	}

	public function get_user_info($pin = 'ALL'):array {
		$input = "<GetUserInfo><ArgComKey xsi:type=\"xsd:integer\">0</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">{$pin}</PIN></Arg></GetUserInfo>";
		$this->curl("http://10.20.4.9/iWsService", 'xmlRequest=' . $input, '', [ "Content-Type: text/xml"]);

		$atts = [];
		foreach(xml_to_array($this->body)['Row'] as $user) {
			if(!is_null($user['Name']) || !empty($user['Name'])) {
				array_push($atts, $user['PIN2']);				
			}
		}
		return $atts;
	}

	public function get_user_info_new($pin = 'ALL'):array {
		$input = "<GetUserInfo><ArgComKey xsi:type=\"xsd:integer\">0</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">{$pin}</PIN></Arg></GetUserInfo>";
		$this->curl("http://10.20.4.9/iWsService", 'xmlRequest=' . $input, '', [ "Content-Type: text/xml"]);

		return (xml_to_array($this->body)['Row']);
	}

	public function get_log_absensi($ip_mesin, $pin = 'ALL'):array {
		$input = "<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">0</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">{$pin}</PIN></Arg></GetAttLog>";
		$this->curl("http://{$ip_mesin}/iWsService", 'xmlRequest=' . $input, '', [ "Content-Type: text/xml"]);

		return (xml_to_array($this->body)['Row']);
	}


}
