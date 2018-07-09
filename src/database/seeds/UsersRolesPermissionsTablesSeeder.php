<?php

use Illuminate\Database\Seeder;
use Rhinoda\Admin\Models\User;
use Rhinoda\Admin\Models\Role;
use Rhinoda\Admin\Models\Permission;

class UsersRolesPermissionsTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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

        $faker = \Faker\Factory::create();

        // Let's make sure everyone has the same password and
        // let's hash it before the loop, or else our seeder
        // will be too slow.
        $password = Hash::make('12345');

        $admin = User::create([
            'first_name' => 'Administrator',
            'email' => 'admin@test.com',
            'password' => $password,
        ]);

        $admin->attachRole($adminRole);

        // And now let's generate users for our app:
        for ($i = 0; $i < 600; $i++) {
            $host = User::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->email,
                'password' => $password,
                'gender' => [User::MALE_GENDER, User::FEMALE_GENDER][rand(0,1)],
                'birth_date' => $faker->dateTimeBetween('-50 years', '-15 years')->format('Y-m-d')
            ]);

            $host->attachRole($hostRole);
        }


        for ($i = 0; $i < 10; $i++) {
            $client = User::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->email,
                'password' => $password,
                'gender' => [User::MALE_GENDER, User::FEMALE_GENDER][rand(0,1)],
                'birth_date' => $faker->dateTimeBetween('-50 years', '-15 years')->format('Y-m-d')
            ]);

            $client->attachRole($clientRole);
        }
    }
}
