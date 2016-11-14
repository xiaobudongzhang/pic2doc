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
namespace Toolkit;

/**
 * TemplateManager
 *
 * @author  Lorne Wang < post@lorne.wang >
 * @package Toolkit
 */
class TemplateManager
{
    private static $instances = [];

    public static function add($id, $instance)
    {
        self::$instances[$id] = $instance;
    }

    public static function get($id)
    {
        return isset(self::$instances[$id]) ? self::$instances[$id] : false;
    }
}

/* End file */
