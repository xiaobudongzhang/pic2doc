<?php
class Home extends SimpleBaseController {

  public function project_sub() {
  	$id=(int)Input::get("project_id",1);
  	$list=\ProjectModel::getList($id);

  	View::assign('list',$list);
    View::render('project/page_list');
  }

  public function page_sub() {
  	$map_project_id=(int)Input::get("map_project_id",1);

  	$list=\PageModel::getList($map_project_id);

  	View::assign('list',$list);
    View::render('page/list');
  }

}