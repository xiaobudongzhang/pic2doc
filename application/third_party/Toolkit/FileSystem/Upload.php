<?php
/**
 * Toolkit
 *
 * Licensed under the Open Software License version 3.0
 *
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author      Lorne Wang < post@lorne.wang >
 * @copyright   Copyright (c) 2013 - 2014 , All rights reserved.
 * @link        http://lorne.wang/projects/toolkit
 * @license     http://wanglong.name/projects/licenses/OSL-3.0
 */
namespace Toolkit\FileSystem;

/**
 * 文件上传类
 *
 * @author  Lorne Wang < post@lorne.wang >
 * @package Toolkit\FileSystem
 */
class Upload
{
    private $field        = 'file';
    private $uploadPath   = '';
    private $maxSize      = 0;
    private $allowedTypes = ['jpg', 'gif', 'png'];
    private $noRepeat     = true;
    private $subDir       = '';
    private $orgnName     = '';
    private $fileTemp     = '';
    private $filePath     = '';
    private $fileName     = '';
    private $fileSize     = '';
    private $fileExtn     = '';
    private $errorMsg     = '';

    /**
     * Began to upload
     *
     * @access  public
     * @return  boolean
     */
    public function upload()
    {
        // form upload field exist ?
        if (isset($_FILES[$this->field]))
        {
            $this->orgnName = $_FILES[$this->field]['name'];
            $this->fileTemp = $_FILES[$this->field]['tmp_name'];
            $this->fileSize = $_FILES[$this->field]['size'];
            $this->fileExtn = ltrim(strrchr($this->orgnName, '.'), '.');
        }

        // check the upload file
        if ( ! $this->validateFile())
        {
            return false;
        }

        // has a sub directory ?
        // it usually used to create time folder
        // eg: /uploads/09232012/flower.jpg
        if ( ! empty($this->subDir))
        {
            $this->uploadPath .= $this->subDir;
            if ( ! is_dir($this->uploadPath))
            {
                mkdir($this->uploadPath);
            }
        }

        // move the file
        if (move_uploaded_file($this->fileTemp, $this->generateFilePath()) === false)
        {
            $this->errorMsg = 'upload_fail';
            return false;
        }

        return $this->data();
    }

    // --------------------------------------------------------------------

    /**
     * Set field name
     *
     * @access  public
     * @param   string
     * @return  void
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    // --------------------------------------------------------------------

    /**
     * Set file name
     *
     * @access  public
     * @param   string
     * @return  void
     */
    public function setName($name)
    {
        $this->fileName = $name;
    }

    // --------------------------------------------------------------------

    /**
     * Set Upload Path
     *
     * @access  public
     * @param   string
     * @return  void
     */
    public function setPath($path)
    {
        // make sure it has a trailing slash
        $this->uploadPath = rtrim($path, '/') . '/';
    }

    // --------------------------------------------------------------------

    /**
     * Set Maximum File Size
     *
     * @access  public
     * @param   int
     * @return  void
     */
    public function setMaxSize($n)
    {
        $this->maxSize = ((int) $n < 0) ? 0 : (int) $n;
    }

    // --------------------------------------------------------------------

    /**
     * Set Allowed File Types
     *
     * @access  public
     * @param   array
     * @return  void
     */
    public function setAllowedTypes($types)
    {
        $this->allowedTypes = $types;
    }

    // --------------------------------------------------------------------

    /**
     * Set sub directory
     *
     * @access  public
     * @param   int
     * @return  void
     */
    public function setSubDir($dir)
    {
        $this->subDir = trim($dir, '/') . '/';
    }

    // --------------------------------------------------------------------

    /**
     * Display the error message
     *
     * @access  public
     * @return  string
     */
    public function error()
    {
        return $this->errorMsg;
    }

    // --------------------------------------------------------------------

    /**
     * Generates a correct upload file path
     *
     * @access  private
     * @return  string
     */
    private function generateFilePath()
    {
        if (empty($this->fileName))
        {
            $this->fileName = $this->noRepeat
                ? rtrim($this->orgnName, '.' . $this->fileExtn) . '_' . time() . '_' . rand(10000, 99999) . '.' . $this->fileExtn 
                : $this->orgnName;
        }
        else
        {
            $this->fileName = $this->fileName . '.' . $this->fileExtn;
        }

        $this->filePath = $this->uploadPath . $this->fileName;

        return $this->filePath;
    }

    // --------------------------------------------------------------------

    /**
     * Check the upload file
     *
     * @access  private
     * @return  void
     */
    private function validateFile()
    {
        if (empty($this->orgnName) || empty($this->fileTemp) || empty($this->fileSize))
        {
            $this->errorMsg = 'upload_no_file_selected';
        }
        elseif (@is_dir($this->uploadPath) === false)
        {
            $this->errorMsg = 'upload_directory_does_not_exist.';
        }
        elseif (@is_writable($this->uploadPath) === false)
        {
            $this->errorMsg = 'upload_unable_to_write_file';
        }
        elseif (@is_uploaded_file($this->fileTemp) === false)
        {
            $this->errorMsg = 'upload_no_temp_directory';
        }
        elseif ($this->maxSize !== 0 && $this->fileSize > $this->maxSize)
        {
            $this->errorMsg = 'upload_file_exceeds_form_limit';
        }
        elseif (in_array($this->fileExtn, $this->allowedTypes) === false)
        {
            $this->errorMsg = 'upload_stopped_by_extension';
        }
    }

    // --------------------------------------------------------------------

    /**
     * Finalized Data Array
     *
     * Returns an associative array containing all of the information
     * related to the upload, allowing the developer easy access in one array.
     *
     * @param   string
     * @return  mixed
     */
    private function data($index = null)
    {
        $data = new stdClass;
        $data->originName = $this->orgnName;
        $data->fileName = $this->fileName;
        $data->uploadPath = $this->uploadPath;
        $data->fullPath = $this->uploadPath . $this->fileName;
        $data->fileExtn = $this->fileExtn;
        $data->fileSize = $this->fileSize;

        // if ( ! empty($index))
        // {
        //     return isset($data[$index]) ? $data[$index] : null;
        // }

        return $data;
    }
}

/* End file */
