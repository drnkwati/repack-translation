<?php

namespace Repack\Translation;

use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the translation line loader.
     *
     * @return void
     */
    protected function registerLoader()
    {
        static::bindLoader($this->app);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        static::bindTranslator($this->app);
    }

    /**
     * Register the translation line loader.
     *
     * @return void
     */
    public static function bindLoader($app)
    {
        $app->singleton('translation.loader', function ($app) {
            return new FileLoader($app['path.lang']);
        });
    }

    /**
     * Bind the translator with app or ioc container.
     *
     * @return void
     */
    public static function bindTranslator($app)
    {
        //1. Bind the translation line loader.
        static::bindLoader($app);

        //2. Bind the translator.
        $app->singleton('translator', function ($app) {
            // When registering the translator component, we'll need to set the default
            // locale as well as the fallback locale. So, we'll grab the application
            // configuration so we can easily get both of these values from there.
            $trans = new Translator(
                $app['translation.loader'], $app['config']['app.locale']
            );

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('translator', 'translation.loader');
    }
}
