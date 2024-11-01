<?php

/**
 * Sumedia URL Changer
 *
 * @package     Sumedia_Urlify
 * @copyright   Copyright (C) 2019, Sumedia - kontakt@sumedia-webdesign.de
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or higher
 *
 * @wordpress-plugin
 * Plugin Name: Sumedia Urlify
 * Plugin URI:  https://github.com/sumedia-wordpress/urlify
 * Description: Changes important URL's to improve security
 * Version:     0.3.8
 * Requires at least: 5.3 (nothing else tested yet)
 * Requires PHP: 5.6.0 (not tested, could work)
 * Author:      Sven Ullmann
 * Author URI:  https://www.sumedia-webdesign.de
 * License:     GPL-3.0-or-later
 * Text Domain: sumedia-urlify
 * Domain Path: /languages/
 * Bug Reporting: https://github.com/sumedia-wordpress/urlify/issues
 *
 * WC requires at least: 3.0
 * WC tested up to: 3.8
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Sumedia\Urlify;

if (!defined('ABSPATH')) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

if (-1 == version_compare(PHP_VERSION, '5.6.0')) {

    function sumedia_base_phpversionlow_message()
    {
        return print '<div id="message" class="error fade"><p>' . __('Your PHP version is to low for using Sumedia plugins.') . '</p></div>';
    }
    add_action('admin_notices', 'sumedia_base_phpversionlow_message');

} else {

    define('SUMEDIA_URLIFY_VERSION', '0.3.7');
    define('SUMEDIA_URLIFY_PLUGIN_NAME', dirname(plugin_basename(__FILE__)));
    define('SUMEDIA_URLIFY_PLUGIN_PATH', __DIR__);
    define('SUMEDIA_URLIFY_PLUGIN_URL', plugin_dir_url(__FILE__));

    require_once(__DIR__ . str_replace('/', DIRECTORY_SEPARATOR, '/inc/functions.php'));
    require_once(__DIR__ . ds('/src/Sumedia/Urlify/Base/Autoloader.php'));

    $autoloader = \Sumedia\Urlify\Base\Autoloader::get_instance();
    $autoloader->register_autoloader();

    $plugin = new \Sumedia\Urlify\Plugin;
    register_activation_hook(__FILE__, [$plugin, 'activate']);
    register_deactivation_hook(__FILE__, [$plugin, 'deactivate']);
    $plugin->init();
}