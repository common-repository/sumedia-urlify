<?php

namespace Sumedia\Urlify;

class HtaccessFactory extends \Sumedia\Urlify\Base\Factory
{
    public function build()
    {
        $urls = \Sumedia\Urlify\Base\Registry::get('Sumedia\Urlify\Repository\Urls');
        $admin_url = $urls->get_admin_url();
        $login_url = $urls->get_login_url();
        return new Htaccess($admin_url, $login_url);
    }
}