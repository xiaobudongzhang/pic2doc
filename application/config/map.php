<?php
$config ['houseImageType'] = [ 
		'NO_TYPE' => 0,
		'HUXING' => 1,
		'JIAOTONG' => 2,
		'WAIJING' => 3,
		'SHIJING' => 4,
		'XIAOGUO' => 5,
		'YANGBAN' => 6,
		'TING' => 7,
		'SHI' => 8,
		'WEI' => 9,
		'CHU' => 10,
		'YANGTAI' => 11,
		'OTHER' => 12,
		'QUANJING' => 13,
		'CELL' => 14 
];

$config ['houseProperty'] = [ 
		'OWNER' => 2, // 自售
		'AGENT' => 3, // 经纪人 
		'MINGDAN' => 5,//名单
		'DEFAULT'=>0,
];


$config ['houseDirection'] = [ 
		'EAST' => [ 
				'value' => 1,
				'name' => '东' 
		],
		'SOUTH' => [ 
				'value' => 2,
				'name' => '南' 
		],
		'WEST' => [ 
				'value' => 3,
				'name' => '西' 
		],
		'NORTH' => [ 
				'value' => 4,
				'name' => '北' 
		],
		'SOUTH_EAST' => [ 
				'value' => 5,
				'name' => '东南' 
		],
		'NORTH_EAST' => [ 
				'value' => 6,
				'name' => '东北' 
		],
		'SOUTH_WEST' => [ 
				'value' => 7,
				'name' => '西南' 
		],
		'NORTH_WEST' => [ 
				'value' => 8,
				'name' => '西北' 
		],
		'SOUTH_NORTH' => [ 
				'value' => 9,
				'name' => '南北' 
		],
		
		'EAST_WEST' => [ 
				'value' => 10,
				'name' => '东西' 
		] ,
		'DEFAULT' => [
				'value' => 0,
				'name' => ''
		]
];

$config ['houseSaleStatus'] = [ 
		'ONLINE' => 3, //在售
		'OFFLINE' => 4, // 下线
		'DEFAULT' => 0,//未知
];


$config ['typeAll'] = [ 
'cp'=> 1,  //产品
'sj'=> 2 , //设计
'kf'=> 3 ,//开发
'cs'=> 4,  //测试
];
$config ['typeAllName'] = [ 
'cp'=> '产品',  //产品
'sj'=> '设计' , //设计
'kf'=> '开发' ,//开发
'cs'=> '测试',  //测试
];


