<?php

namespace App\Services\IHCS\Providers;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Services\IHCS\Foundation\Curly;
use App\Services\IHCS\Models\SSOUser;
use App\Services\IHCS\Exceptions\SSOException;

class SSOUserProvider implements UserProvider
{
    use Curly;

    private $body;

    /**
     * The hasher implementation.
     *
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;

    /**
     * The Eloquent user model.
     *
     * @var string
     */
    protected $model;

    /**
     * Create a new database user provider.
     *
     * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
     * @param  string  $model
     * @return void
     */
    public function __construct(Hasher $hasher, $model)
    {
        $this->model = $model;
        $this->hasher = $hasher;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier) {
        // cek token dengan identifier dan kode aplikasi sendiri
        $params = [
            'token' => $identifier,
            'appCode' => env('SSO_APP_CODE'),
        ];

        $this->ssoCurl('exchange-token', $params);        
        $apiResponse = json_decode($this->body, true);

        if(!$apiResponse['status']) {
            throw new SSOException("Token tidak valid");
        } else {
            $sso_session_token = $apiResponse['data']['token'];
        }

        if(!is_null($sso_session_token)) {
            //get user information from sso api
            $params = [
                'sessionKey' => $sso_session_token,
                'appCode' => env('SSO_APP_CODE'),
            ];

            $this->ssoCurl('get-auth-user', $params);

            $userResult = (object) json_decode($this->body, true);

            if (is_null($userResult->status) || $userResult->status == "USER_BLOCKED") {
                throw new SSOException("User diblokir!!! Terlalu sering salah password. Hubungi administrator untuk masalah ini");
            }else if (is_null($userResult->status) || $userResult->status == "WRONG_PASS" || $userResult->status == 'USER_NOT_FOUND') {
                throw new SSOException("User atau password anda salah");
            } else if ($userResult->status == 'USER_DENIED' || $userResult->status == 'APP_ACCESS_DENIED') {
                throw new SSOException("User tidak memiliki akses");
            } else if ($userResult->status == 'UNKNOWN_APP') {
                throw new SSOException("Aplikasi tidak dikenali");
            } else if ($userResult->status == 'SUCCESS') {                
                $user = new SSOUser($userResult, $sso_session_token);
            } else {
                throw new SSOException("Authentikasi SSO Gagal, status tidak dikenali");
            }
        }

        return $user;
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed   $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token) {
        dd('retrieveByToken');
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token) {
        //dd('updateRememberToken', $user, $token);
        return true;
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials) {
        dd('retrieveByCredentials');
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials) {
        dd('validateCredentials');
    }
}
