<?php

namespace App\Services;

use App\Models\City;
use GuzzleHttp\Client;

class CityService
{
    public function search($search)
    {
        $query = [
            "query" => $search,
            "contentType" => "city",
            "typeCode" => 1
        ];
        $client = new Client();
        $result = $client->get(
            "http://kladr-api.ru/api.php",
            [
                "query" => $query
            ]
        );
        try {
            $result = \GuzzleHttp\json_decode($result->getBody()->getContents());
            return collect($result->result)->map(function ($e) {
                return [
                    "id" => $e->id,
                    "type" => $e->typeShort,
                    "name" => $e->name
                ];
            });
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function ensureCity($name)
    {
        if ($this->getCityId($name))
            return $this->getCityId($name);
        else
            return $this->saveCity($name);
    }

    public function getCityId($name)
    {
        $city = City::where('name', $name)->first();
        if ($city)
            return $city->id;

        return false;
    }

    public function getCity($name)
    {
        $city = City::where('name', $name)->first();
        if ($city)
            return $city;

        return false;
    }

    public function saveCity($name)
    {
        $city = new City();
        $city->name = $name;
        if ($city->save())
            return $city->id;

        return false;
    }
}
