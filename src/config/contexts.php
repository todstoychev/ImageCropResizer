<?php
/*
 * ImageCropResizer configuration
 *
 * 'default' is the default image context.
 * Context has different sizes which has width, height and action.
 * The action defines the way that the image will be processed.
 * "crop" crops a region with the provided measures from the middle of the image.
 * "resize" resize the image to given measures
 * "crop-resize" crops a region from the image, but first resize it,
 * so the image will be cropped with no deformations and with minimal losses.
 */

return [
    'default' => [
        'small' => [
            'width' => 100,
            'height' => 100,
            'action' => 'crop-resize'
        ],
        'medium' => [
            'width' => 200,
            'height' => 200,
            'action' => 'crop-resize'
        ],
        'large' => [
            'width' => 300,
            'height' => 300,
            'action' => 'crop-resize'
        ]
    ]
];