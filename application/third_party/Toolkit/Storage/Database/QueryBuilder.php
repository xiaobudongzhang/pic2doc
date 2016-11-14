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
namespace Toolkit\Store\Database;

/**
 * Query Builder
 *
 * The database query builder provides a convenient, fluent
 * interface to creating and running database queries. It
 * can be used to perform most database operations in your
 * application, and works on all supported database systems.
 *
 * Note: The database query builder uses PDO parameter
 * binding throughout to protect your application against SQL
 * injection attacks. There is no need to clean strings being
 * passed as bindings.
 *
 * @author  Lorne Wang < post@lorne.wang >
 * @package Toolkit\Store\Database
 */
class QueryBuilder
{
    private $connection;
    private $operation = 'SELECT';
    private $table;
    private $select = '*';
    private $join;
    private $order;
    private $limit;
    private $start = 0;
    private $page = 0;
    private $group;
    private $having;
    private $update;

    // for where
    private $where;
    private $whereValues = [];

    // for insert/update
    private $data;
    private $sequence;

    /**
     * Constructor
     *
     * @access public
     * @param  Connection $connection a database connection object
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    // --------------------------------------------------------------------

    /**
     * Set table name
     *
     * @access public
     * @param  string $table table name
     * @return QueryBuilder
     */
    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Set select fields
     *
     * @access public
     * @param  string $select fields list
     * @return QueryBuilder
     */
    public function select($select)
    {
        $this->operation = 'SELECT';
        $this->select = $select;
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Set query conditions
     *
     * @access public
     * @param  string $where where string fo SQL
     * @return QueryBuilder
     */
    public function where($where)
    {
        if ($this->where)
        {
            $this->where .= ' AND ' . $where;
        }
        else
        {
            $this->where = $where;
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Set order by
     *
     * @access public
     * @param  string $order
     * @return QueryBuilder
     */
    public function order($order)
    {
        $this->order = $order;
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Set group by
     *
     * @access public
     * @param  string $group
     * @return QueryBuilder
     */
    public function group($group)
    {
        $this->group = $group;
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Set having
     *
     * @access public
     * @param  string $having
     * @return QueryBuilder
     */
    public function having($having)
    {
        $this->having = $having;
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Set limit
     *
     * @access public
     * @param  string $limit
     * @return QueryBuilder
     */
    public function limit($limit)
    {
        $this->limit = intval($limit);

        if ($this->page)
        {
            $this->start = ($this->page - 1) * $this->limit;
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Set start
     *
     * @access public
     * @param  string $offset
     * @return QueryBuilder
     */
    public function start($offset)
    {
        $this->start = intval($offset);
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Set page value
     *
     * @access public
     * @param  string $page
     * @return QueryBuilder
     */
    public function page($page)
    {
        if ($this->limit)
        {
            $this->start = ($page - 1) * $this->limit;
        }

        $this->page = $page;

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Set join
     *
     * @access public
     * @param  string $join
     * @return QueryBuilder
     */
    public function join($join)
    {
        $this->join = $join;
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Get a query result
     *
     * @access public
     * @return array
     */
    public function get()
    {
        return $this->connection->query($this->toString(), $this->bindValues())->fetch();
    }

    // --------------------------------------------------------------------

    /**
     * Get the query results
     *
     * @access public
     * @return array
     */
    public function all()
    {
        return $this->connection->query($this->toString(), $this->bindValues())->fetchAll();
    }

    // --------------------------------------------------------------------

    /**
     * Execute query and get the affect the number of rows
     *
     * @access public
     * @return integer
     */
    private function execute()
    {
        return (int) $this->connection->query($this->toString(), $this->bindValues())->rowCount();
    }

    // --------------------------------------------------------------------

    /**
     * Get query results total number
     *
     * @access public
     * @return integer
     */
    public function count()
    {
        $this->operation = 'SELECT';
        $this->select = 'COUNT(1) AS __total_number';
        return (int) @$this->get()['__total_number'];
    }

    // --------------------------------------------------------------------

    /**
     * Execute update and get the affect the number of rows
     *
     * @access public
     * @param  mixed $mixed
     * @return integer
     * @throws \Exception
     */
    public function update($mixed)
    {
        $this->operation = 'UPDATE';

        if (is_array($mixed))
        {
            $this->data = $mixed;
        }
        elseif (is_string($mixed))
        {
            $this->update = $mixed;
        }
        else
        {
            throw new \Exception('Updating requires a hash or string.');
        }

        return $this->execute();
    }

    // --------------------------------------------------------------------

    /**
     * Execute insert and get the affect the number of rows
     *
     * @access public
     * @param  mixed $mixed
     * @return integer
     */
    public function insert($mixed)
    {
        $this->operation = 'INSERT';
        $this->data = $mixed;
        return $this->execute();
    }

    // --------------------------------------------------------------------

    /**
     * Execute delete and get the affect the number of rows
     *
     * @access public
     * @return integer
     */
    public function delete()
    {
        $this->operation = 'DELETE';
        return $this->execute();
    }

    // --------------------------------------------------------------------

    /**
     * Return the SQL string
     *
     * @access public
     * @return string
     */
    public function toString()
    {
        $func = 'build' . ucfirst($this->operation);
        return $this->$func();
    }

    // --------------------------------------------------------------------

    /**
     * Return the bind values
     *
     * @access public
     * @return array
     */
    public function bindValues()
    {
        $data = [];

        if ($this->data)
        {
            $data = array_values($this->data);
            array_unshift($data, null);
            unset($data[0]);
        }

        return $data;
    }

    // --------------------------------------------------------------------

    /**
     * Build a select statement
     *
     * @access public
     * @return string
     */
    private function buildSelect()
    {
        $sql = "SELECT $this->select FROM $this->table";

        if ($this->join)
            $sql .= ' ' . $this->join;

        if ($this->where)
            $sql .= " WHERE $this->where";

        if ($this->group)
            $sql .= " GROUP BY $this->group";

        if ($this->having)
            $sql .= " HAVING $this->having";

        if ($this->order)
            $sql .= " ORDER BY $this->order";

        if ($this->limit || $this->start)
        {
            $sql = $this->connection->limit($sql, $this->start, $this->limit);
        }

        return $sql;
    }

    // --------------------------------------------------------------------

    /**
     * Build an update statement
     *
     * @access public
     * @return string
     */
    private function buildUpdate()
    {
        $set = strlen($this->update) > 0 ? $this->update : join('=?, ', $this->quotedKeyNames()) . '=?';
        $sql = "UPDATE $this->table SET $set";
        $this->where AND $sql .= " WHERE $this->where";
        return $sql;
    }

    // --------------------------------------------------------------------

    /**
     * Build an insert statement
     *
     * @access public
     * @return string
     */
    private function buildInsert()
    {
        $keys = join(',', $this->quotedKeyNames());
        $vals = join(',', array_fill(0, count($this->data), '?'));
        $sql  = "INSERT INTO $this->table($keys) VALUES($vals)";
        return $sql;
    }

    // --------------------------------------------------------------------

    /**
     * Build a delete statement
     *
     * @access public
     * @return string
     */
    private function buildDelete()
    {
        $sql = "DELETE FROM $this->table";

        $this->where AND $sql .= " WHERE $this->where";

        if ($this->connection->acceptsLimitAndOrderForUpdateAndDelete())
        {
            $this->order AND $sql .= " ORDER BY $this->order";
            $this->limit AND $this->connection->limit($sql, null, $this->limit);
        }

        return $sql;
    }

    // --------------------------------------------------------------------

    /**
     * Quote these key names like table names and field names
     *
     * @access public
     * @return string
     */
    private function quotedKeyNames()
    {
        $keys = [];

        foreach ($this->data as $key => $value)
            $keys[] = $this->connection->quoteName($key);

        return $keys;
    }
}

/* End file */
