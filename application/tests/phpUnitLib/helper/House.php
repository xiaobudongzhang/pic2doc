<?php
namespace phpUnitLib\helper;
class House{
	public static function getHouseIdFromSearch(){
		$params['size']=1;
		$houseSearchList=@\Provider\SearchModel::query($params);
		return isset($houseSearchList['data'][0]['house_id'])?$houseSearchList['data'][0]['house_id']:0;
	}	
	public static function getHouseFromSearch(){
		$params['size']=1;
		$houseSearchList=@\Provider\SearchModel::query($params);
		return isset($houseSearchList['data'][0])?$houseSearchList['data'][0]:[];
	}
}
