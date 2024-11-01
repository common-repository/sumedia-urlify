<?php

namespace Sumedia\Urlify\Db;

class Installer
{
    /**
     * @var string
     */
    protected $option_name;

    /**
     * @var string
     */
    protected $table_name;

    public function __construct()
    {
        $this->option_name = str_replace('-', '_', SUMEDIA_URLIFY_PLUGIN_NAME) . '_version';
        $this->table_name = str_replace('-', '_', SUMEDIA_URLIFY_PLUGIN_NAME) . '_urls';
    }

    public function install()
    {
        $this->install_table();
        add_option($this->option_name, SUMEDIA_URLIFY_VERSION);
    }

    protected function install_table()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . $this->table_name;

        $query = "SHOW TABLES LIKE '" . $table_name . "'";
        $row = $wpdb->get_row($query);
        if ($row) {
            return;
        }

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE `$table_name` (
            `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `urltype` VARCHAR(64) NOT NULL UNIQUE KEY,
            `url` VARCHAR(128) NOT NULL UNIQUE KEY            
        ) $charset_collate;";
        $wpdb->query($sql);
    }
}