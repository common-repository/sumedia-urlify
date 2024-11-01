<?php

namespace Sumedia\Urlify\Admin\View;

class Menu extends \Sumedia\Urlify\Base\View
{
    /**
     * @var string
     */
    protected $page_title;

    /**
     * @var string
     */
    protected $menu_title;

    /**
     * @var string
     */
    protected $icon_file;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var int
     */
    protected $pos;

    public function __construct()
    {
        $this->set_template(\Sumedia\Urlify\ds(SUMEDIA_URLIFY_PLUGIN_PATH . '/templates/admin/menu.phtml'));
        $this->set_page_title(__('Urlify', SUMEDIA_URLIFY_PLUGIN_NAME));
        $this->set_menu_title(__('Urlify', SUMEDIA_URLIFY_PLUGIN_NAME));
        $this->set_icon_file(SUMEDIA_URLIFY_PLUGIN_URL . '/assets/images/wp-n-suma.png');
        $this->set_slug(SUMEDIA_URLIFY_PLUGIN_NAME);
        $this->set_pos(10);
    }

    /**
     * @return string
     */
    public function build_iconified_title()
    {
        return '<img style="float:left;" height="16" src="' . esc_url($this->icon_file) . '" alt="' . esc_attr(__('Sumedia', 'sumedia-base')) . '" /> ' . esc_html($this->menu_title);
    }

    /**
     * @return string
     */
    public function get_page_title()
    {
        return $this->page_title;
    }

    /**
     * @param string $page_title
     */
    public function set_page_title($page_title)
    {
        $this->page_title = $page_title;
    }

    /**
     * @return string
     */
    public function get_menu_title()
    {
        return $this->menu_title;
    }

    /**
     * @param string $menu_title
     */
    public function set_menu_title($menu_title)
    {
        $this->menu_title = $menu_title;
    }

    /**
     * @return string
     */
    public function get_icon_file()
    {
        return $this->icon_file;
    }

    /**
     * @param string $icon_file
     */
    public function set_icon_file($icon_file)
    {
        $this->icon_file = $icon_file;
    }

    /**
     * @return string
     */
    public function get_slug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function set_slug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return int
     */
    public function get_pos()
    {
        return $this->pos;
    }

    /**
     * @param int $pos
     */
    public function set_pos($pos)
    {
        $this->pos = $pos;
    }
}
