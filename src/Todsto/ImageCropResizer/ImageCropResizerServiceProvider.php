<?php namespace Todsto\ImageCropResizer;

use Illuminate\Support\ServiceProvider;

/**
 * Class ImageCropResizerServiceProvider
 * @package Todsto\ImageCropResizer
 *
 * Service provider class
 *
 * @author Todor Todorov
 * @copyright 2014
 */
class ImageCropResizerServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {
        $this->package('todsto/image-crop-resizer');
        $this->app->register('Orchestra\Imagine\ImagineServiceProvider');

        // Add my database configurations to the default set of configurations
        $this->app['config']['contexts'] = array_merge(
            $this->app['config']['contexts']
            ,\Config::get('image-crop-resizer::contexts')
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Imagine', 'Orchestra\Imagine\ImagineServiceProvider');
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return array();
    }

}
