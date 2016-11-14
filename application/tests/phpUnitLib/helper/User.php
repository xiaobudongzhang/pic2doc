<?php

namespace phpUnitLib\helper;

class User {
	/**
	*获取登录的cookie(若登录过取缓存)
	*/
	public static  function getLoginCookie($userId=205869,$mobile=18800347631) {
		$userInfo = [ ];
		$returnCookie=[];
		$holdtime = 3600 * 24 * 7;
		$loginStatus = false;
		$removeStatus = true;

		if($cacheRes=\CacheRedis::get('loginCookieInfo'.$userId)){

			return json_decode($cacheRes,true);
		}
		//登录..................
		$blackInfo = \Rest\AModel::blacklInfo ( $userId );
		if ($blackInfo) {
			$removeStatus = \Rest\AModel::removeBlacklists ( $blackInfo ['id'] );
		}
		if ($removeStatus) {
			$authStatus = \Rest\AModel::authCode ( $mobile, 'login' );
			
			sleep ( 3 );
			
			if ($authStatus ['code'] == '00000') {
				
				$user = \Rest\AModel::loginByAuthCode ( $mobile, 111111 );
			
				if ($user ['code'] == '00000') {
					$loginStatus = true;
				}
			}
		}


		if(!$loginStatus){
			return $returnCookie;
		}


		//保存登录信息............
		$returnCookie=[
			'phone'=>[ 'key'=>'phone','value'=> $mobile, 'expiry'=>$holdtime, 'domain'=>get_top_domain ()],
			'userId'=>['key'=>'userId', 'value'=>$user ['data'] ['user_id'], 'expiry'=>$holdtime, 'domain'=>get_top_domain () ],
			'token'=>['key'=>'token', 'value'=>$user ['data'] ['token'], 'expiry'=>$holdtime, 'domain'=>get_top_domain () ]
		];

		\CacheRedis::save('loginCookieInfo'.$userId, json_encode($returnCookie), 86400);
		
		if (! isset ( $user ['data'] ['username'] )) {
			$user ['data'] ['username'] = "";
		}

		
		$ciphertext = my_sha1 ( $mobile );
		writeUserSession ( $ciphertext, array (
				"mobile" => $user ['data'] ['mobile'],
				"user_id" => $user ['data'] ['user_id'],
				"token" => $user ['data'] ['token'],
				"username" => $user ["data"] ["username"] 
		) );

	}
	
}