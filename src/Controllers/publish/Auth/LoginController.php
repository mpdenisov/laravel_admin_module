<?php

namespace App\Modules\Admin\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers AS AuthenticatesUsers;
use App\Modules\Admin\Controllers\AdminController AS AdminController;

class LoginController extends AdminController
{

    /*
        |--------------------------------------------------------------------------
        | Login Controller
        |--------------------------------------------------------------------------
        |
        | This controller handles authenticating users for the application and
        | redirecting them to your home screen. The controller uses a trait
        | to conveniently provide its functionality to your applications.
        |
        */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->redirectTo = config('admin.homeRoute');
    }

}