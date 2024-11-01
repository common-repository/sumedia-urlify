<?php

namespace Sumedia\Urlify\Base;

class Registry
{
    /**
     * @var array
     */
    protected static $vars = [];

    /**
     * @var array
     */
    protected static $factories = [];

    /**
     * @param string $name
     * @param mixed $value
     */
    public static function set($name, $value)
    {
        static::$vars[$name] = $value;
    }

    /**
     * @param string $class_name
     * @param object $factory implementing method public function build()
     */
    public static function set_factory($class_name, $factory)
    {
        static::$factories[$class_name] = $factory;
    }

    /**
     * @param $class_name
     * @return object
     */
    public static function get_factory($class_name)
    {
        if (isset(static::$factories[$class_name])) {
            return static::$factories[$class_name];
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public static function get($name)
    {
        if (!isset(static::$vars[$name]) && class_exists($name)) {
            if ($factory = static::get_factory($name)) {
                static::set($name, $factory->build());
            } else {
                static::set($name, new $name);
            }
        }
        return static::$vars[$name];
    }
}