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

use System\Store\Session\Base;

/**
 * Native Session Driver Class
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
class Session extends Base
{
    /**
     * Start
     *
     * @access public
     * @return void
     */
    public static function start()
    {
        session_start();
    }

    // --------------------------------------------------------------------

    /**
     * Reads given session attribute value
     *
     * @access public
     * @param  string
     * @param  mixed
     * @return mixed
     */
    public static function get($item, $default = false)
    {
        return ( ! isset($_SESSION[$item])) ? $default : $_SESSION[$item];
    }

    // --------------------------------------------------------------------

    /**
     * Sets session attributes to the given values
     *
     * @access public
     * @return array
     */
    public static function all()
    {
        return $_SESSION;
    }

    // --------------------------------------------------------------------

    /**
     * Sets session attributes to the given values
     *
     * @access  public
     * @param   mixed
     * @param   string
     * @return    void
     */
    public static function set($newData = [], $newVal = '')
    {
        if (is_string($newData))
        {
            $newData = array($newData => $newVal);
        }

        if (count($newData) > 0)
        {
            foreach ($newData as $key => $val)
            {
                $_SESSION[$key] = $val;
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Erases given session attributes
     *
     * @access  public
     * @param   mixed
     * @return    void
     */
    public static function remove($newData = array())
    {
        if (is_string($newData))
        {
            $newData = array($newData => '');
        }

        if (count($newData) > 0)
        {
            foreach ($newData as $key => $val)
            {
                unset($_SESSION[$key]);
            }
        }
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
        self::destroy();
        session_start();
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
        // cleanup session
        unset($_SESSION);
        $name = session_name();

        if (isset($_COOKIE[$name]))
        {
            // clear session cookie
            $params = session_get_cookie_params();
            setcookie($name, '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            unset($_COOKIE[$name], $params, $name);
        }

        session_destroy();
    }
}

/* End file */
