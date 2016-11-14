<?php
class Pic extends SimpleBaseController {
	
	public function getOne(){
		$out = getFormatOut ();
		$out ['data'] = PicModel::getlastone ( Input::get ( 'page' ) );
		Response::json ( $out );
	}
	
	public function insert() {
		$out = getFormatOut ();
	
		$page = Input::post ( 'page' );
		$pic = Input::post ( 'pic' );

		if (! $page || ! $pic ) {
			$out ['code'] = ERROR_CODE_LESS_CAN;
			$out ['msg'] = '参数错误';
			Response::json ( $out );
		}
	
		$req = [
				'page' => $page,
				'pic' => $pic,
		];
		PicModel::insert ( $req );
		$out ['data'] = $req;
		Response::json ( $out );
	}
	
	public function update() {
		$out = getFormatOut ();
	
		$id = Input::post ( 'id' );
		$pic = Input::post ( 'pic' );
	
		if (! $id || ! $pic) {
			$out ['code'] = ERROR_CODE_LESS_CAN;
			$out ['msg'] = '参数错误';
			Response::json ( $out );
		}
	
		$req = [
				'pic' => $pic
		];
	
		$res= PicModel::update ( $id, $req );
		if(!$res){
			$out['code']=ERROR_CODE_FAIL_RES;
			$out['msg']='失败';
		}
		Response::json ( $out );
	}
	
	public function delete() {
		$out = getFormatOut ();
	
		$id = Input::post ( 'id' );
	
		if (! $id) {
			$out ['code'] = ERROR_CODE_LESS_CAN;
			$out ['msg'] = '参数错误';
			Response::json ( $out );
		}
		$res = PicModel::delete ( $id );
		if(!$res){
			$out['code']=ERROR_CODE_FAIL_RES;
			$out['msg']='失败';
		}
	
	
		Response::json ( $out );
	}
}