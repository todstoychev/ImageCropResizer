<?php

namespace Todsto\ImageCropResizer;

use Illuminate\Support\Facades\Config;
use Todsto\ImageCropResizer\Models\ImageCropResizer as Model;

class ImageCropResizer {

    /**
     * Resize and crops image, saves it and then puts it into the database
     *
     * @param Object $image The image field @example Input::file('image')
     * @param string $context Context to use
     */
    public static function cropResize($image, $context) {
        $config = Config::get('image-crop-resizer::contexts.' . $context);

        $img = new CropResizer($image);
        $base_file_name = $img->baseFileName($context);

        foreach ($config as $key => $size) {
            $img->cropResizeImage($size['width'], $size['height']);
            $name = $img->saveImage($base_file_name, $context, $key);
        }

        Model::create([
            'image' => $name,
            'context' => $context
        ]);
    }
}
