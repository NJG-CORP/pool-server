<?php

use Illuminate\Database\Seeder;

class CityTableSeeder extends Seeder
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
            \App\Models\City::create([
                "name" => $faker->city,
                "id" => pow($row, 2)
            ]);
        }
    }
}
