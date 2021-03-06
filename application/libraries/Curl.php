<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: byz
 * Date: 2015/11/9
 * Time: 16:26
 */
class Curl
{


    /**
     * @param array $data
     * @return array|mixed|stdClass
     * @throws Exception
     *
     *
     * config  配置  //
     * url method  请求方法
     * http method 默认 get
     * code_default 返回的code  默认0
     * log_level 默认1 0 不记录 1 记录返回码错误的 2 记录所有
     * print  1 打印
     * headers 默认[]
     * data 默认[]
     * log_path 默认 errors
     * $options 默认[]
     * code_name 返回的name 默认 code
     * url  url优先于config
     * cache_ttl 缓存时间
     * data_default  data的名字
     * responseType 输出格式 ( json body  all)
     */

    public static function request($data = [])
    {
        $out = array(
            'code' => 0,
            'msg' => '',
            'data' => [],
        );
        //url存在不取config
        if (isset($data['url'])) {
            $url = $data['url'];
        } else {
            $config = $data['config'];
            $url = 'http://' . $config['host'] . ':' . $config['port'];
            if (isset($data['url_method'])) {
                $url .= $data['url_method'];
            }
        }

        //变量
        $type = isset($data['http_method']) ? strtoupper($data['http_method']) : 'GET';
        $headers = isset($data['headers']) ? $data['headers'] : [];
        $options = isset($data['options']) ? $data['options'] : [];
        $request_data = isset($data['data']) ? $data['data'] : [];
        $code_default = isset($data['code_default']) ? $data['code_default'] : 0;
        $code_name = isset($data['code_name']) ? $data['code_name'] : 'code';
        $log_path = isset($data['log_path']) ? $data['log_path'] : 'curl_errors';
        $log_level = isset($data['log_level']) ? $data['log_level'] : 0;
        $sms_type = isset($data['sms_type']) ? $data['sms_type'] : 500;
        $sms_count = isset($data['sms_count']) ? $data['sms_count'] : 1;//发送次数
        $cache_ttl = isset($data['cache_ttl']) ? $data['cache_ttl'] : 0;//发送次数
        $data_default = isset($data['data_default']) ? $data['data_default'] : 'data';
        $responseType = isset($data['response_type']) ? $data['response_type'] : 'json';
        //变量默认
        if (!isset($options['timeout'])) {
            $options['timeout'] = 2;
        }


        //日志记录内容
        $log_data_tmp = $data;
        if (!empty($data['data'])) {
            if (!is_array($data['data'])) {//json转化为数组
                $tmp = json_decode($data['data'], TRUE);
                $log_data_tmp['data'] = $tmp ?: $data['data'];
            }
        }
        $log_data['req'] = $log_data_tmp;

        //缓存
        $cache_key = md5(json_encode($data));
        if ($cache_ttl > 0 && $data = Cache::get($cache_key)) {
            return $data;
        }
        //开始请求
        $res = [];
        try {
            //打印
            if (\Input::request('printcurl') == 1 && ENVIRONMENT != 'production') {
                dump($log_data);
            }
            $res = \Requests::request($url, $headers, $request_data, $type, $options);
            //打印
            if (\Input::request('printcurl') == 1 && ENVIRONMENT != 'production') {
                dump($res);
            }
            if ($res->status_code != 200) {

                $log_data['res'] = ['code' => $res->status_code];

                array_unshift($log_data, ['type' => 'status_code error']);
                add_log('curl_error', json_encode($log_data));
                //报警
                throw new \Exception('status_code not 200 ,is ' . $res->status_code, 999999);
            }
        } catch (\Exception $e) {

            $log_data['type'] = 'Exception';
            $log_data['res_code'] = [
                'code' => $e->getCode(),
                'msg' => $e->getMessage()

            ];
            array_unshift($log_data, ['type' => 'Exception']);
            add_log('curl_error', json_encode($log_data));
            throw $e;
        }

        // 输出
        if ($responseType == 'json') {
            $out = json_decode($res->body, TRUE);
            if (!$out) {
                $log_data ['type'] = 'json_decode error';
                $log_data ['res'] = ['data' => $res->body];
                add_log('curl_error', json_encode($log_data));
                throw new \Exception ('json解析出错', 999999);
            }
            if (isset ($out [$data_default]) && $cache_ttl > 0) {
                Cache::save($cache_key, $out, $cache_ttl);
            }
            $log_data ['res'] = $out;
            // 日志级别
            if ($log_level == 0) {
                return $out;
            }
            if (($log_level == 1)) {
                if ($out [$code_name] != $code_default) {
                    $log_data ['type'] = 'res code  error';
                    add_log('curl_error', json_encode($log_data));
                }

                return $out;
            }

            $log_data ['type'] = 'no error';
            add_log('curl_error', json_encode($log_data));

            return $out;


        }

        if ($responseType == 'body') {
            return $res->body;
        }


        return $res;
    }


    /**
     * dubbo 请求
     * @param $method
     * @param $header
     * @param $data
     * @return array|mixed|stdClass
     */
    public static function dubbo_request($method, $header, $data)
    {
        $req = [];
        $req['http_method'] = 'post';
        $req['config'] = \Config::get('api', 'dubbo_proxy');
        $req['url_method'] = '/' . $method;
        $req['headers'] = [
            'Content-Type' => 'application/json',
            'service' => $header
        ];

        $req['data'] = $data;
        try {
            $res = \Curl::request($req);
        } catch (\Exception $e) {
            $res['code'] = $e->getCode();
            $res['msg'] = $e->getMessage();
        }

        return $res;

    }

    public static function test(){
        echo "test";
    }
}
