<?php


// 取得调用标识名称
function get_invoke_tag_name()
{
    $CI =& get_instance();

    $directory = str_replace('/', '.', $CI->router->directory);
    if (trim($directory) == '.') {
        $directory = '';
    }

    $class = $CI->router->class;
    $method = $CI->router->method;

    return "{$directory}{$class}.{$method}";
}

// 获取当前域名
function get_domain()
{
    $url = current_url();
    $url = preg_replace('~^http(?:s)?://~', '', $url);
    $url = substr($url, 0, stripos($url, '/'));

    return $url;
}

// 获取当前一级域名
function get_top_domain($hasPoint = FALSE)
{
    $host = get_domain();

    if (preg_match('/^[\d\.]+/', $host, $m) && isset($m[0])) {
        return $m[0]; // IP Address
    }

    if (preg_match('/(?:[a-z0-9][a-z0-9-]*)\.(?:com\.cn|com|cn|co|net|org|gov|name|me|cc|biz|info)/isU', $host, $match) && isset($match[0])) {
        $domain = rtrim($match[0], '/');

        if ($hasPoint) {
            return '.' . $domain;
        }

        return $domain;
    }

    return '';
}

// 获取当前城市ID
function get_city_id()
{
    $CI =& get_instance();
    $city_id = (int)$CI->current_city_id;

    return $city_id > 0 ? $city_id : 121;
}

// 设置当前城市ID
function set_city_id($id)
{
    $CI =& get_instance();
    $CI->current_city_id = $id;
    Input::setCookie('city_id', $id, 86400 * 365, get_top_domain());
}

// 获取当前城市名
function get_city_name($id = -1)
{
    $city_id = $id > 0 ? $id : get_city_id();
    return LocationModel::getCityValueById($city_id, 'name');
}

// 获取当前城市拼音
function get_city_pinyin($city_id = 0)
{
    $city_id = $city_id > 0 ? $city_id : get_city_id();

    return LocationModel::getCityValueById($city_id, 'pinyin');
}

// 获取当前渠道类型
function get_source_type()
{
    $CI =& get_instance();

    return $CI->source_type;
}

// 获取当前地铁线名称
function get_metro_line_name()
{
    $CI =& get_instance();

    return $CI->current_metro_line_name;
}

// 获取当前地铁站点名称
function get_metro_station_name()
{
    $CI =& get_instance();

    return $CI->current_metro_station_name;
}

// 获取区域列表
function get_sections($city_id = 0)
{
    $city_id = $city_id > 0 ? $city_id : get_city_id();

    return LocationModel::getOpenedDescendant($city_id);
}

// 获取板块列表
function get_blocks($section_id, $city_id = 0)
{
    $city_id = $city_id > 0 ? $city_id : get_city_id();

    return LocationModel::getOpenedDescendant($city_id, $section_id);
}

// 获取当前随机版块数据
function get_rand_blocks($city_id = 0, $limit = 20)
{
    $city_id = $city_id > 0 ? $city_id : get_city_id();
    $data = LocationModel::getAllBlocks($city_id);
    if (!empty($data) && is_array($data)) {
        shuffle($data);
        $data = array_slice($data, 0, $limit);
    }

    return $data;
}

// 获取某城市小区数据列表
function get_cell_data($city_id = 0, $page = 1, $limit = 10, &$count = 0, $rand = 0)
{
    $city_id = $city_id > 0 ? $city_id : get_city_id();

    return CellModel::findAllByCityId($city_id, $page, $limit, $count, $rand);
}

// 获取某城市小区数据列表
function get_cell_data_of_appoint($city_id = 0, $limit = 10)
{
    $city_id = $city_id > 0 ? $city_id : get_city_id();

    return \Provider\CellModel::get_list_bottom_hot_cell($city_id, $limit);
}

function get_cell_data_all()
{
    return CellModel::findAllByRand();
}

// 城市是否支持二手房
function is_support_esf($city_id = 0)
{
    $city_id = $city_id > 0 ? $city_id : get_city_id();
    if ($row = RegionModel::getDataById($city_id)) {
        if ($row->is_valid_esf > 0) {
            return TRUE;
        }
    }

    return FALSE;
}

// 城市是否支持地铁房
function is_support_metro($city_id = 0)
{
    $cities = [
        121, // 上海
        3, // 苏州
        2316, // 杭州
        1337, // 深圳
        267, // 南京
        852, // 广州
    ];

    $city_id = $city_id > 0 ? $city_id : get_city_id();

    return in_array($city_id, $cities);
}

//城市是否支持学区房
function is_support_school($city_id = 0)
{
    $cities = [
        121, // 上海
        3, // 苏州
        2316, // 杭州
        1337, // 深圳
        267, // 南京
        852, // 广州
    ];

    $city_id = $city_id > 0 ? $city_id : get_city_id();

    return in_array($city_id, $cities);
}

// 是否支持双十二
function is_double_tweleve($city_id = 0)
{
    return '';
    $cities = [
        943,
        1337,
        852,
        3,
        2316,
        121,
        267,
        2179,
        8243,
        2099,
        9119,
        10668,
        1492,
        788,
        450,
        2323,
        619,
        9915,
        2760,
        1230
    ];
    $city_id = $city_id > 0 ? $city_id : get_city_id();

    return in_array($city_id, $cities);
}

// 取得热门城市列表
function get_hot_cities()
{
    //上海、苏州、杭州、南京、广州、深圳
    $data = [
        [
            'id' => 121,
            'name' => '上海',
            'pinyin' => 'shanghai'
        ],
        [
            'id' => 3,
            'name' => '苏州',
            'pinyin' => 'suzhou'
        ],
        [
            'id' => 2316,
            'name' => '杭州',
            'pinyin' => 'hangzhou'
        ],
        [
            'id' => 267,
            'name' => '南京',
            'pinyin' => 'nanjing'
        ],
        [
            'id' => 852,
            'name' => '广州',
            'pinyin' => 'guangzhou'
        ],
        [
            'id' => 1337,
            'name' => '深圳',
            'pinyin' => 'shenzhen'
        ],
        // [
        //     'id' => 450,
        //     'name' => '成都',
        //     'pinyin' => 'chengdu'
        // ],
    ];

    return $data;
}

// 根据source type得到phone type值
function get_phone_type($source_type = '', $suffix = '')
{
    static $map;

    return 0; // 暂时关闭

    $source_type = trim($source_type);

    /*    if ( ! preg_match('~_(?:h5|pc)$~', $source_type))
        {
            $source_type = $source_type . '_' . $suffix;
        }*/

    if (empty($map)) {
        $map['baidu_sem_pc'] = 200; // 百度
        $map['baidu_sem_h5'] = 201; // 百度
        $map['360_sem_pc'] = 202; // 360
        $map['360_sem_h5'] = 203; // 360
        $map['sogou_sem_pc'] = 204; // 搜狗
        $map['sogou_sem_h5'] = 205; // 搜狗
        $map['shenma_sem_h5'] = 206; // 神马
        $map['py_dsp_pc'] = 207; // 品友
        $map['py_dsp_h5'] = 208; // 品友
        $map['jz_dsp_pc'] = 209; // 晶赞
        $map['jz_dsp_h5'] = 210; // 晶赞
        $map['zht_dsp_pc'] = 211; // 智汇推
        $map['zht_dsp_h5'] = 212; // 智汇推
        $map['gdt_dsp_pc'] = 213; // 广点通
        $map['gdt_dsp_h5'] = 214; // 广点通
        $map['360_brand'] = 215; // pc360品专
        $map['baidu_brand'] = 216; // pc百度品专
        $map['sogou_dibiao'] = 217; // pc搜狗地标
        $map['sogou_brand'] = 218; // pc搜狗品专
        $map['sogou_mob_brand'] = 219; // h5搜狗移动品专
        $map['sogou_zhitongche'] = 220; // pc搜狗直通车
        $map['baidu_seo_pc'] = 225; // 百度
        $map['baidu_seo_h5'] = 226; // 百度
        $map['360_seo_pc'] = 227; // 360
        $map['360_seo_h5'] = 228; // 360
        $map['sogou_seo_pc'] = 229; // 搜狗
        $map['sogou_seo_h5'] = 230; // 搜狗
        $map['google_seo_pc'] = 231; // Google
        $map['google_seo_h5'] = 232; // Google
        $map['hao123_dh_pc'] = 245; // hao123导航
        $map['hao123_dh_h5'] = 246; // hao123导航
        $map['360_dh_pc'] = 247; // 360导航
        $map['360_dh_h5'] = 248; // 360导航
        $map['sogou_dh_pc'] = 249; // 搜狗导航
        $map['sogou_dh_h5'] = 250; // 搜狗导航
        $map['114_dh_pc'] = 251; // 114导航
        $map['114_dh_h5'] = 252; // 114导航
        $map['qq_dh_pc'] = 253; // qq
        $map['2345_dh_pc'] = 254; // 2345
        $map['265_dh_pc'] = 255; // 265
        $map['cainixihuan_dh_pc'] = 256; // cainixihuan
        $map['kuzhan_dh_pc'] = 257; // kuzhan
        $map['gongjiao'] = 270; // 公交
        $map['ditie'] = 271; // 地铁
        $map['shequ'] = 272; // 社区

    }

    return isset($map[$source_type]) ? $map[$source_type] : 0;
}

// 得到400电话
function get_phone_400($houseId, $suffix, $returnArray = FALSE)
{
    $phone400 = '4000089900';
    if ($returnArray) {
        $phone400 = ['4000089900', ''];
    }

    if (($phone_type = get_phone_type(get_source_type(), $suffix)) > 0) {
        list($phone_number, $ext_number) = PhoneModel::bind400ForEsf($houseId, $phone_type);
        if ($phone_number && $ext_number) {
            if ($returnArray) {
                return [$phone_number, $ext_number];
            }

            $phone400 = $phone_number . ',' . $ext_number;
        }
    }

    return $phone400;
}


function get_metro($city_id = '')
{
    // 地铁线路
    $city_id = $city_id ?: get_city_id();
    $metroLines = [];
    try {
        $metroLines = MetroModel::findMetroLinesByCityId($city_id);
    } catch (Exception $e) {

    }

    $metroLines = isset($metroLines->data) ? $metroLines->data : [];
    foreach ($metroLines as &$row) {
        $row->subs = [];
        try {
            $t = MetroModel::findMetroStationsByLine($city_id, $row->lineNo);
        } catch (Exception $e) {

        }
        if (!empty($t->data)) {
            $row->subs = $t->data;
        }

    }
    unset($row);

    return $metroLines;
}

//获取二手房城市
if (!function_exists('get_esf_city_ids')) {
    function get_esf_city_ids()
    {
        $ids = [];
        $res = RegionModel::getValidEsfData();
        foreach ($res as $row) {
            $ids[] = $row->id;
        }

        return $ids;
    }
}

//获取二手房城市
if (!function_exists('get_esf_citys')) {
    function get_esf_citys($is_array = FALSE, $is_test = FALSE)
    {
        $ids = [];
        $res = RegionModel::getValidEsfData($is_array, $is_test);

        return $res;
    }
}


//获取二手房城市
if (!function_exists('get_esf_cities')) {
    function get_esf_cities($is_array = FALSE, $is_test = FALSE)
    {
        $ids = [];
        $res = RegionModel::getValidEsfDataV2($is_array, $is_test);

        return $res;
    }
}
