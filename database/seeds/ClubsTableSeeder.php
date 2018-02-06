<?php

use Illuminate\Database\Seeder;

class ClubsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create("ru_RU");

        foreach (range(1, 10) as $row){
            \App\Models\Club::create([
                "name" => $faker->company,
                "description" => substr($faker->text, 0, 100),
                "location_id" => $row,
            ]);
        }
    }
}
