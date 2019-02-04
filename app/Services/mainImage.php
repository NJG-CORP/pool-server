<?php

namespace App\Services;

use App\Models\Image;

trait mainImage
{
    public function getMainImage()
    {
        return $this->getMainImageEvent()->first() ? $this->getMainImageEvent()->first()->getUrlAttribute() : Image::getDefaultImage()['url'];
    }
}