<?php

namespace Rhinoda\Admin;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        require_once(__DIR__ . '/database/seeds/DatabaseSeeder.php');
        require_once(__DIR__ . '/database/seeds/UsersRolesPermissionsTablesSeeder.php');

        $this->publishes([
            __DIR__ . 'database/migrations/' => database_path('migrations')
        ], 'migrations');
        $this->publishes([
            __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'admin' => base_path('public' . DIRECTORY_SEPARATOR . 'admin'),
            //Adding Models
            __DIR__ . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'publish' . DIRECTORY_SEPARATOR . 'Role' => app_path('Models/Role.php'),
            __DIR__ . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'publish' . DIRECTORY_SEPARATOR . 'Menu' => app_path('Models/Menu.php'),
            __DIR__ . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'publish' . DIRECTORY_SEPARATOR . 'Permission' => app_path('Models/Permission.php'),
            __DIR__ . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'publish' . DIRECTORY_SEPARATOR . 'User' => app_path('Models/User.php'),
            __DIR__ . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'publish' . DIRECTORY_SEPARATOR . 'UsersLogs' => app_path('Models/UsersLogs.php'),

            // Adding Contollers
            __DIR__ . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'publish'  => app_path('Modules/Admin/Controllers'),
            // Adding resource
            __DIR__ . DIRECTORY_SEPARATOR . 'resources'   => app_path('Modules/Admin/resources'),
            // Adding Builders
            __DIR__ . DIRECTORY_SEPARATOR . 'Builders' . DIRECTORY_SEPARATOR . 'publish' => app_path('Modules/Admin/Builders'),
            // Adding Cache
            __DIR__ . DIRECTORY_SEPARATOR . 'Cache' . DIRECTORY_SEPARATOR . 'publish' => app_path('Modules/Admin/Cache'),
            // Adding Fields
            __DIR__ . DIRECTORY_SEPARATOR . 'Fields'.DIRECTORY_SEPARATOR . 'publish'  => app_path('Modules/Admin/Fields'),
            // Adding Requests
            __DIR__ . DIRECTORY_SEPARATOR . 'Requests'.DIRECTORY_SEPARATOR . 'publish'  => app_path('Modules/Admin/Requests'),
            // Adding Templates
            __DIR__ . DIRECTORY_SEPARATOR . 'Templates' => app_path('Modules/Admin/Templates'),
            //Adding Traits
            __DIR__ . DIRECTORY_SEPARATOR . 'Traits'.DIRECTORY_SEPARATOR . 'publish'  => app_path('Modules/Admin/Traits'),
            // Adding Controller.php
            __DIR__ . DIRECTORY_SEPARATOR . 'Controller.php'  => app_path('Modules/Controller.php'),
            // Adding Configs
            __DIR__ . DIRECTORY_SEPARATOR . 'config'  => config_path(),
            // Adding Providers
            __DIR__ . DIRECTORY_SEPARATOR . 'Providers'. DIRECTORY_SEPARATOR . 'ModulesServiceProvider' => app_path('Providers/ModulesServiceProvider.php'),
            // Adding Observer
            __DIR__ . DIRECTORY_SEPARATOR . 'Observers'.DIRECTORY_SEPARATOR . 'publish'  => app_path('Modules/Admin/Observers'),
        ], 'RhinodAdmin');

    }

    public function register()
    {


    }
}