<?php

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

        foreach (range(1, 10) as $row){
            User::create([
                "name" => $faker->firstName,
                "surname" => $faker->lastName,
                'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
                'remember_token' => str_random(10),
                "api_token" => str_random(24),
                "email" => $faker->email,
                "age" => $faker->numberBetween(16, 40),
                "verified" => $faker->boolean,
                "verification_token" => str_random()
            ]);
        }
    }
}
