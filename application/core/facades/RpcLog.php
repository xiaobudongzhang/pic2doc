<?php

/*
后端接口访问记录
刘年年
2015-11-02
*/

class RpcLog
{
	private $msgtype_;
	private $rpc_params_;
	private $start_microtime;
    private $rpc_id;
    private $log_level="debug";

	public function __construct($msgtype, $params)
	{
		$this->msgtype_ = $msgtype;
		$this->rpc_params_ = $params;

		$this->start_microtime = 0;
        $this->rpc_id = microtime(true).mt_rand(111111, 999999);
	}

    public function error($line="")
    {
        $this->log_level = "error";
        $this->rpcstart();
        $this->rpcend();
    }

	private function log($line)
	{
		//$filename="rpc_".$this->msgtype_.".".date("Ymd").".log";
		$filename="rpc_".date("Ymd")."_".$this->log_level.".log";

		list($msecs, $sec) = explode(" ", microtime());
        $msec = strlen($msecs)>4?substr($msecs, 2, 3):substr($msecs, 2, strlen($msecs)-2);

        $pageUrl = isset($_SERVER["REQUEST_URI"])?$_SERVER["REQUEST_URI"]:"unkownPage";

		$data ="[". $this->log_level ."] ". date("Y-m-d H:i:s.").$msec." ". $this->rpc_id. " ". $pageUrl ." ".$line."\r\n";

		$config =& get_config();
        if( !file_exists($config["rpclog_path"]) ){
        	if ('development' != ENVIRONMENT){
            mkdir( $config["rpclog_path"] );
        	}
        }

		if ('development' != ENVIRONMENT)
		file_put_contents($config["rpclog_path"].$filename, $data, FILE_APPEND);
	}

	private function context()
	{
		$now = microtime();
		list($msecs, $sec) = explode(" ", $this->start_microtime);
		list($now_msecs, $now_sec) = explode(" ", $now);
		$consume_msec = ($now_sec-$sec)*1000+( floatval($now_msecs) - floatval($msecs) ) * 1000;
		$consume_msec = intval($consume_msec);

		return $this->msgtype_." "."ms:".$consume_msec." params:".json_encode($this->rpc_params_);
	}

	public function rpcstart()
	{
		$this->start_microtime = microtime();
	}

	public function rpcend()
	{
		$this->log( $this->context());
	}
};

//test
/*
$search = new RpcLog("search", array("city_id"=>21));

$search->rpcstart();

$search->rpcend(4000);

echo "done";
*/

?>
