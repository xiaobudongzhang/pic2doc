<?php

class CacheBase
{
    private static $CI;
    protected  static $adapter='';
    /**
     * 初始化
     *
     * @return  void
     */
    public static function init($adapter='')
    {
        self::$CI =& get_instance();
        $options['key_prefix'] = self::getKeyPrefix();
        $options['adapter'] = 'file';
        $options['backup'] = 'file';

        if (in_array(ENVIRONMENT, [ 'beta', 'production']))
        {
            $options['adapter'] = 'memcached';
        }

		if(in_array($adapter,['redis','memcached','wincache','apc'])){
			$options['adapter'] = $adapter;
		}

        self::$CI->load->driver('cache', $options,'cache'.$adapter);
    }

    /**
     * CI 缓存方法映射
     *
     * @param  string $method 方法名
     * @param  array  $params 参数数组
     * @return  mixed
     */
    public static function __callStatic($method, $params = null)
    {
        $no_cache=Input::request('no_cache');
        if($no_cache==1&&self::$adapter!='redis'){//保留redis信息
            return '';
        }

        $tmp_cache_name='cache';
        if(self::$adapter){
            $tmp_cache_name='cache'.self::$adapter;
            Cache::init(self::$adapter);
          //  self::$adapter='';//重置到默认
        }else{
            Cache::init();
        }

        return call_user_func_array([self::$CI->$tmp_cache_name, $method], $params);
    }

    public function setData($data,$cacheTTL){
        $return = false;
        $cacheKey = $this->getCacheKey();
        if($cacheTTL >0){
            $return = Cache::save($cacheKey, $data, $cacheTTL);
        }
        return $return;
    }

    public static function getData($key)
    {
        if ($trace = debug_backtrace(false,  2)[1])
        {
            $id = $trace['class'] . '::' . $trace['function'];

            if (is_array($key))
            {
                ksort($key);
                $key = json_encode($key);
            }

            $key = md5($id . '_' . $key);

            return self::get($key);
        }

        return false;
    }

    public static function saveData($key, $data, $ttl = 0)
    {
        if ($trace = debug_backtrace(false,  2)[1])
        {
            $id = $trace['class'] . '::' . $trace['function'];

            if (is_array($key))
            {
                ksort($key);
                $key = json_encode($key);
            }

            $key = md5($id . '_' . $key);

            self::save($key, $data, $ttl);
            return $data;
        }

        return false;
    }

    public static function  set_adapter($adapter){
        if(in_array($adapter,['','file','redis','memcached','wincache','apc','dummpy'])){
            self::$adapter=$adapter;
        }
    }
    /**
     * Increment a not raw value
     *
     * @param	string	$id	Cache ID
     * @param	int	$offset	Step/value to add
     * @return	mixed	New value on success or FALSE on failure
     */
    public static function incrementTranKey($id, $offset = 1)
    {
    	return self::increment( self::getKeyPrefix().$id, $offset);
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Decrement a not raw value
     *
     * @param	string	$id	Cache ID
     * @param	int	$offset	Step/value to reduce by
     * @return	mixed	New value on success or FALSE on failure
     */
    public static function decrementTranKey($id, $offset = 1)
    {
    	return self::decrement( self::getKeyPrefix().$id, $offset);
    }
    
    private static function getKeyPrefix(){
    	//解决s1与线上缓存公用的问题
    	if(LOAD_BALANCE==='s1-web'){
    		return ENVIRONMENT.'_s1_web_esf_';    		
    	}else{
    		return ENVIRONMENT.'_esf_';
    	}

    }

}

// 初始化静态类
//Cache::init();
