<?php

namespace Todsto\ImageCropResizer\Models;

use Illuminate\Database\Eloquent;

/**
 * Class ImageCropResizer
 * @package Todsto\ImageCropResizer\Models
 *
 * ImageCropResizer model class
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @copyright 2014
 */
class ImageCropResizer extends \Eloquent {

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'image_crop_resizer';

    /**
     * Attributes allowed for mass assignment
     *
     * @var array
     */
    protected $fillable = ['image', 'context'];

}