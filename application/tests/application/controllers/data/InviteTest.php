<?php

namespace controllers\data;

class InviteTest extends \PHPUnit {
	public $base_url='';
	public function __construct(){
		parent::__construct();
		$this->base_url=\phpUnitLib\helper\Url::getMBaseUrl ();
	}
	public function testExample() {
		$this->assertTrue(true);
	}
	
	public function testAppointInfo(){
		$house_id=\phpUnitLib\helper\House::getHouseIdFromSearch();
	    $loginCookie=\phpUnitLib\helper\User::getLoginCookie();

    	$this->assertNotEmpty( $loginCookie);

    	$cookies = [];
    	foreach ($loginCookie as $key => $value) {
    		$cookies[$key]=$value['value'];
    	}
    	

		$reqUrl="{$this->base_url}data/invite/appoint_info?house_id={$house_id}";
	
		$req['url']=$reqUrl;
		$req['http_method']='get';
		$req['options']['cookies']=$cookies;

		$res=\Curl::request($req);

        $this->assertArrayHasKey('code',$res);
		$this->assertArrayHasKey('data',$res);
		$this->assertArrayHasKey('msg',$res);
		$this->assertEquals ( '00000', $res['code'] );

	}
	
}