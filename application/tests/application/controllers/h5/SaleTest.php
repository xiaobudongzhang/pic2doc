<?php

namespace controllers\h5;

class SaleTest extends \PHPUnit_Extensions_Selenium2TestCase {
	
	protected function setUp() {
		$base_url = \phpUnitLib\helper\Url::getMBaseUrl ();
		$this->setBrowser ( 'chrome' );
		$this->setBrowserUrl ( $base_url );
	}
	
	public function testExample() {
		$this->assertTrue(true);
	}
		
	public function testDetail() {
		$house = \phpUnitLib\helper\House::getHouseFromSearch ();
		$base_url = \phpUnitLib\helper\Url::getMBaseUrl ();
		$url = "{$base_url}h5/sale/detail?house_id={$house['house_id']}";
		
		$this->url ( $url );
		$title0=mb_substr($house['house_name'],0,-2);
		$title="{$house['cell_name']}{$title0}{$house['house_area']}平米二手房-{$house['city_name']}房多多";
		file_put_contents('E:\t3.log', $title);
		file_put_contents('E:\t4.log',  $this->title () );
		$this->assertEquals ( $title, $this->title () );
		$this->closeWindow ();
	}
	
	/**
	 * 检查字段
	*/
	public function testDetailZiduan() {
		$checkList = [
				'houseId',
				'ownerId',
				'districtId',
				'houseSaleStatus',
				'lat',
				'lng',
				'cellAddress',
				'suggestLookHouseTime',
				'ownerName',
				'houseTitle',
				'area',
				'cityId',
				'cellId',
				'cellName',
				'cellAddress',
				'halfprice',
				'allFloor',
				'shi',
				'ting',
				'wei',
				'districtName',
				'houseProperty',
				'onFloor'
		];
		$houseId = \phpUnitLib\helper\House::getHouseIdFromSearch ();
		$res = \H5\HouseModel::detail ( $houseId );
		$this->assertObjectHasAttribute ( 'code', $res );
		$this->assertObjectHasAttribute ( 'data', $res );
		$this->assertEquals ( '00000', $res->code );
		foreach ( $checkList as $v ) {
			$this->assertObjectHasAttribute ( $v, $res->data );
		}
	} 
}