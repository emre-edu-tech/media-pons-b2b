<?php

use App\Category;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // timestamps
        $now = Carbon::now()->toDateTimeString();

        Category::insert([
            ['name' => 'Wein, Spirituosen & Tabak', 'slug' => 'wein-spirituosen-tabak', 'parent_id' => NULL, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Obst & Gemüse', 'slug' => 'obst-gemuese', 'parent_id' => NULL, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Frische & Kühlung', 'slug' => 'frische-kuehlung', 'parent_id' => NULL, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Nahrungsmittel', 'slug' => 'nahrungsmittel', 'parent_id' => NULL, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tiefkühl', 'slug' => 'tiefkuehl', 'parent_id' => NULL, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Süßes & Salziges', 'slug' => 'suesses-salziges', 'parent_id' => NULL, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kaffee, Tee & Kakao', 'slug' => 'kaffee-tee-kakao', 'parent_id' => NULL, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tabak & Zigaretten', 'slug' => 'wein-spirituosen-tabak-tabak-zigaretten', 'parent_id' => NULL, 'parent_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Obst', 'slug' => 'obst-gemuese-obst', 'parent_id' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Gemüse', 'slug' => 'obst-gemuese-gemuese', 'parent_id' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kaffee', 'slug' => 'kaffee-tee-kakao-kaffee', 'parent_id' => 7, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tabak', 'slug' => 'tabak', 'parent_id' => 7, 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->command->info('Categories table seeded!');
    }
}
