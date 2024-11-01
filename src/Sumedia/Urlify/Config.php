<?php

namespace Sumedia\Urlify;

if (!class_exists('WPConfigTransformer')) {
    require_once(SUMEDIA_URLIFY_PLUGIN_PATH . str_replace('/', DIRECTORY_SEPARATOR, '/vendor/wp-cli/wp-config-transformer/src/WPConfigTransformer.php'));
}

if (!function_exists('get_home_path')) {
    include_once ABSPATH . '/wp-admin/includes/file.php';
}

class Config
{
    /**
     * @param string $admin_url
     */
    public function write($admin_url)
    {
        $path = $this->buildAdminCookiePath($admin_url);
        $wp_config = new \WPConfigTransformer(get_home_path() . DIRECTORY_SEPARATOR . 'wp-config.php');
        $wp_config->update('constant', 'ADMIN_COOKIE_PATH', $path);
    }

    public function remove()
    {
        $wp_config = new \WPConfigTransformer(get_home_path() . DIRECTORY_SEPARATOR . 'wp-config.php');
        $wp_config->remove('constant', 'ADMIN_COOKIE_PATH');
    }

    public function buildAdminCookiePath($admin_url)
    {
        return $this->get_wp_path() . DIRECTORY_SEPARATOR . $admin_url;
    }

    public function getCurrentAdminCookiePath()
    {
        if (defined('ADMIN_COOKIE_PATH')) {
            return ADMIN_COOKIE_PATH;
        }
        return $this->buildAdminCookiePath('wp-admin');
    }

    /**
     * @return string
     */
    protected function get_wp_path()
    {
        return parse_url(get_bloginfo('url'), PHP_URL_PATH);
    }
}