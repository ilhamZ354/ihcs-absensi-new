<?php

use App\Services\IHCS\Controllers\TelegramController as Telegram;

if(!function_exists('get_log_files')) {
	function get_log_files()
	{
		$path = storage_path('logs');
		$dir = scandir($path);
		$logs = [];
		foreach ($dir as $filename) {
			if(substr($filename, -3) == 'log') {
				array_push($logs, $path . DIRECTORY_SEPARATOR . $filename);
			}
		}
		return $logs;
	}
}

if(!function_exists('lockStatusToStr')) {
	function lockStatusToStr($intStatus)
	{
		if($intStatus == 1) {
			return '<i class="fa fa-unlock fa-1x" style="color:#4ec59f;" title="Dibuka"></i>';
		} 
		else {
			return '<i class="fa fa-lock fa-1x" style="color:#ff6a00;" title="Dikunci"></i>';
		}
	}
}

if(!function_exists('read_log_file')) {
	function read_log_file($file_path)
	{
		$logLines = [];

		$handle = fopen($file_path, "r");
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				array_push($logLines, $line);
			}
			fclose($handle);
		}

		return $logLines;
	}
}

if(!function_exists('get_log_filename')) {
	function get_log_filename($file_path)
	{
		$stuff = explode('\\', $file_path);
		return $stuff[count($stuff) -1];
	}
}

if(!function_exists('is_new_error')) {
	function is_new_error($line)
	{
		preg_match('/\[[\d]+\-\d+\-\d+\s\d+\:\d+\:\d+\]/', substr($line, 0, 21), $matches, PREG_OFFSET_CAPTURE);
		return !empty($matches);
	}
}

if(!function_exists('isAllow')) {
	function isAllow($action, $slug)
	{
		if(!allow($action, $slug)) {
			throw new App\Services\Absensi\Exceptions\AccessPermissionDeniedException("Anda tidak punya akses ke halaman ini", 1);
		}
		return true;
	}
}

if(!function_exists('allow')) {
	function allow($action, $slug)
	{		
		$listGroup = collect(Auth::user()->getGroups())->first();
		if ($listGroup){
			$kode_group = $listGroup->kode;

			$can_access = \App\Models\TpRolePermission::select('permission')->where('kode_group', $kode_group)
			->where('slug', $slug)
			->where('permission', $action)
			->count();		
			return $can_access > 0;
		}
		else {
			return $can_access = 0;
		}
	}
}

if(!function_exists('btn_disabled')) {
	function btn_disabled($icon)
	{
		return '<button type="button" class="btn btn-secondary btn-sm" disabled><i class="fa fa-'.$icon.'"></i></button> ';
	}
}

if(!function_exists('nama_unit')) {
	function nama_unit($kodeunitbagian)
	{
		$unit = \DB::table('unitbagianmaster')->select('namaunitbagian')->where('kodeunitbagian', $kodeunitbagian)->first();
		if(!is_null($unit->namaunitbagian)) {
			return $unit->namaunitbagian;			
		}
		return null;
	}
}

if(!function_exists('nama_bagian')) {
	function nama_bagian($kodebagian)
	{
		$bagian = \DB::table('tm_bagian')->select('namabagian')->where('kodebagian', $kodebagian)->first();
		if(is_null($bagian)) {
			return null;
		}
		
		if(!is_null($bagian->namabagian)) {
			return $bagian->namabagian;			
		}
		return null;
	}
}

if(!function_exists('generate_seeder')) {
	function generate_seeder($rows)
	{
		$arrs = "";
		$arrs .= "[<br>";
		foreach($rows as $i => $row) {
			$arrs .= "[<br>";
			foreach ($row as $key => $value) {
				$arrs .= "'{$key}' => '{$value}', <br>";
			}
			$arrs .= "],<br>";
		}
		$arrs .= "]<br>";
		return ($arrs);
	}
}

if(!function_exists('xml_to_array')) {
	function xml_to_array($xml)
	{
		$xml = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
		$json = json_encode($xml);
		return json_decode($json, TRUE);
	}
}

if(!function_exists('xml_to_json')) {
	function xml_to_json($xml)
	{
		$xml = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
		return json_encode($xml);
	}
}

if(!function_exists('send_file_to_safety_place')) {
	function send_file_to_safety_place($file_path)
	{
		$fp = explode(DIRECTORY_SEPARATOR, $file_path);
		$filename = str_replace(DIRECTORY_SEPARATOR, '', ($fp[count($fp) - 1]));
		
		$really_file_path = '';

		foreach ($fp as $i => $p) {
			if($i >= 2)
				$really_file_path .= DIRECTORY_SEPARATOR . $p;
		}

		$is_exists = Storage::disk('ftpdb')->exists($really_file_path);
		if ($is_exists) {
			$telegram = new Telegram();

			$file = Storage::disk('ftpdb')->get($really_file_path);

			$send = $telegram->sendDocument($file, $filename);
			\Log::info(json_encode($send));
		}

	}
}

if(!function_exists('sso_db')) {
	function sso_db()
	{
		return env("SSO_DB_NAME", "[10.10.5.175].laravel_sso");
	}
}

if(!function_exists('nama_golongan')) {
	function nama_golongan($kode)
	{
		$gols = listGroupGolongan();
		return $gols->{$kode};
	}
}

if(!function_exists('ip_address')) {
	function ip_address($myIP = '')
	{
		//net use "\\ptpn4.co.id\dfs\DB ABSENSI" /user:ADSERVER\absensi Admin123*

		$ips = shell_exec("ipconfig | findstr /C:\"IPv4 Address\"");
		$ips = explode("\n", $ips);
		$result = [];
		foreach($ips as $ip) {
			$_ip = substr($ip, 39);
			if(!is_bool($_ip))
				$result[] = $_ip;
		}

		if($myIP)
			$result = in_array($myIP, $result);

		return $result;
	}
}



