<?php

namespace Sumedia\Urlify\Admin\Controller;

class SetConfig extends \Sumedia\Urlify\Base\Controller
{
    /**
     * @var $this
     */
    protected static $instance;

    public function execute()
    {
        $form = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Admin\Form\Config');
        $urls = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Repository\Urls');
        $messenger = \Sumedia\Urlify\Base\Messenger::get_instance();

        if ($form->is_valid($_POST)) {
            $urls->set_admin_url($form->get_data('admin_url'));
            $urls->set_login_url($form->get_data('login_url'));

            $htaccess = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Htaccess');
            $htaccess->write($form->get_data('admin_url'), $form->get_data('login_url'));

            $config = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Config');
            $config->write($form->get_data('admin_url'));

            $messenger->add_message($messenger::TYPE_SUCCESS, __('The data has successfully been saved', SUMEDIA_URLIFY_PLUGIN_NAME));
        } else {
            $messenger->add_message($messenger::TYPE_ERROR, __('The data could not be setted', SUMEDIA_URLIFY_PLUGIN_NAME));
        }

        wp_redirect(admin_url('admin.php?page=' . SUMEDIA_URLIFY_PLUGIN_NAME . '&action=Config'));
        exit;
    }
}
