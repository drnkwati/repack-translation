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
    function trans($key = null, $replace = [], $locale = null)
    {
        if (is_null($key)) {
            return app('translator');
        }

        return call_user_func_array([app('translator'), __FUNCTION__], func_get_args());
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
    function transChoice($key, $number, array $replace = [], $locale = null)
    {
        return call_user_func_array([app('translator'), __FUNCTION__], func_get_args());
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
    function __($key, $replace = [], $locale = null)
    {
        return app('translator')->getFromJson($key, $replace, $locale);
    }
}

if (!function_exists('transMatch')) {
    /**
     *
     * @param  mixed   $values
     * @param  string  $pattern
     * @param  array   $replace
     * @param  string  $locale
     * @return mixed
     */
    function transMatch($values, $pattern = "|<[^>]+>(.*)</[^>]+>|U", array $replace = array(), $locale = null)
    {
        return call_user_func_array([app('translator'), __FUNCTION__], func_get_args());
    }
}

if (!function_exists('transCall')) {
    /**
     *
     * @param  mixed     $values
     * @param  callable  $callback
     * @param  array     $replace
     * @param  string    $locale
     * @return mixed
     */
    function transCall($values, $callback = null, array $replace = array(), $locale = null)
    {
        return call_user_func_array([app('translator'), __FUNCTION__], func_get_args());
    }
}

if (!function_exists('transKeys')) {
    /**
     *
     * @param  array   $values
     * @param  array   $replace
     * @param  string  $locale
     * @return mixed
     */
    function transKeys(array $values, array $replace = array(), $locale = null)
    {
        return call_user_func_array([app('translator'), __FUNCTION__], func_get_args());
    }
}

if (!function_exists('transValues')) {
    /**
     *
     * @param  array   $values
     * @param  array   $replace
     * @param  string  $locale
     * @return mixed
     */
    function transValues(array $values, array $replace = array(), $locale = null)
    {
        return call_user_func_array([app('translator'), __FUNCTION__], func_get_args());
    }
}
