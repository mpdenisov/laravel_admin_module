<?php

namespace App\Modules\Admin\Controllers\Auth;



use App\Modules\Admin\Controllers\AdminController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;



class ForgotPasswordController extends AdminController
{

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {

        $this->middleware('guest');

    }
}