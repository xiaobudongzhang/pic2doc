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
 * This implementation of the singleton pattern does not conform to the 
 * strong definition given by the "Gang of Four." The __construct() method 
 * has not be privatized so that a singleton pattern is capable of being 
 * achieved; however, multiple instantiations are also possible. This allows 
 * the user more freedom with this pattern.
 *
 * @author  Lorne Wang < post@lorne.wang >
 * @package Toolkit\Schema
 */
abstract class Singleton
{
    /**
     * Array of cached singleton objects
     *
     * @var array
     */
    private static $instances = [];

    /**
     * Array of locked singleton objects
     *
     * @var array
     */
    private static $isLockeds = [];

    /**
     * Static method for instantiating a singleton object
     *
     * @return object
     */
    final public static function instance()
    {
        $className = get_called_class();

        if (isset(self::$isLockeds[$className]) && self::$isLockeds[$className] === true)
        {
            trigger_error("{$className} class is locked.", E_USER_ERROR);
        }

        if (empty(self::$instances[$className]))
        {
            self::$isLockeds[$className] = true;
            self::$instances[$className] = new $className;
            self::$isLockeds[$className] = false;
        }

        return self::$instances[$className];
    }

    // --------------------------------------------------------------------

    /**
     * Singleton objects should not be cloned
     *
     * @return void
     */
    final private function __clone()
    {
        trigger_error('Singleton object should not be cloned.', E_USER_ERROR);
    }
}

/* End file */