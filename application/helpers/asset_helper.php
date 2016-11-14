<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (isset($_SERVER["HTTP_X_FORWARDED_PROTOCOL"]) && $_SERVER["HTTP_X_FORWARDED_PROTOCOL"] == 'https') define('PROTOCOL', 'https');
else define('PROTOCOL', 'http');

/**
 * Sekati CodeIgniter Asset Helper
 *
 * @package        Sekati
 * @author        Jason M Horwitz
 * @copyright    Copyright (c) 2013, Sekati LLC.
 * @license        http://www.opensource.org/licenses/mit-license.php
 * @link        http://sekati.com
 * @version        v1.2.7
 * @filesource
 *
 * @usage        $autoload['config'] = array('asset');
 *                $autoload['helper'] = array('asset');
 * @example        <img src="<?=asset_url();?>imgs/photo.jpg" />
 * @example        <?=img('photo.jpg')?>
 *
 * @install        Copy config/asset.php to your CI application/config directory
 *                & helpers/asset_helper.php to your application/helpers/ directory.
 *                Then add both files as autoloads in application/autoload.php:
 *
 *                $autoload['config'] = array('asset');
 *                $autoload['helper'] = array('asset');
 *
 *                Autoload CodeIgniter's url_helper in application/config/autoload.php:
 *                $autoload['helper'] = array('url');
 *
 * @notes        Organized assets in the top level of your CodeIgniter 2.x app:
 *                    - assets/
 *                        -- css/
 *                        -- download/
 *                        -- img/
 *                        -- js/
 *                        -- less/
 *                        -- swf/
 *                        -- upload/
 *                        -- xml/
 *                    - application/
 *                        -- config/asset.php
 *                        -- helpers/asset_helper.php
 */

// ------------------------------------------------------------------------
// // URL HELPERS

function get_url($file, $json)
{
    $json = $json['res'];
    $resName = 'assets/' . ltrim($file, '/');

    if (array_key_exists($resName, $json)) {
        if (array_key_exists('uri', $json[$resName])) {
            return PROTOCOL . '://' . Config::get('static', 'static_host') . '/' . ltrim($json[$resName]['uri'], '/');
        }
    }

    return '/' . ltrim($file, '/');
}


/**
 * Get asset URL
 *
 * @access  public
 * @return  string
 */
if (!function_exists('asset_url')) {
    function asset_url($file)
    {

        if (empty($file)) {
            return null;
        }
        $url = null;

        $url = ltrim($file, '/');

        static $map_data = false;

        if ($map_data === false) {
            $filename = 'map.json';
            if (file_exists($filename)) {
                $handle = fopen($filename, "r");
                $json_str = fread($handle, filesize($filename));
                fclose($handle);
                $map_data = json_decode($json_str, true);
            }
        }
        if ($map_data !== false) {

            $url = get_url($file, $map_data);
            return $url;
        }

        //information about a file path
        $path_parts = pathinfo($url);

        if ($path_parts['extension'] == 'less') {
            return base_url('/less.php?path=' . $url);
        }

        return base_url('/assets/' . $url);
    }
}

/**
 * Load CSS
 * Creates the <link> tag that links all requested css file
 * @access  public
 * @param   string
 * @return  string
 */
if (!function_exists('css')) {
    function css($file, $media = 'all')
    {
        return '<link rel="stylesheet" type="text/css" href="' . asset_url($file) . '" media="' . $media . '">' . "\n";
    }
}

/**
 * Load JS
 * Creates the <script> tag that links all requested js file
 * @access  public
 * @param   string
 * @param    array $atts Optional, additional key/value attributes to include in the SCRIPT tag
 * @return  string
 */
if (!function_exists('js')) {
    function js($file, $atts = array())
    {
        $element = '<script type="text/javascript" src="' . asset_url($file) . '"';

        foreach ($atts as $key => $val)
            $element .= ' ' . $key . '="' . $val . '"';
        $element .= '></script>' . "\n";

        return $element;
    }
}


/** ==================================================================== **/
/** =======================  NEW VERSION  ============================== **/
/** ==================================================================== **/

if (!function_exists('manifest')) {
    function manifest()
    {
        static $map_data = false;
        if ($map_data === false) {
            $filename = 'manifest.json';
            if (file_exists($filename)) {
                $handle = fopen($filename, "r");
                $json_str = fread($handle, filesize($filename));
                fclose($handle);
                $map_data = json_decode($json_str, true);
            }
        }
        return $map_data;
    }
}

/**
 *  get client ip
 * @access  public
 * @return  string(ip)
 */
if (!function_exists('get_client_ip')) {
    function get_client_ip()
    {
        return isset($_SERVER["SERVER_ADDR"])?$_SERVER["SERVER_ADDR"]:Input::ip_address();
    }
}

/** ======================== M STATION ====================================== **/

/**
 * Get asset URL
 *
 * @access  public
 * @return  string
 */
if (!function_exists('asset_url_m')) {
    function asset_url_m($file)
    {
        if (ENVIRONMENT === 'development' || ENVIRONMENT === 'local') {
            return format_url_m($file);
        } else if (ENVIRONMENT === 'testing') {
            $map = manifest();
            if (isset($map['./assets/' . $file])) {
                return format_url_m($map['./assets/' . $file]);
            }
            return $file;
        } else {
            $map = manifest();
            if (isset($map['./assets/' . $file])) {
                return PROTOCOL . '://test.com/esf/h5/' . $map['./assets/' . $file];
            }
            return $file;
        }
    }
}

if (!function_exists('format_url_m')) {
    function format_url_m($file)
    {
        if (ENVIRONMENT === 'development' || ENVIRONMENT === 'local') {
            return PROTOCOL . '://' . get_client_ip() . ':3333/esf/h5/assets/' . $file;
// 			return '/assets/' . $file;
        } else if (ENVIRONMENT === 'testing') {
            return '/assets/esf/h5/' . $file;
        } else {
            return PROTOCOL . '://test.com/esf/h5/' . $file;
        }
    }
}

/**
 * Load CSS_M
 * Creates the <script> tag that links all requested js file
 * @access  public
 * @param   string
 * @return  string
 */
if (!function_exists('css_m')) {
    function css_m($file)
    {
        if (ENVIRONMENT === 'development' || ENVIRONMENT === 'local') {
            return '<link rel="stylesheet" type="text/css" href="' . PROTOCOL . '://' . get_client_ip() . ':3333/esf/h5/' . $file . '.css">';
        } else {
            $map = manifest();
            if (is_array($map[$file])) {
                return '<link rel="stylesheet" type="text/css" href="' . format_url_m($map[$file][1]) . '" media="all">';
            } else if (isset($map[$file])) {
                return '<link rel="stylesheet" type="text/css" href="' . format_url_m($map[$file]) . '" media="all">';
            } else {
                return '';
            }
        }
    }
}

/**
 * Load JS
 * Creates the <script> tag that links all requested js file
 * @access  public
 * @param   string
 * @param    array $atts Optional, additional key/value attributes to include in the SCRIPT tag
 * @return  string
 */
if (!function_exists('js_m')) {
    function js_m($file)
    {
        if (ENVIRONMENT === 'development' || ENVIRONMENT === 'local') {
            return '<script src="' . PROTOCOL . '://' . get_client_ip() . ':3333/esf/h5/' . $file . '.js"></script>';
        } else {
            $map = manifest();
            if (is_array($map[$file])) {
                return '<script src="' . format_url_m($map[$file][0]) . '"></script>';
            } else if (isset($map[$file])) {
                return '<script src="' . format_url_m($map[$file]) . '"></script>';
            } else {
                return '';
            }
        }
    }
}


/** ======================== WEB SITE ====================================== **/

/**
 * Get asset URL
 *
 * @access  public
 * @return  string
 */
if (!function_exists('asset_url_web')) {
    function asset_url_web($file)
    {
        if (ENVIRONMENT === 'development' || ENVIRONMENT === 'local') {
            return format_url_web($file);
        } else if (ENVIRONMENT === 'testing') {
            $map = manifest();
            if (isset($map['./assets/' . $file])) {
                return format_url_web($map['./assets/' . $file]);
            }
            return $file;
        } else {
            $map = manifest();
            if (isset($map['./assets/' . $file])) {
                return PROTOCOL . '://test.com/esf/web/' . $map['./assets/' . $file];
            }
            return $file;
        }
    }
}

if (!function_exists('format_url_web')) {
    function format_url_web($file)
    {
        if (ENVIRONMENT === 'development' || ENVIRONMENT === 'local') {
            return PROTOCOL . '://' . get_client_ip() . ':18899/public/assets/' . $file;
// 			return '/assets/' . $file;
        } else if (ENVIRONMENT === 'testing') {
            return '/public/' . $file;
        } else {
            return PROTOCOL . '://test.com/esf/web/' . $file;
        }
    }
}
/**
 * Load CSS_WEB
 * Creates the <script> tag that links all requested js file
 * @access  public
 * @param   string
 * @return  string
 */
if (!function_exists('css_web')) {
    function css_web($file)
    {
        if (ENVIRONMENT === 'development' || ENVIRONMENT === 'local') {
            return '<link rel="stylesheet" type="text/css" href="' . PROTOCOL . '://' . get_client_ip() . ':18899/public/' . $file . '.css">';
        } else {
            $map = manifest();
            if (is_array($map[$file])) {
                return '<link rel="stylesheet" type="text/css" href="' . format_url_web($map[$file][1]) . '" media="all">';
            } else if (isset($map[$file])) {
                return '<link rel="stylesheet" type="text/css" href="' . format_url_web($map[$file]) . '" media="all">';
            } else {
                return '';
            }
        }
    }
}

/**
 * Load JS_WEB
 * Creates the <script> tag that links all requested js file
 * @access  public
 * @param   string
 * @param    array $atts Optional, additional key/value attributes to include in the SCRIPT tag
 * @return  string
 */
if (!function_exists('js_web')) {
    function js_web($file)
    {
        if (ENVIRONMENT === 'development' || ENVIRONMENT === 'local') {
            return '<script src="' . PROTOCOL . '://' . get_client_ip() . ':18899/public/' . $file . '.js"></script>';
        } else {
            $map = manifest();
            if (is_array($map[$file])) {
                return '<script src="' . format_url_web($map[$file][0]) . '"></script>';
            } else if (isset($map[$file])) {
                return '<script src="' . format_url_web($map[$file]) . '"></script>';
            } else {
                return '';
            }
        }
    }
}

/**
 * get a resized image url from 
 * @access public
 * @return string
 */
if (!function_exists('fs_url')) {
    function fs_url($file = '', $size = 0)
    {
       
    }
}

// ------------------------------------------------------------------------
// EMBED HELPERS


/* End of file asset_helper.php */
