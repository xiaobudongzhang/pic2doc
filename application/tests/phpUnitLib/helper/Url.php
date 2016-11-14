<?php

namespace phpUnitLib\helper;

class Url {
	public static function getWebBaseUrl() {
		$userName = \Config::get ( 'phpunit', 'user_name' );
		$config = \Config::get ( 'phpunit', 'web_base_url' );
		return isset ( $config [$userName] ) ? $config [$userName] : [ ];
	}
	public static function getMBaseUrl() {
		$userName = \Config::get ( 'phpunit', 'user_name' );
		$config = \Config::get ( 'phpunit', 'm_base_url' );
		return isset ( $config [$userName] ) ? $config [$userName] : [ ];
	}
}
