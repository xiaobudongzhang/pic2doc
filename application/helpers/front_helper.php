<?php
/**
 * User: byz
 * Date: 2015/11/9
 * Time: 16:09
 */

/**
 * 获取时间字段
 * @param int $plusDay
 * @param str $type
 * @return string|unknown
 */

function getDateStrKyu($plusDay, $type)
{
    $plusTime = time() + $plusDay * 60 * 60 * 24;
    $date1 = date('n月d日', $plusTime);
    $dateymd = date('Y-m-d', $plusTime);
    $dateymdtr = date('Ymd', $plusTime);
    $dateWeek = date('w', $plusTime);
    $dateHouer = date('G', $plusTime);
    $dayWeekList = ['周日', '周一', '周二', '周三', '周四', '周五', '周六'];
    $datew = $dayWeekList[$dateWeek];
    $datew2 = '（' . $datew . '）';
    if ($type == 'a') {
        return $date1 . $datew2;
    } else if ($type == 'ymd') {
        return $dateymd;
    } else if ($type == 'ymdtr') {
        return $dateymdtr;
    } else if ($type == 'md') {
        return $date1;
    } else if ($type == 'w') {
        return $datew;
    } else if ($type == 'w2') {
        return $datew2;
    } else if ($type == 'G') {
        return $dateHouer;
    } else if ($type == 'ds') {
        return $dateHouer;
    }

    return '';
}

// 获取是不是二手房城市
function getCityIsEsf()
{
    // 二手房城市
    $esfObjArr = LocationModel::getValidEsfCitys();
    $esfCityList = [];
    foreach ($esfObjArr as $item) {
        $esfCityList[] = $item->id;
    }
    $city_id = get_city_id();
    if (in_array($city_id, $esfCityList)) {
        return TRUE;
    }

    return FALSE;
}

function getH5ScriptNoRelease($jsDataMain)
{
    $nm = $jsDataMain;

    return load_script_h5($nm);
}

/**
 * get script file
 * @param string $jsDataMain
 * @return string
 */
function getMstationScript($jsDataMain)
{
    return load_script_h5($jsDataMain);
}

/**
 * get script file for web
 * @param string $jsDataMain
 * @return string
 */
function getWebScript($jsDataMain)
{
    return load_script_web($jsDataMain);
}

// Load require config
function load_script_h5($name)
{
    $require_config_json = get_require_config('h5');
    $require_str = json_encode($require_config_json);

    $rtnStr = "<script>var require = " . $require_str . ";" . "</script>";
    $rtnStr .= "\n";
    $rtnStr .= '<script type="text/javascript" async src="' . asset_url('h5/jsrelease/jscommon.js') . '" data-main="' . $name . '"></script>';

    return $rtnStr;
}

// Load require config
function load_script_web($name)
{
    $require_config_json = get_require_config('web');
    $require_str = json_encode($require_config_json);

    $rtnStr = "<script>var require = " . $require_str . ";" . "</script>";
    $rtnStr .= "\n";
    $rtnStr .= '<script type="text/javascript" async src="' . asset_url('lib/require.js') . '" data-main="' . $name . '"></script>';

    return $rtnStr;
}

function get_require_config($type)
{
    $filename = '';
    $globalValue = '';
    if ($type == 'h5') {
        $filename = FEPATH . 'assets/require-config-h5.json';
        $globalValue = 'require_config_h5_json';
    } else {
        $filename = FEPATH . 'assets/require-config-web.json';
        $globalValue = 'require_config_web_json';
    }

    if (isset($GLOBALS[$globalValue])) {
        return $GLOBALS[$globalValue];
    } else {
        if (file_exists($filename)) {
            $handle = fopen($filename, "r");
            $require_str = fread($handle, filesize($filename));
            fclose($handle);
        } else {
            $GLOBALS[$globalValue] = array();

            return $GLOBALS[$globalValue];
        }

        $require_json = json_decode($require_str, TRUE);

        $paths =& $require_json['paths'];

        $map_json = get_assets_map();
        foreach ($paths as $key => $value) {
            if (strpos($value, 'http://') > -1) {
                continue;
            }
            $paths[$key] = preg_replace('/^(.+)(\.js)$/', '${1}', asset_url($value . '.js'));
        }

        $GLOBALS[$globalValue] = $require_json;
    }

    return $GLOBALS[$globalValue];
}

// return assets_map_json;
// e.g. {"assets/libs/jquery-1.9.1.js": "assets/libs/jquery-1.9.1.397754ba.js"}
function get_assets_map()
{
    $map_path = 'map.json';

    if (isset($GLOBALS['assets_map_json'])) {
        return $GLOBALS['assets_map_json'];
    } else {
        if (file_exists($map_path)) {
            $handle = fopen($map_path, "r");
            $map_str = fread($handle, filesize($map_path));
            fclose($handle);
            $GLOBALS['assets_map_json'] = json_decode($map_str, TRUE)['res'];

            return $GLOBALS['assets_map_json'];
        }
    }

    $GLOBALS['assets_map_json'] = array();

    return $GLOBALS['assets_map_json'];
}

//获取当前module
function get_current_module()
{
    $CI =& get_instance();

    return $CI->current_module;
}

// 搜索框文字
function search_text_url()
{
    $CI =& get_instance();
    $text = '';
    if ($CI->current_module == 'web_sale_list' || $CI->current_module == 'web_sale_detail') $text = '请输入小区名称';
    elseif ($CI->current_module == 'web_cell_list' || $CI->current_module == 'web_cell_detail') $text = '请输入小区名称';
    elseif ($CI->current_module == 'web_index_esf') $text = '请输入小区名或楼盘名..';
    elseif ($CI->current_module == 'web_index_nh') $text = "请输入楼盘名称或地址..";
    elseif (preg_match('/web_house_price.*/', $CI->current_module)) $text = '您可以输入楼盘名称 / 楼盘地址';

    return $text;
}
// 自定义图片尺寸
function optimize_image_size($url, $width = null, $height = null) {
    if (empty($url) || (strpos($url, 'thumb/') !== false)) {
        return $url;
    }
    if ($width) {
        return $url . '?imageView2/2/w/' . $width;
    }
    if ($height) {
        return $url . '?imageView2/2/h/' . $height;
    }
    if ($width && $height) {
        return $url . '?imageView2/2/w/' . $width .'\/h/' . $height;
    }
}
