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
 * Facade provide a "static" interface to classes that are
 * available in the application's service container.
 * This "facade" serve as "static proxies" to underlying classes
 * in the service container, providing the benefit of a terse,
 * expressive syntax while maintaining more testability and
 * flexibility than traditional static methods.
 *
 * @author  Lorne Wang < post@lorne.wang >
 * @package Toolkit\Schema
 */
abstract class Facade implements iFacade
{
    /**
     * Array of cached facade objects
     *
     * @var array
     */
    private static $instances = [];

    // --------------------------------------------------------------------

    /**
     * Calling static magic methods
     *
     * @return mixed
     */
    final static function __callStatic($method, $parameters)
    {
        $className = get_called_class();

        if ( ! isset(self::$instances[$className]))
        {
            self::$instances[$className] = static::__targetObject();
        }

        return call_user_func_array([self::$instances[$className], $method], $parameters);
    }
}

/* End file */