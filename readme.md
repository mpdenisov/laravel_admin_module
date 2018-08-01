<a href='https://svgshare.com/s/7d4' ><img src='https://svgshare.com/i/7d4.svg' title='' /></a>
 
[![GitHub issues](https://img.shields.io/github/issues/mpdenisov/laravel_admin_module.svg)](https://github.com/mpdenisov/laravel_admin_module/issues)
[![GitHub forks](https://img.shields.io/github/forks/mpdenisov/laravel_admin_module.svg)](https://github.com/mpdenisov/laravel_admin_module/network)
[![GitHub stars](https://img.shields.io/github/stars/mpdenisov/laravel_admin_module.svg)](https://github.com/mpdenisov/laravel_admin_module/stargazers)
[![GitHub license](https://img.shields.io/github/license/mpdenisov/laravel_admin_module.svg)](https://github.com/mpdenisov/laravel_admin_module)
[![Laravel Support](https://img.shields.io/badge/Laravel-5.6-brightgreen.svg)]()
## Rhinoda Admin Module

Role management,creating CRUD controllers and file manager  for Laravel framework

## Contents
- [Installation](#installation)
- [Configuration](#configuration)
    - [Entrast](#entrast)
        - [Description](#description)
        - [Link to package guide](https://github.com/Zizaco/entrust#installation)
- [Usage](#usage)
    - [First User](#first-user)
    - [Menu item creating](#item-creating) 
    - [CRUD](#crud)
    - [Controller](#controller)
    - [File management](#file-management)
- [Troubleshooting](#troubleshooting)
- [License](#license)
- [Contribution guidelines](#contribution-guidelines)

## Installation

1.Change CACHE_DRIVER=array in env.

2.Remove user table from  migration 

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

6.Run the command below to publish  Rhinoda And Entrust files :

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

11.Remove Auth Routes from  web.php

12.Run migration 

````
php artisan migrate
````

## Configuration

   ### Entrast
    
   In this module already created all general models, which will be used.

   For  more information about Role-based Permission: 
   
   [Link to package guide](https://github.com/Zizaco/entrust#installation)
   
 
   
## Usage

   ### First User
    
    php artisan admin:install
   
   ### Item creating 
   
   ### CRUD
     
   After creation CRUD controller, immediately created:
     
   * Model in App\Models folder
   
   * Controller in App\Http\Controllers\Admin folder
   
   * Requests in  App\Http\Requests folder
   
   * views  in  resource\views\admin\\[crud_name] folder
     
   ### Controller
     
   After creation custom controller, immediately created:
   
   * Controller in App\Http\Controllers\Admin folder
   
   * views  in  resource\views\admin\\[crud_name] folder
   
   ### File management
   
   Directory: public/admin,
   
   You can upload and edit files.
   
     
## Troubleshooting

  
  
## License

Rhinoda Admin is free software distributed under the terms of the MIT license. 

## Contribution guidelines

Support follows PSR-1 and PSR-4 PHP coding standards, and semantic versioning.

Please report any issue you find in the issues page.

Pull requests are welcome.