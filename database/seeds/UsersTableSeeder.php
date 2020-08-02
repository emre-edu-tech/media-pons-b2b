<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        $path = 'database/Developer_docs/add_users.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Users table seeded!');
    }
}
