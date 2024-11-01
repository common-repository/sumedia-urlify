<?php

namespace Sumedia\Urlify\Admin\Controller;

class Config
{
    public function prepare()
    {
        $overview = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Admin\View\Overview');
        $overview->set_content_view(\Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Admin\View\Config'));

        $heading = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Admin\View\Heading');
        $heading->set_title(__('Urlify', SUMEDIA_URLIFY_PLUGIN_NAME));
        $heading->set_side_title(__('Configuration', SUMEDIA_URLIFY_PLUGIN_NAME));
        $heading->set_version(SUMEDIA_URLIFY_VERSION);
        $heading->set_options([
            [
                'name' => __('Back to the plugin overview'),
                'url' => admin_url('admin.php?page=' . SUMEDIA_URLIFY_PLUGIN_NAME)
            ]
        ]);
    }

    public function execute()
    {
        $form = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Admin\Form\Config');
        $urls = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Repository\Urls');
        $form->set_data([
            'admin_url' => $urls->get_admin_url(),
            'login_url' => $urls->get_login_url()
        ]);
    }
}