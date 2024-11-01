<?php

namespace Sumedia\Urlify\Base;

class View
{
    /**
     * @var string
     */
    protected $template;

    /**
     * @var array
     */
    protected $vars = [];

    /**
     * @param $template
     */
    public function set_template($template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function get_template()
    {
        return $this->template;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        $this->vars[$name] = $value;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
        if (!$this->has($name)){
            throw new \RuntimeException(sprintf(__('No property setted with name "%s"', SUMEDIA_BASE_PLUGIN_NAME), $name));
        }
        return $this->vars[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->vars[$name]);
    }

    /**
     * @param bool $return
     * @return null|string
     */
    public function render($return = false)
    {
        if ($return) {
            ob_start();
        }
        require $this->template;
        if ($return) {
            $content = ob_get_clean();
            return $content;
        }
    }
}
