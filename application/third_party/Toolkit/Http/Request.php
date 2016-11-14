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
namespace Toolkit\Http;

/**
 * 请求处理类
 *
 * @author  Lorne Wang < post@lorne.wang >
 * @package Toolkit\Http
 */
class Request
{
    /**
     * IP 地址
     * 
     * @var boolean
     */
    private $ipAddress = false;

    // --------------------------------------------------------------------

    /**
     * 用户设备标识
     * 
     * @var boolean
     */
    private $userAgent = false;

    // --------------------------------------------------------------------

    /**
     * 头信息
     * 
     * @var array
     */
    private $headers = [];

    // --------------------------------------------------------------------

    /**
     * Fetch from array
     *
     * This is a helper function to retrieve values from global arrays
     *
     * @access protected
     * @param  array
     * @param  string
     * @param  bool
     * @return string
     */
    protected function fetchFromArray(&$array, $index = null, $default = null, $xssClean = false)
    {
        $ret = $index === null && ! empty($array)
            ? $array 
            : ( isset($array[$index])
                ? $array[$index]
                : $default );

        return $xssClean ? $this->xssClean($ret) : $ret;
    }

    // --------------------------------------------------------------------

    /**
     * XSS Clean
     *
     * Sanitizes data so that Cross Site Scripting Hacks can be
     * prevented.  This function does a fair amount of work but
     * it is extremely thorough, designed to prevent even the
     * most obscure XSS attempts.  Nothing is ever 100% foolproof,
     * of course, but I haven't been able to get anything passed
     * the filter.
     *
     * Note: This function should only be used to deal with data
     * upon submission. It's not something that should
     * be used for general runtime processing.
     *
     * This function was based in part on some code and ideas I
     * got from Bitflux: http://channel.bitflux.ch/wiki/XSS_Prevention
     *
     * To help develop this script I used this great list of
     * vulnerabilities along with a few other hacks I've
     * harvested from examining vulnerabilities in other programs:
     * http://ha.ckers.org/xss.html
     *
     * @access public
     * @param  mixed  $str
     * @param  bool   $isImage 
     * @return string
     */
    public function xssClean($str, $isImage = false)
    {
        // Is the string an array?
        if (is_array($str))
        {
            while (list($key) = each($str))
            {
                $str[$key] = $this->xssClean($str[$key]);
            }

            return $str;
        }

        /*
         * URL Decode
         *
         * Just in case stuff like this is submitted:
         *
         * <a href="http://%77%77%77%2E%67%6F%6F%67%6C%65%2E%63%6F%6D">Google</a>
         *
         * Note: Use rawurldecode() so it does not remove plus signs
         */
        $str = rawurldecode($str);

        /*
         * Convert character entities to ASCII
         *
         * This permits our tests below to work reliably.
         * We only convert entities that are within tags since
         * these are the ones that will pose security problems.
         */
        $str = preg_replace_callback("/[a-z]+=([\'\"]).*?\\1/si", function($match) {
            return str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'), $match[0]);
        }, $str);

        /*
         * Convert all tabs to spaces
         *
         * This prevents strings like this: ja    vascript
         * NOTE: we deal with spaces between characters later.
         * NOTE: preg_replace was found to be amazingly slow here on
         * large blocks of data, so we use str_replace.
         */
        $str = str_replace("\t", ' ', $str);

        // Capture converted string for later comparison
        $convertedString = $str;

        /*
         * Makes PHP tags safe
         *
         * Note: XML tags are inadvertently replaced too:
         *
         * <?xml
         *
         * But it doesn't seem to pose a problem.
         */
        if ($isImage === true)
        {
            // Images have a tendency to have the PHP short opening and
            // closing tags every so often so we skip those and only
            // do the long opening tags.
            $str = preg_replace('/<\?(php)/i', '&lt;?\\1', $str);
        }
        else
        {
            $str = str_replace(array('<?', '?'.'>'),  array('&lt;?', '?&gt;'), $str);
        }

        /*
         * Compact any exploded words
         *
         * This corrects words like:  j a v a s c r i p t
         * These words are compacted back to their correct state.
         */
        $words = array(
            'javascript', 'expression', 'vbscript', 'script', 'base64',
            'applet', 'alert', 'document', 'write', 'cookie', 'window'
        );

        foreach ($words as $word)
        {
            $word = implode('\s*', str_split($word)).'\s*';

            // We only want to do this when it is followed by a non-word character
            // That way valid stuff like "dealer to" does not become "dealerto"
            $str = preg_replace_callback('#('.substr($word, 0, -3).')(\W)#is', function($matches) {
                return preg_replace('/\s+/s', '', $matches[1]).$matches[2];
            }, $str);
        }

        /*
         * Remove disallowed Javascript in links or img tags
         * We used to do some version comparisons and use of stripos for PHP5,
         * but it is dog slow compared to these simplified non-capturing
         * preg_match(), especially if the pattern exists in the string
         */
        do
        {
            $original = $str;

            if (preg_match('/<a/i', $str))
            {
                $str = preg_replace_callback('#<a\s+([^>]*?)(?:>|$)#si', function($match) use($str) {
                    $out = str_replace(array('<', '>'), '', $match[1]);
                    if (preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\\1]*?)\\1#is', $str, $matches))
                    {
                        foreach ($matches[0] as $m)
                        {
                            $out .= preg_replace('#/\*.*?\*/#s', '', $m);
                        }
                    }

                    return str_replace($match[1],
                    preg_replace('#href=.*?(?:alert\(|alert&\#40;|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|data\s*:)#si', '', $out), $match[0]);
                }, $str);
            }

            if (preg_match('/<img/i', $str))
            {
                $str = preg_replace_callback('#<img\s+([^>]*?)(?:\s?/?>|$)#si', function($match) use($str) {
                    $out = str_replace(array('<', '>'), '', $match[1]);
                    if (preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\\1]*?)\\1#is', $str, $matches))
                    {
                        foreach ($matches[0] as $m)
                        {
                            $out .= preg_replace('#/\*.*?\*/#s', '', $m);
                        }
                    }

                    return str_replace($match[1],
                        preg_replace('#src=.*?(?:alert\(|alert&\#40;|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si', '', $out), $match[0]);
                }, $str);
            }

            if (preg_match('/script|xss/i', $str))
            {
                $str = preg_replace('#</*(?:script|xss).*?>#si', '[removed]', $str);
            }
        }
        while ($original !== $str);

        unset($original);

        /*
         * Sanitize naughty HTML elements
         *
         * If a tag containing any of the words in the list
         * below is found, the tag gets converted to entities.
         *
         * So this: <blink>
         * Becomes: &lt;blink&gt;
         */
        $naughty = 'alert|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|isindex|layer|link|meta|object|plaintext|style|script|textarea|title|video|xml|xss';
        $str = preg_replace_callback('#<(/*\s*)('.$naughty.')([^><]*)([><]*)#is', function($matches) {
            return '&lt;'.$matches[1].$matches[2].$matches[3] // encode opening brace
            // encode captured opening or closing brace to prevent recursive vectors:
            .str_replace(array('>', '<'), array('&gt;', '&lt;'), $matches[4]);
        }, $str);

        /*
         * Sanitize naughty scripting elements
         *
         * Similar to above, only instead of looking for
         * tags it looks for PHP and JavaScript commands
         * that are disallowed. Rather than removing the
         * code, it simply converts the parenthesis to entities
         * rendering the code un-executable.
         *
         * For example:    eval('some code')
         * Becomes:    eval&#40;'some code'&#41;
         */
        $str = preg_replace('#(alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si',
                    '\\1\\2&#40;\\3&#41;',
                    $str);

        /*
         * Images are Handled in a Special Way
         * - Essentially, we want to know that after all of the character
         * conversion is done whether any unwanted, likely XSS, code was found.
         * If not, we return true, as the image is clean.
         * However, if the string post-conversion does not matched the
         * string post-removal of XSS, then it fails, as there was unwanted XSS
         * code found and removed/changed during processing.
         */
        if ($isImage === true)
        {
            return ($str === $convertedString);
        }

        return $str;
    }

    // --------------------------------------------------------------------

    /**
     * Fetch an item from the GET array
     *
     * @access  public
     * @param   string
     * @param   bool
     * @return  string
     */
    public function get($index = null, $default = null, $xssClean = false)
    {
        return self::fetchFromArray($_GET, $index, $default, $xssClean);
    }

    // --------------------------------------------------------------------

    /**
     * Fetch an item from the POST array
     *
     * @access  public
     * @param    string
     * @param    bool
     * @return    string
     */
    public function post($index = null, $default = null, $xssClean = false)
    {
        return self::fetchFromArray($_POST, $index, $default, $xssClean);
    }

    // --------------------------------------------------------------------

    /**
     * Fetch an item from the SERVER array
     *
     * @access public
     * @param  string
     * @param  bool
     * @return string
     */
    public function server($index = '', $default = null, $xssClean = false)
    {
        return self::fetchFromArray($_SERVER, $index, $default, $xssClean);
    }

    // --------------------------------------------------------------------

    /**
     * Fetch an item from the COOKIE array
     *
     * @access public
     * @param  string
     * @param  bool
     * @return string
     */
    public function cookie($index = '', $default = null, $xss_clean = FALSE)
    {
        return self::fetchFromArray($_COOKIE, $index, $default, $xss_clean);
    }

    // --------------------------------------------------------------------

    /**
     * Set cookie
     *
     * Accepts seven parameters, or you can submit an associative
     * array in the first parameter containing all the values.
     *
     * @access public
     * @param  mixed   $name
     * @param  string  $value the value of the cookie
     * @param  string  $expire the number of seconds until expiration
     * @param  string  $domain the cookie domain.  Usually:  .yourdomain.com
     * @param  string  $path the cookie path
     * @param  string  $prefix the cookie prefix
     * @param  boolean $secure true makes the cookie secure
     * @param  boolean $httpOnly true makes the cookie accessible via http(s) only (no javascript)
     * @return void
     */
    public function setCookie($name = '', $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = false, $httpOnly = false)
    {
        if (is_array($name))
        {
            // always leave 'name' in last place, as the loop will break otherwise, due to $$item
            foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'secure', 'httpOnly', 'name') as $item)
            {
                if (isset($name[$item]))
                {
                    $$item = $name[$item];
                }
            }
        }

        if ( ! is_numeric($expire))
        {
            $expire = time() - 86500;
        }
        else
        {
            $expire = ($expire > 0) ? time() + $expire : 0;
        }

        setcookie($prefix . $name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

    /**
     * Fetch the IP Address
     *
     * @access public
     * @return string
     */
    public function ipAddress()
    {
        if ($this->ipAddress !== false)
        {
            return $this->ipAddress;
        }

        if ($ips = filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_FOR', FILTER_UNSAFE_RAW) ?: getenv('HTTP_X_FORWARDED_FOR'))
        {
            foreach ((array) explode(',', $ips) as $ip)
            {
                if ($this->ipAddress = filter_var(trim($ip), FILTER_VALIDATE_IP))
                {
                    break;
                }
            }
        }

        if ($this->ipAddress = filter_input(INPUT_SERVER, 'HTTP_CLIENT_IP', FILTER_VALIDATE_IP))
        {
            return $this->ipAddress;
        }

        if ($this->ipAddress = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP))
        {
            return $this->ipAddress;
        }

        if ($this->ipAddress = filter_var(getenv('HTTP_CLIENT_IP'), FILTER_VALIDATE_IP))
        {
            return $this->ipAddress;
        }

        if ($this->ipAddress = filter_var(getenv('REMOTE_ADDR'), FILTER_VALIDATE_IP))
        {
            return $this->ipAddress;
        }

        return ($this->ipAddress = '0.0.0.0');
    }

    // --------------------------------------------------------------------

    /**
     * Validate IP Address
     *
     * @access public
     * @param  string
     * @param  string
     * @return bool
     */
    public function validIP($ip, $which = '')
    {
        switch (strtolower($which))
        {
            case 'ipv4':
                $which = FILTER_FLAG_IPV4;
                break;
            case 'ipv6':
                $which = FILTER_FLAG_IPV6;
                break;
            default:
                $which = null;
                break;
        }

        return (bool) filter_var($ip, FILTER_VALIDATE_IP, $which);
    }

    // --------------------------------------------------------------------

    /**
     * User Agent
     *
     * @access public
     * @return string
     */
    public function userAgent()
    {
        if ($this->userAgent !== false)
        {
            return $this->userAgent;
        }

        return $this->userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : false;
    }

    // --------------------------------------------------------------------

    /**
     * 取得阅读器名称和版本
     *
     * @access public
     * @return string
     */ 
    public function browser()
    {
        $agent   = self::userAgent();
        $browser = 'Unknow';
        $version = 0; 

        if (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs))
        {
          $browser = 'OmniWeb';
          $version = $regs[2];
        }

        if (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs))
        {
          $browser = 'Netscape';
          $version = $regs[2];
        }

        if (preg_match('/safari\/([^\s]+)/i', $agent, $regs))
        {
          $browser = 'Safari';
          $version = $regs[1];
        }

        if (preg_match('/chrome\/([^\s]+)/i', $agent, $regs))
        {
          $browser = 'Chrome';
          $version = $regs[1];
        }

        if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) 
        {
          $browser = 'IE';
          $version = $regs[1];
        }

        if (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs)) 
        {
          $browser = 'Opera';
          $version = $regs[1];
        }

        if (preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs))
        {
          $browser = 'IE';
          $version = $regs[1];
        }

        if (preg_match('/Maxthon/i', $agent, $regs))
        {
          $browser = 'Maxthon';
          $version = '';
        }

        if (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs))
        {
          $browser = 'Firefox';
          $version = $regs[1];
        }

        if (preg_match('/Lynx\/([^\s]+)/i', $agent, $regs))
        {
          $browser = 'Lynx';
          $version = $regs[1];
        }

        return ['browser' => $browser, 'version' => (float) $version];
    }

    // --------------------------------------------------------------------

    /**
     * 取得当前URL根地址
     *
     * @access public
     * @return string
     */
    public function baseUrl()
    {
        static $url;

        if ( ! empty($url))
        {
            return $url;
        }

        if (isset($_SERVER['HTTP_HOST']))
        {
            $url = ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') ? 'https' : 'http';
            $url .= '://'.$_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        }
        else
        {
            $url = 'http://localhost/';
        }

        return $url;
    }

    // --------------------------------------------------------------------

    /**
     * Request Headers
     *
     * In Apache, you can simply call apache_requestHeaders(), however for
     * people running other webservers the function is undefined.
     *
     * @access public
     * @param  boolean $xssClean XSS cleaning
     * @return array
     */
    public function requestHeaders($xssClean = false)
    {
        // Look at Apache go!
        if (function_exists('apache_requestHeaders'))
        {
            $headers = apache_requestHeaders();
        }
        else
        {
            $headers['Content-Type'] = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : @getenv('CONTENT_TYPE');

            foreach ($_SERVER as $key => $val)
            {
                if (strpos($key, 'HTTP_') === 0)
                {
                    $headers[substr($key, 5)] = self::fetchFromArray($_SERVER, $key, $xssClean);
                }
            }
        }

        // take SOME_HEADER and turn it into Some-Header
        foreach ($headers as $key => $val)
        {
            $key = str_replace('_', ' ', strtolower($key));
            $key = str_replace(' ', '-', ucwords($key));

            $this->headers[$key] = $val;
        }

        return $this->headers;
    }

    // --------------------------------------------------------------------

    /**
     * Get Request Header
     *
     * Returns the value of a single member of the headers class member
     *
     * @access public
     * @param  string  $index    array key for $this->headers
     * @param  boolean $xssClean XSS Clean or not
     * @return mixed  false on failure, string on success
     */
    public function getRequestHeader($index, $xssClean = false)
    {
        if (empty($this->headers))
        {
            self::requestHeaders();
        }

        if ( ! isset($this->headers[$index]))
        {
            return null;
        }

        return ($xssClean === true)
            ? self::xssClean($this->headers[$index])
            : $this->headers[$index];
    }

    // --------------------------------------------------------------------

    /**
     * Is ajax Request?
     *
     * Test to see if a request contains the HTTP_X_REQUESTED_WITH header
     *
     * @access public
     * @return boolean
     */
    public function isAjaxRequest()
    {
        return ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
    }

    // --------------------------------------------------------------------

    /**
     * Is cli Request?
     *
     * Test to see if a request was made from the command line
     *
     * @access public
     * @return boolean
     */
    public function isCliRequest()
    {
        return (php_sapi_name() === 'cli' OR defined('STDIN'));
    }

    // --------------------------------------------------------------------

    /**
     * Get Request Method
     *
     * Return the Request Method
     *
     * @access public
     * @return string
     */
    public function method()
    {
        return strtolower(self::server('REQUEST_METHOD'));
    }
}

/* End file */
