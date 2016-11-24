<?php

namespace Backend\Http\Controllers;

use Backend\Api\ApiInterfaces\AuthApi\OAuthApiInterface;
use Backend\Facades\Log;
use Backend\Repo\RepoInterfaces\AdminerInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Illuminate\Http\Request;
use Noty;

class AuthController extends BaseController
{
    private $user_repo;
    private $admin_repo;
    private $oauth_api;

    public function __construct(
        UserInterface $user_repo,
        AdminerInterface $admin_repo,
        OAuthApiInterface $oauth_api
    ) {
        parent::__construct();

        $this->user_repo  = $user_repo;
        $this->admin_repo = $admin_repo;
        $this->oauth_api  = $oauth_api;
    }

    public function login(Request $request)
    {
        $email    = $request->get('email');
        $password = $request->get('password');

        $cert = [
            'email'    => $email,
            'password' => $password
        ];

        if (!auth()->attempt($cert)) {
            return $this->loginFail($email);
        }

        $response = $this->oauth_api->clientCredentials();

        if (!$response->isOk()) {
            auth()->logout();
            return $this->loginFail($email);
        } else {

        }

        return $this->loginSuccess();
    }

    public function oauthLogin(Request $request)
    {
        $email    = $request->get('email');
        $password = $request->get('password');

        $user = $this->user_repo->byMail($email);

        if (empty($user)) {
            return $this->loginFail($email);
        }

        $adminer = $this->admin_repo->findHWTrekMember($user->id());

        if (empty($adminer)) {
            return $this->loginFail($email);
        }

        // TODO run OAuth login

        auth()->loginUsingId($adminer->id());

        if (auth()->check() === false) {

            return $this->loginFail($email);
        }

        return $this->loginSuccess();
    }

    private function loginSuccess()
    {
        $user =  auth()->user();


        $this->request->session()->put('cert', $user->role->cert);
        $this->request->session()->put('admin', $user->id);

        Noty::success('Welcome, ' . $user->name . ". Login success");

        $log_action = 'Log in';
        $log_data   = [
            'email'   => $user->email,
            'success' => true
        ];
        Log::info($log_action, $log_data);

        return redirect('/');
    }

    private function loginFail($email)
    {
        Noty::warn('OAuth login fail');

        $log_action = 'Log in';
        $log_data   = [
            'email'   => $email,
            'success' => false
        ];
        Log::info($log_action, $log_data);

        return redirect('/');
    }

    public function logout()
    {
        auth()->logout();
        Noty::success('Logout Success');

        return redirect('/');
    }
}
