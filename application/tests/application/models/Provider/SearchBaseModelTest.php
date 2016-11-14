<?php

namespace models\Provider;

class SearchBaseModelTest extends \PHPUnit {
		
	public function testExample() {
		$this->assertTrue(true);
	}
	public function testQuery(){
		$city_id=\phpUnitLib\helper\Common::getCityIdRand();
		ob_start();
		set_city_id($city_id);
		$params['size']=1;
		$searchList=\Provider\SearchModel::query($params);
		$this->assertArrayHasKey( 'code', $searchList );
		$this->assertArrayHasKey ( 'data', $searchList );
		$this->assertEquals ( '00000', $searchList['code'] );
		$this->assertNotEmpty($searchList['data']); 
	}
}