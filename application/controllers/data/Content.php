<?php
class Content extends CI_Controller {


	public function update() {
		$out = getFormatOut ();

		$map_page_sub_point_id =  $this->input->post ( 'map_page_sub_point_id' );
		$type_name =  $this->input->post ( 'type_name' );
		$content =  $this->input->post ( 'content' );
		$send_dd  =  $this->input->post ( 'send_dd' );

        $this->config->load('map');
		$mapAll=$this->config->item('typeAll');
        
		if (! $map_page_sub_point_id || !in_array( $type_name,array_values(array_flip($mapAll)))||!$content ) {
			$out ['code'] = ERROR_CODE_LESS_CAN;
			$out ['msg'] = '参数错误';
			Response::json ( $out );
		}

		$type_index=$mapAll[$type_name];
  		

		$req = [
				'map_page_sub_point_id' => $map_page_sub_point_id,
				'type_name' => $type_name,
				'type_name_index' => $type_index,
				'content'=>$content
		];

		$res= ContentModel::replace ( $req );
		if(!$res){
			$out['code']=ERROR_CODE_FAIL_RES;
			$out['msg']='失败';
		}


		Response::json ( $out );

	}


}
