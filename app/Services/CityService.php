<?php
namespace App\Services;

use App\Exceptions\ControllableException;
use App\Models\Club;
use App\Models\User;
use App\Utils\R;
use GuzzleHttp\Client;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

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
}
