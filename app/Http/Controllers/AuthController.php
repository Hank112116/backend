<?php

namespace Backend\Http\Controllers;

use Auth;
use Input;
use Noty;
use Session;

class AuthController extends BaseController
{

    public function login()
    {
        $cert = [
            'email'    => Input::get('email'),
            'password' => Input::get('password')
        ];

        $is_remember = Input::has('remember');

        if (Auth::attempt($cert, $is_remember)) {
            Noty::success('Welcome, ' . Auth::user()->name . ". Login success");
            Session::put('cert', Auth::user()->role->cert);

            return redirect('/user/all');
        } else {

            Noty::warn('Login fail');
            return redirect('/');
        }

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
        return $this->routeFilter($type = 'front_page');
    }

    public function mailFilter()
    {
        return $this->routeFilter($type = 'email_template');
    }

    public function hubFilter()
    {
        return $this->routeFilter($type = 'hub');
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
