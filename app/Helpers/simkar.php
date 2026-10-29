<?php

if(!function_exists('listGroupGolongan')) {
	function listGroupGolongan($kode = null)
	{
		$file = file_get_contents(resource_path('assets/lookups/kelompok_golongan.json'));
		$json = json_decode(json_decode(json_encode($file, true)));

		$user = Auth::user()->getUser();
		// if(!Auth::user()->hasGroup('ADMIN_ABSENSI') && !in_array($user->username, ['hukum', 'hukum2'])) {
		// 	unset($json->SATPAM);
		// }
		return $json;
	}
}

if(!function_exists('listRSMedan')) {
	//JSON untuk daftar RS di Medan
	function listRSMedan($kode = null)
	{
		$file = file_get_contents(resource_path('assets/lookups/rumah_sakit.json'));
		return json_decode(json_decode(json_encode($file, true)));
	}
}

if(!function_exists('list_unit_bagian_master')) {
	function list_unit_bagian_master($fields = [], $filters = [])
	{
		$units = DB::connection('simkar')->table('unitbagianmaster');
		if(!empty($fields))
			$units->select($fields);
			
		if(!empty($filters))
			foreach ($filters as $key => $value) {
				if(is_array($value))
					$units->whereIn($key, $value);
				else
					$units->where($key, $value);
			}
		
		$units->where('status', 1)
		->orderBy('noguu', 'asc')
		->orderBy('kodeunitbagian', 'asc');
		return $units->get();
	}
}
