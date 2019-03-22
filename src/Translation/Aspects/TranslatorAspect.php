<?php

namespace Closet\Translation\Aspects;

if (interface_exists('\Illuminate\Contracts\Translation\Translator')) {
    interface TranslatorAspect extends \Illuminate\Contracts\Translation\Translator
    {
        //
    }
}
//
else {
    interface TranslatorAspect
    {
        /**
         * Get the translation for a given key.
         *
         * @param  string  $key
         * @param  array   $replace
         * @param  string  $locale
         * @return mixed
         */
        public function trans($key, array $replace = array(), $locale = null);

        /**
         * Get a translation according to an integer value.
         *
         * @param  string  $key
         * @param  int|array|\Countable  $number
         * @param  array   $replace
         * @param  string  $locale
         * @return string
         */
        public function transChoice($key, $number, array $replace = array(), $locale = null);

        /**
         * Get the default locale being used.
         *
         * @return string
         */
        public function getLocale();

        /**
         * Set the default locale.
         *
         * @param  string  $locale
         * @return void
         */
        public function setLocale($locale);
    }
}