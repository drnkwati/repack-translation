<?php

namespace Closet\Translation;

class TranslationServiceProvider extends Abstracts\ServiceProvider
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
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->bindTranslator($this->app);
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

    /**
     * Bind the translator with app or ioc container.
     *
     * @return void
     */
    public static function bindTranslator($app)
    {
        //1. Bind the translation line loader.

        $app->singleton('translation.loader', function ($app) {
            return new FileLoader($app['path.lang']);
        });

        //2. Bind the translator.

        $app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            // When registering the translator component, we'll need to set the default
            // locale as well as the fallback locale. So, we'll grab the application
            // configuration so we can easily get both of these values from there.
            $locale = $app['config']['app.locale'];

            $trans = new Translator($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });
    }
}
