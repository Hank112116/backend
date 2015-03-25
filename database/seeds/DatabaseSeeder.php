<?php

use Backend\Model\Eloquent\Adminer;
use Backend\Model\Eloquent\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        $this->call('RoleTableSeeder');
        $this->call('AdminTableSeeder');
    }
}

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        Role::truncate();

        $admin = new Role;
        $admin->name = 'admin';
        $admin->cert = implode(',', Config::get('cert.default_admin'));
        $admin->save();

        $manager = new Role;
        $manager->name = 'manager';
        $manager->cert = implode(',', Config::get('cert.default_manager'));
        $manager->save();
    }
}

class AdminTableSeeder extends Seeder
{
    public function run()
    {
        Adminer::truncate();

        $admin_role = Role::where('name', '=', 'admin')->first();
        $manager_role = Role::where('name', '=', 'manager')->first();

        $vivienne = new Adminer;
        $vivienne->name  = 'Admin';
        $vivienne->email = 'vivienne.liao@hwtrek.com';
        $vivienne->password = Hash::make('admin');
        $vivienne->role()->associate($admin_role);
        $vivienne->save();

        $vivienne = new Adminer;
        $vivienne->name  = 'Roger';
        $vivienne->email = 'roger@hwtrek.com';
        $vivienne->password = Hash::make('manager');
        $vivienne->role()->associate($manager_role);
        $vivienne->save();

        $vivienne = new Adminer;
        $vivienne->name  = 'Amanda';
        $vivienne->email = 'amanda@hwtrek.com';
        $vivienne->password = Hash::make('manager');
        $vivienne->role()->associate($manager_role);
        $vivienne->save();

        $jaster = new Adminer;
        $jaster->name  = 'Jaster';
        $jaster->email = 'jaster.chung@tmi.vc';
        $jaster->password = Hash::make('0000');
        $jaster->role()->associate($admin_role);
        $jaster->save();

        $manager = new Adminer;
        $manager->name  = 'Test Admin';
        $manager->email = 'ta@tmi.vc';
        $manager->password = Hash::make('0000');
        $manager->role()->associate($admin_role);
        $manager->save();

        $manager = new Adminer;
        $manager->name  = 'Test Manager';
        $manager->email = 'tm@tmi.vc';
        $manager->password = Hash::make('0000');
        $manager->role()->associate($manager_role);
        $manager->save();
    }
}
