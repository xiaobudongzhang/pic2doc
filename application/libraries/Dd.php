<?php

class Dd 
{
	
	private $appid;
	private $secretKey;
	public $api_config;
	public $base_header=[];

	public function __construct()
	{
		$config=\Config::get('dd','default');
		$this->appid=$config['appid'];
		$this->secretKey=$config['secretKey'];

		$this->api_config=\Config::get('api', 'dd');


		$timestamp = time();
		$signature = md5($this->appid.$this->secretKey.$timestamp);
		$this->base_header=[
        	'appId'=>$this->appid,
			'timestamp'=>$timestamp,
			'signature'=>$signature,
           // 'Content-Type' => 'application/json'
        ];

	}

	//发送消息
	public function	chat_send($team_chatid,$sender,$content,$messageUrl="http://10.12.21.119:8899/v/point/index?map_page_sub_id=1"){ 

		$out = getFormatOut();

		$data = [
			"chatid"=>$team_chatid,
			"sender"=>$sender,
			"msgtype"=>"text",
			"text"=>[
					"content"=>$content,
					]
		];

        $method = "/chat/send";

 		$req=[]; 
        $req['config'] = $this->api_config;
        $req['url_method'] = $method;
        $req['http_method'] = 'post';
        $req['headers']=$this->base_header;
        $req['headers']['Content-Type']= 'application/json';
		$req['log_level'] =2;

        try {
            $req['data'] = json_encode($data);
            $out = \Curl::request($req);
        } catch (\Exception $e) {
            $out['code']=$e->getCode();
            $out['msg']=$e->getMessage();
            return $out;
        }

        return $out;
	}
	//创建
	public function chat_create($name='',$owner='',$useridlist=[]){

		$out = getFormatOut();

        $data = [
            'name' => $name,
            'owner' => $owner,
            'useridlist' => $useridlist,
        ];

        $method = "/chat/create";

 		$req=[]; 
        $req['config'] = $this->api_config;
        $req['url_method'] = $method;
        $req['http_method'] = 'post';
        $req['headers']=$this->base_header;
        $req['headers']['Content-Type']= 'application/json';
		$req['log_level'] =2;

        try {
            $req['data'] = json_encode($data);
            $out = \Curl::request($req);
        } catch (\Exception $e) {
            $out['code']=$e->getCode();
            $out['msg']=$e->getMessage();
            return $out;
        }

        return $out;
	}
	//获取
	public function chat_get($chatid=''){

	$out = getFormatOut();

	$method = "/chat/get?chatid={$chatid}";

	$req=[]; 
    $req['config'] = $this->api_config;
	$req['url_method'] = $method;
	$req['http_method'] = 'get';
    $req['headers']=$this->base_header;
	
	try {
	    $out = \Curl::request($req);
	} catch (\Exception $e) {
	        $out['code']=$e->getCode();
            $out['msg']=$e->getMessage();
            return $out;
	}

	return $out;
	}


}