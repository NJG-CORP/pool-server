<?php

namespace App\Services;

use App\Models\City;
use App\Models\Club;
use App\Models\GameType;
use App\Models\Image;
use App\Models\Kitchens;
use App\Models\Location;
use App\Models\Weekday;
use App\Models\WorkTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ClubsService
{
    const LIMIT_LIST = 10;

    public function getList($limit = self::LIMIT_LIST, $withRating = false, $name = null, $not_id = null)
    {
        $query = Club::with(['rating', 'location', 'images'])
            ->limit($limit)
            ->select('clubs.*')
            ->whereRaw('deleted_at is null');

        if ($withRating && !$name) {
            $query
                ->join('rating', 'rateable_id', '=', 'clubs.id')
                ->where('rating.rateable_type', '=', Club::class)
                ->where('rating.is_verified', '=', '1')
                ->orWhere('rating.rateable_type', 'is', 'null')
                ->selectRaw('AVG(rating.score) AS average_rating')
                ->orderByRaw('AVG(rating.score) desc')
                ->groupBy('clubs.id');
        }

        if ($not_id) {
            $query->whereRaw('clubs.id NOT IN (' . implode(',', $not_id) . ')');
        }

        if ($name) {
            $query->whereRaw('name like \'%' . $name . '%\'');
        }

        $clubs = $query
            ->get()
            ->map(function ($e) {
                return $e->setAppends(['calculated_rating']);
            });

        if (count($clubs) < $limit && $withRating) {
            $not_in = [];
            foreach ($clubs as $club) {
                $not_in[] = $club->id;
            };
            $additionalClubs = $this->getList($limit - count($clubs), false, $name, $not_in);
            foreach ($additionalClubs as $club) {
                $clubs->push($club);
            }
        }

        return $clubs;
    }

    public function getAllClubsData()
    {
        $data = Club::orderBy('id', 'DESC')->paginate(10);
        return $data;
    }

    public function getWeekDay()
    {
        $data = Weekday::all();
        return $data;
    }

    public function getallKitchensData()
    {
        $data = Kitchens::all();
        return $data;
    }

    public function storingClubData(Request $request)
    {
        $gametype = GameType::create([
            'pool' => $request['pool'],
            'Russian' => $request['russian'],
            'Snooker' => $request['snooker'],
            'Cannon' => $request['cannon']
        ]);

        $city = City::firstOrCreate([
            'name' => $request['city-name']
        ], [
            'name' => $request['city-name']
        ]);

        $location = Location::query()->where(['latitude' => $request['lat'], 'longitude' => $request['lng']])->first();

        if (!$location) {
            $location = new Location();
            $location->latitude = $request['lat'];
            $location->longitude = $request['lng'];
            $location->city_id = $city->id;
            $location->address = $request['location'];
            $location->save();
        }

        $club = Club::create([
            'name' => $request['name'],
            'description' => $request['des'],
            'title' => $request['title'],

            'gametype_id' => $gametype->id,
            'location_id' => $location->id,
            'gallery_title' => $request['gallery_title'],
            'phone' => $request['mob'],
            'url' => $request['url']
        ]);
        $club->kitchens_id = $request['kitchen'];
        if ($request->file('img')) {
            $main_image = (new ImageService)->create($request->file('img'), $club, $request->file('img')->getClientOriginalName());
        }

        if ($request->gallery_images != '') {
            foreach ($request->gallery_images as $key => $image) {
                // save additional images
                (new ImageService)->create($image, $club, $image->getClientOriginalName());
            }
        }

        foreach ($request['worktime'] as $key => $value) {
            if (!empty($value['from']) && !empty($value['to'])) {
                WorkTime::create([
                    'weekday_id' => $key,
                    'from' => $value['from'],
                    'to' => $value['to'],
                    'club_id' => $club->id
                ]);
            }
        }
        $club->save();

        return $club;
    }

    public function editClubData($id)
    {
        $data = Club::with('getWorkTime')->find($id);

        if (empty($data)) {
            return null;
        }

        return $data;
    }

    public function updateDetails(Request $request)
    {

        if (empty($request['item'])) {
            return null;
        }
        $club = Club::find($request['item']);
        $gametype = GameType::find($club->gametype_id);
        if (empty($gametype)) {
            return null;
        }
        $gametype->pool = $request['pool'];
        $gametype->Russian = $request['russian'];
        $gametype->Snooker = $request['snooker'];
        $gametype->Cannon = $request['cannon'];
        $gametype->save();


        $city = City::firstOrCreate([
            'name' => $request['city-name']
        ], [
            'name' => $request['city-name']
        ]);

        $location = Location::query()->where(['latitude' => $request['lat'], 'longitude' => $request['lng']])->first();

        if (!$location) {
            $location = new Location();
            $location->latitude = $request['lat'];
            $location->longitude = $request['lng'];
            $location->city_id = $city->id;
            $location->address = $request['location'];
            $location->save();
        }

        $club->title = $request['title'];
        $club->url = $request['url'];
        $club->name = $request['name'];
        $club->description = $request['des'];

        $club->gametype_id = $gametype->id;
        $club->kitchens_id = $request['kitchen'];
        $club->location_id = $location->id;
        $club->phone = $request['mob'];

        if ($request->gallery_images != '') {
            foreach ($request->gallery_images as $key => $image) {
                // save additional images
                (new ImageService)->create($image, $club, $image->getClientOriginalName());
            }
        }

        $club->save();


        if ($request->file('img')) {

            $image = Image::where([
                ['imageable_id', '=', $club->id],
                ['imageable_type', '=', 'App\Models\Club']
            ])->get();
            if (count($image)) {
                $image->delete();
            }

            $main_image = (new ImageService)->create($request->file('img'), $club, $request->file('img')->getClientOriginalName());
            $club->mainImg = $main_image->id;
            $club->save();
        }

        $timing = WorkTime::where('club_id', $club->id)->delete();
        foreach ($request['worktime'] as $key => $value) {
            if (!empty($value['from']) && !empty($value['to'])) {
                WorkTime::create([
                    'weekday_id' => $key,
                    'from' => $value['from'],
                    'to' => $value['to'],
                    'club_id' => $club->id
                ]);
            }
        }

        return $club;

    }

    public function getDeleteDetails(Request $request)
    {

        $data = Club::find($request['id']);

        $data->delete();

        return null;
    }

    public function getGalleryImages(int $clubId): Collection
    {
        $images = $this->getOne($clubId)->images;

        $images->shift();

        return $images;
    }

    public function getOne($id): Club
    {
        return Club::with(['rating', 'location', 'images'])
            ->where(['id' => $id])
            ->firstOrFail();
    }

    public function getClubByUrl(string $url)
    {
        return Club::with(['images'])->where(['url' => $url])->first();
    }

    public function findSuggestion(string $name)
    {
        return Club::query()->select(['name'])->whereRaw('name like \'%' . $name . '%\'')->get()->toArray();
    }

    public function getMarkers($clubs)
    {
        $markers = [];

        foreach ($clubs as $club) {
            if ($club->location) {
                $markers[] = [
                    'lat' => $club->location->latitude,
                    'lng' => $club->location->longitude,
                    'id' => $club->id,
                    'url' => route('club.show', ['url' => $club->url])
                ];
            }
        }

        return $markers;
    }

    /**
     * @param string $city
     * @return array
     */
    public function findByCity(string $city)
    {
        $city_id = City::query()->where('name', '=', $city)->firstOrFail()->id;

        $clubs = Club::whereHas('location', function ($query) use ($city_id) {
            $query->where('city_id', '=', $city_id);
        })->get()->map(function ($e) {
            return [
                'club_id' => $e->id,
                'title' => $e->title,
                'address' => $e->location->address
            ];
        });

        return $clubs;
    }
}
