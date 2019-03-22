<?php

namespace Repack\Translation;

use Closure;

class Translator extends Abstracts\TranslatorAbstract implements Aspects\TranslatorAspect
{
    /**
     * @var Closure|string
     */
    protected static $listDirectory;

    /**
     * Translate keys for the given array.
     *
     * @param  mixed $values
     * @param  string  $pattern
     * @param  array   $replace
     * @param  string  $locale
     * @return mixed
     */
    public function transMatch($values, $pattern = "|<[^>]+>(.*)</[^>]+>|U", array $replace = array(), $locale = null)
    {
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $values[$key] = $this->transMatch($value, $pattern, $replace, $locale);
            }
        } else {
            preg_match_all($pattern, $values, $out, PREG_PATTERN_ORDER);

            if (isset($out[1][0])) {
                $rKeys = array();
                $rValues = array();

                foreach ($out[1] as $key => $value) {
                    $tValue = $this->getFromJson($value, $replace, $locale);
                    if ($tValue != $value) {
                        $rKeys[] = $value;
                        $rValues[] = $tValue;
                    }
                }

                $values = str_replace($rKeys, $rValues, $values);
            }
        }

        return $values;
    }

    /**
     * Translate keys for the given array.
     *
     * @param  mixed $values
     * @param  callable  $callback
     * @param  array   $replace
     * @param  string  $locale
     * @return mixed
     */
    public function transCall($values, $callback = null, array $replace = array(), $locale = null)
    {
        if (is_null($callback)) {
            $callback = function ($key) {
                return ucwords(str_replace('_', ' ', $key));
            };
        }

        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $values[$key] = $this->transCall($value, $callback, $replace, $locale);
            }
        } else {
            $transValue = $this->getFromJson($values, $replace, $locale);

            if ($transValue == $values && is_callable($callback)) {
                $values = call_user_func($callback, $values);
            } else {
                $values = $transValue && $transValue != $values ? $transValue : $values;
            }
        }

        return $values;
    }

    /**
     * Translate keys for the given array.
     *
     * @param  array $values
     * @param  array   $replace
     * @param  string  $locale
     * @return mixed
     */
    public function transKeys(array $values, array $replace = array(), $locale = null)
    {
        return $this->transArray($values, $replace, $locale, true);
    }

    /**
     * Translate only the values for the given array.
     *
     * @param  array $values
     * @param  array   $replace
     * @param  string  $locale
     * @return mixed
     */
    public function transValues(array $values, array $replace = array(), $locale = null)
    {
        return $this->transArray($values, $replace, $locale, false);
    }

    /**
     * Translate only the keys for the given array.
     *
     * @param  array $values
     * @param  array   $replace
     * @param  string  $locale
     * @param  boolean  $transKey
     * @return mixed
     */
    public function transArray(array $values, array $replace = array(), $locale = null, $transKey = true)
    {
        foreach ($values as $key => $value) {
            $replacements = isset($replace[$key]) ? (array) $replace[$key] : $replace;

            if ($transKey) {
                unset($values[$key]);

                $values[$this->getFromJson($key, $replacements, $locale)] = $value;
            } else {
                $values[$key] = $this->getFromJson($value, $replacements, $locale);
            }
        }

        return $values;
    }

    /**
     * ************************************************************************************************************
     */

    public function listTld($locale = null, $flip = true, array $only = array())
    {
        return $this->listValues('tld', $locale ?: $this->getLocale() ?: $this->getFallback(), $flip, $only);
    }

    public function listLocale($locale = null, $flip = true, array $only = array())
    {
        return $this->listValues('locales', $locale ?: $this->getLocale() ?: $this->getFallback(), $flip, $only);
    }

    public function listLanguage($locale = null, $flip = true, array $only = array())
    {
        return $this->listValues('language', $locale ?: $this->getLocale() ?: $this->getFallback(), $flip, $only);
    }

    public function listCountry($locale = null, $flip = true, array $only = array())
    {
        return $this->listValues('country', $locale ?: $this->getLocale() ?: $this->getFallback(), $flip, $only);
    }

    public function listCurrency($locale = null, $flip = true, array $only = array())
    {
        return $this->listValues('currency', $locale ?: $this->getLocale() ?: $this->getFallback(), $flip, $only);
    }

    public static function listValues($target = 'language', $locale = 'en', $flip = true, array $only = array())
    {
        $directory = static::getListDirectory($locale);

        if (!is_dir($directory)) {
            $directory = static::getListDirectory();
        }

        $target = $directory . '/' . $target . '.php';

        if (is_file($target)) {
            $values = (array) require $target;

            if ($only) {
                $values = array_intersect_key($values, $only);
            }

            return $flip ? array_flip($values) : $values;
        }

        return array();
    }

    /**
     * @return string
     */
    public static function getListDirectory()
    {
        $listDirectory = static::$listDirectory;

        if (is_callable($listDirectory)) {
            $listDirectory = call_user_func_array($listDirectory, func_get_args());
        }

        return (string) $listDirectory;
    }

    /**
     * @param Closure|string $listDirectory
     *
     * @return void
     */
    public static function setListDirectory($listDirectory)
    {
        static::$listDirectory = $listDirectory;
    }
}
