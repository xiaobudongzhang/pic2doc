<?php
class Z extends SimpleBaseController {

	public function __construct(){
		parent::__construct();
/*		if(ENVIRONMENT!='development'){
			die('die');
		}*/
	}

	public function curl(){
		\Curl::request([]);
	}

}