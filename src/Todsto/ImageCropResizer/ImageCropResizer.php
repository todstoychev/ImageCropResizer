<?php

namespace Todsto\ImageCropResizer;

use Illuminate\Support\Facades\Config;

/**
 * Class ImageCropResizer
 * @package Todsto\ImageCropResizer
 *
 * Class that proccess image resizing
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @copyright 2014
 */
class ImageCropResizer {

    /**
     * Resize and crops image, saves it and then puts it into the database
     *
     * @param Object $image The image field @example Input::file('image')
     * @param string $context Context to use
     */
    public static function process($image, $context) {
        $config = Config::get('image-crop-resizer::contexts.' . $context);

        $base_file_name = CropResizer::baseFileName($context);

        foreach ($config as $key => $size) { 
            $img = new CropResizer($image);
            switch ($size['action']) {
                case 'crop':
                    $img->crop($size['width'], $size['height']);
                    break;
                case 'resize':
                    $img->resize($size['width'], $size['height']);
                    break;
                case 'crop-resize':
                    $img->cropResizeImage($size['width'], $size['height']);
                    break;
                default:
                    $img->cropResizeImage($size['width'], $size['height']);
                    break;
            }
            $name = $img->saveImage($base_file_name, $context, $key);
        }

        return $name;
    }
}
