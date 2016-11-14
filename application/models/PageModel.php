<?php
//项目
class PageModel extends BaseModel {
		/**
		*项目下列表
		*/
		public static function getList($id){
			$db = self::db ( 'test' );
			$db->where('map_project_id',$id);
			$res = $db->get ( 'visual_map_page_sub' )->result_array ();
			return $res;
		}

				public static function getOne($id){
			$db = self::db ( 'test' );
			$db->where('id',$id);
			$res = $db->get ( 'visual_map_page_sub' )->row_array ();
			return $res;
		}
}