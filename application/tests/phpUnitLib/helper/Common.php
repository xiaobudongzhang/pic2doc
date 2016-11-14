<?php
namespace phpUnitLib\helper;
class Common{
	
	public static function getCityIdRand(){
		$citys=[
				1=>121,
				2=>3,
				3=>267
		];
		return $citys[rand(1,3)];	
	}
}