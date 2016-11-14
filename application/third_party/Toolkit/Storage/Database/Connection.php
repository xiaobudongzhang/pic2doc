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
namespace Toolkit\Storage\Database;

use PDO;
use PDOStatement;
use PDOException;

/**
 * Connection Class
 *
 * The base class for database connection adapters.
 *
 * @author  Lorne Wang < post@lorne.wang >
 * @package Toolkit\Storage\Database
 */
abstract class Connection
{
    /**
     * Default port
     *
     * @var integer
     */
    const DEFAULT_PORT = 0;

    /**
     * The quote character for stuff like column and field names
     *
     * @var string
     */
    const QUOTE_CHARACTER = '`';

    /**
     * The last query run
     *
     * @var string
     */
    protected $lastQuery = '';

    /**
     * The PDO connection object
     *
     * @var object
     */
    protected $connection = null;

    /**
     * Default PDO options to set for each connection
     *
     * @var array
     */
    protected static $PdoOptions = [
        PDO::ATTR_CASE => PDO::CASE_LOWER,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_STRINGIFY_FETCHES => false
    ];

    // --------------------------------------------------------------------

    /**
     * Multiple singletons pattern constructor,
     * and support the created new instance.
     *
     * @access public
     * @param  array $options
     */
    public function __construct($options = [])
    {
        $this->connection = @new PDO($this->getDSN($options), $options['username'], $options['password'], self::$PdoOptions);
        $this->connection->setAttribute(PDO::ATTR_PERSISTENT, $options['persistent']);
        $this->setCharset($options['character']);
    }

    // --------------------------------------------------------------------

    /**
     * Execute a raw SQL query on the database.
     *
     * @access public
     * @param  string $sql    raw SQL string to execute
     * @param  array  $values optional array of bind values
     * @return PDOStatement
     */
    public function query($sql, $values = [])
    {
        $this->lastQuery = $sql;
        $statement = $this->connection->prepare($sql);

        if ($values)
        {
            foreach ($values as $key => $value)
            {
                if (is_int($value))
                    $param = PDO::PARAM_INT;
                elseif (is_bool($value))
                    $param = PDO::PARAM_BOOL;
                elseif (is_null($value))
                    $param = PDO::PARAM_NULL;
                elseif (is_string($value))
                    $param = PDO::PARAM_STR;
                else
                    $param = FALSE;

                if ($param)
                    $statement->bindValue($key, $value, $param);
            }
        }

        $statement->execute();
        return $statement;
    }

    // --------------------------------------------------------------------

    /**
     * Create a table object and returns the query builder
     *
     * @access public
     * @param  string $table table name
     * @return QueryBuilder
     */
    public function table($table)
    {
        return (new QueryBuilder($this))->table($table);
    }

    // --------------------------------------------------------------------

    /**
     * Retrieve the insert id of the last model saved
     *
     * @access public
     * @param  string $sequence optional name of a sequence to use
     * @return integer
     */
    public function insertId($sequence = null)
    {
        return $this->connection->lastInsertId($sequence);
    }

    // --------------------------------------------------------------------

    /**
     * Starts a transaction
     *
     * @access public
     */
    public function transaction()
    {
        if ( ! $this->connection->beginTransaction())
            throw new PDOException($this);
    }

    // --------------------------------------------------------------------

    /**
     * Commits the current transaction
     *
     * @access public
     */
    public function commit()
    {
        if ( ! $this->connection->commit())
            throw new PDOException($this);
    }

    // --------------------------------------------------------------------

    /**
     * Rollback a transaction
     *
     * @access public
     */
    public function rollback()
    {
        if ( ! $this->connection->rollback())
            throw new PDOException($this);
    }

    // --------------------------------------------------------------------

    /**
     * Quote a name like table names and field names
     *
     * @access public
     * @param  string $string string to quote
     * @return string
     */
    public function quoteName($string) {
        return $string[0] === static::QUOTE_CHARACTER || $string[strlen($string) - 1] === static::QUOTE_CHARACTER ?
            $string : static::QUOTE_CHARACTER . $string . static::QUOTE_CHARACTER;
    }

    // --------------------------------------------------------------------

    /**
     * Build the DSN string for current adapter
     *
     * @access public
     * @param  array  $options
     * @return string
     */
    abstract public function getDSN($options);

    /**
     * Adds a limit clause to the SQL query
     *
     * @access public
     * @param  string  $sql    the SQL statement
     * @param  integer $offset row offset to start at
     * @param  integer $limit  number of rows to return
     * @return string
     */
    abstract public function limit($sql, $offset, $limit);

    // --------------------------------------------------------------------

    /**
     * Executes query to specify the character set for this connection
     *
     * @access public
     * @param  string $charset charset name
     * @return void
     */
    abstract public function setCharset($charset);

    // --------------------------------------------------------------------

    /**
     * Specifies whether or not adapter can use LIMIT/ORDER clauses with DELETE & UPDATE operations
     *
     * @internal
     * @access public
     * @return boolean
     */
    public function acceptsLimitAndOrderForUpdateAndDelete()
    {
        return false;
    }
}

/* End file */
