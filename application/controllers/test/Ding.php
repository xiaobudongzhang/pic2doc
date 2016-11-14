<?php
class Ding extends SimpleBaseController {

	public $dd;

	public function __construct(){
		parent::__construct();
		if(ENVIRONMENT!='development'){
			die('die');
		}
		$this->dd=new \Dd();

	}
	//创建dd群
	public function chat_create() {
		$res=getFormatOut();

	//	$res=$this->dd->chat_create('可视化文档test','zhangbaoya',['zhangbaoya','renqingsong']);

		dump($res);
	}

	//发送
	public function chat_send() {
		$res=getFormatOut();

		$res=$this->dd->chat_send('chatd4da00b14f8e70b5db9cdbb2cb140fc0','zhangbaoya');

		dump($res);
	}
	//获取
	public function chat_get() {
		$res=getFormatOut();

		$res=$this->dd->chat_get('chatd4da00b14f8e70b5db9cdbb2cb140fc0');

		dump($res);
	}

}