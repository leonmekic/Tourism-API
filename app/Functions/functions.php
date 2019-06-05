<?php

if (!function_exists('class_to_event_str')) {
    /**
     * Create Snake case event based on full class name
     *
     * @param mixed $str
     * @param string $action
     * @param bool $uppercase
     *
     * @return string
     */
    function class_to_event_str($str, $action, $uppercase = true) {
        if (!is_string($str)) {
            $str = get_class($str);
        }

        $str = $str."\\".$action;

        $str = str_replace("\\", "_", $str);

        if ($uppercase) {
            $str = strtoupper($str);
        }

        return $str;
    }
}