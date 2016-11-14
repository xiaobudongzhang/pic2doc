<?php

class Config
{
    public static function get($file, $key, $default = false)
    {
        $CI =& get_instance();
        $CI->config->load($file, true);
        $value = $CI->config->item($key, $file);
        return $value ? $value : $default;
    }
}