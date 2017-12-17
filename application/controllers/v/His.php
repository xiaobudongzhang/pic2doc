<?php
class His extends CI_Controller {
  public function index() {
  	$map_page_sub_point_id=(int)$this->input->get("map_page_sub_point_id",1);
	$type_name=$this->input->get("type_name",'cp');
	$mapAll=$this->config->item('map','typeAll');
	$type_index=isset($mapAll[$type_name])?$mapAll[$type_name]:0;
  	$list=HisModel::getList($map_page_sub_point_id,$type_index);

  	View::assign('list',$list);
    View::render('his/index');
  }
}