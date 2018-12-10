<?php
namespace App\Services\AdminPanel;

use Illuminate\Database\Eloquent\Model;
use App\Models\Image as ImageModel;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class ImageService
{
    /**
     * @param $image
     * @param Model|null $model
     * @param $path
     * @return string|ImageModel
     */
    public function save($image, Model $model = null, $path){
            



        $public = public_path();
        $assetsImagesPath = "/assets/images";
        $imagePath = $public . $assetsImagesPath;
        $imagePath = $imagePath . '/' . $path;
        $imageDir = dirname($imagePath);
        if ( !is_dir($imageDir) ){
            mkdir($imageDir, 0777, true);
        }
        $nativeImage = \Image::make(
            $image
        )->save($imagePath);
        ImageOptimizer::optimize($imagePath);
        
        if ( $model ){
            $image = ImageModel::create([
                "imageable_id" => $model->id,
                "imageable_type" => get_class($model),
                "path" => str_replace($public . $assetsImagesPath, '', $imagePath)
            ]);
            return $image;
        }
        return $path;
    }

    /* 
     @role: remove image from 
     
     @comments: 
     */ 
     
     private function removeImage($id) 
     { 
            
            ImageModel::findOrFail($id)->delete();

     
     
     }
}
