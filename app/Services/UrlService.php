<?php


namespace App\Services;


class UrlService
{
    public static function getMetaTitle(string $title)
    {
        return $title . ' | ' . config('app.name');
    }
}