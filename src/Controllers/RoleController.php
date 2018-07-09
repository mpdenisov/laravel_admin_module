<?php

namespace Rhinoda\Admin\Controllers;

use Rhinoda\Admin\Models\Role;
use Illuminate\Http\Request;
use Rhinoda\Admin\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * @var Role
     */
    protected $roles;

    public function __construct(Role $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Show a list of roles
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $roles = $this->roles->get();

        return view('Admin::role.index', compact('roles'));
    }

    /**
     * Show a page of user creation
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('Admin::role.create');
    }

    /**
     * Insert new role into the system
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->roles->create($request->all());

        return redirect()->route('roles.index')->withMessage(trans('Admin::admin.roles-controller-successfully_created'));
    }

    /**
     * Show a role edit page
     *
     * @param $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $role = $this->roles->findOrFail($id);

        return view('Admin::role.edit', compact('role'));
    }

    /**
     * Update our role information
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->roles->findOrFail($id)->update($request->all());

        return redirect()->route('roles.index')->withMessage(trans('Admin::admin.roles-controller-successfully_updated'));
    }

    /**
     * Destroy specific role
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->roles->findOrFail($id)->delete();

        return redirect()->route('roles.index')->withMessage(trans('Admin::admin.roles-controller-successfully_deleted'));
    }
}

