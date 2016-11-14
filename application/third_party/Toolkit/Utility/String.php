<?php
/**
 * Toolkit
 * 
 * Licensed under the Massachusetts Institute of Technology
 * 
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 *
 * @author      Lorne Wang < post@lorne.wang >
 * @copyright   Copyright (c) 2014 - 2015 , All rights reserved.
 * @link        http://lorne.wang/projects/toolkit
 * @license     http://lorne.wang/licenses/MIT
 */
namespace Toolkit\Utility;

/**
 * String
 *
 * @author  Lorne Wang < post@lorne.wang >
 * @package Toolkit\Utility
 */
class String
{
    /**
     * Calculate the length of the string, 
     * support Chinese and other code
     *
     * @param  string $str
     * @return string
     */
    public static function length($str)
    {
        preg_match_all("/./u", $str, $match);
        return count($match[0]);
    }

    // ------------------------------------------------------------------------
    
    /**
     * String to be truncate
     *
     * @param  string  $str
     * @param  integer $start
     * @param  string  $length
     * @param  string  $suffix
     * @param  string  $charset
     * @return string
     */
    public static function truncate($str, $start = 0, $length = null, $suffix = ' ...', $charset = 'utf-8')
    {
        if ($length === null)
        {
            $length = $start;
            $start = 0;
        }

        if ($length >= self::length($str))
        {
            $suffix = '';
        }
        
        if (function_exists("mb_substr"))
        {
            return mb_substr($str, $start, $length, $charset) . $suffix;
        }
        elseif (function_exists('iconv_substr'))
        {
            return iconv_substr($str, $start, $length, $charset) . $suffix;
        }

        $re['utf-8'] = '/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/';
        $re['gb2312'] = '/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/';
        $re['gbk'] = '/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/';
        $re['big5'] = '/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/';
        preg_match_all($re[$charset], $str, $match);

        return join('', array_slice($match[0], $start, $length)) . $suffix;
    }
}

/* End file */