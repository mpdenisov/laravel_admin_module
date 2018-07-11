<?php

namespace Rhinoda\Admin\Commands;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Console\Command;
use App\Models\Menu;
use App\Models\Role;

class AdminInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:install';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run installation of QuickAdmin.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this->createRole();
        $this->info('Create first user');
        $this->createUser();
        $this->info('User was created ');
    }


    public function createRole()
    {

    }

    /**
     *  Create first user
     */
    public function createUser()
    {
        $adminRole = new Role();
        $adminRole->name         = Role::ADMIN;
        $adminRole->display_name = 'User Administrator';
        $adminRole->description  = 'User is allowed to manage and edit other users';
        $adminRole->save();

        $hostRole = new Role();
        $hostRole->name         = Role::HOST;
        $hostRole->display_name = 'Host';
        $hostRole->description  = 'User that posts dishes';
        $hostRole->save();

        $clientRole = new Role();
        $clientRole->name         = Role::CLIENT;
        $clientRole->display_name = 'Client';
        $clientRole->description  = 'User that looks for dishes';
        $clientRole->save();

        $createDishPermission = new Permission();
        $createDishPermission->name         = 'create-dish';
        $createDishPermission->display_name = 'Create Dishes';
        $createDishPermission->description  = 'create new dinner dish';
        $createDishPermission->save();

        $updateDishPermission = new Permission();
        $updateDishPermission->name         = 'update-dish';
        $updateDishPermission->display_name = 'Update Dishes';
        $updateDishPermission->description  = 'update existing dishes';
        $updateDishPermission->save();

        $hostRole->attachPermission($createDishPermission);
        $hostRole->attachPermission($updateDishPermission);

        $admin = User::create([
            'first_name' => $this->ask('Administrator name'),
            'email' => $this->ask('Administrator email'),
            'password' => bcrypt($this->secret('Administrator password'))
        ]);

        $admin->attachRole($adminRole);
    }

}
