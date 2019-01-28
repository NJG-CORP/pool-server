<?php

namespace App\Services\AdminPanel;

use App\Models\News;
use App\Services\ImageService;


class NewsService
{


    /*
     @role: get all news data with 10 in each page

     @comments:
     */

    public function getNewsData()
    {

        return News::orderBy('id', 'desc')->paginate(10);

    }

    /*
     @role: get specific news details

     @comments:
     */

    public function getOne($id)
    {

        return News::with('images')->findOrFail($id);


    }

    /*
     @role: save New

     @comments:
     */

    public function saveNews($request)
    {

        if ($request->news_id > 0) // if we have update request
            $news = News::findOrFail($request->news_id);
        else    // add new
            $news = new News;

        $news->title = $request->title;
        $news->name = $request->name;
        $news->url = $request->url;
        $news->paragraph = $request->paragraph;
        $news->description = $request->description;


        $news->description = $request->description;
        $news->gallery_title = $request->gallery_title;
        $news->save();


        if ($news) {
            // check if news has any images
            if ($request->mainImg != '') {


                //echo $_FILES['mainImg']['tmp_name']; exit;
                $main_image = (new ImageService)->create($request->file('mainImg'), $news, $request->file('mainImg')->getClientOriginalName());

                // now save main image id to news table
                $news->mainImg = $main_image->id;
                $news->save();

            }

            if ($request->images != '') {

                foreach ($request->images as $key => $image) {

                    // save additional images
                    (new ImageService)->create($image, $news, $image->getClientOriginalName());


                }

            }


            if ($request->gallery_images != '') {


                $news_image_array = [];

                foreach ($request->gallery_images as $key => $image) {

                    // save additional images
                    $news_g_images = (new ImageService)->create($image, $news, $image->getClientOriginalName());

                    array_push($news_image_array, $news_g_images->id);

                }


                if ($news->gallery_images != '')
                    $im_arr = unserialize($news->gallery_images);

                else
                    $im_arr = [];


                $news->gallery_images = serialize(array_merge($news_image_array, $im_arr));
                $news->save();

            }


            return true;

        } else
            return redirect()->route('get:all:blogs');

    }


}