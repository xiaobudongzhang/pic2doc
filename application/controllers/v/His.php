<?php
class His extends SimpleBaseController {
  public function index() {
  	$map_page_sub_point_id=(int)Input::get("map_page_sub_point_id",1);
	$type_name=Input::get("type_name",'cp');
	$mapAll=\Config::get('map','typeAll');
	$type_index=isset($mapAll[$type_name])?$mapAll[$type_name]:0;
  	$list=\HisModel::getList($map_page_sub_point_id,$type_index);

  	View::assign('list',$list);
    View::render('his/index');
  }
}