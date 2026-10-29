<?php

namespace App\Helpers;

use App\Services\IHCS\Foundation\Curly;
use Log;

class AttMobileService {

	use Curly;

	private $mobileapiurl = "https://kindi.id/apiptpn4/?option=vw_absensi&task=data_absensi2";
	private $body;

	public function getMonthly($tahun, $bulan)
	{
		$startDate = date('Y-m-d', strtotime($this->tahun . '-' . $this->bulan . '-01'));
		$endDate = date('Y-m-d', strtotime("-1 days", strtotime("+1 months", strtotime($this->tahun . '-' . $this->bulan . '-01'))));

		return $this->_getFromApi($startDate, $endDate);
	}

	public function getDaily($date)
	{
		$startDate = date('Y-m-d', strtotime($date));
		$endDate = date('Y-m-d', strtotime($date));

		return $this->_getFromApi($startDate, $endDate);
	}

	public function getByDateRange($startDate, $endDate)
	{
		return $this->_getFromApi($startDate, $endDate);
	}

	private function _getFromApi($startDate, $endDate)
	{
		$bulan = date('m', strtotime($startDate));
		$tahun = date('Y', strtotime($startDate));

		$mobileapiurl = env('MOBILE_ATTENDANCE_API_URL', $this->mobileapiurl) . "&bulan={$bulan}&tahun={$tahun}";

		$proxy = ['ip' => '10.10.5.200', 'port' => '8080'];
		// curl($url, $post = '', $ref = '', $header = '', $proxy = '')
		//saat ini Server SDM memiliki direct connection ke Public
		//Tanpa proxy
		$this->curl($mobileapiurl, '', '', '', '');
		
		$result =  collect(json_decode($this->body))->where('tanggal', '>=', $startDate)->where('tanggal', '<=', $endDate)
		->unique()
		->sortBy('tanggal')
		->sortBy('nama');
		return $result;	
	}
}