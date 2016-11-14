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

/*
 * Include drive static class and inheritance in the `Base` 
 * drive class, it looks like an abstract static class. Using 
 * static adapter pattern, make instant call more flexible.
 *
 * With the `Config` core class, through dynamic configuration
 * file using the corresponding driver.
 */
include __DIR__ . DIRECTORY_SEPARATOR . 'drivers' . DIRECTORY_SEPARATOR . Config::get('config.session.adapter') . '_driver.php';

/**
 * Initialize static class, it will be calling initialization
 * method before calling static method.
 *
 * The function called after this file is loaded.
 */
Session::initialize();

/* End file */
