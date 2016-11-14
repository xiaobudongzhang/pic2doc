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
namespace Toolkit\Store\Database\Adapter;

use Toolkit\Store\Database\Connection;

/**
 * Database Adapter for MySQL
 *
 * @author  Lorne Wang < post@lorne.wang >
 * @package Toolkit\Store\Database\Adapter
 */
class Mysql extends Connection
{
    const DEFAULT_PORT = 3306;

    /**
     * Build the DSN string for this adapter
     *
     * @access public
     * @param  array  $options
     * @return string
     */
    public function getDSN($options)
    {
        return strtolower($options['adapter']) . ":dbname={$options['database']};host={$options['hostname']}";
    }

    // --------------------------------------------------------------------

    /**
     * Adds a limit clause to the SQL query
     *
     * @access public
     * @param  string  $sql    the SQL statement
     * @param  integer $offset row offset to start at
     * @param  integer $limit  number of rows to return
     * @return string
     */
    public function limit($sql, $offset, $limit)
    {
        $offset = is_null($offset) ? '' : intval($offset) . ',';
        $limit  = intval($limit);
        return "$sql LIMIT {$offset}$limit";
    }

    // --------------------------------------------------------------------

    /**
     * Set the character encoding
     *
     * @access public
     * @param  string $charset charset name
     * @return void
     */
    public function setCharset($charset)
    {
        $this->query('SET NAMES ' . $charset);
    }

    // --------------------------------------------------------------------

    /**
     * Retrieves column meta data for the specified table
     *
     * @access public
     * @param  string $table name of a table
     * @return array
     */
    public function columns($table)
    {
        $results = [];
        $columns = $this->query("SHOW COLUMNS FROM $table")->fetchAll();

        foreach ($columns as $column)
        {
            $results[] = [
                'name' => $column['Field'],
                'pk'   => ($column['Key'] === 'PRI' ? true : false),
                'null' => ($column['Null'] === 'YES' ? true : false)
            ];
        }

        return $results;
    }

    // --------------------------------------------------------------------

    /**
     * Specifies whether or not adapter can use LIMIT/ORDER clauses with DELETE & UPDATE operations
     *
     * @access public
     * @return boolean
     */
    public function acceptsLimitAndOrderForUpdateAndDelete()
    {
        return true;
    }
}

/* End file */
