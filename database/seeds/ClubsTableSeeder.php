<?php

use App\Models\Image;
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
            $club = \App\Models\Club::create([
                "name" => $faker->company,
                "description" => substr($faker->text, 0, 100),
                "location_id" => $row,
            ]);
            $image = Image::create([
                "imageable_id" => $row,
                "imageable_type" => \App\Models\Club::class,
                "path" => "/placeholder.jpg"
            ]);
        }
    }
}
