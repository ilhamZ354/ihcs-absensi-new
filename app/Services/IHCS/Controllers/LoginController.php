<?php

namespace App\Services\IHCS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        if(env('SSO_STATUS')) {
            return redirect()->to(env('SSO_CHECKPOINT_URL').env('SSO_APP_CODE', 'IHCS-ABSENSI'));
        } else {
            return view('auth.login');
        }
    }

    public function logout()
    {
        \Auth::logout();
        return redirect(env('SSO_STATUS') ? env('SSO_LOGOUT_REDIRECT', 'http://sso.ptpn4.co.id') : "/");
    }

}
