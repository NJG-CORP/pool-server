<?php
namespace App\Services;

use App\Exceptions\ControllableException;
use App\Models\Club;
use App\Models\User;
use App\Models\Weekday;
use App\Models\Kitchens;
use App\Models\WorkTime;
use App\Models\GameType;
use App\Utils\R;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
class ClubsService
{
    public function getList(){
        return Club::with(['rating', 'location', 'images'])
            ->get()
            ->map(function($e){
                return $e->setAppends(['calculated_rating']);
            });
    }

    public function getOne($id){
        return Club::with(['rating', 'location', 'images'])
            ->find($id)
            ->setAppends(['calculated_rating']);
    }
    public function getAllClubsData()
    {
        $data=Club::all();
        return $data;
    }
    public function getWeekDay()
    {
        $data=Weekday::all();
        return $data;
    }
    public function getallKitchensData()
    {
        $data=Kitchens::all();
        return $data;
    }
    public function storingClubData(Request $request)
    {
       
 
       //dd($request->all());
        
      
        $gametype=GameType::create([
          'pool'=>$request['pool'],
          'Russian'=>$request['russian'],
          'Snooker'=>$request['snooker'],
          'Cannon'=>$request['cannon']
        ]);


        $club=Club::create([
           'name'=>$request['title'],
           'description'=>$request['des'],
          
           'gametype_id'=>$gametype->id,
           'kitchens_id'=>$request['kitchen'],
           'location_id'=>$request['location'],
           'phone'=>$request['mob']
        ]);
   if($request->file('img')){
        $main_image = (new ImageService)->create($request->file('img'),$club,$request->file('img')->getClientOriginalName());
   }
    

        foreach ($request['worktime'] as $key => $value) {
            if(!empty($value['from'])&&!empty($value['to'])){
            WorkTime::create([
             'weekday_id'=>$key,
             'from'=>$value['from'],
             'to'=>$value['to'],
             'club_id'=>$club->id
            ]);
        }
        }

  return $club;

     }
}
