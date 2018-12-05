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
        $weekdays = [
            'monday' => 'Понедельник',
            'tuesday' => 'Вторник',
            'wednesday' => 'Среда',
            'thursday' => 'Четверг',
            'friday' => 'Пятница',
            'saturday' => 'Суббота',
            'sunday' => 'Воскресенье',
            ];

        foreach ($weekdays as $key => $name){
            \App\Models\Weekday::create([
                "key" => $key,
                "name" => $name,
            ]);
        }
    }
}
