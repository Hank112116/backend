<?php

namespace Backend\Http\Controllers;

use Backend\Facades\Log;

use Illuminate\Http\Request;
use Noty;

class AuthController extends BaseController
{

    public function login(Request $request)
    {
        $cert = [
            'email'    => $request->get('email'),
            'password' => $request->get('password')
        ];

        $login_status = true;

        if (!auth()->attempt($cert)) {
            Noty::warn('Login fail');
            $login_status = false;
        }

        $log_action = 'Log in';
        $log_data   = [
            'email'   => $cert['email'],
            'success' => $login_status
        ];
        Log::info($log_action, $log_data);

        if ($login_status) {
            return $this->loginSuccess();
        } else {
            return redirect('/');
        }
    }

    public function oauthLogin(Request $request)
    {

    }

    private function loginSuccess()
    {
        $user =  auth()->user();

        $this->request->session()->put('cert', $user->role->cert);
        $this->request->session()->put('admin', $user->id);

        Noty::success('Welcome, ' . $user->name . ". Login success");

        return redirect('/');
    }

    public function logout()
    {
        auth()->logout();
        Noty::success('Logout Success');

        return redirect('/');
    }
}
