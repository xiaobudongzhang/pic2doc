<?php
$page=isset($_REQUEST['page'])?$_REQUEST['page']:'';
$num=isset($_REQUEST['num'])?$_REQUEST['num']:'';
$content=isset($_REQUEST['content'])?$_REQUEST['content']:'';
$point=isset($_REQUEST['point'])?$_REQUEST['point']:'';

if($_SERVER['REQUEST_METHOD']=='GET'){

	$map=file_get_contents("map.json");
	$result=json_decode($map,true);
	$out='';
	if(isset($result[$page])){
		$out=array_values($result[$page]);
	}
	header('Content-type:text/json');
	echo json_encode(['code'=>'00000','msg'=>'ok',"data"=>$out]);
}elseif ($_SERVER['REQUEST_METHOD']=='POST'){
	header('Content-type:text/json');
	$map=file_get_contents("map.json");
	$maxnum=file_get_contents("max.txt");
	$result=json_decode($map,true);
	if(isset($result[$_POST['page']][$num])){
		//修改
		$result[$page][$num]['content']=$content;
		$result[$page][$num]['point']=$point;
		$result[$page][$num]['num']=$num;
	}elseif($content&&$point){
		//新增
		$result[$_POST['page']][$maxnum+1]=['point'=>$point,'content'=>$content,'num'=>$maxnum+1];
		file_put_contents("max.txt",$maxnum+1);
	}

	file_put_contents("map.json",json_encode($result));
	header('Content-type:text/json');
	echo json_encode(['code'=>'00000','msg'=>'ok',"data"=>[]]);
}
