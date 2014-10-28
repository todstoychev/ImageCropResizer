# Installation

You can install this package in the standart way. 
Add:
    
    "todsto/image-crop-resizer": "dev"
    
to your ```composer.json```.
Run ```composer update```
Publish the configuration
    
    php artisan config:publish todsto/image-crop-resizer
    
Open your ```app/config/app.php``` and add ```'Todsto\ImageCropResizer\ImageCropResizerServiceProvider', ``` to the providers array. Then add ```'ICR' => 'Todsto\ImageCropResizer\ImageCropResizer'``` to the aliases array. This will register 'ICR' as alias for the base bundle class.

# Basic usage
The bundle stores the images in the ```public/uploads/context/size``` folder. 

## Cofiguration
To set the your own contexts use the ```app/config/packages/todsto/image-crop-resizer/contexts.php```.
The file looks like this
    
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

"default" is the default image context.
Context has different sizes which has width, height and action.
The action defines the way that the image will be processed.
"crop" crops a region with the provided measures from the middle of the image.
"resize" resize the image to given measures.
"crop-resize" crops a region from the image, but first resize it,
so the image will be cropped with no deformations and with minimal losses.

## Usage
To use the bundle just call in you controller the process method of the ImageCropResizer class. As you have an alias, your code might look like this:

1. Controller
    
        class TestController extends Controller {
    
            public function getTest() {
                return View::make('test');
            }
    
            public function postTest() {
                $image_name = ICR::process(Input::file('image'), 'default');
    
                // Some database actions here
                
                return Redirect::back()->with('message', 'Success');
            }
    
        } 
    
2. Template
    
        @if(Session::has('message'))
        {{ Session::get('message') }}
        @endif
    
        {{ Form::open(['url' => 'test/test', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        {{ Form::token() }}
    
        {{ Form::file('image') }}
    
        {{ Form::submit('Submit') }}
    
        {{ Form::close() }}

The process method takes as first argument the file object from the input field. The second argument is the context. The process method returns the generated unique image name.