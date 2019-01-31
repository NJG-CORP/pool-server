<?php


namespace App\Services;


class WorkTimeService
{
    const WEEKDAY_LABELS = [
        1 => [
            "name" => "Понедельник",
            "short" => "Пн",
        ],
        2 => [
            "name" => "Вторник",
            "short" => "Вт",
        ],
        3 => [
            "name" => "Среда",
            "short" => "Ср",
        ],
        4 => [
            "name" => "Четверг",
            "short" => "Чт",
        ],
        5 => [
            "name" => "Пятница",
            "short" => "Пт",
        ],
        6 => [
            "name" => "Суббота",
            "short" => "Сб",
        ],
        7 => [
            "name" => "Воскресенье",
            "short" => "Вс",
        ],
    ];

    public static function getHtml($workTime)
    {
        $html = [];
        $start = null;
        $last = [
            "start" => '',
            "end" => ''
        ];
        $diffDays = [];
        $i = 0;

        foreach ($days = self::getDayArray($workTime) as $day => $options) {
            if ($last['start'] === '') {
                $last = $options;
                $diffDays[$i]['s'] = $last;
            }
            if ($last['start'] !== $options['start'] || $last['end'] !== $options['end']) {
                $diffDays[$i]['e'] = $last;
                $i++;
                $diffDays[$i]['s'] = $options;
            }
            $last = $options;
        }
        $diffDays[$i]['e'] = $last;

        foreach ($diffDays as $day) {
            if ($day['s']['short'] === $day['e']['short']) {
                $html[] = "{$day['s']['short']} {$day['s']['start']}-{$day['e']['end']}";
            } else
                $html[] = "{$day['s']['short']}-{$day['e']['short']} {$day['s']['start']}-{$day['e']['end']}";
        }

        return implode(",<br>", $html);
    }

    private static function getDayArray($workTimes)
    {
        $days = [];
        foreach ($workTimes as $workTime) {
            $days[] = [
                'start' => date('H:i', strtotime($workTime->from)),
                'end' => date('H:i', strtotime($workTime->to)),
                'short' => self::getLabelForDay($workTime->weekday_id)['short']
            ];
        }

        return $days;
    }

    public static function getLabelForDay($weekdayId)
    {
        return self::WEEKDAY_LABELS[$weekdayId];
    }
}