<?php

namespace Sumedia\Urlify\Base;

class Messenger
{
    /**
     * Defines the error types
     */
    const TYPE_ERROR = 'error';
    const TYPE_WARNING = 'warning';
    const TYPE_SUCCESS = 'success';

    /**
     * @var $this
     */
    protected static $instance;

    protected function __construct()
    {
        add_action('admin_notices', [$this, 'get_messages']);
        add_action('template_footer', [$this, 'reset_messages']);
    }

    /**
     * @return $this
     */
    public static function get_instance()
    {
        if (null == static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * @param string $type
     * @return bool
     */
    protected function is_valid_type($type)
    {
        if (in_array($type, [self::TYPE_ERROR, self::TYPE_WARNING, self::TYPE_SUCCESS])) {
            return true;
        }
        return false;
    }

    /**
     * @param $message
     */
    public function add_message($type, $message)
    {
        if ($this->is_valid_type($type)) {
            if (!is_string($message)) {
                throw new \RuntimeException(__('The given message is not of type string', 'sumedia-base'));
            }
            $_SESSION['sumedia_messenger'][$type][] = $message;
        }
    }

    /**
     * @return string
     */
    public function get_messages()
    {
        $html = '';
        if (isset($_SESSION['sumedia_messenger'])) {
            if (empty($_SESSION['sumedia_messenger'])) {
                return;
            }

            foreach ($_SESSION['sumedia_messenger'] as $type => $messages) {
                if (count($messages)) {
                    $html .= '<ul class="message ' . $type . ' fade">';
                    foreach ($messages as $message) {
                        $html .= '<li>' . esc_html($message) . '</li>';
                    }
                    $html .= '</ul>';
                }
            }
        }
        echo $html;
    }

    public function reset_messages()
    {
        $_SESSION['sumedia_messenger'] = [];
    }
}
