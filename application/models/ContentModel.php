<?php
class ContentModel extends BaseModel {

	public static function insert($data) {
		$db = self::db ( 'test' );
		return $db->insert ( 'visual_all_content', $data );
	}
	public static function delete($id) {
		$db = self::db ( 'test' );
		$where ['id'] = $id;
		return $db->delete ( 'visual_all_content', $where );
	}




/*				'map_page_sub_point_id' => $map_page_sub_point_id,
				'type_index' => $type_index,
				'content'=>$content
				*/

	public static function replace( $data) {
		$db = self::db ( 'test' );
		$data['create_time']=time();
		$data['update_time']=time();

		$db->insert ( 'visual_map_point_his', $data );

		return $db->replace( 'visual_map_point', $data );
	}
}