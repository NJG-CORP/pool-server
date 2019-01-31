<?php

namespace App\Services;

use App\Models\Club;
use App\Models\GameType;
use App\Models\Image;
use App\Models\Kitchens;
use App\Models\Weekday;
use App\Models\WorkTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ClubsService
{
    public function getList()
    {
        return Club::with(['rating', 'location', 'images'])
            ->get()
            ->map(function ($e) {
                return $e->setAppends(['calculated_rating']);
            });
    }

    public function getOne($id): Club
    {
        return Club::with(['rating', 'location', 'images'])
            ->where(['id' => $id])
            ->firstOrFail();
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

        $club = Club::create([
            'name' => $request['name'],
            'description' => $request['des'],
            'title' => $request['title'],

            'gametype_id' => $gametype->id,
            'location_id' => $request['location'],
            'gallery_title' => $request['gallery_title'],
            'phone' => $request['mob']
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

        $club->title = $request['title'];
        $club->name = $request['name'];
        $club->description = $request['des'];

        $club->gametype_id = $gametype->id;
        $club->kitchens_id = $request['kitchen'];
        $club->location_id = $request['location'];
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

    public function getClubByUrl(string $url)
    {
        return Club::with(['images'])->where(['url' => $url])->first();
    }
}
