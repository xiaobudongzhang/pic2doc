<?php
//项目
class ProjectModel extends BaseModel {
		/**
		*项目下列表
		*/
		public static function getList($id){
			$db = self::db ( 'test' );
			$db->where('project_name_index',$id);
			$res = $db->get ( 'visual_map_project' )->result_array ();
			return $res;
		}
}