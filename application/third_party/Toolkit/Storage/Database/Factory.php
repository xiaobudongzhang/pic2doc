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
namespace Toolkit\Store;

use Toolkit\Facade\Config;
use Toolkit\Store\Database\ConnectionManager;

/**
 * Relation database static Class
 *
 * @author  Lorne Wang < post@lorne.wang >
 * @package Toolkit\Store\Database
 */
class Factory
{
    /**
     * Connection object
     * 
     * @access public
     * @param  string $key
     * @return Database\Connection
     */
    public static function connection($key = 'default')
    {

        if ($connection = ConnectionManager::getConnection($key))
        {
            return $connection;
        }
        
        $options = Config::get("database.{$key}");
        $className  = __NAMESPACE__ . '\Rdb\Adapter\\' . ucwords($options['adapter']);

        return ConnectionManager::addConnection($key, new $className($options));
    }

    public static function getAdapterClass($adapter)
    {
        $className  = __NAMESPACE__ . '\Rdb\Adapter\\' . ucwords($options['adapter']);
        return __NAMESPACE__ . '\Rdb\Adapter\\' . ucwords($adapter);
    }

    // --------------------------------------------------------------------

    /**
     * Execute a raw SQL query on the database.
     *
     * @access public
     * @param  string $statement Raw SQL string to execute.
     * @param  array  &$values   Optional array of bind values
     * @return \PDOStatement
     */
    public static function query($statement, $values = [])
    {
        return self::connection()->query($statement, $values);
    }

    // --------------------------------------------------------------------

    /**
     * Fetch a Query builder
     * 
     * @access public
     * @param  string $table
     * @return Database\QueryBuilder
     */
    public static function table($table)
    {
        return self::connection()->table($table);
    }
}

/* End file */