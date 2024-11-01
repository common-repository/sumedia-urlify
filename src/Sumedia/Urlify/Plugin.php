<?php

namespace Sumedia\Urlify;

class Plugin
{
    public function init()
    {
        add_action('admin_init', [$this, 'check_version']);
        add_action('admin_init', [$this, 'check_rewrite_version']);
        add_action('plugins_loaded', [$this, 'textdomain']);
        add_action('admin_print_styles', [$this, 'admin_stylesheets']);
        add_action('admin_menu', [$this, 'setup_menu']);
        $this->factories();
        $this->rewrite_listener();
        add_filter('script_loader_src', [$this, 'urlify']);
        add_filter('style_loader_src', [$this, 'urlify']);
        add_filter('site_url', [$this, 'urlify']);
        add_filter('login_url', [$this, 'urlify']);
        add_filter('admin_url', [$this, 'urlify']);
        add_action('plugins_loaded', [$this, 'checkRewriteEngineChanges']);
        add_action('plugins_loaded', [$this, 'controller']);
    }

    function check_version()
    {
        $version_option_name = str_replace('-', '_', SUMEDIA_URLIFY_PLUGIN_NAME) . '_version';
        $version = get_option($version_option_name) ? : 0;
        if (-1 == version_compare($version, SUMEDIA_URLIFY_VERSION)) {
            if ($version == 0) {
                add_option($version_option_name, SUMEDIA_URLIFY_VERSION);
            } else {
                update_option($version_option_name, SUMEDIA_URLIFY_VERSION);
            }
        }
    }

    function check_rewrite_version()
    {
        $version_option_name = str_replace('-', '_', SUMEDIA_URLIFY_PLUGIN_NAME) . '_version';
        $rewrite_version_option_name = str_replace('-', '_', SUMEDIA_URLIFY_PLUGIN_NAME) . '_rewrite_version';
        $version = get_option($version_option_name);
        $rewrite_version = get_option($rewrite_version_option_name) ? : 0;
        if (-1 == version_compare($rewrite_version, $version)) {
            global $wp_rewrite;
            $wp_rewrite->flush_rules();

            if ($rewrite_version == 0) {
                add_option($rewrite_version_option_name, SUMEDIA_URLIFY_VERSION);
            } else {
                update_option($rewrite_version_option_name, SUMEDIA_URLIFY_VERSION);
            }

            add_action('admin_init', function(){
                wp_redirect(admin_url());
            });
        }
    }

    function activate()
    {
        $installer = new \Sumedia\Urlify\Db\Installer;
        $installer->install();

        $urls = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Repository\Urls');
        $admin_url = $urls->get_admin_url();
        $login_url = $urls->get_login_url();

        $htaccess = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Htaccess');
        $htaccess->write($admin_url, $login_url);

        $config = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Config');
        $config->write($admin_url);

        add_action('admin_init', function(){
            wp_redirect(admin_url('plugins.php?' . $_SERVER['QUERY_STRING']));
        });
    }

    function deactivate()
    {
        $htaccess = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Htaccess');
        $htaccess->register_rewrite_filter();
        $htaccess->remove();

        $config = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Config');
        $config->remove();

        // can't redirect here =/ - user drops to 404
    }

    function textdomain()
    {
        load_plugin_textdomain(
            SUMEDIA_URLIFY_PLUGIN_NAME,
            false,
            SUMEDIA_URLIFY_PLUGIN_NAME . DIRECTORY_SEPARATOR . 'languages'
        );
    }

    public function admin_stylesheets()
    {
        $cssFile = SUMEDIA_URLIFY_PLUGIN_URL . '/assets/css/admin-style.css';
        wp_enqueue_style('sumedia_urlify_admin_style', $cssFile);
    }

    public function setup_menu()
    {
        $menu = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Admin\View\Menu');
        add_submenu_page(
            'plugins.php',
            $menu->get_page_title(),
            $menu->build_iconified_title(),
            'manage_options',
            $menu->get_slug(),
            [$menu, 'render'],
            $menu->get_pos()
        );
    }

    public function factories()
    {
        $factory = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\HtaccessFactory');
        \Sumedia\Urlify\Base\Registry::set_factory('Sumedia\Urlify\Htaccess', $factory);
    }

    public function rewrite_listener()
    {
        \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Htaccess');
    }

    public function checkRewriteEngineChanges()
    {
        $version_option_name = str_replace('-', '_', SUMEDIA_URLIFY_PLUGIN_NAME) . '_version';
        $version = get_option($version_option_name) ? : 0;
        if (-1 == version_compare($version, '0.3.5')) {
            $isRewriteEnabled = true;
        } else {
            $isRewriteEnabled = isset($_SERVER['HTTP_MOD_REWRITE']) && $_SERVER['HTTP_MOD_REWRITE'] == 'On';
        }
        $urls = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Repository\Urls');

        if (!$isRewriteEnabled && $urls->get_admin_url() != 'wp-admin') {
            $urls->set_admin_url('wp-admin');
            $urls->set_login_url('wp-login.php');
            $config = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Config');
            $config->write('wp-admin');

            add_action('admin_init', function(){
                wp_redirect(admin_url());
            });
        }
    }

    public function controller()
    {
        if (isset($_GET['page']) && $_GET['page'] == SUMEDIA_URLIFY_PLUGIN_NAME) {

            $action = isset($_POST['action']) ? $_POST['action'] : null;
            $action = null == $action && isset($_GET['action']) ? $_GET['action'] : $action;
            $action = null == $action ? 'Config' : $action;
            if (!preg_match('#^[a-z0-9_\-]+$#i', $action)) {
                return;
            }

            $controller = 'Sumedia\Urlify\Admin\Controller\\' . $action;

            $check_file = SUMEDIA_URLIFY_PLUGIN_PATH . DS . 'src' . DS . str_replace('\\', DS, $controller) . '.php';

            if (file_exists($check_file)) {
                $controller = \Sumedia\Urlify\Base\Registry::get($controller);
                add_action('admin_init', [$controller, 'prepare']);
                add_action('admin_init', [$controller, 'execute']);
            }

        }
    }

    public function urlify($url)
    {
        $urls = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Repository\Urls');
        $admin_url = $urls->get_admin_url();
        $login_url = $urls->get_login_url();

        return str_replace(
            ['wp-login.php', '/wp-admin/'],
            [$login_url, '/' . $admin_url . '/'],
            $url
        );
    }
}