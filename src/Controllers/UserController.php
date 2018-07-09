<?php

namespace Rhinoda\Admin\Controllers;

use Rhinoda\Admin\Models\Role;
use Rhinoda\Admin\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Rhinoda\Admin\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Show a list of users
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::all();

        return view('Admin::user.index', compact('users'));
    }

    /**
     * Show a page of user creation
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::pluck('display_name', 'id');

        return view('Admin::user.create', compact('roles'));
    }

    /**
     * Insert new user into the system
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $role = Role::findOrFail($input['role_id']);
        $user = User::create($input);
        // Attaches role to user
        $user->attachRole($role);

        return redirect()->route('users.index')->withMessage(trans('admin::admin.users-controller-successfully_created'));
    }

    /**
     * Show a user edit page
     *
     * @param $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user  = User::findOrFail($id);
        $roles = Role::pluck('display_name', 'id');

        return view('Admin::user.edit', compact('user', 'roles'));
    }

    /**
     * Update our user information
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $role = Role::findOrFail($input['role_id']);
        $user->update($input);
        // Detaches old role and attaches the new one
        if (!$user->hasRole($role->name)) {
            $user->detachRoles($user->roles);
            $user->attachRole($role);
        }

        return redirect()->route('users.index')->withMessage(trans('admin::admin.users-controller-successfully_updated'));
    }

    /**
     * Destroy specific user
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        User::destroy($id);

        return redirect()->route('users.index')->withMessage(trans('admin::admin.users-controller-successfully_deleted'));
    }
}