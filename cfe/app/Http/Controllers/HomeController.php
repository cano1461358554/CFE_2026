<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function adminDashboard()
    {
        return view('dashboards.admin');
    }

    public function encargadoDashboard()
    {
        return view('dashboards.encargado');
    }

    public function empleadoDashboard()
    {
        return view('dashboards.empleado');
    }
}
