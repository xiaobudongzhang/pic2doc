<?php

class MY_Controller extends CI_Controller
{
    public $idKey = 0;
    public $uuid = '';
    public $current_module = 'index';
    public $current_city_id = 0;
    public $source_type = '';
    public $current_metro_line_no = 0;
    public $current_metro_line_name = '';
    public $current_metro_station_no = 0;
    public $current_metro_station_name = '';
    public $current_member_info = 0;
    public $http_protocol = '';

    public function __construct()
    {
        parent::__construct();
        $this->idKey = md5(time() . rand(1000, 9999)); // 请求周期唯一标识

        header("Environment: " . ENVIRONMENT);
        header("Load-Balance: " . LOAD_BALANCE);


    }


}
