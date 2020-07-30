<?php

use Illuminate\Database\Seeder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $credentials = [
            'first_name' => 'Администратор',
            'last_name' => 'Системы',
            'email'    => 'admin@site.ru',
            'password' => 'admin',
        ];
        
        $user = Sentinel::registerAndActivate($credentials);
        $role = Sentinel::findRoleById(1);

        $role->users()->attach($user);
    }
}
