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
namespace Toolkit\Schema;

/**
 * Facade 抽象类应该继承自这个接口，子类需要实现 __targetObject
 * 静态方法，该方法返回值指向一个有效的对象实例
 *
 * @author  Lorne Wang < post@lorne.wang >
 * @package Toolkit\Schema
 */
interface iFacade
{
    public static function __targetObject();
}

/* End file */