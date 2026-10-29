<?php

namespace App\Services\IHCS\Auth;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\SessionGuard;
use App\Services\IHCS\Foundation\Curly;
use App\Services\IHCS\Exceptions\SSOException;

class SSOServiceGuard extends SessionGuard
{

    use Curly;

    // private $ssoApi = 'http://localhost/hcis-sso/sso-ws-provider/public/sso/';
    //private $ssoApi = "https://apisso.ptpn4.co.id:8789/sso/";

    private $body;
    private $secretKey = 'SGFyZXZh';

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user() {
        //Cek status logout
        if($this->loggedOut) {
            return;
        }

        //Jika sudah login, maka itu aja return
        if(!is_null($this->user)) {
            return $this->user;
        }

        $id = $this->session->get($this->getName());

        $user = null;

        if(!is_null($id)) {
            $user = $this->session->get($this->secretKey);
            if($user->isNeedToCheckSSO()) {
                $post = [
                    'username' => $user->getAuthIdentifier(),
                    'sessionKey' => $user->getToken(),
                ];
                $this->ssoCurl('check-session', $post);
                $apiResponse = json_decode($this->body, true);
                
                if(!$apiResponse['status']) {
                    throw new SSOException("SSO: Tidak dapat melakukan validasi session", 500);                        
                } else {                    
                    if(!$apiResponse['data']['status']) {
                        session()->flush();
                        throw new \Illuminate\Session\TokenMismatchException("SSO: Session tidak valid", 500);
                    } else {
                        $user->refreshNextCheckTime();
                    }
                }
            }
            $this->fireAuthenticatedEvent($user);
        }

        // If the user is null, but we decrypt a "recaller" cookie we can attempt to
        // pull the user data on that cookie which serves as a remember cookie on
        // the application. Once we have a user we can return it to the caller.
        $recaller = $this->recaller();

        if (is_null($user) && ! is_null($recaller)) {
            $user = $this->getUserByRecaller($recaller);

            if ($user) {
                $this->updateSession($user->getAuthIdentifier());

                $this->fireLoginEvent($user, true);
            }
        }

        return $this->user = $user;
    }

     /**
     * Log a user into the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  bool  $remember
     * @return void
     */
     public function login(AuthenticatableContract $user, $remember = false)
     {
        $this->updateSession($user);

        // If the user should be permanently "remembered" by the application we will
        // queue a permanent cookie that contains the encrypted copy of the user
        // identifier. We will then decrypt this later to retrieve the users.
        if ($remember) {
            $this->createRememberTokenIfDoesntExist($user);

            $this->queueRecallerCookie($user);
        }

        // If we have an event dispatcher instance set we will fire an event so that
        // any listeners will hook into the authentication events and run actions
        // based on the login and logout events fired from the guard instances.
        // $this->fireLoginEvent($user, $remember);

        //dispatch(new \App\Listeners\LogSuccessfulLogin($user));
        $this->setUser($user);
    }

    /**
    * Update the session with the given ID.
    *
    * @param  string  $id
    * @return void
    */
    protected function updateSession($user)
    {
        $this->session->put($this->getName(), $user->getAuthIdentifier());
        $this->session->put($this->secretKey, $user);

        $this->session->migrate(true);
    }
    
    public function logout() {
        session()->flush();
        \Log::info('SSO: User berhasil logout');
    }

}
