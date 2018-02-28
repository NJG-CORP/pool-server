<?php

use App\Models\Club;
use App\Models\Image;
use App\Models\Rating;
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
            $club = Club::create([
                "name" => $faker->company,
                "description" => substr($faker->text, 0, 100),
                "location_id" => $row,
            ]);
            foreach ( range(1, random_int(0, 3)) as $rating ){
                Rating::create([
                    "rater_id" => $rating,
                    'rateable_id' => $row,
                    'score' => random_int(1, 5),
                    'rateable_type' => Club::class,
                    'comment' => $faker->text(25)
                ]);
            }
            $image = Image::create([
                "imageable_id" => $row,
                "imageable_type" => \App\Models\Club::class,
                "path" => "/placeholder.jpg"
            ]);
        }
    }
}
