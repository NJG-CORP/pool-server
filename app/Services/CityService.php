<?php
namespace App\Services;

use App\Models\City;
use GuzzleHttp\Client;

class CityService
{
    public function search($search){
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
            return collect($result->result)->map(function($e){
                return [
                    "id" => $e->id,
                    "type" => $e->typeShort,
                    "name" => $e->name
                ];
            });
        } catch (\Throwable $e){
            return [];
        }
    }

    public function ensureCity($id, $name){
        $city = City::firstOrCreate([
            'id' => $id
        ], [
            'name' => $name
        ]);
        return $city;
    }

    public function getCityId($name) {
        $city = City::where('name', $name)->first();
        return $city->id;
    }
}
