<?php


namespace App\Services;


class LocationHelper
{

    public static function decorateQuery($query, array $coords)
    {
        if (array_key_exists('lat', $coords)) {
            if (array_key_exists('min', $coords['lat'])) {
                $query->whereHas('location', function ($query) use ($coords) {
                    return $query->where('latitude', '>', $coords['lat']['min']);
                });
            }
            if (array_key_exists('max', $coords['lat'])) {
                $query->whereHas('location', function ($query) use ($coords) {
                    return $query->where('latitude', '<=', $coords['lat']['max']);
                });
            }
        }
        if (array_key_exists('lng', $coords)) {
            if (array_key_exists('min', $coords['lng'])) {
                $query->whereHas('location', function ($query) use ($coords) {
                    return $query->where('longitude', '>=', $coords['lng']['min']);
                });
            }
            if (array_key_exists('max', $coords['lng'])) {
                $query->whereHas('location', function ($query) use ($coords) {
                    return $query->where('longitude', '<=', $coords['lng']['max']);
                });
            }
        }
        return $query;
    }
}