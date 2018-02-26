<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
             TaxonomyTableSeeder::class,
             WeekdaysTableSeeder::class,
             CityTableSeeder::class,
             UsersTableSeeder::class,
             ClubsTableSeeder::class
         ]);
    }
}
