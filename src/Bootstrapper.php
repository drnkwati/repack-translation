<?php

namespace Repack\Translation;

use ArrayAccess;

class Bootstrapper
{
    /**
     * Register the translation line loader.
     *
     * @return void
     */
    public static function bootstrap(ArrayAccess $ioc)
    {
        //1. Bind the translation line loader.
        static::bindLoader($ioc);

        //2. Bind the translator.
        $ioc['repack.translator'] = function () use ($ioc) {
            // When registering the translator component, we'll need to set the default
            // locale as well as the fallback locale. So, we'll grab the application
            // configuration so we can easily get both of these values from there.
            $trans = new Translator(
                $ioc['repack.translation.loader'], $ioc['config']['app.locale']
            );

            $trans->setFallback($ioc['config']['app.fallback_locale']);

            return $trans;
        };
    }

    /**
     * Register the translation line loader.
     *
     * @return void
     */
    public static function bindLoader(ArrayAccess $ioc)
    {
        $ioc['repack.translation.loader'] = function () use ($ioc) {
            return new FileLoader($ioc['path.lang']);
        };
    }

    /**
     * Register a translation file namespace.
     *
     * @param  string  $path
     * @param  string  $namespace
     * @return void
     */
    public static function loadTranslationsFrom($path, $namespace, $translator)
    {
        !($translator instanceof ArrayAccess) ?: $translator = $translator['translator'];

        $translator->addNamespace($namespace, $path);
    }

    /**
     * Register a JSON translation file path.
     *
     * @param  string  $path
     * @return void
     */
    public static function loadJsonTranslationsFrom($path, $translator)
    {
        !($translator instanceof ArrayAccess) ?: $translator = $translator['translator'];

        $translator->addJsonPath($path);
    }
}
