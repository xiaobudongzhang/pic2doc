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
namespace Toolkit\Template;

use Toolkit\Rules\Rule;

class Compiler
{
    public $id = 0;

    /**
     * Rule Object
     *
     * @var Rule
     */
    public $rule;

    /**
     * Left Delimiter
     *
     * @var string
     */
    public $leftDelimiter = '{';

    /**
     * Right Delimiter
     *
     * @var string
     */
    public $rightDelimiter = '}';


    public function __construct(Rule $rule)
    {
        $this->rule = $rule;
    }

    public function initialize()
    {
        $this->rule->id = $this->id;
        $this->rule->setting();
    }

    public function setLeftDelimiter($leftDelimiter)
    {
        $this->leftDelimiter = $leftDelimiter;
    }

    // --------------------------------------------------------------------

    public function setRightDelimiter($rightDelimiter)
    {
        $this->rightDelimiter = $rightDelimiter;
    }

    /**
     * Template Parser
     *
     * @access public
     * @param  string $code template source code
     * @return string
     */
    public function parser($code)
    {
        $code = preg_replace_callback('/' . $this->leftDelimiter . '(.+?)' . $this->rightDelimiter . '/', function($match){
            $_code = $match[1];

            foreach ($this->rule->rules as $exp)
            {
                $_code = preg_replace($exp[0], $exp[1], $_code);
            }

            return $_code == $match[1] ? $match[0] : $_code;
        }, $code);

        return $code;
    }

}