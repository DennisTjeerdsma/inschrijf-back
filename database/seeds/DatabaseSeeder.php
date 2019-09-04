<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      // May be you see this line in a comment, please uncomment or write the next line
        $this->call(UsersTableSeeder::class);
    }
}
