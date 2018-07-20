<?php

use Illuminate\Support\Facades\View;
use Rhinoda\Admin\Models\Menu;

Route::group([
    'module' => 'Admin',
    'namespace' => 'App\Modules\Admin\Controllers',
    'middleware' => 'web'
], function() {

    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');

    // Registration Routes...
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');

    // Password Reset Routes...
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');



});

Route::group([
    'module' => 'Admin',
    'namespace' => 'App\Modules\Admin\Controllers',
    'middleware' => ['web', 'auth']
], function() {
    Route::get(config('admin.homeRoute'), config('admin.homeAction','DashboardController@index'));
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    Route::get('home', 'DashboardController@index');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

});


if (Schema::hasTable('menus')) {
    $menus = Menu::with('children')->where('menu_type', '!=', 0)->orderBy('position')->get();
    View::share('menus', $menus);
    if (! empty($menus)) {
        Route::group([
            'module'     => 'Admin',
            'middleware' => ['web', 'auth'],
            'prefix'     => config('admin.route'),
            'as'         => config('admin.route') . '.',
            'namespace'  => 'App\Modules\Admin\Controllers',
        ], function () use ($menus) {
            foreach ($menus as $menu) {
                switch ($menu->menu_type) {
                    case 1:
                        Route::post(strtolower($menu->plural_name) . '/massDelete', [
                            'as'   => strtolower($menu->plural_name) . '.massDelete',
                            'uses' => ucfirst(camel_case($menu->singular_name)) . 'Controller@massDelete'
                        ]);
                        Route::resource(strtolower($menu->plural_name),
                            ucfirst(camel_case($menu->singular_name)) . 'Controller', ['except' => 'show']);
                        break;
                    case 3:
                        Route::get(strtolower($menu->plural_name), [
                            'as'   => strtolower($menu->plural_name) . '.index',
                            'uses' => ucfirst(camel_case($menu->singular_name)) . 'Controller@index',
                        ]);
                        break;
                }
            }
        });
    }
}

Route::group([
    'module'     => 'Admin',
    'namespace'  => 'App\Modules\Admin\Controllers',
    'middleware' => ['web', 'auth']
], function () {
    // Menu routing
    Route::get(config('admin.route') . '/menu', [
        'as'   => 'menu',
        'uses' => 'MenuController@index'
    ]);
    Route::post(config('admin.route') . '/menu', [
        'as'   => 'menu',
        'uses' => 'MenuController@rearrange'
    ]);

    Route::get(config('admin.route') . '/menu/edit/{id}', [
        'as'   => 'menu.edit',
        'uses' => 'MenuController@edit'
    ]);
    Route::post(config('admin.route') . '/menu/edit/{id}', [
        'as'   => 'menu.edit',
        'uses' => 'MenuController@update'
    ]);

    Route::get(config('admin.route') . '/menu/crud', [
        'as'   => 'menu.crud',
        'uses' => 'MenuController@createCrud'
    ]);
    Route::post(config('admin.route') . '/menu/crud', [
        'as'   => 'menu.crud.insert',
        'uses' => 'MenuController@insertCrud'
    ]);

    Route::get(config('admin.route') . '/menu/parent', [
        'as'   => 'menu.parent',
        'uses' => 'MenuController@createParent'
    ]);
    Route::post(config('admin.route') . '/menu/parent', [
        'as'   => 'menu.parent.insert',
        'uses' => 'MenuController@insertParent'
    ]);

    Route::get(config('admin.route') . '/menu/custom', [
        'as'   => 'menu.custom',
        'uses' => 'MenuController@createCustom'
    ]);
    Route::post(config('admin.route') . '/menu/custom', [
        'as'   => 'menu.custom.insert',
        'uses' => 'MenuController@insertCustom'
    ]);

    Route::get(config('admin.route') . '/actions', [
        'as'   => 'actions',
        'uses' => 'UserActionsController@index'
    ]);
    Route::get(config('admin.route') . '/actions/ajax', [
        'as'   => 'actions.ajax',
        'uses' => 'UserActionsController@table'
    ]);

    Route::get(config('admin.route').'/files',[
        'as'   => 'files',
        'uses' => 'FileController@index'
    ]);
    Route::get(config('admin.route').'/files/{folder}',[
        'as'   => 'folder',
        'uses' => 'FileController@folder'
    ]);
    Route::get(config('admin.route').'/files/{folder}/create-folder',[
        'as'   => 'folder.create',
        'uses' => 'FileController@createFolder'
    ]);
    Route::get(config('admin.route').'/files/{folder}/delete-file',[
        'as'   => 'file.delete',
        'uses' => 'FileController@deleteFile'
    ]);
    Route::post(config('admin.route').'/files/{folder}/upload-file',[
        'as'   => 'file.upload',
        'uses' => 'FileController@uploadFile'
    ]);
    Route::get(config('admin.route').'/files/{folder}/rename',[
        'as'   => 'file.rename',
        'uses' => 'FileController@edit'
    ]);
    Route::post(config('admin.route').'/files/{folder}/rename',[
        'as'   => 'file.rename',
        'uses' => 'FileController@rename'
    ]);
    Route::post(config('admin.route').'/files/{folder}/move',[
        'as'   => 'file.move',
        'uses' => 'FileController@move'
    ]);

});