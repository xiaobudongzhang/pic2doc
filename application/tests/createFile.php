<?php
/**
 * 生成tests\application目录里对应的文件
 * @var unknown
 */
// 过滤的目录
$skipFile = [ 
		'cache',
		'config',
		'hooks',
		'language',
		'logs',
		'tests',
		'third_party',
		'thrift',
		'views',
		'data' 
];

$dir = new RecursiveDirectoryIterator ( '..' );
$totalSize = 0;
foreach ( new RecursiveIteratorIterator ( $dir ) as $file ) {
	// 过滤
	if ($dir->isDot ()) {
		continue;
	}
	if (in_array ( $file->getFilename (), [ 
			'.',
			'..' 
	] )) {
		continue;
	}
	if ($dir->isFile ()) {
		continue;
	}
	if (in_array ( $dir->getFilename (), $skipFile )) {
		continue;
	}
	if ($file->getExtension () != 'php') {
		continue;
	}
	// 创建的文件信息
	$filePathName = $file->getPath () . DIRECTORY_SEPARATOR . $file->getBasename ( '.' . $file->getExtension () ) . 'Test.' . $file->getExtension ();
	$filePathNames [] = [ 
			'file' => substr ( $filePathName, 2 ),
			'dir' => substr ( $file->getPath (), 2 ),
			'namespace' => substr ( $file->getPath (), 3 ),
			'classname' => $file->getBasename ( '.' . $file->getExtension () ) . 'Test' 
	];
}
// 创建文件
foreach ( $filePathNames as $file ) {
	$mkFile = getcwd () . DIRECTORY_SEPARATOR . 'application' . $file ['file'];
	$mkDir = getcwd () . DIRECTORY_SEPARATOR . 'application' . $file ['dir'];
	// 添加testExample消除phpunit 报错 No tests found in class
	$content = <<<CONTENT
<?php

namespace {$file['namespace']};

class {$file['classname']} extends \PHPUnit {
		
	public function testExample() {
		\$this->assertTrue(true);
	}
}
CONTENT;
	
	if (! file_exists ( $mkFile )) {
		if (! file_exists ( $mkDir )) {
			mkdir ( $mkDir, 0, true );
		}
		file_put_contents ( $mkFile, $content );
	}
}  
 