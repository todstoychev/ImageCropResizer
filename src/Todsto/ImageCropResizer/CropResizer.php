<?php

namespace Todsto\ImageCropResizer;

use Imagine\Gd\Imagine as Img;
use Imagine\Image\Box as Box;
use Imagine\Image\Point as Point;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class CropResizer {

    /**
     * Holds the Imagine instave
     *
     * @var Img Imagine instance
     */
    private $image;

    /**
     * File extension
     *
     * @var string
     */
    private $ext;

    /**
     * @param $image
     */
    public function __construct($image) {
        $this->validate($image);
        $this->ext = $image->getClientOriginalExtension();
        $img = new Img();
        $this->image = $img->open($image);
    }

    /**
     * Resize the image.
     */
    public function resize($output_width, $output_height) {
        $size = $this->calcOutputSize($output_width, $output_height);
        $this->image->resize(new Box($size['width'], $size['height']));
    }

    /**
     * Crop the image.
     */
    public function crop($output_width, $output_height) {
        $this->image->crop($this->calcCropPoint($output_width, $output_height), new Box($output_width, $output_height));
    }

    /**
     * Crops and resize the image.
     */
    public function cropResizeImage($output_width, $output_height) {
        $size = $this->calcOutputSize($output_width, $output_height);
        $this->image->resize(new Box($size['width'], $size['height']))
            ->crop($this->calcCropPoint($size['width'], $size['height']), new Box($output_width, $output_height));
    }

    /**
     * Checks and creates directories and filename
     *
     * @param string $context Context to use
     * @return string Base file name
     */
    public function baseFileName($context) {
        $base = md5(time() * microtime());

        foreach (\Config::get('image-crop-resizer::contexts.' . $context) as $key => $size) {
            $dir = public_path() . '/uploads/' . $context . '/' . $key;
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            if (\File::exists(public_path() . '/uploads/' . $context . '/' . $key . '/' . $base . '.' . $this->ext)) {
                $this->baseFileName($context);
            }
        }

        return $base;
    }

    /**
     * Saves the image
     *
     * @param string $base_image_name Base image name
     * @param string $context Context
     * @param string $size Context size
     * @return string Uploaded image name
     */
    public function saveImage($base_image_name, $context, $size) {
        $image_name = $base_image_name . '.' . $this->ext;
        $this->image->save(public_path() . '/uploads/' . $context . '/' . $size . '/' . $image_name);

        return $image_name;
    }

    /**
     * Validate the image for any case ;)
     *
     * @param Object $image Image
     */
    private function validate($image) {
        $validator = Validator::make(['image' => $image], ['image' => 'image']);

        if ($validator->fails()) {
            throw new FileException;
        }
    }

    /**
     * Calculates the output size
     *
     * @param integer $output_width Output width
     * @param integer $output_height Output height
     * @return array Sizes
     */
    private function calcOutputSize($output_width, $output_height) {

        $width_ratio = $this->image->getSize()->getWidth() / $output_width;
        $height_ratio = $this->image->getSize()->getHeight() / $output_height;

        $ratio = min($width_ratio, $height_ratio);

        $width = round($this->image->getSize()->getWidth() / $ratio);
        $height = round($this->image->getSize()->getHeight() / $ratio);

        return ['width' => (int) $width, 'height' => (int) $height];
    }

    /**
     * Calculates the position of the crop point and returns Imagine Point object
     *
     * @param integer $output_width Output width
     * @param integer $output_height Output height
     * @return Point Imagine Point interface instance
     */
    private function calcCropPoint($output_width, $output_height) {

        $size = $this->image->getSize();

        if ($this->image->getSize()->getHeight() > $output_height) {
            $y = ($this->image->getSize()->getHeight() - $output_height) / 2;
            $x = 0;
        } else if ($this->image->getSize()->getWidth() > $output_width) {
            $x = ($this->image->getSize()->getWidth() - $output_width) / 2;
            $y = 0;
        } else {
            $x = ($this->image->getSize()->getWidth() - $output_width) / 2;
            $y = ($this->image->getSize()->getHeight() -$output_height) / 2;
        }

        $x = round($x);
        $y = round($y);

        return new Point((int) $x, (int) $y);
    }

}