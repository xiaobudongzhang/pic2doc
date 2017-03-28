<?php
class Point extends CI_Controller {
	public function getList() {
		$out = getFormatOut ();
		$out ['data'] = PointModel::getlist ( (int)$this->input->get ( 'map_page_sub_id' ) );
		Response::json ( $out );
	}

	public function insert() {
		$out = getFormatOut ();
		
		$map_page_sub_id = $this->input->post ( 'map_page_sub_id' );
		$pointx = $this->input->post  ( 'point_x' );
		$pointy = $this->input->post  ( 'point_y' );
		
		if (! $map_page_sub_id  || ! $pointx || ! $pointy) {
			$out ['code'] = ERROR_CODE_LESS_CAN;
			$out ['msg'] = '参数错误';
			Response::json ( $out );
		}
		
		$req = [ 
				'map_page_sub_id' => $map_page_sub_id,
				'point_x' => $pointx,
				'point_y' => $pointy 
		];
		PointModel::insert ( $req );
		$out ['data'] = $req;
		Response::json ( $out );
	}
	public function delete() {
		$out = getFormatOut ();
		
		$id = $this->input->post ( 'id' );
		
		if (! $id) {
			$out ['code'] = ERROR_CODE_LESS_CAN;
			$out ['msg'] = '参数错误';
			Response::json ( $out );
		}
		$res = PointModel::delete ( $id );		
		if(!$res){
			$out['code']=ERROR_CODE_FAIL_RES;
			$out['msg']='失败';
		}

		
		Response::json ( $out );
	}

		public function updateTitle() {
		$out = getFormatOut ();
		
		$id =  $this->input->post ( 'id' );
		$title =  $this->input->post ( 'title' );

		
		if (! $id  || ! $title ) {
			$out ['code'] = ERROR_CODE_LESS_CAN;
			$out ['msg'] = '参数错误';
			Response::json ( $out );
		}
		
		$req = [ 
				'id' => $id,
				'title' => $title,
		];
		PointModel::updateTitle ( $req );
		$out ['data'] = $req;
		Response::json ( $out );
	}
    
}
