<?php
/**
 * View Facade
 *
 * @method static void setBasePath(string $basePath)
 * @method static void setTemplateEnabled(boolean $isEnabled)
 * @method static void setTemplateDebugEnabled(boolean $isEnabled)
 * @method static void setTemplateLeftDelimiter(string $leftDelimiter)
 * @method static void setTemplateRightDelimiter(string $rightDelimiter)
 * @method static void setCacheDirectory(string $directory)
 * @method static void setFileSuffix(string $suffix)
 * @method static void assign(mixed $mixed, mixed $value = '')
 * @method static void render(string $view)
 * @method static void setLayout(string $layoutPath)
 * @method static void placeholder()
 */
class View
{
    private static $instance;

    final static function __callStatic($method, $parameters)
    {
        if ( ! isset(self::$instance))
        {
            $rule = new PHPTemplate\Rules\Classical;
            $rule->add("/^base_url$/", "<?php echo base_url(); ?>");
            $rule->add("/^auto_url:(.+?)$/", '<?php echo auto_url("$1"); ?>');

            $view = new PHPTemplate\Template(new PHPTemplate\Compiler($rule));
            $view->setViewDirectory(APPPATH. 'views');
            $view->setCompiledDirectory(APPPATH . 'cache/views');
            $view->setTemplateEnabled(true);
            $view->setTemplateDebugEnabled(true);
            $view->setFileSuffix('phtml');

            self::$instance = $view;
        }

        return call_user_func_array([self::$instance, $method], $parameters);
    }
}
