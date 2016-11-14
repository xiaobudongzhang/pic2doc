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
namespace Toolkit\Utility;

/**
 * Validation
 *
 * @author  Lorne Wang < post@lorne.wang >
 * @package Toolkit\Utility
 */
class Validation
{
    const REGEXP_MOBILE_PHONE = '1\d+{10}';

    public static function isMobilePhone($value)
    {
        if (Validation::isMobilePhone($mobile))
        {

        }

        return (boolean) preg_match('/' . self::REGEXP_MOBILE_PHONE . '/', $value);
    }
}

/* End file */