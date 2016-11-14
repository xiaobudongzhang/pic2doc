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
namespace System\Store;

use Toolkit\Utility\File;

/**
 * Native Session Class
 *
 * Session management class, has struck the function such as data. 
 * To provide a uniform interface, so that after session storage mechanism 
 * of expansion and replacement, and will not affect your code.
 *
 * @category    Library
 * @package     Toolkit
 * @subpackage  Core
 * @author      Lorne Wang < post@lorne.wang >
 */
class Cache
{
    /**
     * Directory in which to save cache files
     *
     * @var string
     */
    protected static $cachePath;

    /**
     * Initialize file-based cache
     *
     * @access public
     * @return void
     */
    public static function initialize()
    {
        $path = '';
        self::$cachePath = ($path === '') ? APP_PATH . 'runtime/cache/' : APP_PATH . $path . '/';
    }

    // ------------------------------------------------------------------------

    /**
     * Fetch from cache
     *
     * @access public
     * @param  mixed   $id key id
     * @param  boolean $default
     * @return mixed
     */
    public static function get($id, $default = false)
    {
        if ( ! file_exists(self::$cachePath . $id))
        {
            return $default;
        }

        $data = unserialize(file_get_contents(self::$cachePath . $id));

        if ($data['ttl'] > 0 && time() >  $data['time'] + $data['ttl'])
        {
            unlink(self::$cachePath . $id);
            return $default;
        }

        return $data['data'];
    }

    // ------------------------------------------------------------------------

    /**
     * Save into cache
     *
     * @access public
     * @param  string  $id    key
     * @param  mixed   $data  to store
     * @param  integer $ttl   length of time (in seconds) the cache is valid
     *                        - Default is 60 seconds
     * @return boolean
     */
    public static function set($id, $data, $ttl = 60)
    {
        $contents = [
			'time' => time(),
			'ttl'  => $ttl,
			'data' => $data
        ];

        if (File::write(self::$cachePath . $id, serialize($contents)))
        {
            @chmod(self::$cachePath . $id, 0660);
            return true;
        }

        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Delete from Cache
     *
     * @access public
     * @param  mixed   $id unique identifier of item in cache
     * @return boolean
     */
    public static function delete($id)
    {
        return file_exists(self::$cachePath . $id) ? unlink(self::$cachePath . $id) : false;
    }

    // ------------------------------------------------------------------------

    /**
     * Clean the Cache
     *
     * @access public
     * @return boolean
     */
    public static function clean()
    {
        return File::delete(self::$cachePath);
    }

    // ------------------------------------------------------------------------

    /**
     * Cache Info
     *
     * Not supported by file-based caching
     *
     * @access public
     * @param  string $type
     * @return mixed
     */
    public static function cacheInfo($type = null)
    {
        return File::getDirinfo(self::$cachePath);
    }

    // ------------------------------------------------------------------------

    /**
     * Get Cache Metadata
     *
     * @access public
     * @param  mixed $id key to get cache metadata on
     * @return mixed
     */
    public static function getMetadata($id)
    {
        if (!file_exists(self::$cachePath . $id))
        {
            return false;
        }

        $data = unserialize(file_get_contents(self::$cachePath.$id));

        if (is_array($data))
        {
            $mtime = filemtime(self::$cachePath.$id);

            if ( ! isset($data['ttl']))
            {
                return FALSE;
            }

            return [
                'expire' => $mtime + $data['ttl'],
                'mtime'     => $mtime
            ];
        }

        return FALSE;
    }

    // ------------------------------------------------------------------------

    /**
     * Is supported
     *
     * In the file driver, check to see that the cache directory is indeed writable
     *
     * @access public
     * @return boolean
     */
    public static function isSupported()
    {
        //return is_really_writable(self::$cachePath);
    }
}

/* End file */