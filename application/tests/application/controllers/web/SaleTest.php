<?php

namespace controllers\web;

class SaleTest extends \PHPUnit_Extensions_Selenium2TestCase {
	protected function setUp() {
		$base_url = \phpUnitLib\helper\Url::getWebBaseUrl ();
		$this->setBrowser ( 'chrome' );
		$this->setBrowserUrl ( $base_url );
	}
	
	public function testDetail() {
		$house = \phpUnitLib\helper\House::getHouseFromSearch ();
		$base_url = \phpUnitLib\helper\Url::getWebBaseUrl ();
		$url = "{$base_url}web/sale/detail?house_id={$house['house_id']}";
		$this->url ( $url );
		$this->assertEquals ( "{$house['cell_name']}{$house['house_name']}{$house['house_area']}平米_{$house['city_name']}{$house['block_name']}二手房-{$house['city_name']}房多多", $this->title () );
		$this->closeWindow ();
		
	}

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
				'newimg',
				'houseTitle',
				'area',
				'cityId',
				'cellId',
				'cellName',
				'cellAddress' ,
			     'districtName',
		];
		$houseId = \phpUnitLib\helper\House::getHouseIdFromSearch ();
		$res = \Web\HouseModel::detail ( $houseId );
		$this->assertObjectHasAttribute ( 'code', $res );
		$this->assertObjectHasAttribute ( 'data', $res );
		$this->assertEquals ( '00000', $res->code );
		foreach ( $checkList as $v ) {
			$this->assertObjectHasAttribute ( $v, $res->data );
		}
	} 
}