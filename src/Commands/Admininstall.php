<?php

namespace Rhinoda\Admin\Commands;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Console\Command;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Support\Facades\Artisan;

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
        Artisan::call('migrate:fresh');
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

        $admin = User::create([
            'first_name' => $this->ask('Administrator name'),
            'email' => $this->ask('Administrator email'),
            'password' => bcrypt($this->secret('Administrator password'))
        ]);

        $admin->attachRole($adminRole);
    }

}
