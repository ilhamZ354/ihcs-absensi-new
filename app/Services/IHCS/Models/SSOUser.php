<?php

namespace App\Services\IHCS\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DateTime;
use DateInterval;
use DatePeriod;

class SSOUser extends Authenticatable
{
    use Notifiable;

    protected $user;
    protected $token;
    protected $nextCheckTime;

    public function __construct($user, $token)
    {
    	$this->user = $user;
    	$this->token = $token;
    	$this->refreshNextCheckTime();
    }

    /**
	* Mengambil nama kode unik user
	* @return string
	*/
	public function getAuthIdentifierName() {
		return 'username';
	}

	/**
	* Mengambil nilai dari kode unik user
	* @return mixed
	*/
	public function getAuthIdentifier() {
		return $this->getUser()->username;
	}

	/**
	* Mencegah pemanggilan nilai password
	* @return string
	*/
	public function getAuthPassword() {
		return null;
	}

	/**
	* Mengambil nilai remember me dalam session
	* @return string
	*/
	public function getRememberToken() {
		return $this->{$this->getRememberTokenName()};
	}

	/**
     * Menambahkan nilai remember token 
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->{$this->getRememberTokenName()} = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

        /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    // public function getUser() {
    //     return ((object) $this->user)->user;
    // }

    public function getUser() {
        $user =  (object) ((object) $this->user)->user;
        return $user;
    }

    public function getJenisUser() {
        return $this->getUser()->jenis_user;
    }

    public function getSimkar() {
        $user = (object) $this->user->user;
        $jenis_user = strtolower($user->jenis_user);
        return (object) collect($this->user->{$jenis_user})->first();
    }

    public function getJabatan() {
        $jabatan = null;        
        $jenis_user = $this->getJenisUser();
        if($jenis_user == 'KARYAWAN_PTPN4' || $jenis_user == 'KARYAWAN_NON_PTPN4') {
            $kode_jabatan = $this->getSimkar()->kode_jabatan;
            if(!is_null($kode_jabatan)) {
                $jabSimkar = app('db')->connection('simkar')->table('tm_jabatan')->select('kodejabatan as kode_jabatan', 'jabatan', 'jenis_karyawan', 'kelompok', app('db')->raw('case when isactive = \'Y\' then 1 else 0 end as status'))->where('kodejabatan', $kode_jabatan)->first();
                if(!is_null($jabSimkar)) {
                    $jabatan = $jabSimkar;
                }
            }
        }
        return $jabatan;
    }

    public function getUnit() {
        $unit = null;        
        $jenis_user = $this->getJenisUser();
        if($jenis_user == 'KARYAWAN_PTPN4' || $jenis_user == 'KARYAWAN_NON_PTPN4') {
            $kode_unit = $this->getSimkar()->kode_unit;
            if(!is_null($kode_unit)) {
                $unitSimkar = app('db')->connection('simkar')->table('unitbagianmaster')
                                ->select('kodeunitbagian as kode_unit', 'NamaUnitBagian as nama_unit', 'inisial', 'kelompok', 'status')
                                ->where('kodeunitbagian', $kode_unit)->first();
                if(!is_null($unitSimkar)) {
                    $unit = $unitSimkar;
                }
            }
        }
        return $unit;
    }

    public function getBagian() {
        $bagian = null;        
        $jenis_user = $this->getJenisUser();
        if($jenis_user == 'KARYAWAN_PTPN4' || $jenis_user == 'KARYAWAN_NON_PTPN4') {
            $kode_bagian = $this->getSimkar()->kode_bagian;
            if(!is_null($kode_bagian)) {
                $bagianSimkar = app('db')->connection('simkar')->table('tm_bagian')
                                ->select('kodebagian as kode_bagian', 'namabagian as nama_bagian', 'kode', 'kelompok', app('db')->raw('case when isactive = \'Y\' then 1 else 0 end as status'), 'katagori as kategori')
                                ->where('kodebagian', $kode_bagian)->first();
                if(!is_null($bagianSimkar)) {
                    $bagian = $bagianSimkar;
                }
            }
        }
        return $bagian;
    }

    public function getGroups() {
        $groups = (object) $this->user->groups;
        $result =  [];
        foreach ($groups as $key => $value) {
            $temp = new \stdClass();
            foreach ($value as $k => $v) {
                $temp->{$k} = $v;
            }
            array_push($result, $temp);
        }
        return $result;
    }

    public function getApps() {
        $apps = (object) $this->user->apps;
        $result =  [];
        foreach ($apps as $key => $value) {
            $temp = new \stdClass();
            foreach ($value as $k => $v) {
                $temp->{$k} = $v;
            }
            array_push($result, $temp);
        }
        return $result;
    }

    public function getAuthStatus() {
        $groups = (object) $this->user;
        return $groups->status;
    }

    public function hasGroup($groupToCheck) {
        $groupsToCheck = explode('|', $groupToCheck);
        $groups = $this->getGroups();
        $arrResult = [];
        foreach($groups as $group) {
            if(in_array($group->kode, $groupsToCheck)) {
                array_push($arrResult, true);
            }
        }
        return in_array(true, $arrResult);
    }

    public function refreshNextCheckTime() {
        $this->nextCheckTime = new DateTime(date_default_timezone_get());
        $this->nextCheckTime->add(new DateInterval("PT10S"));
        return;
    }

    public function isNeedToCheckSSO() {
        $now = new DateTime(date_default_timezone_get());
        return $now > $this->nextCheckTime;
    }

}
