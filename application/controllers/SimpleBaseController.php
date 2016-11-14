<?php
class SimpleBaseController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        header("Environment: " . ENVIRONMENT);
        header("Load-Balance: " . LOAD_BALANCE);
    }
}