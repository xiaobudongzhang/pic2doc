<?php

//缓存相关
class Caches extends SimpleBaseController
{

    public function clean_cache_home()
    {
        $city_id = Input::request('city_id');
        if ($city_id > 0) {
            $cacheKey = md5('home_cache_' . $city_id);
            $res = $this->delete($cacheKey);

        }
        $citys = [
            3,//苏州
            121,// 上海
            267, //南京
            852,//广州
            1337,// 深圳
            2316,// 杭州
        ];
        foreach ($citys as $row) {
            $cacheKey = 'home_cache_' . $row;
            $res = $this->delete($cacheKey);
        }
    }

    public function clean_cache_id()
    {
        $cache_id = Input::request('cache_id');
        $res = $this->delete($cache_id);
    }

    public function clean_cache_recommend()
    {
        $citys = [
            3,//苏州
            121,// 上海
            267, //南京
            852,//广州
            1337,// 深圳
            2316,// 杭州
        ];
        foreach ($citys as $v) {
            if (Sess::userdata('esf_boutique_ids' . $v)) {
                Sess::delete('esf_boutique_ids' . $v);
                echo 'delete:' . $v . '<br>';
            }
        }
        echo 'no cahe';

    }

    public function clean_cache()
    {
        //$cache_id=Input::request('adapter');
        Cache::clean();
        echo 'sucess';
    }

    private function delete($key, $print = true)
    {
        Cache::set_adapter('');
        $detail = Cache::get($key);
        dump( $detail);
        echo "$key".PHP_EOL;
        if(!$detail){
            if ($print) {
                echo '</pre>';
                echo $detail ? '' : '不存在'.PHP_EOL;
            }
            if($print){
                echo '<br></pre>';
            }
            return;
        }
        $del = Cache::delete($key);
        if ($print) {
            echo $del ? '删除成功' : '删除失败';
            echo '<br></pre>';
        }

    }
    public function clear_zfst_result_h5(){
        $user_id=Input::request('user_id');

        $cache_key_tiaojian='zfst_list_by_user_id'.$user_id;//条件缓存
        $tiaojian=\Cache::get($cache_key_tiaojian);
        if($tiaojian){
            $zfst_ids=array_column($tiaojian,'zfst_id');
            foreach($zfst_ids as $v){
                $zfst_result_key='zfst_result_by_zfst_id'.$v;//条件缓存
                $this->delete($zfst_result_key,false);
            }
        }
        $this->delete($cache_key_tiaojian,false);
        $cacheKey = 'h5_holmes_result_view' . $user_id;//页面缓存
        $this->delete($cacheKey,false);
    }
}
