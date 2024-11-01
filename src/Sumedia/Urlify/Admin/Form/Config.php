<?php

namespace Sumedia\Urlify\Admin\Form;

class Config extends \Sumedia\Urlify\Base\Form
{
    /**
     * @param array $request_data
     * @return bool
     */
    public function is_valid_data(array $request_data)
    {
        $valid = true;
        $messenger = \Sumedia\Urlify\Base\Messenger::get_instance();

        if (!isset($request_data['_wpnonce']) || !wp_verify_nonce($request_data['_wpnonce'])){
            $messenger->add_message($messenger::TYPE_ERROR, __('The form could not be verified, please try again', SUMEDIA_URLIFY_PLUGIN_NAME));
            $valid = false;
        }

        if (!isset($request_data['admin_url'])) {
            $messenger->add_message($messenger::TYPE_ERROR, sprintf(__('Missing parameter: %s.', SUMEDIA_URLIFY_PLUGIN_NAME), 'admin_url'));
            $valid = false;
        }

        if (!isset($request_data['login_url'])) {
            $messenger->add_message($messenger::TYPE_ERROR, sprintf(__('Missing parameter: %s.', SUMEDIA_URLIFY_PLUGIN_NAME), 'login_url'));
            $valid = false;
        }

        if (!$this->is_valid_slug($request_data['admin_url'])) {
            $messenger->add_message($messenger::TYPE_ERROR, sprintf(__('The given slug: %s is not a valid slug.', SUMEDIA_URLIFY_PLUGIN_NAME), 'admin_url'));
            $valid = false;
        }

        if (!$this->is_valid_slug($request_data['login_url'])) {
            $messenger->add_message($messenger::TYPE_ERROR, sprintf(__('The given slug: %s is not a valid slug.', SUMEDIA_URLIFY_PLUGIN_NAME), 'login_url'));
            $valid = false;
        }

        return $valid;
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
     * @param array $request_data
     * @return bool
     */
    public function is_valid(array $request_data)
    {
        if ($this->is_valid_data($request_data)) {
            $this->set_data([
                'admin_url' => $request_data['admin_url'],
                'login_url' => $request_data['login_url']
            ]);
            return true;
        }
        return false;
    }
}
