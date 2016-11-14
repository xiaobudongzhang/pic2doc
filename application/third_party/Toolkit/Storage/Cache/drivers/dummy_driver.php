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

/**
 * Dummy Caching Class
 *
 * @category    Library
 * @package     Toolkit
 * @subpackage  Core
 * @author      Lorne Wang < post@lorne.wang >
 */
class Cache
{
    /**
     * Initialize cache
     *
     * @return void
     */
    public static function initialize()
    {
        // NOTHING TO DO
    }

    // ------------------------------------------------------------------------

    /**
     * Get
     *
     * Since this is the dummy class, it's always going to return false.
     *
     * @access  public
     * @param   string
     * @return  boolean
     */
    public function get($id)
    {
        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Cache Save
     *
     * @access  public
     * @param   string  Unique Key
     * @param   mixed   Data to store
     * @param   integer Length of time (in seconds) to cache the data
     * @return  bool    TRUE, Simulating success
     */
    public function set($id, $data, $ttl = 60)
    {
        return true;
    }

    // ------------------------------------------------------------------------

    /**
     * Delete from Cache
     *
     * @param   mixed    unique identifier of the item in the cache
     * @return  boolean  true, simulating success
     */
    public function delete($id)
    {
        return true;
    }

    // ------------------------------------------------------------------------

    /**
     * Clean the cache
     *
     * @return  bool    TRUE, simulating success
     */
    public function clean()
    {
        return true;
    }

    // ------------------------------------------------------------------------

    /**
     * Cache Info
     *
     * @param   string  user/filehits
     * @return  bool    FALSE
     */
     public function cacheInfo($type = null)
     {
         return true;
     }

    // ------------------------------------------------------------------------

    /**
     * Get Cache Metadata
     *
     * @param   mixed   key to get cache metadata on
     * @return  bool    FALSE
     */
    public function getMetadata($id)
    {
        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Is this caching driver supported on the system?
     * Of course this one is.
     *
     * @return  bool    TRUE
     */
    public function isSupported()
    {
        return true;
    }
}

/* End file */
