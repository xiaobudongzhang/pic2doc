<?php


// 首页地址
function home_url()
{
    $home_url = config_item('home_url');

    return $home_url ? $home_url : base_url();
}

// 二手房根地址
function esf_base_url()
{
    $esf_base_url = config_item('esf_base_url');

    return $esf_base_url ? $esf_base_url : base_url();
}

// m站根地址
function m_base_url()
{
    $m_base_url = config_item('m_base_url');

    return $m_base_url ? $m_base_url : base_url();
}

// 自动适配URL
function auto_url($route_url)
{
    $route_url = trim($route_url);
    $route_url = ltrim($route_url, '/');
    $url = base_url() . $route_url;

    return pretty_url($url);
}

// 自动适配搜索URL
function auto_search_url()
{
    $CI =& get_instance();

    $url = '';
    if ($CI->current_module == 'web_sale_list' || $CI->current_module == 'web_sale_detail' || preg_match('/web_house_price.*/', $CI->current_module)) $url = 'web/sale/all';
    elseif ($CI->current_module == 'web_cell_list' || preg_match('/web_cell_detail.*/', $CI->current_module)) $url = 'web/cell/all';
    elseif ($CI->current_module == 'web_index_esf') $url = 'web/sale/all';
    elseif ($CI->current_module == 'web_index_nh') return "http://test.com";

    return auto_url($url);
}

// 给当前url追加参数
function url_append_param($key, $value, $clearKeys = [], $url = '')
{
    if (!$url) {
        $url = current_url();
        $url = str_replace('index.php/', '', $url);
    }

    $params = $_GET;
    $params[$key] = $value;
    if ($key == 'tag' && isset($_GET['tag'])) {
        $multi_tags = explode('-', $_GET['tag']);
        if (count($multi_tags) > 1) {
            $rest_tag_arr = array_diff($multi_tags, array($value));
            $rest_tag = implode('-', $rest_tag_arr);
            $params['tag'] = $rest_tag;
        }
    }

    if (count($clearKeys) > 0) {
        foreach ($clearKeys as $k) {
            unset($params[$k]);
        }
    }
    unset($params['page']);

    if ($value == '') {
        unset($params[$key]);
    }
    if (count($params) > 0) {
        $query_string = http_build_query($params);

        $url = $url . '?' . $query_string;
    }

    return pretty_url($url);
}

// url附加参数，多标签处理
function url_append_param_multi_tags($key, $value, $clearKeys = [], $url = '')
{
    if (!$url) {
        $url = current_url();
        $url = str_replace('index.php/', '', $url);
    }

    $params = $_GET;
    $params[$key] = $value;
    if ($key == 'tag') {
        $params['tag'] = $value;
    }

    if (count($clearKeys) > 0) {
        foreach ($clearKeys as $k) {
            unset($params[$k]);
        }
    }
    unset($params['page']);

    if ($value == '') {
        unset($params[$key]);
    }
    if (count($params) > 0) {
        $query_string = http_build_query($params);

        $url = $url . '?' . $query_string;
    }

    return pretty_url($url);
}

// 自适应当前带有排序标识字段的URL状态
function url_sort_param($key, $field = '')
{
    $params = $_GET;
    $mode = 'desc';

    if (isset($params[$key])) {
        $val = $params[$key];
        $arr = explode('-', $val);

        if (isset($arr[1]) && $arr[1] == 'desc') {
            $mode = 'asc';
        }

        if ($arr[0] != $field) {
            $mode = 'desc';
        }

    }


    if ($field == '') {
        $value = '';
    } else {
        $value = "{$field}-{$mode}";
    }

    return url_append_param($key, $value);
}

// 获取当前某一个URL key的值
function url_get_value($key)
{
    if ($value = Input::get($key, '')) {
        return $value;
    }

    return '';
}

// 获取当前某一个URL的带有区间key的第一个元素值
function url_get_s1($key)
{
    if ($value = Input::get($key, '')) {
        $arr = explode('-', $value);
        if (isset($arr[0])) {
            return $arr[0];
        }
    }

    return '';
}

// 获取当前某一个URL的带有区间key的第二个元素值
function url_get_s2($key)
{
    if ($value = Input::get($key, '')) {
        $arr = explode('-', $value);
        if (isset($arr[1])) {
            return $arr[1];
        }
    }

    return '';
}

// 给当前URL匹配排序上下箭头符
function active_arrow($key, $field, $up, $down)
{
    $params = $_GET;

    if (isset($params[$key])) {
        $val = $params[$key];
        $arr = explode('-', $val);

        if ($arr[0] == $field) {
            // 降序
            if (isset($arr[1]) && $arr[1] == 'desc') {
                return $down;
            }

            return $up;
        }
    }

    return '';
}

// 获取当前url参数渲染css class
function active_class($key, $value, $class_name, $params = [], $tag_flag = FALSE)
{
    $params = $params ? $params : $_GET;
//    if (isset($params[$key]) && urldecode($params[$key]) == $value) {
//        return $class_name;
//    }

    if (isset($params[$key])) {
        if ($tag_flag) {
            $multi_tags = explode('-', $params[$key]);
            foreach ($multi_tags as $v) {
                if (urldecode($v) == $value) {
                    return $class_name;
                }
            }
        } else {
            if (urldecode($params[$key]) == $value) {
                return $class_name;
            }
        }

    }

    if ($value === '' && !isset($params[$key])) {
        return $class_name;
    }

    return '';
}

/**
 * 根据当前标签和tag参数确定multi样式
 * @param $current_tag_name
 * @param $multi_params_tag
 * @param $class_name
 * @return string
 */
function active_multi_tag_class($current_tag_name, $multi_params_tag, $class_name)
{
    if (in_array($current_tag_name, $multi_params_tag)) {
        return $class_name;
    } else {
        return '';
    }
}

// 获取 canonical 连接
function get_canonical_url()
{


    return '';
}

// 解析url的查询字符串为数组
function parse_url_to_params($url)
{
    $arr = parse_url($url);
    if (isset($arr['query'])) {
        parse_str($arr['query'], $params);

        return $params;
    }

    return FALSE;
}

// 智能转化成美观的URL（SEO，伪静态规则风格）
function pretty_url($url)
{
    if (!in_array(ENVIRONMENT, ['testing', 'beta', 'production'])) {
        return $url;
    }

    $url = trim($url);

    // 列表页筛选项链接转化
    if (preg_match('~/web$~', $url)) {
        return home_url() . get_city_pinyin();
    }

    // 列表页筛选项链接转化
    if (preg_match('~/h5[$\?]~', $url)) {
        return m_base_url() . get_city_pinyin();
    }

    $params = parse_url_to_params($url);

    $city_id = 0;
    if (!empty($params['city_id'])) {
        $city_id = $params['city_id'];
        unset($params['city_id']);
    }

    if (is_array($params)) {
        $params = array_map('urlencode', $params);
    }

    if (preg_match('~web[$\?]~', $url)) {
        return base_url() . get_city_pinyin($city_id);
    }
    if (preg_match('~/foot/index/deal~', $url)) {
        return $rurl = esf_base_url() . 'service';
    }

    // sitemap链接转化
    if (preg_match('~/foot/index/sitemap_dibiao~', $url)) {
        if ($params) {
            $rurl = $url;
            if (!empty($params['place_type']) && !empty($params['cityId']))
                $rurl = esf_base_url() . 'foot/index/sitemap_dibiao_' . $params['place_type'] . DIRECTORY_SEPARATOR . $params['cityId'];
            else if (!empty($params['place_type']))
                $rurl = esf_base_url() . 'foot/index/sitemap_dibiao_' . $params['place_type'] . DIRECTORY_SEPARATOR . get_city_id();
            if (!empty($params['page']))
                $rurl .= "_pa{$params['page']}";

            return $rurl;
        }

        return $url;
    }

    // sitemap路名链接转化
    if (preg_match('~/foot/index/sitemap_luming~', $url)) {
        if ($params) {
            $rurl = $url;
            if (!empty($params['alphabet']) && !empty($params['cityId']))
                $rurl = esf_base_url() . 'foot/index/sitemap_lm_' . $params['alphabet'] . DIRECTORY_SEPARATOR . $params['cityId'];
            else if (!empty($params['place_type']))
                $rurl = esf_base_url() . 'foot/index/sitemap_lm_' . $params['alphabet'] . DIRECTORY_SEPARATOR . get_city_id();
            if (!empty($params['page']))
                $rurl .= "_pa{$params['page']}";

            return $rurl;
        }

        return $url;
    }

    // 列表页筛选项链接转化
    if (preg_match('~/web/sale/(all|list)~', $url)) {
        if ($params) {
            if (!empty($params['school_section_id'])) {
                $rurl = esf_base_url() . get_city_pinyin($city_id) . '/xuexiao';
                $params['page'] = isset($params['page']) ? $params['page'] : 1;
                if ($params['school_section_id'] > 0)
                    $rurl .= "/s" . $params['school_section_id'] . (($params['page'] > 1) ? "_pa${params['page']}" : '');
                else
                    $rurl .= ($params['page'] > 1) ? "/pa${params['page']}" : '';

                return $rurl;
            } else
                return esf_base_url() . get_city_pinyin($city_id) . '/list/' . filter_to_build($params);
        }

        return esf_base_url() . get_city_pinyin($city_id);
    }

    // 详情页百度链接转化
    if (preg_match('~/h5/sale/detailBaidumap~', $url)) {
        return $url;
    }

    // 详情页链接转化
    if (preg_match('~/web/sale/detail~', $url)) {
        if ($params) {
            return esf_base_url() . get_city_pinyin($city_id) . '/' . $params['house_id'] . '.html';
        }
    }

    //学校详情页链接转化
    if (preg_match('~/web/sale/school_detail~', $url) && !empty($params['school_id'])) {
        return esf_base_url() . get_city_pinyin($city_id) . '/xuexiao/' . $params['school_id'] . '.html';
    }


    //小区列表
    if (preg_match("~/web/cell/all~", $url)) {
        if ($params) {
            if (isset($params['place_id']) && $params['place_id'] != '') {
                unset($params['section_id']);
                unset($params['block_id']);
                unset($params['metro_line']);
                unset($params['metro_station']);
            }
            if (isset($params['road_id']) && $params['road_id'] != '') {
                unset($params['section_id']);
                unset($params['block_id']);
                unset($params['metro_line']);
                unset($params['metro_station']);
            }

            return esf_base_url() . get_city_pinyin($city_id) . '/xiaoqu' . cell_filter_to_build($params);
        }

        return esf_base_url() . get_city_pinyin($city_id) . "/xiaoqu";
    }


    if (preg_match("~/web/houseprice/page/city~", $url)) {
        return esf_base_url() . get_city_pinyin($params['id']) . '/fangjia';
    }
    if (preg_match("~/web/houseprice/page/section~", $url)) {
        return esf_base_url() . get_city_pinyin(get_city_id()) . "/fangjia/s{$params['id']}";
    }
    if (preg_match("~/web/houseprice/page/block~", $url)) {
        return esf_base_url() . get_city_pinyin(get_city_id()) . "/fangjia/s{$params['section_id']}_b{$params['id']}";
    }

    //小区详情
    if (preg_match("~/web/cell/detail~", $url)) {
        //小区子页面url规则
        if (isset($params['sub_type']) && isset($params['cell_id'])) {
            $cell_sub_types = [
                'price' => 'fangjia',
                'room' => 'huxingtu',
                'round' => 'peitao',
                'house' => 'fangyuan',
                'main' => 'xiaoqu'
            ];

            if (array_key_exists($params['sub_type'], $cell_sub_types)) {
                if ($params['sub_type'] == 'house' && isset($params['page'])) {
                    return esf_base_url() . get_city_pinyin($city_id) . "/{$cell_sub_types[$params['sub_type']]}_{$params['cell_id']}_{$params['page']}.html";
                }

                return esf_base_url() . get_city_pinyin($city_id) . "/{$cell_sub_types[$params['sub_type']]}_" . $params['cell_id'] . '.html';
            }
        } elseif (isset($params['cell_id'])) {
            return esf_base_url() . get_city_pinyin($city_id) . '/xiaoqu_' . $params['cell_id'] . '.html';
        }
    }

    //卖房
    if (preg_match("~/web/sale/house~", $url)) {
        return esf_base_url() . "maifang/" . get_city_pinyin($city_id);
    }
    //m站户型列表页
    if (preg_match('~/h5/cell/all_house~', $url) && !empty($params['cell_id'])) {
        if(!empty($params['room'])){
            return m_base_url() . get_city_pinyin($city_id) . '/esf/fangyuan_' . $params['cell_id'] . '.html?room='.$params['room'];
        }
        return m_base_url() . get_city_pinyin($city_id) . '/esf/fangyuan_' . $params['cell_id'] . '.html';
    }

    //m站学校详情页链接转化
    if (preg_match('~/h5/sale/list_school_detail~', $url) && !empty($params['school_id'])) {
        return m_base_url() . get_city_pinyin($city_id) . '/esf/xuexiao/' . $params['school_id'] . '.html';
    }

    // m站学校列表页筛选项链接转化
    if (preg_match('~/h5/sale/list_school~', $url)) {
        if ($params) {
            return m_base_url() . get_city_pinyin($city_id) . '/esf/xuexiao/' . filter_to_build_h5($params);
        }

        return m_base_url() . get_city_pinyin($city_id) . '/esf/xuexiao/';
    }

    // m站列表页筛选项链接转化
    if (preg_match('~/h5/sale/(all|list)~', $url)) {
        if ($params) {
            return m_base_url() . get_city_pinyin($city_id) . '/esf/' . filter_to_build_h5($params);
        }

        return m_base_url() . get_city_pinyin($city_id) . '/esf';
    }

    // m站房源详情页链接转化
    if (preg_match('~/h5/sale/detail~', $url)) {
        if ($params) {
            if(!empty($params['utm_from'])){
                return m_base_url() . get_city_pinyin($city_id) . '/esf/' . $params['house_id'] . '.html?utm_from='.$params['utm_from'];
            }
            return m_base_url() . get_city_pinyin($city_id) . '/esf/' . $params['house_id'] . '.html';
        }
    }

    // m站小区详情页链接转化
    if (preg_match('~/h5/cell/detail~', $url)) {
        if ($params) {
            return m_base_url() . get_city_pinyin($city_id) . '/esf/xiaoqu_' . $params['cell_id'] . '.html';
        }
    }

    return $url;
}

// 列表页筛选项链接转化
function filter_to_build($params)
{
    $rh = array(

        's' => 'section_id',
        'b' => 'block_id',
        'ml' => 'metro_line',
        'ms' => 'metro_station',
        'ce' => 'cell_id',
        'co' => 'cost',
        'r' => 'room',
        'o' => 'orderby',
        't' => 'tag',
        'q' => 'query',
        'pa' => 'page',
        'sc' => 'school_section_id',
        'pt' => 'price_cut',
        're' => 'recent',
        'sp' => 'space',
        'hy' => 'house_year'
    );
    $r = array();
    foreach ($rh as $k => $v) {
        if (isset($params[$v]) && $params[$v] != '') {
            $r[] = $k . $params[$v];
        }
    }

    $rmore = '';//?后的参数
    foreach ($params as $key => $val) {
        if (!in_array($key, $rh)) {
            $rmore .= "&{$key}={$val}";
        }
    }
    $url = implode('_', $r);

    $url = preg_replace('/_ce\d+/', '', $url);
    if ($rmore) {
        $rmore = ltrim($rmore, '&');
        $rmore = "?" . $rmore;
        $url .= $rmore;
    }

    return $url;


}

// 列表页筛选项链接转化
function filter_to_build_h5($params)
{
    $rh = array(

        's' => 'district',
        'b' => 'section',
        'ml' => 'line_no',
        'ms' => 'station_no',
        'ce' => 'cell_id',
        'co' => 'price',
        'r' => 'room',
        'o' => 'orderby',
        't' => 'tag',
        'q' => 'query',
        'pa' => 'page',
        'pt' => 'price_cut',
        're' => 'recent'
    );
    $r = array();
    foreach ($rh as $k => $v) {
        if (isset($params[$v]) && $params[$v] != '') {
            $r[] = $k . $params[$v];
        }
    }
    $rmore = '';//?后的参数
    foreach ($params as $key => $val) {
        if (!in_array($key, $rh)) {
            $rmore .= "&{$key}={$val}";
        }
    }
    $url = implode('_', $r);

    $url = preg_replace('/_ce\d+/', '', $url);
    if ($rmore) {
        $rmore = ltrim($rmore, '&');
        $rmore = "?" . $rmore;
        $url .= $rmore;
    }

    return $url;


}

// 小区列表页筛选项链接转化
function cell_filter_to_build($params)
{
    $rh = array(

        's' => 'section_id',
        'b' => 'block_id',
        'ml' => 'metro_line',
        'ms' => 'metro_station',
        'co' => 'cost',
        'o' => 'orderby',
        'pa' => 'page',
        'db' => 'place_id',
        'lm' => 'road_name',
    );
    $r = array();
    foreach ($rh as $k => $v) {
        if (isset($params[$v]) && $params[$v] != '') {
            $r[] = $k . $params[$v];
        }
    }

    $url = implode('_', $r);

    $url = !empty($url) ? "_" . $url : "";

    if (isset($params["cell_name"])) {
        $params["query"] = $params["cell_name"];
    }

    if (!empty($params["query"])) {
        $url .= "?query=" . ($params["query"]);
    }

    return $url;
}
