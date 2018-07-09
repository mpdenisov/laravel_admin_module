<?php
namespace Rhinoda\Admin\Controllers;

use Rhinoda\Admin\Controllers\Controller;
use Rhinoda\Admin\Models\UsersLogs;
use Yajra\Datatables\Datatables;

class UserActionsController extends Controller
{
    /**
     * Show User actions log
     *
     * @return Response
     */
    public function index()
    {
        return view('Admin::logs.index');
    }

    public function table()
    {
        return Datatables::of(UsersLogs::with('users')->orderBy('id', 'desc'))->make(true);
    }
}