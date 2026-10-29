CARA MENGIMPLEMENTASI PORTAL HCIS DALAM APLIKASI LARAVEL
By Harapan Jaya Harefa
--------------------------------------------------------

1. Buatkan folder "Services" dalam folder "app" (kalau sudah ada, tidak perlu dibuat lagi)
2. Copy folder "IHCS" ke dalam folser "Services"
3. Tambahkan dua provider berikut ke dalam file config/app.php
		

		//SSO Providers
        App\Services\IHCS\Providers\SSOServiceProvider::class,
        App\Services\IHCS\Providers\SSOAuthProvider::class,
   
   Penjelasan:
   - SSOServiceProvider, adalah driver Service SSO. Provider ini wajib diisikan jika menggunakan Portal IHCS
   - SSOAuthProvider, adalah provider untuk menangani Authentikasi versi PORTAL IHCS


4. Copy baris berikut ini ke dalam route (routes/web.php)
		
		//SSO Routes
		Route::get("/sso/checkpoint/{token}", '\App\Services\IHCS\Controllers\SSOLoginController');
		Route::get('/login', '\App\Services\IHCS\Controllers\LoginController@showLoginForm')->name('login');
		Route::post('/logout', '\App\Services\IHCS\Controllers\LoginController@logout')->name('logout');


5. Buat dan sesuaikan parameter berikut ini ke dalam file .env

		SSO_STATUS=true
		SSO_APP_CODE=IHCS-ABSENSI
		SSO_CHECKPOINT_URL=https://ihcs.ptpn4.co.id/login?redirect=/sso/checkpoint/
		SSO_LOGOUT_REDIRECT=https://ihcs.ptpn4.co.id
		SSO_API_URL=https://apisso.ptpn4.co.id:8789/sso/
  
  Penjelasan:
  - SSO_STATUS, menyatakan status aplikasi terhadap PORTAL IHCS, jika boleh diakses melalui SSO (PORTAL IHCS) maka nilai wajib diisi "true", jika tidak maka nilainya wajib diisi "false"
  - SSO_APP_CODE, merupakan kode aplikasi yang dikenalkan ke Server PORTAL IHCS
  - SSO_CHECKPOINT_URL, alamat checkpoint SSO ini disesuaikan dengan konfigurasi di server PORTAL IHCS
  - SSO_LOGOUT_REDIRECT, alamat redirect ketika aplikasi logout. Secara default akan menuju alamat Server Portal SSO https://ihcs.ptpn4.co.id
  - SSO_API_URL, alamat API Portal IHCS. Ini merupakan konstanta yang tidak boleh berubah kecuali karena perubahan alamat api tsb



Demikian, semoga bermanfaat
Salam
