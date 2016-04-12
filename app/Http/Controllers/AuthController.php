<?php

namespace Backend\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Input;
use Noty;
use Session;
use Artisan;
use Log;

class AuthController extends BaseController
{

    public function login(Request $request)
    {
        $cert = [
            'email'    => $request->get('email'),
            'password' =>$request->get('password')
        ];

        $login_status = true;

        if (!Auth::attempt($cert)) {
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

    private function loginSuccess()
    {
        $user =  Auth::user();

        Session::put('cert', $user->role->cert);
        Session::put('admin', $user->id);
        Noty::success('Welcome, ' . $user->name . ". Login success");

        return redirect('/user/all');
    }

    public function logout()
    {
        Auth::logout();
        Noty::success('Logout Success');

        return redirect('/');
    }

    public function loginFilter()
    {
        if (Auth::check()) {
            return;
        }

        Noty::warn('No access permission');

        return redirect('/');
    }

    public function adminerFilter()
    {
        return $this->routeFilter($type = 'adminer');
    }

    public function userFilter()
    {
        return $this->routeFilter($type = 'user');
    }

    public function projectFilter()
    {
        return $this->routeFilter($type = 'project');
    }

    public function solutionFilter()
    {
        return $this->routeFilter($type = 'solution');
    }

    public function landingFilter()
    {
        return $this->routeFilter($type = 'marketing');
    }

    public function mailFilter()
    {
        return $this->routeFilter($type = 'email_template');
    }

    public function hubFilter()
    {
        return $this->routeFilter($type = 'hub');
    }

    public function reportFilter()
    {
        return $this->routeFilter($type = 'report');
    }

    public function reportRegistrationFilter()
    {
        return $this->routeFilter($type = [ 'report_full', 'registration_report' ]);
    }

    public function reportCommentFilter()
    {
        return $this->routeFilter($type = [ 'report_full', 'comment_report' ]);
    }

    public function reportEventFilter()
    {
        return $this->routeFilter($type = [ 'report_full', 'event_report' ]);
    }

    public function reportProjectFilter()
    {
        return $this->routeFilter($type = [ 'report_full', 'project_report' ]);
    }

    private function routeFilter($type)
    {
        if (Auth::check() and str_contains(Session::get('cert'), $type)) {
            return;
        }

        Noty::warnLang('common.no-permission');

        return redirect('/');
    }
}
