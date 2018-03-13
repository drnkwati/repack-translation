<?php

namespace Illuminate\Compatible;

use BadMethodCallException;
use Closure;
use ReflectionClass;
use ReflectionMethod;

class Macroable
{
    /**
     * The registered string macros.
     *
     * @var array
     */
    protected static $macros = array();

    /**
     * Register a custom macro.
     *
     * @param  string          $name
     * @param  object|callable $macro
     * @return void
     */
    public static function macro($name, $macro)
    {
        static::$macros[$name] = $macro;
    }

    /**
     * Mix another object into the class.
     *
     * @param  object $mixin
     * @return void
     */
    public static function mixin($mixin)
    {
        $reflectionClass = new ReflectionClass($mixin);

        $methods = $reflectionClass->getMethods(
            ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED
        );

        foreach ($methods as $method) {
            $method->setAccessible(true);

            static::macro($method->name, $method->invoke($mixin));
        }
    }

    /**
     * Checks if macro is registered.
     *
     * @param  string $name
     * @return bool
     */
    public static function hasMacro($name)
    {
        return isset(static::$macros[$name]);
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param  string                    $method
     * @param  array                     $parameters
     * @throws \BadMethodCallException
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        if (!static::hasMacro($method)) {
            throw new BadMethodCallException("Method {$method} does not exist.");
        }

        if (static::$macros[$method] instanceof Closure) {
            return call_user_func_array(Closure::bind(static::$macros[$method], null, self), $parameters);
        }

        return call_user_func_array(static::$macros[$method], $parameters);
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param  string                    $method
     * @param  array                     $parameters
     * @throws \BadMethodCallException
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (!static::hasMacro($method)) {
            throw new BadMethodCallException("Method {$method} does not exist.");
        }

        $macro = static::$macros[$method];

        if ($macro instanceof Closure) {
            return call_user_func_array($macro->bindTo($this, self), $parameters);
        }

        return call_user_func_array($macro, $parameters);
    }
}
