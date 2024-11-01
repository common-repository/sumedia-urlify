<?php

namespace Sumedia\Urlify\Admin\View;

class Config extends \Sumedia\Urlify\Base\View
{
    public function __construct()
    {
        $this->template = \Sumedia\Urlify\ds(SUMEDIA_URLIFY_PLUGIN_PATH . '/templates/admin/config.phtml');
        $form = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Admin\Form\Config');
        $form->set_data([
            'admin_url' => 'wp-admin',
            'login_url' => 'wp-login.php'
        ]);
        $this->set('form', $form);
    }
}