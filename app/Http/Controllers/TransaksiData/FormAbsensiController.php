<?php

namespace App\Http\Controllers\TransaksiData;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Validator;
use Jenssegers\Agent\Agent;
use App\Http\Controllers\DesktopBaseController;
use App\Models\Simkar\UnitBagianMaster;
use App\Models\Simkar\TmBagian;
use App\Models\TrJenisAbsen;
use App\Models\TpFormulirHeader;
use App\Models\TpFormulirDetail;
use App\Models\TpPeserta;
use App\Models\TrKuotaCutiTahunan;
use App\Models\TrKuotaCutiPanjang;
use App\Models\Simkar\KaryawanNonPtpn4;
use App\Models\Simkar\KaryawanPtpn4;
use App\Http\Controllers\Api\SimkarController;
use App\Jobs\RegenerateDetailAbsensiHarianIndividuJob;
use App\Jobs\MonitoringCutiTahunanIndividuJob;
use DB;
use Auth;
use Log;
use App\Rules\Cuti;
use Storage;
use Illuminate\Support\Carbon;

class FormAbsensiController extends DesktopBaseController
{
    protected $agent;
    protected $route = 'transaksi-data.formulir-absensi.';
    private $karyawan;

    public function __construct()
    {
        parent::__construct();
        $this->agent = new Agent();
        $this->karyawan = new SimkarController;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        allow('show', 'formulir-absensi');
        $unit = UnitBagianMaster::where('status', 1);
        $bagian = TmBagian::where('isactive', 'Y');

        if (in_array(Auth::user()->getUser()->jenis_user, ['USER_UNIT', 'USER_BAGIAN'])) {
            $unit->where('kodeunitbagian', Auth::user()->getUser()->kode_unit);
            if (in_array(Auth::user()->getUser()->kode_unit,['01','02','03','04','05'])) {
                $bagian->where('kelompok', 'GUU')->where('isActive','Y');
            }
            else {
                $bagian->where('kelompok', 'unit usaha')->where('isActive','Y');
            }
            //langsung cek keberadaan datanya
            $is_uploaded = DB::table('tr_file_upload_ctcps')
            ->where('kode_unit', Auth::user()->getUser()->kode_unit)
            ->where('verified_status',1)
            ->get();

            if (count($is_uploaded) <= 0) {
                return redirect()->route('home')
                ->with('swalWarningLink', 'Unit/Bagian anda belum mengupload Template Cuti Tahunan/Cuti Panjang !')
                // ->with('warning_footer','<a href="'.route("master-data.ctcp.index-download").'">Download Template</a>');
                ->with('warning_footer','Mohon maaf, Periode upload/download Cuti Tahunan/Panjang sudah berakhir.');
            }
        }

        if (Auth::user()->getUser()->jenis_user == 'USER_BAGIAN') {
            $bagian->where('kodebagian', Auth::user()->getUser()->kode_bagian);
            //langsung cek keberadaan datanya
            $is_uploaded = DB::table('tr_file_upload_ctcps')
            ->where('kode_unit', Auth::user()->getUser()->kode_unit)
            ->where('kode_bagian',  Auth::user()->getUser()->kode_bagian)
            ->get();

            if (count($is_uploaded) <= 0) {
                return redirect()->route('home')
                ->with('swalWarningLink', 'Unit/Bagian anda belum mengupload Template Cuti Tahunan/Cuti Panjang')
                // ->with('warning_footer','<a href="'.route("master-data.ctcp.index-download").'">Download Template</a>');
                ->with('warning_footer','Mohon maaf, Periode upload/download Cuti Tahunan/Panjang sudah berakhir.');
            }
        }
        $unit->orderBy('kodeunitbagian', 'asc');
        $bagian->orderBy('namabagian', 'asc');

        $data = [
        'route' => $this->route,
        'breadcrumb' => [
            'Transaksi Data' => true,
            'Formulir Absensi' => route($this->route.'index'),
        ],
        'units' => $unit->get(),
        'bagians' => $bagian->get(),
        'gols' => listGroupGolongan(),
      ];
        if (!$this->agent->isMobile()) {
            return $this->view($this->route . 'index', $data);
        } else {
            // return $this->view($this->route . 'index', $data);
            // return view('mobile.home');
            return $this->view($this->route . 'index', $data);
            // return('Silahkan akses aplikasi dengan perangkat komputer desktop/laptop');
        }
    }

    public function create(Request $request)
    {
        isAllow('create', 'formulir-absensi');
        $data = [
            'route' => $this->route,
            'breadcrumb' => [
                'Transaksi Data' => true,
                'Formulir Absensi' => route($this->route.'index'),
                'Form Pengajuan Absensi' => route($this->route.'create'),
            ],
            'detail_karyawan'=> $this->karyawan->takeKaryawan($request->input('nik')),
            'errors' => $request->input('errors'),
        ];

        $nik = $data['detail_karyawan']->nik;
        $is_satpam = $this->_isSatpam($nik);
        if($is_satpam) {
            $jenis_absens = TrJenisAbsen::where('status', 1)->whereNotIn('kode',['CB','N/A']);
        } else {
            $jenis_absens = TrJenisAbsen::where('status', 1)->whereNotIn('kode',['CB', 'L', 'N/A']);
        }

        $dataKaryawan = DB::table('v_history_karyawan as h')
        ->where('h.nik', $nik)
        ->where('h.tahun', date('Y'))
        ->where('h.bulan', date('m'))
        ->first();

        if(!in_array($dataKaryawan->unit, ['00']))
            $jenis_absens->whereNotIn('kode', ['KN']);        
        if(!in_array($dataKaryawan->unit, ['00','62', '01', '02']))
            $jenis_absens->whereNotIn('kode', ['KN']);
        if (!Auth::user()->hasGroup('ADMIN_ABSENSI')) $jenis_absens->whereNotIn('kode', ['KN']);

        $data['jenis_absens'] = $jenis_absens->get();

        if (is_null($data['errors'])) {
            unset($data['errors']);
        }
        return $this->view($this->route . 'create', $data);
    }


    public function getTable(Request $request)
    {
        if (in_array(Auth::user()->getUser()->jenis_user, ['USER_UNIT', 'USER_BAGIAN'])) {
            $request->request->add(['unit' => Auth::user()->getUser()->kode_unit]);
        }

        if (Auth::user()->getUser()->jenis_user == 'USER_BAGIAN') {
            $request->request->add(['bagian' => Auth::user()->getUser()->kode_bagian]);
        }

        $emps = DB::table('v_karyawan_all as k');

        if ($request->has('golongan') && !is_null($request->input('golongan'))) {
            if($request->input('golongan') == 'HONOR')
                $emps= $emps->whereRaw("left(k.nik, 1) in ('P', 'H')");
            else
                $emps= $emps->where('k.tipe_karyawan', $request->input('golongan'));
        }

        if ($request->has('unit') && !empty($request->input('unit'))) {
            $emps= $emps->where('k.unit', $request->input('unit'));
        }

        if ($request->has('bagian') && !empty($request->input('bagian'))) {
            $emps= $emps->where('k.bagian', $request->input('bagian'));
        }

        // if ($request->has('jabatan') && !empty($request->input('jabatan'))) {
        //     $emps= $emps->where('k.jabatan', $request->input('jabatan'));
        // }

        if ($request->has('nikNama') && !empty($request->input('nikNama'))) {
            $emps= $emps->where('k.nama', 'like', '%'.$request->nikNama.'%');
        }

        $emps= $emps->orderBy('nama', 'asc')->get();

        if (count($emps) > 0) {
            return $this->toJson($emps);
        } else {
            return response()->json('empty');
        }
    }

    private function getIDPeserta($nik)
    {
        $nik_simkar = $this->getNIKSimkar($nik);
        $id_peserta = TpPeserta::where('nik_simkar', $nik_simkar)->first();
        $id_peserta = $id_peserta->id;
        return $id_peserta;
    }

    private function getNIKSimkar($nik)
    {
        $niks =  explode('/', $nik);
        $nik_simkar = $niks[0];
        // $nik_sap    = $niks[1];
        return $nik_simkar;
    }

    private function getJlhHariKerja($tgl_mulai, $tgl_selesai, $nik, $kode_jenis_absen)
    {
        if($kode_jenis_absen == 'K') {
            $hariKerja  = DB::connection('dev_absensi')->select("select count(*) as result from dbo.fn_get_list_date_range (?,?)", [
                $tgl_mulai,
                (new \DateTime($tgl_selesai))->modify('+1 day')
            ]);
        } else if($kode_jenis_absen == 'CP') {
            $hariKerja  = DB::connection('dev_absensi')->select("select dbo.fn_get_jml_hk_pengajuan_cuti_panjang (?,?,?) as result", [
              $nik,
              $tgl_mulai,
              $tgl_selesai
            ]);
        } else {
            //Log::info("mulai menjalankan fn_get_jml_hk_pengajuan_cuti");
            $hariKerja  = DB::connection('dev_absensi')->select("select dbo.fn_get_jml_hk_pengajuan_cuti (?,?,?) as result", [
              $nik,
              $tgl_mulai,
              $tgl_selesai
            ]);            
            //Log::info("selesai menjalankan fn_get_jml_hk_pengajuan_cuti");
        }
        return intval(collect($hariKerja)->first()->result);
    } 

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik'                       => 'required|min:7',
            'nama'                      => 'required',
            'tgl_bekerja'               => 'required',
            'jenis_absen'               => 'required',
            'tgl_mulai'                 => 'required',
            'tgl_selesai'               => 'required',
            'jenis_penyakit'            => 'required_if:jenis_absen,S|required_if:jenis_absen,S1',
            'nama_rumah_sakit'          => 'required_if:jenis_absen,S|required_if:jenis_absen,S1',
            'jlh_cuti_tahunan_tersedia' => 'bail|required_if:jenis_absen,CT|nullable',
            'jlh_cuti_panjang_tersedia' => 'bail|required_if:jenis_absen,CP|nullable',
            'alasan'                    => 'required|min:10|max:200',
            'nik_pengganti'             => 'nullable'
        ]);

        $tgl_mulai = date('Y-m-d', strtotime($request->tgl_mulai));
        $tgl_selesai = date('Y-m-d', strtotime($request->tgl_selesai));
        $nik_simkar = $this->getNIKSimkar($request->nik);
        $jlhHariKerja   = $this->getJlhHariKerja(date('Y-m-d', strtotime($tgl_mulai)), date('Y-m-d', strtotime($tgl_selesai)), $nik_simkar, $request->jenis_absen);

        /**
        * Melakukan pengecekan tanggal pengajuan
        * 1. Untuk karyawan yang menggunakan mesin absensi, pengajuan hanya boleh dlm rentang tgl 26 (Bulan -1) s/d 25 Bulan ini (kecuali jika diizinkan oleh admin)
        * 2. Formulir KERJA (K) hanya diperbolehkan pada hari libur nasional (dan hari sabtu untuk KANPUS dan KPJ)
        * 3. Formulir KERJA SPECIAL (KK) hanya diperbolehkan pada hari kerja saja
        * 4. Tidak diperkenankan formulir ganda 1 hari hanya boleh berisi 1 formulir saja
        * 5. Tidak diperkenankan pengajuan cuti (CT dan CP) jika kuota sudah habis
        */
        $validator->after(function ($validator) use ($request, $nik_simkar, $jlhHariKerja, $tgl_mulai, $tgl_selesai) {

            if($request->input('jenis_absen') == 'WFH' 
                && $this->_getUnitKerjaKaryawan($nik_simkar) == '01' 
                && date('Y-m-d', strtotime($tgl_mulai)) < date('Y-m-d', strtotime('2020-12-01 00:00:00'))
            ) {
                $validator->errors()->add('tgl_mulai', 'Formulir '.$this->_getKodeAbsensi($request->input('jenis_absen')).' untuk GUU I hanya boleh digunakan mulai dari Tgl 01 Desember 2020.');
            }

            if($request->input('jenis_absen') == 'WFH' 
                && $this->_getUnitKerjaKaryawan($nik_simkar) == '02' 
                && date('Y-m-d', strtotime($tgl_mulai)) < date('Y-m-d', strtotime('2020-11-26 00:00:00'))
            ) {
                $validator->errors()->add('tgl_mulai', 'Formulir '.$this->_getKodeAbsensi($request->input('jenis_absen')).' untuk GUU II hanya boleh digunakan mulai dari Tgl 26 November 2020.');
            }

            //Cegah kode K, KK dan KN agar tidak diisi untuk masa depan
            if (in_array($request->input('jenis_absen'), ['K', 'KK', 'KN'])) {
                $startDate = new \DateTime($tgl_mulai);
                $endDate = new \DateTime($tgl_selesai);
                $currentDate = new \DateTime();

                if ($startDate > $currentDate || $endDate > $currentDate) {
                    $validator->errors()->add('tgl_mulai', 'Formulir '.$this->_getKodeAbsensi($request->input('jenis_absen')).' tidak boleh diisi untuk masa depan, maksimal hari ini.');
                }
            }


            if (is_user_menggunakan_mesin($nik_simkar)) { 
                if(!is_pengajuan_dlm_periode($tgl_mulai, $tgl_selesai)) {
                    $date = get_date_min_max();
                    $validator->errors()->add('tgl_mulai', 'Maaf, pengisian formulir bukan dalam jadwal yang seharusnya.
                        Rentang tanggal yang boleh diajukan per hari ini adalah dari tgl ' . date('d-m-Y', strtotime($date->min)) .' s/d '. date('d-m-Y', strtotime($date->max)));
                }
            } else {
                /**
                * Berdasarkan poin pertama dalam Memo SDM Nomor 04.11/Kol/M-1165/VI/2019 Tanggal 12 Juni 2019, maka:
                * 1. Jika karyawan yang tidak menggunakan mesin absensi, pengisian diizinkan hingga tgl 5 di bulan +1
                */
                if(!is_pengajuan_dlm_periode_nonmesin($tgl_mulai, $tgl_selesai)) {
                    $date = get_date_min_max_nonmesin($tgl_mulai, $tgl_selesai);
                    $validator->errors()->add('tgl_mulai', 'Maaf, pengisian formulir bukan dalam jadwal yang seharusnya.
                        Rentang tanggal yang boleh diajukan per hari ini adalah dari tgl ' . date('d-m-Y', strtotime($date->min)) .' s/d '. date('d-m-Y', strtotime($date->max)));
                }
            }

            if ($request->input('jenis_absen') == 'K') {
                if(!cek_kebenaran_pengajuan_KERJA($nik_simkar, date('Y-m-d', strtotime($tgl_mulai)), date('Y-m-d', strtotime($tgl_selesai)), $request->jenis_absen)) {
                    $validator->errors()->add('jenis_absen', 'Formulir "Kerja (K)" hanya boleh dilakukan di hari libur atau cuti bersama saja. Kami mendeteksi pengajuan yang Anda lakukan dalam hari kerja.');
                }
            }
            
            if ($request->input('jenis_absen') == 'KN') {
                if(!in_array($this->_getUnitKerjaKaryawan($nik_simkar), ['01', '02', '03', '04'])) {
                    $validator->errors()->add('jenis_absen', 'Formulir '.$this->_getKodeAbsensi($request->input('jenis_absen')).' hanya boleh digunakan oleh karyawan Distrik saja.');
                }
            }

            if (in_array($request->input('jenis_absen'), ['KK', 'CT', 'WFH', 'KN'])) {
                if(!cek_kebenaran_pengajuan_KERJA_SPECIAL($nik_simkar, date('Y-m-d', strtotime($tgl_mulai)), date('Y-m-d', strtotime($tgl_selesai)), $request->jenis_absen)) {
                    if (!$this->_isSatpam($nik_simkar)) {
                        $validator->errors()->add('jenis_absen', 'Formulir ' . $this->_getKodeAbsensi($request->input('jenis_absen')) . ' hanya boleh dilakukan di hari kerja saja. Kami mendeteksi pengajuan yang Anda lakukan dalam hari libur atau hari minggu.');
                    }
                }
            }

            if(exist_on_header($nik_simkar, date('Y-m-d', strtotime($tgl_mulai)), date('Y-m-d', strtotime($tgl_selesai)))) {
                $validator->errors()->add('tgl_mulai', 'Tanggal Pengajuan sudah digunakan. Tidak boleh menggunakan tanggal yang sama!.');
            }

            if (in_array($request->jenis_absen, ['CP', 'CT'])) {
                if (get_kuota_cuti($request->jenis_absen, $nik_simkar, date('Y-m-d', strtotime($tgl_mulai)), date('Y-m-d', strtotime($tgl_selesai))) < $jlhHariKerja) {
                    Log::error('User ' . Auth::user()->getUser()->username . ' menambah formulir yang bernilai minus NIK =' . $nik_simkar);
                    $validator->errors()->add('jenis_absen', 'Maaf, sisa kuota '. $request->jenis_absen .' untuk karyawan yang bersangkutan tidak mencukupi.');
                }
            }
        });

        if ($validator->fails()) {
            $request->merge([
                'errors' => $validator->messages(),
                'nik' => $nik_simkar
            ]);
            $request->flash();

            return $this->create($request);
        }

        $id_kuota = null;       

        if($request->input('jenis_absen') == 'CT') {                
            $kuota = TrKuotaCutiTahunan::where('nik_simkar', $nik_simkar)
            ->whereRaw("'".date('Y-m-d', strtotime($request->input('tgl_mulai')))."' between tgl_mulai and tgl_berakhir")
            ->whereRaw("'".date('Y-m-d', strtotime($request->input('tgl_selesai')))."' between tgl_mulai and tgl_berakhir")
            ->first();
            $id_kuota = $kuota->id;
        } else if($request->input('jenis_absen') == 'CP') {
            $kuota = TrKuotaCutiPanjang::where('nik_simkar', $nik_simkar)
            ->whereRaw("'".date('Y-m-d', strtotime($request->input('tgl_mulai')))."' between tgl_mulai and tgl_berakhir")
            ->whereRaw("'".date('Y-m-d', strtotime($request->input('tgl_selesai')))."' between tgl_mulai and tgl_berakhir")
            ->first();
            $id_kuota = $kuota->id;
        }
        try {
            DB::beginTransaction();
            $idFormulirHeader = TpFormulirHeader::insertGetId([
                'nik_simkar'        => $nik_simkar,
                'id_kuota'          => $id_kuota,
                'nik_pengganti'     => $request->input('nik_pengganti'),
                'kode_jenis_absen'  => $request->input('jenis_absen'),
                'tgl_mulai'         => date('Y-m-d', strtotime($request->input('tgl_mulai'))),
                'tgl_selesai'       => date('Y-m-d', strtotime($request->input('tgl_selesai'))),
                'jml_hk'            => $jlhHariKerja,
                'alasan'            => $request->input('alasan'),
                'status'            => 1,
                'created_by'        => Auth::user()->getUser()->username,
                'created_at'        => date('Y-m-d H:i:s'),
            ]);

            if ($request->input('jenis_absen') == 'S' || $request->input('jenis_absen') == 'S1') {
                TpFormulirDetail::insert([
                    'id_formulir_header'  => $idFormulirHeader,
                    'jenis_penyakit'      => $request->input('jenis_penyakit'),
                    'nama_rumah_sakit'    => $request->input('nama_rumah_sakit'),
                    'status'              => 1,
                    'created_by'          => Auth::user()->getUser()->username,
                    'created_at'          => date('Y-m-d H:i:s')
                ]);
            }

            if ($request->input('jenis_absen') == 'CT') {
                $updateCT = TrKuotaCutiTahunan::where('nik_simkar', $nik_simkar)
                ->whereDate('tgl_mulai', '<=', $tgl_mulai)
                ->whereDate('tgl_berakhir', '>=', $tgl_selesai)
                ->update([
                    'terpakai' => DB::raw('terpakai +'.$jlhHariKerja),
                    'updated_by' => Auth::user()->getUser()->username
                ]);
            }

            if ($request->input('jenis_absen') == 'CP') {
                $updateCP = TrKuotaCutiPanjang::where('nik_simkar', $nik_simkar)
                ->whereDate('tgl_mulai', '<=', $tgl_mulai)
                ->whereDate('tgl_berakhir', '>=', $tgl_selesai)
                ->update([
                    'terpakai' => DB::raw('terpakai +'.$jlhHariKerja),
                    'updated_by' => Auth::user()->getUser()->username
                ]);
            }
            DB::commit();

            RegenerateDetailAbsensiHarianIndividuJob::dispatch($nik_simkar, $tgl_mulai, $tgl_selesai, Auth::user()->getUser()->username);
            
            MonitoringCutiTahunanIndividuJob::dispatch($nik_simkar);

            Log::info(Auth::user()->getUser()->username . ' menyimpan data formulir pengajuan absensi '.$request->input('jenis_absen') .' dari '.$tgl_mulai.' s/d '.$tgl_selesai.' NIK: '.$nik_simkar.' alasan: '.$request->input('alasan'));

            $this->writeLog('Berhasil menyimpan data formulir pengajuan absensi '.$request->input('jenis_absen') .' dari '.$tgl_mulai.' s/d '.$tgl_selesai.' NIK: '.$nik_simkar.' alasan: '.$request->input('alasan'));
            return redirect()->route($this->route . 'index')->with('swalSuccess', 'Berhasil menyimpan formulir absensi');
        } catch (QueryException $e) {
            \Log::error($e);
            DB::rollBack();
            $this->writeLog('Gagal menyimpan formulir pengajuan absensi '.$request->input('jenis_absen') .' dari '.$tgl_mulai.' s/d '.$tgl_selesai.' NIK: '.$nik_simkar.' alasan: '.$request->input('alasan'));
            return redirect()->route($this->route . 'index')->with('swalError', 'Terjadi kesalahan pada proses simpan database. Hubungi Administrator.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e);
            return redirect()->route($this->route . 'index')->with('swalError', 'Terjadi kesalahan di server saat menyimpan data. Hubungi Administrator.');
        }

    }

    private function _getKodeAbsensi($kode)
    {
        return TrJenisAbsen::find($kode)->nama . " (".$kode.")";
    }

    private function _getUnitKerjaKaryawan($nik)
    {
        if(in_array(substr($nik, 0, 1), ['H', 'P', 'O', 'M'])) {
            return KaryawanNonPtpn4::find($nik)->unit;
        } else {
            return KaryawanPtpn4::find($nik)->unit;            
        }
    }

    public function _isSatpam($nik)
    {
        return DB::table('v_history_karyawan as h')
        ->where('h.nik', $nik)
        ->where('h.tahun', date('Y'))
        ->where('h.bulan', date('m'))
        ->whereExists(function ($query) {
            $query->select('*')
            ->from('tm_jabatan as jab') 
            ->whereIn('jab.jabatan', ['DANTON', 'SATPAM', 'SATPAM DIREKSI', 'SATPAM KANTOR', 'WADANTON', 'PAPAM'])
            ->whereRaw('jab.kodejabatan = h.kodejabatan');
        })->count() > 0;
    }

    
}
