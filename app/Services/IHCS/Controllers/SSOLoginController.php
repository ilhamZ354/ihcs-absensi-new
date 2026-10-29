<?php

namespace App\Services\IHCS\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class SSOLoginController extends Controller
{
    public function __invoke($token)
    {
    	Auth::loginUsingId($token);
        return redirect()->intended('/');
    }
}
