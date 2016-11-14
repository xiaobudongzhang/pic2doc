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
namespace Toolkit\Rules;

abstract class Rule
{
    public $id = 0;
    public $rules = [];

    abstract public function setting();

    /**
     * Add template expression
     *
     * @access public
     * @param  string $match   regular expression
     * @param  string $replace replace string
     * @return string
     */
    public function add($match, $replace)
    {
        $this->rules[] = [$match, $replace];
    }
}