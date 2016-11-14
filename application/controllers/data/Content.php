<?php
class Content extends SimpleBaseController {


	public function update() {
		$out = getFormatOut ();

		$map_page_sub_point_id = Input::post ( 'map_page_sub_point_id' );
		$type_name = Input::post ( 'type_name' );
		$content = Input::post ( 'content' );
		$send_dd  = Input::post ( 'send_dd' );

		$mapAll=\Config::get('map','typeAll');
		if (! $map_page_sub_point_id || !in_array( $type_name,array_values(array_flip($mapAll)))||!$content ) {
			$out ['code'] = ERROR_CODE_LESS_CAN;
			$out ['msg'] = '参数错误';
			Response::json ( $out );
		}

		$type_index=$mapAll[$type_name];
  		$list=\HisModel::getList($map_page_sub_point_id,$type_index);

		$req = [
				'map_page_sub_point_id' => $map_page_sub_point_id,
				'type_name' => $type_name,
				'type_index' => $type_index,
				'content'=>$content
		];

		$res= ContentModel::replace ( $req );
		if(!$res){
			$out['code']=ERROR_CODE_FAIL_RES;
			$out['msg']='失败';
		}

		if($send_dd&&false){//将修改发送到钉钉
			$mapAll=\Config::get('map','typeAllName');
			$title=$mapAll[$type_name];

			$map_page_sub=\PointModel::getOne($map_page_sub_point_id);
			$map_page_sub_id=$map_page_sub['map_page_sub_id'];
			$Url="http://10.12.21.119:8899/v/point/index?map_page_sub_id={$map_page_sub_id}&map_page_sub_point_id={$map_page_sub_point_id}&show_type_name={$type_name}";

			$dd=new \Dd();
			$content="@张保雅 更新【{$title}】文档啦,{$Url}";
			$dres=$dd->chat_send('chatd4da00b14f8e70b5db9cdbb2cb140fc0','zhangbaoya',$content);
		}

		Response::json ( $out );

	}


}
