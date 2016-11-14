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
namespace Toolkit\Manager;

/**
 * 配置文件管理类
 *
 * @author  Lorne Wang < post@lorne.wang >
 * @package Toolkit\Manager
 */
class Config
{
    /**
     * Files loaded
     *
     * @var array
     */
    private $loaded = [];

    /**
     * Files cached
     *
     * @var array
     */
    private $cached = [];

    /**
     * Files directory
     *
     * @var string
     */
    private $directory = '';

    /**
     * File environment
     *
     * @var string
     */
    private $environment = '';

    /**
     * Set config files directory
     *
     * @access public
     * @param  string $directory
     * @return void
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    // --------------------------------------------------------------------

    /**
     * Set config environment
     *
     * @access public
     * @param  string $environment
     * @return void
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    // --------------------------------------------------------------------

    /**
     * Get item value for key
     *
     * @access public
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = false)
    {
        if (isset($this->cached[$key]))
        {
            return $this->cached[$key];
        }

        $keys = explode('.', $key);
        $file = array_shift($keys);

        if ( ! isset($this->loaded[$file]))
        {
            if ($this->environment && file_exists($this->directory . DIRECTORY_SEPARATOR . $this->environment . DIRECTORY_SEPARATOR . $file . '.php'))
            {
                $this->loaded[$file] = require $this->directory . DIRECTORY_SEPARATOR . $this->environment . DIRECTORY_SEPARATOR . $file . '.php';
            }
            else
            {
                $this->loaded[$file] = require $this->directory . DIRECTORY_SEPARATOR . $file . '.php';
            }
        }

        $data = $this->loaded[$file];

        foreach ($keys as $value)
        {
            if ( ! isset($data[$value]))
            {
                return $default;
            }

            $data = $data[$value];
        }

        return ($this->cached[$key] = $data);
    }
}

/* End file */