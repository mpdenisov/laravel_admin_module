
## Rhinoda Admin Module

Role management and creating CRUD controllers

## Contents
* [Installation](#installation)
* [Configuration](#configuration)
    * [Entrast](#entrast)
        * [Description](#description)
        * [Link to package guide](https://github.com/Zizaco/entrust#installation)
    * [CRUD](#crud)
    * [Controller](#controller)
* [Usage](#usage)
    * [CRUD](#crud-creating)
    * [Controller](#controller-creating)
* [Troubleshooting](#troubleshooting)
* [License](#license)

* [Contribution guidelines](#contribution-guidelines)

## Installation

1.Change CACHE_DRIVER=array in env.

2.Remove user table migration 

3.Install package

````
composer require rhinoda/admin_module
````

4.Open your config/app.php and add the following to the providers array:

````
Zizaco\Entrust\EntrustServiceProvider::class,
Rhinoda\Admin\AdminServiceProvider::class,
````

5.In the same config/app.php and add the following to the aliases array:

````
'Entrust'   => Zizaco\Entrust\EntrustFacade::class,
````

6.Run the command below to publish the package Rhinoda And Entrust:

````
php artisan vendor:publish
````

7.Open your config/auth.php and add the following to it:

````
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
        'table' => 'users',
    ],
],
````

8.Open your config/app.php and add the following to the providers array:

````
App\Providers\ModulesServiceProvider::class,
````

9.Open your config/entrust.php and change  path to models for Role and Permission models

````
'role' => 'App\Models\Role',
'permission' => 'App\Models\Permission',
````

10.Laravel Auth

````
php artisan make:auth
````

10.1.Remove Auth Routes from  web.php

11.Install migration 

````
php artisan migrate
````

## Configuration

   ### Entrast
    
   In this module already created all general models, which will be used.

   For  more information about Role-based Permission: 
   
   [Link to package guide](https://github.com/Zizaco/entrust#installation)
   
   ### CRUD
   
   About CRUD
   
   ### Controller
   
   About Controller
   
## Usage
   ### Crud creating
   
   Content
   
   ### Controller creating
   
   Content 
   
## Troubleshooting

## License

Rhinoda Admin is free software distributed under the terms of the MIT license. 

## Contribution guidelines

Support follows PSR-1 and PSR-4 PHP coding standards, and semantic versioning.

Please report any issue you find in the issues page.
Pull requests are welcome.