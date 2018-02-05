<?php

use Illuminate\Database\Seeder;

class WeekdaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ([
                    "Понедельник",
                    "Вторник",
                    "Среда",
                    "Четверг",
                    "Пятница",
                    "Суббота",
                    "Воскресенье"
                 ] as $day){
            \App\Models\Weekday::create([
                "name" => $day
            ]);
        }
    }
}
