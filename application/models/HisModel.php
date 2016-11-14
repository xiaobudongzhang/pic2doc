<?php
//项目
class HisModel extends BaseModel {
		/**
		*列表
		*/
		public static function getList($id,$type_index){
			$db = self::db ( 'test' );
			$db->where('map_page_sub_point_id',$id);
			$db->where('type_index',$type_index);
			$res = $db->get ( 'visual_map_point_his' )->result_array ();
			return $res;
		}
}