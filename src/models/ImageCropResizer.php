<?php

namespace Todsto\ImageCropResizer\Models;

use Illuminate\Database\Eloquent;

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