<?php

use Illuminate\Database\Seeder;

class InvitationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create("ru_RU");

        foreach ( range(1, 11) as $item ){
            foreach (range(1, 11) as $item2){
                $inv = \App\Models\Invitation::create([
                    'inviter_id' => $item2,
                    'invited_id' => $item,
                    'club_id' => random_int(1, 10),
                    'meeting_at' => $item . "-10-2018 " . $item2 . ":30:00"
                ]);
            }
        }
    }
}
