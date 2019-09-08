<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->truncate(); // truncate user table each time of seeders run
        $user = User::create([ // create a new user
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'name' => 'Administrator',
        ]);

        $user->assignRole('Super Admin');
    }
}


