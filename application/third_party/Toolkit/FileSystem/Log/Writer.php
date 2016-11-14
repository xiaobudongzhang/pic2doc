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
namespace Toolkit\FileSystem\Log;

/**
 * Writer Class
 *
 * @author  Lorne Wang < post@lorne.wang >
 * @package Toolkit\FileSystem\Log
 */
class Writer
{
    protected $filePath;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $enabled = true;
    protected $filePermissions = 0644;
    protected $fileExt = 'txt';

    /**
     * Initialize static class
     */
    public function initialize()
    {
//        if ($filePath = Config::get('log.file_path'))
//        {
//            self::$filePath = $filePath;
//        }
//        else
//        {
//            self::$filePath = APP_PATH . 'runtime' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
//        }
//
//        if ( ! is_dir(self::$filePath) OR ! File::isReallyWritable(self::$filePath))
//        {
//            self::$enabled = false;
//        }
//
//        if ($dateFormat = Config::get('log.date_format'))
//        {
//            self::$dateFormat = $dateFormat;
//        }
    }

    // --------------------------------------------------------------------

    /**
     * Write Log File
     *
     * @param  string $directory
     * @param  string $content
     * @return boolean
     */
    public function write($directory, $content)
    {
        if ($this->enabled === false)
        {
            return false;
        }

        if ( ! is_dir($this->filePath . $directory))
        {
            mkdir($this->filePath . $directory);
        }

        $fullPath = $this->filePath . $directory . DIRECTORY_SEPARATOR . 'log-' . date('Y-m-d') . '.' . $this->fileExt;

        if ( ! file_exists($fullPath))
        {
            $newFile = true;
        }

        if ( ! $fp = @fopen($fullPath, 'ab'))
        {
            return false;
        }

        // Instantiating DateTime with microseconds appended to initial date is needed for proper support of this format
        if (strpos($this->dateFormat, 'u') !== false)
        {
            $microTimeFull = microtime(true);
            $microTimeShort = sprintf("%06d", ($microTimeFull - floor($microTimeFull)) * 1000000);
            $date = new \DateTime(date('Y-m-d H:i:s.' . $microTimeShort, $microTimeFull));
            $date = $date->format($this->dateFormat);
        }
        else
        {
            $date = date($this->dateFormat);
        }

        $message = $date . ' --> ' . $content . "\n";

        flock($fp, LOCK_EX);

        for ($written = 0, $length = strlen($message); $written < $length; $written += $result)
        {
            if (($result = fwrite($fp, substr($message, $written))) === false)
            {
                break;
            }
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        if (isset($newFile) && $newFile === true)
        {
            chmod($fullPath, $this->filePermissions);
        }

        return isset($result) && is_int($result);
    }
}

/* End file */