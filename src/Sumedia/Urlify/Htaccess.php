<?php

namespace Sumedia\Urlify;

class Htaccess
{
    /**
     * @var array
     */
    protected $parsed_modifications = [];

    /**
     * @var bool
     */
    protected $filter_registered = false;

    /**
     * @var string
     */
    protected $admin_url;

    /**
     * @var string
     */
    protected $login_url;

    /**
     * Sumedia_Urlify_Htaccess_Repository constructor.
     */
    public function __construct($admin_url, $login_url) {
        $this->init($admin_url, $login_url);
        $this->register_rewrite_filter();
        $this->parse_modifications($admin_url, $login_url);
    }

    public function init($admin_url, $login_url)
    {
        if (!$this->is_valid_slug($admin_url)) {
            throw new \RuntimeException(__('Admin URL is not a valid slug', SUMEDIA_URLIFY_PLUGIN_NAME));
        }
        if (!$this->is_valid_slug($login_url)) {
            throw new \RuntimeException(__('Login URL is not a valid slug', SUMEDIA_URLIFY_PLUGIN_NAME));
        }
        $this->admin_url = $admin_url;
        $this->login_url = $login_url;
    }

    public function register_rewrite_filter()
    {
        if (!$this->filter_registered) {
            add_filter('insert_with_markers_inline_instructions', [$this, 'filter_modifications'], 'WordPress');
            $this->filter_registered = true;
        }
    }

    public function write($admin_url, $login_url)
    {
        global $wp_rewrite;
        $this->init($admin_url, $login_url);
        $this->parse_modifications($this->admin_url, $this->login_url);
        $wp_rewrite->flush_rules();
    }

    public function remove()
    {
        global $wp_rewrite;
        $this->parsed_modifications = [];
        $wp_rewrite->flush_rules();
    }

    /**
     * @return array
     */
    public function filter_modifications()
    {
        if (!$this->admin_url || !$this->login_url) {
            throw new \RuntimeException(__('The class has not been initialized proper', SUMEDIA_URLIFY_PLUGIN_NAME));
        }
        return $this->parsed_modifications;
    }

    /**
     * @param string $admin_url
     * @param string $login_url
     */
    protected function parse_modifications($admin_url, $login_url)
    {
        $modification = $this->get_modification($admin_url, $login_url);
        $this->parsed_modifications = explode("\n", $modification);
    }

    /**
     * @return string
     */
    protected function get_wp_path()
    {
        return parse_url(get_bloginfo('url'), PHP_URL_PATH);
    }

    /**
     * @param string $slug
     * @return bool
     */
    public function is_valid_slug($slug)
    {
        if (preg_match('#^[a-z0-9._/-]+$#i', $slug)) {
            return true;
        }
        return false;
    }

    /**
     * @param string $admin_url
     * @param string $login_url
     * @return string
     */
    protected function get_modification($admin_url, $login_url)
    {
        if (!$this->is_valid_slug($admin_url) || !$this->is_valid_slug($login_url)) {
            throw new \RuntimeException(__('Could not set rewrite to incorrect slug', SUMEDIA_URLIFY_PLUGIN_NAME));
        }

        return '# BEGIN sumedia-urlify
<IfModule mod_rewrite.c>    
    RewriteEngine on
    SetEnv HTTP_MOD_REWRITE On
    RewriteCond %{REQUEST_URI} ^.*?/?' . preg_quote($admin_url) . '/?.*$
    RewriteRule ^(.*?/?)' . preg_quote($admin_url) . '(/?.*)$ $1wp-admin$2 [L,E=IS_BACKEND:1,END]
    
    RewriteCond %{REQUEST_URI} ^' . preg_quote($this->get_wp_path()) . '/wp-admin/load-styles.php$ [OR]
    RewriteCond %{REQUEST_URI} ^' . preg_quote($this->get_wp_path()) . '/wp-admin/load-scripts.php$ [OR]
    RewriteCond %{REQUEST_URI} ^' . preg_quote($this->get_wp_path()) . '/wp-admin/admin-ajax.php$
    RewriteRule ^.* - [L,E=IS_BACKEND:1,END]
    
    RewriteCond %{ENV:REDIRECT_IS_BACKEND} !^1$    
    RewriteCond %{REQUEST_URI} ^.*?/?wp-admin/?.*$
    RewriteRule ^.* - [F,L]
    
    RewriteCond %{REQUEST_URI} ^.*?/' . preg_quote($login_url) . '\??.*$
    RewriteRule ^(.*?/?)' . preg_quote($login_url) . '(\??.*)$ $1wp-login.php$2 [L,E=IS_LOGIN:1]
    
    RewriteCond %{ENV:REDIRECT_IS_LOGIN} !^1$
    RewriteCond %{SCRIPT_FILENAME} ^.*?/wp-login.php$
    RewriteRule ^.* - [F,L]
</IfModule>
# END sumedia-urlify';
    }
}