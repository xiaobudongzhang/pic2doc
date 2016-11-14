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
namespace Toolkit;

/**
 * Template
 *
 * 轻量级的动态模板引擎，实现静态页与后端分离。支持变量与逻辑标签，
 * 模板经一次解析后生成被编译的动态缓存文件提高下一次的执行效率。
 * 编译后的代码具有较严格的程式修复和逻辑判断，避免了普遍的异常抛出，
 * 使开发者在编写模板时无需关注数据的真实性和可靠性，提高了开发速度。
 * 除此之外它还具有常用的视图层接口，并提供了简单的布局视图模块。
 *
 * @author  Lorne Wang < post@lorne.wang >
 * @package Toolkit
 */
class Template
{
    /**
     * 视图文件目录基础路径
     *
     * @var string
     */
    private $basePath = '';

    /**
     * 视图文件相对路径
     *
     * @var string
     */
    private $viewPath = '';

    /**
     * 结构视图文件相对路径
     *
     * @var string
     */
    private $layoutPath = '';

    /**
     * 注册变量集合
     *
     * @var array
     */
    private $variables = [];

    /**
     * 编译缓存文件目录
     *
     * @var string
     */
    private $cacheDirectory = '';

    /**
     * 视图文件后缀名
     *
     * @var string
     */
    private $fileSuffix = 'html';

    /**
     * 是否开启模板引擎
     *
     * @var boolean
     */
    private $templateEnabled = false;

    /**
     * 是否开启模板调试模式
     *
     * @var boolean
     */
    private $templateDebugEnabled = false;

    private $compiler;

    public function __construct(Compiler $compiler)
    {
        $this->compiler = $compiler;
    }

    public function setViewDirectory($basePath)
    {
        $this->basePath = $basePath;
    }

    // --------------------------------------------------------------------

    public function setCompiledDirectory($directory)
    {
        is_dir($directory) OR die("Not found the cache directory in {$directory}");
        $this->cacheDirectory = $directory;
    }

    // --------------------------------------------------------------------

    public function setTemplateEnabled($isEnabled)
    {
        $this->templateEnabled = $isEnabled;
    }

    // --------------------------------------------------------------------

    public function setTemplateDebugEnabled($isEnabled)
    {
        $this->templateDebugEnabled = $isEnabled;
    }

    // --------------------------------------------------------------------

    public function setFileSuffix($suffix)
    {
        $this->fileSuffix = $suffix;
    }

    // --------------------------------------------------------------------

    /**
     * 定义并使用结构化视图
     *
     * @access public
     * @param  string $layoutPath 结构文件相对路径
     * @return void
     */
    public function setLayout($layoutPath = 'shared/main')
    {
        $this->layoutPath = $layoutPath;
    }

    public function getGuid()
    {
        return md5(
            $this->basePath .
            $this->cacheDirectory .
            $this->templateDebugEnabled .
            $this->templateEnabled
        );
    }


    /**
     * 注册变量到视图
     *
     * @access public
     * @param  mixed $mixed 变量名称或数组
     * @param  mixed $value 变量值
     * @return void
     */
    public function assign($mixed, $value = null)
    {
        if (is_object($mixed))
        {
            $mixed = (array) $mixed;
        }

        if (is_array($mixed))
        {
            $this->variables = array_merge($this->variables, $mixed);
        }
        elseif (is_object($this->variables))
        {
            $this->variables->$mixed = $value;
        }
        else
        {
            $this->variables[$mixed] = $value;
        }
    }

    // --------------------------------------------------------------------

    /**
     * 渲染视图
     *
     * @access public
     * @param  string $view 视图文件相对路径
     * @return void
     */
    public function render($view = '')
    {
        $this->viewPath = $view;

        @extract($this->variables);

        unset($key, $value, $view);

        if ($this->layoutPath)
        {
            $this->viewPath = $this->getViewPath();
            include $this->getCachePath($this->getViewPath($this->layoutPath));
        }
        else
        {
            include $this->getCachePath($this->getViewPath($this->viewPath));
        }
    }

    // --------------------------------------------------------------------

    /**
     * 视图模板占位函数
     * 通常与layout结合使用，该调用部分表示占位区域的模板内容
     *
     * @access public
     * @return void
     */
    public function placeholder()
    {
        $this->layoutPath = null;
        $this->render($this->viewPath);
    }

    // --------------------------------------------------------------------

    /**
     * 取得视图文件所在的绝对路径
     *
     * @access private
     * @param  string $view 视图文件所在相对路径
     * @return string
     */
    private function getViewPath($view = '')
    {
        return $this->basePath . DIRECTORY_SEPARATOR . "{$view}.{$this->fileSuffix}";
    }

    // --------------------------------------------------------------------

    /**
     * 检测并返回正确的缓存文件路径
     *
     * @access private
     * @param  string $path 视图文件绝对路径
     * @return string
     */
    private function getCachePath($path)
    {
        $this->compiler->id = $this->getGuid();
        $this->compiler->initialize();
        TemplateManager::add($this->getGuid(), $this);

        if ( ! $this->templateEnabled)
        {
            return $path;
        }

        $cacheFileName = md5($this->getGuid() . $path) . '.php';
        $cacheFilePath = $this->cacheDirectory . DIRECTORY_SEPARATOR . $cacheFileName;

        if ( ! is_file($cacheFilePath) || ($this->templateDebugEnabled && filemtime($cacheFilePath) < filemtime($path)))
        {
            if ( ! is_file($path))
            {
                die($path . ' view page file can not found.');
            }

            $code = file_get_contents($path);
            $code = $this->compiler->parser($code);
            file_put_contents($cacheFilePath, $code);
        }

        return $cacheFilePath;
    }
}

/* End file */
