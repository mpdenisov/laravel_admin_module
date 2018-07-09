<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;

class DashboardController extends AdminController
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
     * Show Admin dashboard page
     *
     * @return Response
     */
    public function index()
    {
        return view('Admin::dashboard');
    }
}
