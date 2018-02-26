<?php
namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Models\Image as ImageModel;

class ImageService
{
    /**
     * @param $b64
     * @param Model|null $model
     * @param $path
     * @return string|ImageModel
     */
    public function create($b64, Model $model = null, $path){
        if ( !is_dir(dirname($path)) ){
            mkdir(dirname($path), 0777, true);
        }
        $nativeImage = Image::make(
            file_get_contents($b64)
        )->save($path);

        if ( $model ){
            $image = ImageModel::create([
                "imageable_id" => $model->id,
                "imageable_type" => get_class($model),
                "path" => $path
            ]);
            return $image;
        }
        return $path;
    }
}
