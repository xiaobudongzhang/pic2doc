<?php



class CacheRedis  extends CacheBase
{
	protected  static $adapter = "redis";

    /**
     * CI 缓存方法映射
     *
     * @param  string $method 方法名
     * @param  array  $params 参数数组
     * @return  mixed
     */
    public static function __callStatic($method, $params = null)
    {
		CacheBase::set_adapter(self::$adapter);

        //return call_user_func_array([self::$CI->$tmp_cache_name, $method], $params);
		return CacheBase::__callStatic($method, $params);
    }
};

?>
