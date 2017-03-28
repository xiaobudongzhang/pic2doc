<?php
class Point extends CI_Controller {

  public function index() {
  	$map_page_sub_id=(int)$this->input->get('map_page_sub_id');
  	$row=PageModel::getOne($map_page_sub_id);
    
  	$modeStatus=(int)$this->input->get('mode_status');

  	View::assign('map_page_sub_id',$map_page_sub_id);
  	View::assign('map_page_sub_point_id',(int)$this->input->get('map_page_sub_point_id'));
  	View::assign('show_type_name',$this->input->get('show_type_name'));
	View::assign('row',$row);
	View::assign('modeStatus',$modeStatus);
    View::render('point/index');
  }

  public function mark() {
    View::render('point/mark');
  }

}

