<?php


class ShowMe
{
    /**
     * 数据打印
     *
     * @param  string $method 方法名
     * @param  array $params 参数数组
     * @return  mixed
     *
     * use:
     * show_me_now_{$number}($number, $v) ?print={$number} $number相等立即输出$v
     * show_me_all($variable_list) ?print=all 输出所有变量
     * show_me_{$variable_name} ?print={variable_name} 输出变量值
     */
    public static function __callStatic($method, $params = null)
    {
        if (ENVIRONMENT != 'production') {
            if (count($params) === 2 && preg_match('/show_me_now_.*/', $method))
            {
                $variable_name = str_replace('show_me_now_', '', $method);
                if ($variable_name == $params[0])
                {
                    dump($params[1]);
                    die;
                }
            }
            else if (count($params) === 1 && preg_match('/show_me_.*/', $method) && is_array($params[0]))
            {
                $variable_name = str_replace('show_me_', '', $method);
                if ($variable_name === 'all') {
                    // print it
                    dump(array_keys($params[0]));
                    die;
                } else if ($variable_name != '') {
                    // name of the variable to print
                    $variable_print = isset($params[0][$variable_name]) ? $params[0][$variable_name] : '变量不存在';

                    // print it
                    dump($variable_print);
                    die;
                }
            }
        }
    }
}

