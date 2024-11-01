<?php

/**
 * @copyright Sven Ullmann <kontakt@sumedia-webdesign.de>
 */

namespace Sumedia\Urlify\Base;

final class Autoloader
{
    /**
     * @var Autoloader
     */
    protected static $instance;

    /**
     * @var array
     */
    protected $directories = [];

    /**
     * Autoloader constructor.
     */
    protected function __construct() {}

    /**
     * @return Autoloader
     */
    public static function get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * @param string $class
     */
    public function autoload($class)
    {
        $class = trim($class, '\\');
        $plugin_path = SUMEDIA_URLIFY_PLUGIN_PATH;
        $lib_file_path = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        $file = $plugin_path . \Sumedia\Urlify\ds('/src/' . $lib_file_path);
        if (file_exists($file)) {
            require_once $file;
        }
    }

    public function register_autoloader()
    {
        spl_autoload_register([$this, 'autoload']);
    }

    /**
     * @param string $dir
     */
    public function register_autoload_dir($dir)
    {
        if (!in_array($dir, $this->directories)) {
            $this->directories[] = $dir;
        }
    }
}
