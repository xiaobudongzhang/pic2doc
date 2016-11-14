<?php

class Input
{
    /**
     * 获取 GET 数据
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function get($key, $default = false)
    {
        $CI =& get_instance();
        $value = $CI->input->get($key, true);
        if($default===false){
        	return isset($value) ? $value : $default;        	
        }else{
        	return ($value != '') ? $value : $default;
        }
    }

    /**
     * 获取 POST 数据
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function post($key, $default = false)
    {
        $CI =& get_instance();
        $value = $CI->input->post($key, true);
        return isset($value) ? $value : $default;
    }

    /**
     * 获取 Request 数据
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function request($key, $default = false)
    {
        $CI =& get_instance();
        $value = $CI->input->get_post($key, true);
        return isset($value) ? $value : $default;
    }

    public static function cookie($index = NULL, $xss_clean = NULL)
    {
        $CI =& get_instance();
        return $CI->input->cookie($index, $xss_clean);
    }

    public static function setCookie($name, $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = FALSE, $httponly = FALSE)
    {
        $CI =& get_instance();
        $CI->input->set_cookie($name, $value, $expire, $domain, $path, $prefix, $secure, $httponly);
    }

    public static function deleteCookie($name, $domain = '', $path = '/', $prefix = '')
    {
        $CI =& get_instance();
        $CI->input->set_cookie($name, '', '', $domain, $path, $prefix);
    }

    public static function ip_address()
    {
        $CI =& get_instance();
        return $CI->input->ip_address();
    }
}