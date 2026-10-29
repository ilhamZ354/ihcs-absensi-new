<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//SSO Routes
Route::get("/sso/checkpoint/{token}", '\App\Services\IHCS\Controllers\SSOLoginController');
Route::get('/login', '\App\Services\IHCS\Controllers\LoginController@showLoginForm')->name('login');
Route::post('/logout', '\App\Services\IHCS\Controllers\LoginController@logout')->name('logout');

Route::get('/', 'HomeController@index')->name('home');

Route::get('/home', function () {
	return redirect('/');
});

//Formulir Absensi Route
// Route::get("/absensi/daftar", 'FormAbsensiController@index')->name('daftar-form-absensi');
/*Route::get("/mdb/absensi", function() {
	$rows = mdb_get_absensi(2020, 3, [1099, 11757]);

	\DB::connection('sqlsrv')->beginTransaction();
	try {
		foreach($rows as $row) {
			DB::connection('sqlsrv')->table('temp_att_log_dailies_sqlite')->insert($row);
		}
		DB::connection('sqlsrv')->commit();
	} catch (\Exception $e) {
		DB::connection('sqlsrv')->rollBack();
	}
});*/

# MASTER DATA
Route::group(['prefix' => 'master-data', 'as' => 'master-data.'], function() {
	Route::get("/", function() {
		return redirect('/');
	});

	Route::resource("kode-absensi", 'MasterData\KodeAbsensiController');
	Route::group(['prefix' => 'kode-absensi', 'namespace' =>'MasterData', 'as' => 'kode-absensi.'], function () {
		Route::post("getTable", 'KodeAbsensiController@getTable')->name('table');
	});

	Route::resource("libur-cuti-bersama", 'MasterData\LiburCutiBersamaController');
	Route::group(['prefix' => 'libur-cuti-bersama', 'namespace' =>'MasterData', 'as' => 'libur-cuti-bersama.'], function () {
		Route::post("getTable", 'LiburCutiBersamaController@getTable')->name('table');
	});

	Route::group(['prefix' => 'data-kehadiran', 'namespace' =>'MasterData', 'as' => 'data-kehadiran.'], function () {
		Route::post("getTable", 'DataKehadiranController@getTable')->name('table');
		Route::get("upload", 'DataKehadiranController@upload')->name('upload');
		Route::post("saveupload", 'DataKehadiranController@saveUpload')->name('saveupload');
		Route::get("uploaded-files", 'DataKehadiranController@uploadedFiles')->name('uploaded-files');
		Route::post("table-uploaded-file", 'DataKehadiranController@tableUploadedFiles')->name('table-uploaded-files');
		Route::post("check-duplicate", 'DataKehadiranController@checkDuplicate')->name('check-duplicate');
		Route::post("{id}/download", 'DataKehadiranController@download')->name('download');
	});
	Route::resource("data-kehadiran", 'MasterData\DataKehadiranController');

	Route::get("peserta", 'MasterData\PesertaController@index')->name('peserta.index');
	Route::get("peserta/create", 'MasterData\PesertaController@create')->name('peserta.create')->middleware('group:ADMIN_ABSENSI');
	Route::post("peserta/getTable", 'MasterData\PesertaController@getTable')->name('peserta.table');

#MASTER Cuti Tahunan dan Cuti Panjang
	// Route::get("ctcp", function() {
	// 	return "<center><h1>Mohon maaf, periode upload/download CTCP sudah berakhir.</h1></center>";
	// });
	// Route::get("ctcp/{ext1}", function() {
	// 	return "<center><h1>Mohon maaf, periode upload/download CTCP sudah berakhir.</h1></center>";
	// });
	// Route::get("ctcp/{ext1}/{ext2}", function() {
	// 	return "<center><h1>Mohon maaf, periode upload/download CTCP sudah berakhir.</h1></center>";
	// });
	// Route::get("ctcp/{ext1}/{ext2}/{ext3}", function() {
	// 	return "<center><h1>Mohon maaf, periode upload/download CTCP sudah berakhir.</h1></center>";
	// });

	// if(false) {
		Route::group(['prefix' => 'ctcp', 'as' => 'ctcp.'], function() {
			Route::get("/", function() {return redirect('/');})->name('home');
			Route::get("download", 'MasterData\DataCTCPController@indexDownload')->name('index-download');
			Route::get("upload", 'MasterData\DataCTCPController@indexUpload')->name('index-upload');
			Route::get("{id}/download", 'MasterData\DataCTCPController@download')->name('download');
			Route::post("store", 'MasterData\DataCTCPController@store')->name('store');
			Route::get("edit",'MasterData\DataCTCPController@edit')->name('index-edit');
		});
	// }

	Route::resource("timetable",'MasterData\TimetableController');
	Route::group(['prefix' => 'timetable', 'namespace' => 'MasterData', 'as' => 'timetable.'], function () {
		Route::post("getTable", 'TimetableController@getTable')->name('table');
	});

	Route::group(['prefix' => 'n4attendance', 'as' => 'n4attendance.', 'namespace' => 'MasterData\N4Attendance'], function() {
		Route::resource("peserta", 'AttEmployeeController');
		Route::resource("lokasi", 'LokasiController')->except('show');
		Route::resource("groups", 'EmployeeGroupController')->except('show');

		Route::group(['prefix' => 'peserta', 'as' => 'peserta.'], function() {
			Route::post("getTable", 'AttEmployeeController@getTable')->name('table');
			Route::post("ceknik", 'AttEmployeeController@cekNik')->name('ceknik');
			Route::post("cekponsel", 'AttEmployeeController@cekPonsel')->name('cekponsel');
		});

		Route::group(['prefix' => 'lokasi', 'as' => 'lokasi.'], function() {
			Route::post("getTable", 'LokasiController@getTable')->name('table');			
			Route::get("{lokasi}/map", 'LokasiController@map')->name('map');			
		});
		Route::group(['prefix' => 'groups', 'as' => 'groups.'], function() {
			Route::post("getTable", 'EmployeeGroupController@getTable')->name('table');			
			Route::get("{group}/map", 'EmployeeGroupController@map')->name('map');			
		});
		Route::group(['prefix' => 'mock', 'as' => 'mock.'], function() {
			Route::get("/", 'MockLocationController@index')->name('index');
			Route::post("getTable", 'MockLocationController@getTable')->name('table');
		});
		Route::group(['prefix' => 'kehadiran', 'as' => 'kehadiran.'], function() {
			Route::get("/", 'AttendanceController@index')->name('index');
			Route::post("getTable", 'AttendanceController@getTable')->name('table');
			Route::get("{kehadiran}/{state}/map", 'AttendanceController@map')->name('map');			
		});

	});
});


Route::group(['prefix' => 'transaksi-data', 'as' => 'transaksi-data.'], function() {
	Route::get("/", function() {
		return redirect('/');
	})->name('home');

	// Route::resource("formulir-absensi", 'TransaksiData\FormAbsensiController');

	Route::group(['prefix' => 'formulir-absensi', 'namespace' =>'TransaksiData', 'as' => 'formulir-absensi.'], function () {
		Route::get("/", 'FormAbsensiController@index')->name('index');
		Route::post("getTable", 'FormAbsensiController@getTable')->name('table');
		Route::post("create", 'FormAbsensiController@create')->name('create');
		Route::post("store", 'FormAbsensiController@store')->name('store');
		Route::get("create", function() { return redirect()->route('transaksi-data.formulir-absensi.index'); });
	});

	Route::resource("transaksi-absensi",'TransaksiData\TransaksiAbsensiController');
	Route::group(['prefix' => 'transaksi-absensi', 'namespace' =>'TransaksiData', 'as' => 'transaksi-absensi.'], function () {
		Route::post("get-table", 'TransaksiAbsensiController@getTable')->name('table');
		Route::post("get-detail", 'TransaksiAbsensiController@getDetail')->name('detail');
		Route::get("/", 'TransaksiAbsensiController@index')->name('index');
		// Route::post("update", 'TransaksiAbsensiController@update')->name('update');
	});

});

#API-SIMKAR
Route::group(['prefix' => 'api/simkar', 'namespace' => 'Api', 'as' => 'simkar.'], function() {
	Route::get('take-rekan-pengganti', 'SimkarController@takeRekanPengganti');
	Route::post('list-unit', 'SimkarController@listUnit');
	Route::post('list-bagian', 'SimkarController@listBagian');
	Route::post('list-jabatan', 'SimkarController@listJabatan');
	Route::get('list-karyawan', 'SimkarController@listKaryawan');
	Route::post('list-karyawan', 'SimkarController@listKaryawan');
	Route::post('list-golongan', 'SimkarController@listGolongan');
	Route::post('list-karyawan-simple', 'SimkarController@listAllKaryawanSimple');
});

#API-ABSENSI
Route::group(['prefix' => 'api/absensi', 'namespace' => 'Api', 'as' => 'api.absensi.'], function() {
	// Route::post('generate-full-day-absensi', 'AbsensiController@full_day_absensi')->name('full_day_absensi');
	// Route::post('generate-telat-cepat-pulang', 'AbsensiController@telat_cepat_pulang')->name('telat_cepat_pulang');
	Route::post('generate-summary-absensi-harian', 'AbsensiController@generateSummaryAbsensi')->name('generate_summary_absensi_harian');
	Route::post('generate-hari-minggu', 'AbsensiController@hari_minggu')->name('hari_minggu');
	Route::post('backup-db', 'AbsensiController@backup_db')->name('backup-db');
	Route::post('get-cuti', 'AbsensiController@getCuti')->name('get-cuti');
	Route::post('cuti-tahunan', 'AbsensiController@cuti_tahunan')->name('cuti-tahunan');
	Route::post('cuti-panjang', 'AbsensiController@cuti_panjang')->name('cuti-panjang');
	Route::post('config-path-mdb', 'AbsensiController@configPathMdb')->name('config-path-mdb');
	Route::post('get-path-mdb', 'AbsensiController@getPathMdb')->name('get-path-mdb');
	Route::post('all-notifs-readed', 'AbsensiController@setAllNotifsReaded')->name('set-all-notifs-raded');
	// Route::post('generate-summary-absensi-harian', 'AbsensiController@generateSummaryHarian')->name('generate_summary_absensi_harian');
	Route::post('sync-att-mobile', 'AbsensiController@syncAttMobile')->name('sync-att-mobile');
});

# LOG
Route::group(['prefix' => 'log', 'as' => 'log.', 'namespace' => 'Log'], function() {
	Route::get('/', function() { return redirect('/'); });
	Route::get('aktifitas', 'AktifitasController@index')->name('aktifitas.index');
	Route::post('aktifitas/getTable', 'AktifitasController@getTable')->name('aktifitas.table');

	Route::get('sistem', 'SistemController@index')->name('sistem.index');
	Route::post('sistem/table', 'SistemController@getTable')->name('sistem.table');
	Route::post('sistem/{file}', 'SistemController@show')->name('sistem.show');
	Route::post('sistem/clear/{logname}', 'SistemController@clear')->name('sistem.clear');
});

# ADMIN

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin'], function() {
	Route::get('/', function() { return redirect('/'); });
	Route::get('panel', 'PanelController@index')->name('panel.index');\

	Route::prefix('backdate')->as('backdate.')->group(function() {
		Route::post("getTable", 'BackdateController@getTable')->name('table');
		Route::put("{backdate}/deactivate", 'BackdateController@deactivate')->name('deactivate');
	});
	Route::resource('backdate', 'BackdateController');

	Route::post('permission/table', 'PermissionController@getTable')->name('permission.table');
	Route::resource('permission', 'PermissionController');

	Route::namespace('Normalisasi')->prefix('normalisasi')->as('normalisasi.')->group(function() {
		Route::prefix('absensi')->as('absensi.')->group(function() {
			Route::get('/', 'AbsensiController@index')->name('index');
			Route::post('/', 'AbsensiController@getTable')->name('peserta');
			Route::post('/create', 'AbsensiController@create')->name('create');
			Route::post('/process', 'AbsensiController@process')->name('process');
		});

		Route::post('insentif/table', 'InsentifController@getTable')->name('insentif.table');
		Route::get('uploads', 'InsentifController@uploads')->name('insentif.uploads');
		Route::delete('uploads/{period}', 'InsentifController@deleteUpload')->name('insentif.uploads.delete');
		Route::resource('insentif', 'InsentifController');
	});
});

# LAPORAN
Route::group(['prefix' => 'laporan', 'as' => 'laporan.', 'namespace' => 'Laporan', 'middleware' => 'hasUploaded'], function() {
	Route::get('/', function() { return redirect('/'); });
	Route::get('absensi', 'AbsensiController@index')->name('absensi.index');
	Route::get('export', 'AbsensiController@export')->name('absensi.export');
	
	Route::get('wfh', 'WfhController@index')->name('wfh.index');
	Route::get('wfh/export', 'WfhController@export')->name('wfh.export');

	Route::group(['middleware' => ['auth', 'group:ADMIN_ABSENSI|SUPER_USER']], function() {
		Route::get('lmsw', 'LsmwController@index')->name('lsmw.index');
		Route::get('hitung-insentif', 'HitungInsentifController@index')->name('hitung-insentif.index');
		Route::get('export-hitung-insentif/{params}', 'HitungInsentifController@export')->name('hitung-insentif.export');
		Route::get('uang-makan', 'UangMakanController@index')->name('uang-makan.index');
		Route::get('uang-makan/export', 'UangMakanController@export')->name('uang-makan.export');

		Route::get('individu-tahunan', 'LaporanIndividuTahunanController@index')->name('kehadiran-individu-tahunan.index');
		Route::get('finger-harian', 'FingerHarianController@index')->name('finger-harian.index');
	});

	Route::group(['prefix' => 'finger', 'as' => 'finger.', 'middleware' => ['auth', 'machine']], function() {
		Route::get('/', 'FingerController@index')->name('index');
		Route::get('{tahun}/{bulan}/{nik_simkar}', 'FingerController@report')->name('report');
		Route::get('{tahun}/{bulan}/{pin}/export', 'FingerController@export')->name('export');
		Route::get('export-custom', 'FingerController@exportCustom')->name('export-custom');
	});

	Route::group(['prefix' => 'finger-harian', 'as' => 'finger-harian.', 'middleware' => ['auth', 'machine']], function() {
		// Route::get('/', 'FingerController@index')->name('index');
		// Route::get('{tahun}/{bulan}/{nik_simkar}', 'FingerController@report')->name('report');
		// Route::get('{tahun}/{bulan}/{pin}/export', 'FingerController@export')->name('export');
		Route::get('export-custom', 'FingerHarianController@exportCustom')->name('export-custom');
	});

	Route::group(['prefix' => 'realtime', 'as' => 'realtime.', 'middleware' => ['auth', 'machine']], function() {
		Route::get('/', 'RealtimeController@index')->name('index');
		Route::post('table', 'RealtimeController@getTable')->name('table');
	});

	Route::group(['prefix' => 'kehadiran-harian', 'as' => 'kehadiran-harian.', 'middleware' => ['auth', 'emp_only']], function() {
		Route::get('/', 'KehadiranHarianController@index')->name('index');
		Route::post('table', 'KehadiranHarianController@getTable')->name('table');
	});

	Route::group(['prefix' => 'bkm', 'as' => 'bkm.', 'middleware' => ['auth', 'bkm']], function() {
		Route::get('/', 'BkmController@index')->name('index');
		Route::post('filter', 'BkmController@filter')->name('filter');
		// Route::post('export', 'BkmController@export')->name('export');
	});

	Route::group(['prefix' => 'asistensi-gpu', 'as' => 'asistensi-gpu.', 'middleware' => ['auth', 'group:ADMIN_ABSENSI|SUPER_USER']], function() {
		Route::get('/', 'AsistensiGpuController@index')->name('index');
		Route::post('/', 'AsistensiGpuController@process')->name('process');
		// Route::post('filter', 'BkmController@filter')->name('filter');
		// Route::post('export', 'BkmController@export')->name('export');
	});
});

Route::get('run-crawler', function () {
	// return base64_encode("niasharapan@gmail.com");
	// return bcrypt("niasharapan@gmail.com");
	$machine = new \App\Services\Absensi\Crawler\GetAttLog();
	return $machine->saveAttLog();
	// return $machine->get_user_info();
	// dd($machine->get_absensi('559'));
});


# API TARIK DATA DARI MESIN
Route::group(['prefix' => 'dotnet', 'namespace' => 'Api', 'middleware' => 'dotnet'], function() {
	Route::post('list-mesin', 'DotNetController@listMesin');
	Route::post('list-satpam', 'DotNetController@listSatpam');
});

# API TARIK DATA ABSENSI DARI APLIKASI LAIN
Route::group(['prefix' => 'ihcs-api', 'namespace' => 'Api', 'middleware' => 'absensi.api'], function() {
	Route::post('absensi-harian', 'AbsensiHarianController@daily');
	Route::post('absensi-harian/detail', 'AbsensiHarianController@detail');
	Route::post('kuota-cp', 'KuotaController@cp');
	Route::post('kuota-ct', 'KuotaController@ct');

	Route::prefix('n4-attendance')->namespace('N4Attendance')->group(function() {
		Route::prefix('simkar')->namespace('Simkar')->group(function() {
			Route::post('detail', 'SimkarController@detail');
		});
	});
});


Route::get('testing', function() {
	if(ip_address('10.10.5.175')) {
		return mdb_get_users();
	}
});
