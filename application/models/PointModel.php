<?php
class PointModel extends BaseModel {
	public static function getlist($map_page_sub_id = '') {
		$db = self::db ( 'test' );
		
		$db->where ( 'map_page_sub_id', $map_page_sub_id );

		$res = $db->get ( 'visual_map_page_sub_point' )->result_array ();
		
		if(!$res){
			return  [];
		}
		
		$mapIds=array_column($res,'id');
		$allContent=$db->where_in('map_page_sub_point_id',$mapIds)->get('visual_map_point')->result_array();
		$allContent=tran_key($allContent,'map_page_sub_point_id',false,false);

		$allContentTran=[];
		foreach($allContent as $id=>$content){
			foreach($content as $cc){
						$allContentTran[$id][$cc['type_name']]=$cc;	
			}
		}

		$initContent=[];
		$initContent['cp']=
			[
			"type_name"=>"cp",
			"content"=>""	
			];
		$initContent['cs']=	[
			"type_name"=>"cs",
			"content"=>""
			];
		$initContent['kf']=
			[
			"type_name"=>"kf",
			"content"=>""
			];
		$initContent['sj']=
			[
			"type_name"=>"sj",
			"content"=>""
			];
						
		foreach ($res as &$row){
			$row['contentList']=$initContent;
			
			foreach($row['contentList'] as $type_name=>$c){
				
				if(isset($allContentTran[$row['id']][$type_name])){
					$row['contentList'][ $type_name]=$allContentTran[$row['id']][$type_name];
				
				}
				
			}
			$row['contentList']=array_values($row['contentList']);
		}
		unset($row);
		return $res;
	}

	public static function delete($id) {
		$db = self::db ( 'test' );
		$where ['id'] = $id;
		return $db->delete ( 'visual_map_page_sub_point', $where );
	}


	public static function getOne($id){
			$db = self::db ( 'test' );
			$db->where('id',$id);
			$res = $db->get ( 'visual_map_page_sub_point' )->row_array ();
			return $res;
	}

	public static function insert($data) {
		$db = self::db ( 'test' );
		return $db->insert( 'visual_map_page_sub_point', $data );
	}

		public static function updateTitle($data) {
		$db = self::db ( 'test' );
		$db->where('id',$data['id']);
		unset($data['id']);
		return $db->update( 'visual_map_page_sub_point', $data );
	}
}