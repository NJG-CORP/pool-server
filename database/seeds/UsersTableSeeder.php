<?php

use App\Models\Rating;
use App\Models\User;
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
        $faker = \Faker\Factory::create("ru_RU");
//        $gameTypeVocabulary = \Taxonomy::getVocabularyByName('GameType');
//        $gamePaymentTypeVocabulary = \Taxonomy::getVocabularyByName('GamePaymentType');

        foreach (range(1, 10) as $row){
            $loc = \App\Models\Location::create([
                "city_id" => 7700000000000,
                "latitude" => $faker->latitude,
                "longitude" => $faker->longitude,
                "address" => $faker->address,
            ]);
            $user = User::create([
                "name" => $faker->firstName,
                "surname" => $faker->lastName,
                'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
                'remember_token' => str_random(10),
                "api_token" => str_random(24),
                "email" => $faker->email,
                "age" => $faker->numberBetween(16, 40),
                "location_id" => $loc->id,
                "city_id" => 7700000000000,
                "status" => true,
                "gender" => \random_int(0, 1),
                "game_time_from" => 0,
                "game_time_to" => 7000 * $row
            ]);
            $image = Image::create([
                "imageable_id" => $row,
                "imageable_type" => User::class,
                "path" => "/placeholder.jpg"
            ]);
            foreach ( range(1, random_int(0, 5)) as $rating ){
                Rating::create([
                    "rater_id" => $row+1,
                    'rateable_id' => $row,
                    'score' => random_int(1, 5),
                    'rateable_type' => User::class,
                    'comment' => $faker->text(25)
                ]);
            }
            \DB::table('game_time')->insert([
                'user_id' => $row,
                "weekday_id" => min($row, 7)
            ]);
            $user->addTerm(random_int(1, 3)); //gameType
            $user->addTerm(random_int(4, 5)); //gamePaymentType
        }
        $loc = \App\Models\Location::create([
            "city_id" => 11,
            "latitude" => $faker->latitude,
            "longitude" => $faker->longitude,
            "address" => $faker->address,
        ]);
        User::create([
            "name" => "Никита",
            "surname" => "Кольцов",
            'password' => bcrypt("123456"),
            'remember_token' => str_random(10),
            "api_token" => str_random(24),
            "email" => "tooy_m@mail.ru",
            "age" => 25,
            "location_id" => $loc->id,
            "city_id" => 11,
            "status" => false
        ]);
    }
}
