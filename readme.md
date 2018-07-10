
## Rhinoda Admin Module
Role management and creating CRUD controllers
##Contents
* [Installation](##Installation)
* [Troubleshooting](##Troubleshooting)
* [License](##License)
* [Contribution guidelines](##Contribution guidelines)

##Installation

1.Install package
````
composer require rhinoda/admin_module
````
3.Open your config/app.php and add the following to the providers array:
````
Zizaco\Entrust\EntrustServiceProvider::class,
````
4.In the same config/app.php and add the following to the aliases array:
````
'Entrust'   => Zizaco\Entrust\EntrustFacade::class,
````
5.Run the command below to publish the package Rhinoda And Entrust:
````
php artisan vendor:publish
````
6.Open your config/auth.php and add the following to it:
````
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
        'table' => 'users',
    ],
],
````
7.Open your config/app.php and add the following to the providers array:
````
App\Providers\ModulesServiceProvider::class,
````
8.Open your config/entrust.php and change  path to models for Role and Permission models
````
'role' => 'App\Models\Role',
'permission' => 'App\Models\Permission',
````
9.Change CACHE_DRIVER=array in env.

2.Laravel Auth
````
php artisan make:auth
````
2.1.Remove Auth Routes from  web.php


##License
Rhinoda Admin is free software distributed under the terms of the MIT license. 
##Contribution guidelines
Support follows PSR-1 and PSR-4 PHP coding standards, and semantic versioning.

Please report any issue you find in the issues page.
Pull requests are welcome.