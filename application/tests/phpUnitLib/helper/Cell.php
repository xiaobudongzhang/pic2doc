<?php
namespace phpUnitLib\helper;
class Cell{
	public static function getCellIdFromSearch(){
		$params['size']=1;
		$houseSearchList=@\Provider\SearchCellModel::query($params);
		return isset($houseSearchList['data'][0]['cell_id'])?$houseSearchList['data'][0]['cell_id']:0;
	}
}