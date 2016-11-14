<?php
class PicModel extends BaseModel {
	public static function getlastone($page){
		$db = self::db ( 'test' );
		
		if ($page) {
			$db->where ( 'page', $page );
		} else {
			return [ ];
		}
		$db->order_by('id',desc);
		$res = $db->get ( 'visual_pic' )->row_array ();
		return $res;
	}
	
	public static function insert($data) {
		$db = self::db ( 'test' );
		return $db->insert ( 'visual_pic', $data );
	}
	public static function delete($id) {
		$db = self::db ( 'test' );
		$where ['id'] = $id;
		return $db->delete ( 'visual_pic', $where );
	}
	public static function update($id, $data) {
		$db = self::db ( 'test' );
		$where ['id'] = $id;
		return $db->update ( 'visual_pic', $data, $where );
	}
}