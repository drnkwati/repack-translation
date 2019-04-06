<?php

if (!function_exists('trans')) {
    /**
     * Translate the given message.
     *
     * @param  string  $key
     * @param  array   $replace
     * @param  string  $locale
     * @return Translator|string|array|null
     */
    function trans($key = null, $replace = array(), $locale = null)
    {
        if (is_null($key)) {
            return app('translator');
        }

        return call_user_func_array(array(app('translator'), __FUNCTION__), func_get_args());
    }
}

if (!function_exists('trans_choice')) {
    /**
     * Translates the given message based on a count.
     *
     * @param  string  $key
     * @param  int|array|\Countable  $number
     * @param  array   $replace
     * @param  string  $locale
     * @return string
     */
    function transChoice($key, $number, array $replace = array(), $locale = null)
    {
        return call_user_func_array(array(app('translator'), __FUNCTION__), func_get_args());
    }
}

if (!function_exists('__')) {
    /**
     * Translate the given message.
     *
     * @param  string  $key
     * @param  array   $replace
     * @param  string  $locale
     * @return string|array|null
     */
    function __($key, $replace = array(), $locale = null)
    {
        return app('translator')->getFromJson($key, $replace, $locale);
    }
}
