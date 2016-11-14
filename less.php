<?php

define('BASEPATH','');
require_once "vendor/less/Less.php";

header('Content-Type: text/css');

$path = $_GET['path'];

$filename = preg_replace('/be$/','fe/',$_SERVER['DOCUMENT_ROOT'] ).'assets/' . ltrim($path, '/');
$url_root = "/assets/" . ltrim($path, '/');

//information about a file path
$path_parts = pathinfo($filename);


if ($path_parts['extension'] != 'less') {
	echo "/**\n * invalid file extension \n */";
	http_response_code(500);
	exit();
}

if (file_exists($filename)) {
	// $last_modified_time = filemtime($filename);
	// $etag = md5_file($filename);
	//
	// header("Last-Modified: ".gmdate("D, d M Y H:i:s", $last_modified_time)." GMT");
	// header("Etag: $etag");
	//
	// if (!empty($_SERVER['HTTP_IF_MODIFIED_SINCE']) && !empty($_SERVER['HTTP_IF_NONE_MATCH'])) {
	// 	if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $last_modified_time ||
	// 	    trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) {
	// 	    header("HTTP/1.1 304 Not Modified");
	// 	    exit;
	// 	}
	// }

	echo "/**\n * " . $path . "\n */\n\n";

  $option = array(
    'sourceMap' => true
  );

	$less = new Less_Parser($option);

	$less->parseFile($filename,  $url_root);

  $file = $less->getCss();

  $pattern = '/(url\s*\(\s*[\'\"]?)(?!data)([^)]+)([\'\"]?\))/';

  //relative url
	//$file = preg_replace($pattern, '$1' . $path_parts['dirname'] . '/' . '$2$3', $file);

	echo $file;
} else {

	echo "/**\n * file \"" . $filename . "\" not exists \n */";

	http_response_code(404);

	exit();
}
