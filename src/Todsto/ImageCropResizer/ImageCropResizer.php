<?php

namespace Todsto\ImageCropResizer;

use Illuminate\Support\Facades\Config;
use Todsto\ImageCropResizer\Models\ImageCropResizer as Model;

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

        $img = new CropResizer($image);
        $base_file_name = $img->baseFileName($context);

        foreach ($config as $key => $size) {
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
            $img->cropResizeImage($size['width'], $size['height']);
            $name = $img->saveImage($base_file_name, $context, $key);
        }

        try {
            Model::create([
                'image' => $name,
                'context' => $context
            ]);
        } catch (\Exception $e) {
            foreach ($config as $key => $value) {
                unlink(public_path() . '/uploads/' . $context . '/' . $key . '/' . $name);
            }

        }
    }
}
