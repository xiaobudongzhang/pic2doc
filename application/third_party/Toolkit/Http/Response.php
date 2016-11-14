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
namespace Toolkit\Http;

/**
 * 响应输出类
 *
 * @author  Lorne Wang < post@lorne.wang >
 * @package Toolkit\Http
 */
class Response
{
    /**
     * JSON output format data (support random 
     * sequence default parameters)
     *
     * @access public
     * @return string
     */
    public static function json(/* mixed args */)
    {
        $args = func_get_args();
        $ret  = [
            'success' => true,
            'message' => '',
            'results' => [],
            'total'   => 0
        ];

        foreach ($args as $value)
        {
            switch (gettype($value))
            {
                case 'string':
                    $ret['message'] = $value;
                    break;

                case 'integer':
                    $ret['total']   = $value;
                    break;

                case 'boolean':
                    $ret['success'] = $value;
                    break;

                case 'array':
                    $ret['results']    = $value;
                    break;

                default:
                    return false;
            }
        }

        //header('Content-type: application/json');
        exit(json_encode($ret));
    }
}

/* End file */
