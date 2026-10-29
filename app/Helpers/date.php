<?php

if(!function_exists('toNumberOfMonth')) {
	function toNumberOfMonth($strMonth)
	{
		return array_search($strMonth, listBulan());
	}
}

if(!function_exists('listBulan')) {
	function listBulan($numberOfMonth = null)
	{
		$listOfMonth = [
			'',
			'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember',
		];

		if(!is_null($numberOfMonth)) {
			return $listOfMonth[$numberOfMonth];
		}
		return $listOfMonth;
	}
}

if(!function_exists('myMonthName')) {
	function myMonthName($tgl) {
		$blnIndo = [
			1 => 'Januari',
			2 => 'Februari',
			3 => 'Maret',
			4 => 'April',
			5 => 'Mei',
			6 => 'Juni',
			7 => 'Juli',
			8 => 'Agustus',
			9 => 'September',
			10 => 'Oktober',
			11 => 'November',
			12 => 'Desember',
		];
		$bln = $blnIndo[intval(date('m', strtotime($tgl)))];
		return $bln;    
}
}

if(!function_exists('indoDateCustom')) {
	function indoDateCustom($tgl) {
		$blnIndo = [
			1 => 'Januari',
			2 => 'Februari',
			3 => 'Maret',
			4 => 'April',
			5 => 'Mei',
			6 => 'Juni',
			7 => 'Juli',
			8 => 'Agustus',
			9 => 'September',
			10 => 'Oktober',
			11 => 'November',
			12 => 'Desember',
		];
		if ($tgl !== null) {
			$bln = $blnIndo[intval(date('m', strtotime($tgl)))];
			return date('d', strtotime($tgl)).' '.$bln.' '.date('Y', strtotime($tgl));    
		}
		else {
			return 'N/A';    
		}
	}
}

if(!function_exists('indoDateShort')) {
	function indoDateShort ($arg, $dayName = false, $hour = false) {
		if (!empty($arg)) {
			setlocale(LC_TIME, 'IND');
			return strftime(($dayName ? '%A, ' : '' ).'%d-%m-%Y'.($hour ? ', %H:%M WIB' : ''), strtotime($arg));
		}
		else{
			return null;
		}
	}
}

if(!function_exists('list_days')) {
	function list_days () {
		return [
			'Sunday' => 'Sunday (Minggu)',
			'Monday' => 'Monday (Senin)',
			'Tuesday' => 'Tuesday (Selasa)',
			'Wednesday' => 'Wednesday (Rabu)',
			'Thursday' => 'Thursday (Kamis)',
			'Friday' => 'Friday (Jumat)',
			'Saturday' => 'Saturday (Sabtu)',
		];
	}
}
