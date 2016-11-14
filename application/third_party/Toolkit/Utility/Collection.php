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
 * Collection
 *
 * @author  Lorne Wang < post@lorne.wang >
 * @package Toolkit\Utility
 */
class Collection
{
    public static function multisort()
    {
        $args = func_get_args();
        $data = array_shift($args);

        foreach ($args as $n => $field)
        {
            if (is_string($field))
            {
                $tmp = [];
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
            }
        }

        $args[] = &$data;
        call_user_func_array('array_multisort', $args);

        return array_pop($args);
    }
}

/* End file */