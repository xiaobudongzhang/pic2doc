<?php

namespace models\Provider;

class HouseModelTest extends \PHPUnit {
		
	public function testExample() {
		$this->assertTrue(true);
	}
	public function testDetail() {
		$houseId = \phpUnitLib\helper\House::getHouseIdFromSearch ();
		$res = \Provider\HouseModel::detail ( $houseId );
		$this->assertObjectHasAttribute ( 'code', $res );
		$this->assertEquals ( '00000', $res->code );
	}
}