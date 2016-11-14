<?php
class Sess
{
    public static function userdata($key, $default = false)
    {
        $key=ENVIRONMENT.$key;
        $CI =& get_instance();
        $CI->load->library('session');
        $value = $CI->session->userdata($key);
        return $value ? $value : $default;
    }

    public static function set_userdata($key, $value)
    {
        $key=ENVIRONMENT.$key;
        $CI =& get_instance();
        $CI->load->library('session');
        $CI->session->set_userdata($key, $value);
    }

    public  static function delete($key){
        $key=ENVIRONMENT.$key;
        $CI =& get_instance();
        $CI->load->library('session');
        $CI->session->unset_userdata($key);
    }
}