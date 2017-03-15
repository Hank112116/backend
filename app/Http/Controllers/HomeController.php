<?php

namespace Backend\Http\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        return view('home.home');
    }
}
