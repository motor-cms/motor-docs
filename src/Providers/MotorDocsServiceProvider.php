<?php

namespace Motor\Docs\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class MotorDocsServiceProvider
 * @package Motor\Docs\Providers
 */
class MotorDocsServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->routes();
        $this->views();
        $this->documentation();
    }


    /**
     * Merge documentation items from configuration file
     */
    public function documentation()
    {
        $config = $this->app['config']->get('motor-docs', []);
        $this->app['config']->set('motor-docs',
            array_replace_recursive(require __DIR__.'/../../config/motor-docs.php', $config));
    }


    /**
     * Set routes
     */
    public function routes()
    {
        if ( ! $this->app->routesAreCached()) {
            require __DIR__.'/../../routes/web.php';
        }
    }


    /**
     * Set view path
     */
    public function views()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'motor-docs');
    }
}
