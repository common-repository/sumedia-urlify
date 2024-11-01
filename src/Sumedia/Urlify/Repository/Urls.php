<?php

namespace Sumedia\Urlify\Repository;

class Urls extends \Sumedia\Urlify\Base\Repository
{
    /**
     * @return string
     */
    public function get_table_name()
    {
        return str_replace('-', '_', SUMEDIA_URLIFY_PLUGIN_NAME) . '_urls';
    }

    /**
     * @param array $data
     * @return bool
     */
    public function is_valid_data($data)
    {
        if (isset($data['url']) && !$this->is_valid_slug($data['url'])) {
            return false;
        }
        if (isset($data['urltype']) && !in_array($data['urltype'], ['admin_url','login_url'])) {
            return false;
        }
        return true;
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
     * @return string
     */
    public function get_admin_url()
    {
        $data = $this->findOne('urltype', 'admin_url');
        if (!$data) {
            return 'wp-admin';
        }
        return $data['url'];
    }

    /**
     * @param string $url
     */
    public function set_admin_url($url)
    {
        if (!preg_match('#^[a-z0-9._\-/]+$#i', $url)) {
            throw new \RuntimeException('invalid url has been given');
        }

        $data = $this->findOne('urltype', 'admin_url');
        if (!$data) {
            $data = [
                'urltype' => 'admin_url',
                'url' => $url
            ];
            $this->create($data);
        } else {
            $data['url'] = $url;
            $this->update($data);
        }
    }

    public function remove_admin_url()
    {
        $data = $this->findOne('urltype', 'admin_url');
        if ($data) {
            $id = $data['id'];
            $this->delete($id);
        }
    }

    /**
     * @return string
     */
    public function get_login_url()
    {
        $data = $this->findOne('urltype', 'login_url');
        if (!$data) {
            return 'wp-login.php';
        }
        return $data['url'];
    }

    /**
     * @param string $url
     */
    public function set_login_url($url)
    {
        if (!preg_match('#^[a-z0-9._\-/]+$#i', $url)) {
            throw new \RuntimeException('invalid url has been given');
        }

        $data = $this->findOne('urltype', 'login_url');
        if (!$data) {
            $data = [
                'urltype' => 'login_url',
                'url' => $url
            ];
            $this->create($data);
        } else {
            $data['url'] = $url;
            $this->update($data);
        }
    }

    public function remove_login_url()
    {
        $data = $this->findOne('urltype', 'login_url');
        if ($data) {
            $id = $data['id'];
            $this->delete($id);
        }
    }
}
