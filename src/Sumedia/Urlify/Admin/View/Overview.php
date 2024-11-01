<?php

namespace Sumedia\Urlify\Admin\View;

class Overview extends \Sumedia\Urlify\Base\View
{
    /**
     * @var Heading
     */
    protected $heading_view;

    /**
     * @var Overview
     */
    protected $content_view;

    public function __construct()
    {
        $this->set_template(\Sumedia\Urlify\ds(SUMEDIA_URLIFY_PLUGIN_PATH . '/templates/admin/overview.phtml'));
        $this->set_heading_view(\Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Admin\View\Heading'));
        $this->set_content_view(\Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Admin\View\Config'));
    }

    /**
     * @return Heading
     */
    public function get_heading_view()
    {
        return $this->heading_view;
    }

    /**
     * @param Heading $heading_view
     */
    public function set_heading_view($heading_view)
    {
        $this->heading_view = $heading_view;
    }

    /**
     * @return Overview
     */
    public function get_content_view()
    {
        return $this->content_view;
    }

    /**
     * @param Overview $content_view
     */
    public function set_content_view($content_view)
    {
        $this->content_view = $content_view;
    }
}