<?php

namespace Sumedia\Urlify\Base;

class Form
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param null|string $key
     * @return array|mixed
     */
    public function get_data($key = null)
    {
        return $key ? $this->data[$key] : $this->data;
    }

    /**
     * @param array $key
     * @param null|mixed $value
     */
    public function set_data($key, $value = null)
    {
        if (null == $value) {
            $this->data = $key;
        } else {
            $this->data[$key] = $value;
        }
    }
}