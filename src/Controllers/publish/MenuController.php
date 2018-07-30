<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Rhinoda\Admin\Builders\ControllerBuilder;
use Rhinoda\Admin\Builders\MigrationBuilder;
use Rhinoda\Admin\Builders\ModelBuilder;
use Rhinoda\Admin\Builders\RequestBuilder;
use Rhinoda\Admin\Builders\ViewsBuilder;
use Rhinoda\Admin\Cache\QuickCache;
use Rhinoda\Admin\Fields\FieldsDescriber;
use App\Models\Menu;

class MenuController extends Controller
{

    /**
     * Quickadmin menu list page
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $menusList = Menu::with(['children'])
            ->where('menu_type', '!=', 0)
            ->where('parent_id', null)
            ->orderBy('position')->get();

        return view('Admin::menu.index', compact('menusList'));
    }

    /**
     * Rearrange quickadmin menu items
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rearrange(Request $request)
    {
        $menusList = Menu::with(['children'])
            ->where('menu_type', '!=', 0)
            ->where('parent_id', null)
            ->orderBy('position')->get();
        foreach ($menusList as $menu) {
            if ($menu->children()->first() == null) {
                $menu->position = $request->{'menu-' . $menu->id};
                $menu->save();
            } else {
                $menu->position = $request->{'menu-' . $menu->id};
                $menu->save();
                foreach ($menu->children as $child) {
                    $child->position = $request->{'child-' . $child->id};
                    $child->parent_id = $request->{'child-parent-' . $child->id};
                    $child->save();
                }
            }
        }

        return redirect()->back();
    }

    /**
     * Show new menu creation page
     * @return \Illuminate\View\View
     */
    public function createCrud()
    {
        $fieldTypes = FieldsDescriber::types();
        $fieldValidation = FieldsDescriber::validation();
        $defaultValuesCbox = FieldsDescriber::default_cbox();
        $menusSelect = Menu::whereNotIn('menu_type', [2, 3])->pluck('title', 'id');
        $roles = Role::all();
        $parentsSelect = Menu::where('menu_type', 2)->pluck('title', 'id')->prepend('-- no parent --', '');
        // Get columns for relationship
        $models = [];
        foreach (Menu::whereNotIn('menu_type', [2, 3])->get() as $menu) {
            $tableName = strtolower($menu->plural_name);
            $models[$menu->id] = Schema::getColumnListing($tableName);
        }

        return view("Admin::menu.createCrud",
            compact('fieldTypes', 'fieldValidation', 'defaultValuesCbox', 'menusSelect', 'models', 'parentsSelect',
                'roles'));
    }

    /**
     * Insert new menu
     *
     * @param Request $request
     *
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function insertCrud(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'singular_name' => 'required|unique:menus,singular_name',
            'plural_name' => 'required|unique:menus,plural_name',
            'title' => 'required',
            'soft' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        // Get model names
        $menus = Menu::all();
        $models = [];
        foreach ($menus as $menu) {
            $tableName = strtolower($menu->plural_name);
            $models[$menu->id] = $tableName;
        }
        // Init QuickCache
        $cache = new QuickCache();
        $cached = [];
        $cached['relationships'] = 0;
        $cached['files'] = 0;
        $cached['password'] = 0;
        $cached['date'] = 0;
        $cached['datetime'] = 0;
        $cached['enum'] = 0;
        $fields = [];

        if(!is_null($request->f_type)){
        foreach ($request->f_type as $index => $field) {
            $fields[$index] = [
                'type' => $field,
                'title' => $request->f_title[$index],
                'label' => $request->f_label[$index],
                'helper' => $request->f_helper[$index],
                'validation' => $request->f_validation[$index],
                'value' => $request->f_value[$index],
                'default' => $request->f_default[$index],
                'relationship_id' => $request->has('f_relationship.' . $index) ? $request->f_relationship[$index] : '',
                'relationship_name' => $request->has('f_relationship.' . $index) ? isset($models[$request->f_relationship[$index]]) ? $models[$request->f_relationship[$index]] : '' : '',
                'relationship_field' => $request->has('f_relationship_field.' . $request->f_relationship[$index]) ? $request->f_relationship_field[$request->f_relationship[$index]] : '',
                'texteditor' => $request->f_texteditor[$index],
                'size' => $request->f_size[$index] * 1024,
                'show' => $request->f_show[$index],
                'dimension_h' => $request->f_dimension_h[$index],
                'dimension_w' => $request->f_dimension_w[$index],
                'enum' => $request->f_enum[$index],
            ];
            if ($field == 'relationship') {
                $cached['relationships']++;
            } elseif ($field == 'file' || $field == 'photo') {
                $cached['files']++;
            } elseif ($field == 'password') {
                $cached['password']++;
            } elseif ($field == 'date') {
                $cached['date']++;
            } elseif ($field == 'datetime') {
                $cached['datetime']++;
            } elseif ($field == 'enum') {
                $cached['enum']++;
            }
        }
        }
        $cached['fields'] = $fields;
        $cached['singular_name'] = $request->singular_name;
        $cached['plural_name'] = $request->plural_name;
        $cached['soft_delete'] = $request->soft;
        $cache->put('fieldsinfo', $cached);

        // Create menu entry
        $menu = Menu::create([
            'position' => 0,
            'icon' => $request->icon != '' ? $request->icon : 'fa-database',
            'singular_name' => $request->singular_name,
            'plural_name' => $request->plural_name,
            'title' => $request->title,
            'parent_id' => $request->parent_id ?: null,
        ]);
        $menu->roles()->sync($request->input('roles', []));
        // Create migrations
        $migrationBuilder = new MigrationBuilder();
        $migrationBuilder->build();
        // Create model
        $modelBuilder = new ModelBuilder();
        $modelBuilder->build();
        // Create request
        $requestBuilder = new RequestBuilder();
        $requestBuilder->build();
        // Create controller
        $controllerBuilder = new ControllerBuilder();
        $controllerBuilder->build();
        // Create views
        $viewsBuilder = new ViewsBuilder();
        $viewsBuilder->build();

        // Call migrations
        Artisan::call('migrate');

        // Destroy our cache file
        $cache->destroy('fieldsinfo');

        return redirect(config('admin.homeRoute'));
    }

    /**
     * Show create parent page
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createParent()
    {
        $roles = Role::all();

        return view('Admin::menu.createParent', compact('roles'));
    }

    /**
     * Insert our fresh parent page
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function insertParent(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        $menu = Menu::create([
            'position' => 0,
            'menu_type' => 2,
            'icon' => $request->icon != '' ? $request->icon : 'fa-database',
            'singular_name' => ucfirst(camel_case($request->title)),
            'plural_name' => ucfirst(camel_case($request->title)),
            'title' => $request->title,
            'parent_id' => null
        ]);
        $menu->roles()->sync($request->input('roles', []));

        return redirect()->route('menu');
    }

    /**
     * Create custom controller page
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createCustom()
    {
        $parentsSelect = Menu::where('menu_type', 2)->pluck('title', 'id')->prepend('-- no parent --', '');
        $roles = Role::all();

        return view('Admin::menu.createCustom', compact('parentsSelect', 'roles'));
    }

    /**
     * Insert custom controller
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function insertCustom(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'name' => 'required|unique:menus,singular_name',
            'title' => 'required'
        ]);


        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }

        $menu = Menu::create([
            'position' => 0,
            'menu_type' => 3,
            'icon' => $request->icon != '' ? $request->icon : 'fa-database',
            'singular_name' => $request->name,
            'plural_name' => $request->name,
            'title' => $request->title,
            'parent_id' => $request->parent_id ?: null,

        ]);

        // Create controller
        $controllerBuilder = new ControllerBuilder();
        $controllerBuilder->buildCustom($request->name);

        // Create views
        $viewsBuilder = new ViewsBuilder();
        $viewsBuilder->buildCustom($request->name);

        $menu->roles()->sync($request->input('roles', []));

        return redirect()->route('menu');
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $parentsSelect = Menu::where('menu_type', 2)->pluck('title', 'id')->prepend('-- no parent --', '');
        $roles = Role::all();

        return view('Admin::menu.edit', compact('menu', 'parentsSelect', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $requestArray = $request->all();
        $requestArray['parent_id'] = (isset($requestArray['parent_id']) && !empty($requestArray['parent_id'])) ? $requestArray['parent_id'] : null;
        $menu = Menu::findOrFail($id);
        $menu->update($requestArray);
        $menu->roles()->sync($request->input('roles', []));

        return redirect()->route('menu');
    }
}


