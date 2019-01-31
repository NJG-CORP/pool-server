<?php


namespace App\Services;


use App\Models\GameType;

class GameTypeService
{
    public static function getLabelsAndCount($games): string
    {
        $labels = GameType::LABELS;
        $result = [];
        foreach ($labels as $col_name => $label) {
            $result[] = $label . ' <span>' . $games[$col_name] . '</span>';
        }

        return implode(',<br>', $result);
    }
}