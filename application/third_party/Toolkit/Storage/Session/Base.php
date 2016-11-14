<?php
/**
 * Burgeons - Application rapid development framework for PHP
 *
 * Licensed under the Open Software License version 3.0
 *
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author      Lorne Wang < post@lorne.wang >
 * @copyright   Copyright (c) 2013 - 2014 , All rights reserved.
 * @link        http://burgeons.org
 * @license     http://opensource.org/licenses/OSL-3.0
 */
namespace Toolkit\Store\Session;

use System\Config;
use System\Core\Input;

/**
 * Session Base Class
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
class Base
{
    const FLASH_KEY = 'flash';

    /**
     * Multiple singletons pattern constructor,
     * and support the created new instance.
     *
     * @access public
     * @return void
     */
    public static function initialize()
    {
        // session start
        static::start();

        // check session expiration, ip, and agent, then clear expired or invalid session and start new
        if (self::checkExpired()
            || (Config::get('config.session.ip_match') === true
                && static::get('ip_address') !== Input::ipAddress())
            || (Config::get('config.session.user_agent_match') === true
                && static::get('user_agent') !== trim(substr(Input::userAgent(), 0, 50)))
        )
        {
            static::regenerate();
        }

        // delete old flashData (from last request)
        self::flashSweep();

        // mark all new flashData as old (data will be deleted before next request)
        self::flashMark();

        // Set matching values as required
        if (Config::get('config.session.ip_match') === true && ! static::get('ip_address'))
        {
            // Store user IP address
            static::set('ip_address', Input::ipAddress());
        }

        if (Config::get('config.session.user_agent_match') === true && ! static::get('user_agent'))
        {
            // Store user agent string
            static::set('user_agent', trim(substr(Input::userAgent(), 0, 50)));
        }

        // Make session ID available
        static::set('session_id', session_id());
    }

    // --------------------------------------------------------------------

    /**
     * Returns "flash" data for the given key.
     *
     * @access public
     * @param  string
     * @return mixed
     */
    public static function flash($key)
    {
        $flashKey = self::FLASH_KEY . ':old:' . $key;
        return static::get($flashKey);
    }

    // --------------------------------------------------------------------

    /**
     * Sets "flash" data which will be available only in next request
     * (then it will be deleted from session). You can use it to
     * implement "Save succeeded" messages after redirect.
     *
     * @access public
     * @param  mixed
     * @param  string
     * @return void
     */
    public static function setFlash($newData = [], $newVal = '')
    {
        if (is_string($newData))
        {
            $newData = array($newData => $newVal);
        }

        if (count($newData) > 0)
        {
            $flashData = array();

            foreach ($newData as $key => $val)
            {
                $flashData[self::FLASH_KEY . ':new:' . $key] = $val;
            }

            self::set($flashData);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Keeps existing "flash" data available to next request.
     *
     * @access public
     * @param  string
     * @return void
     */
    public static function keepFlash($key)
    {
        $oldFlashKey = self::FLASH_KEY . ':old:' . $key;
        $value       = self::get($oldFlashKey);
        $newFlashKey = self::FLASH_KEY . ':new:' . $key;
        self::set($newFlashKey, $value);
    }

    // --------------------------------------------------------------------

    /**
     * Marks "flash" session attributes as 'old'
     *
     * @access  private
     * @return    void
     */
    private static function flashMark()
    {
        foreach (static::all() as $name => $value)
        {
            $parts = explode(':new:', $name);
            if (is_array($parts) && count($parts) == 2)
            {
                $new_name = self::FLASH_KEY . ':old:' . $parts[1];
                self::set($new_name, $value);
                self::remove($name);
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Removes "flash" session marked as 'old'
     *
     * @access private
     * @return void
     */
    private static function flashSweep()
    {
        foreach (static::all() as $name => $value)
        {
            $parts = explode(':old:', $name);
            if (is_array($parts) && count($parts) == 2 && $parts[0] == self::FLASH_KEY)
            {
                self::remove($name);
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Checks if session has expired
     *
     * @access private
     * @return boolean
     */
    private static function checkExpired()
    {
        if ( ! static::get('regenerated'))
        {
            static::set('regenerated', time());
            return false;
        }

        if (static::get('regenerated') <= (time() - Config::get('config.session.expiration', 7200)))
        {
            return true;
        }

        return false;
    }

    public static function start()
    {
        trigger_error('Subclass must implement the static interface method `' . __METHOD__ . '`', E_USER_ERROR);
    }

    // --------------------------------------------------------------------

    /**
     * Regenerates session id
     *
     * @access public
     * @return void
     */
    public static function regenerate()
    {
        trigger_error('Subclass must implement the static interface method `' . __METHOD__ . '`', E_USER_ERROR);
    }

    // --------------------------------------------------------------------

    /**
     * Reads given session attribute value
     *
     * @access public
     * @param  string
     * @return mixed
     */
    public static function get($item)
    {
        trigger_error('Subclass must implement the static interface method `' . __METHOD__ . '`', E_USER_ERROR);
    }

    // --------------------------------------------------------------------

    /**
     * Reads given session attribute value
     *
     * @access public
     * @return mixed
     */
    public static function all()
    {
        trigger_error('Subclass must implement the static interface method `' . __METHOD__ . '`', E_USER_ERROR);
    }

    // --------------------------------------------------------------------

    /**
     * Sets session attributes to the given values
     *
     * @access public
     * @param  mixed
     * @param  string
     * @return void
     */
    public static function set($newData = [], $newVal = '')
    {
        trigger_error('Subclass must implement the static interface method `' . __METHOD__ . '`', E_USER_ERROR);
    }

    // --------------------------------------------------------------------

    /**
     * Erases given session attributes
     *
     * @access public
     * @param  mixed
     * @return void
     */
    public static function remove($newData = [])
    {
        trigger_error('Subclass must implement the static interface method `' . __METHOD__ . '`', E_USER_ERROR);
    }

    // --------------------------------------------------------------------

    /**
     * Destroys the session and erases session storage
     *
     * @access public
     * @return void
     */
    public static function destroy()
    {
        trigger_error('Subclass must implement the static interface method `' . __METHOD__ . '`', E_USER_ERROR);
    }
}

/* End file */
