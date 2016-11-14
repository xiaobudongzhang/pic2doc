<?php


class PhpUnit extends PHPUnit_Framework_TestCase
{
    /**
     * 数组字段完整测试
     * @param $array_test
     * @param $params
     * use:
     * assertFullArray(['test1' => 1, 'test2' => 2], ['test1', 'test2'])
     */
    public static function assertFullArray($array_test, $params)
    {
        if (!is_array($params)) parent::assertArrayHasKey($params, $array_test);
        else foreach ($params as $param) parent::assertArrayHasKey($param, $array_test);
    }

    /**
     * 类完整测试
     * @param $object_test
     * @param $params
     * assertFullObject($object_test, ['test1', 'test2'])
     */
    public static function assertFullObject($object_test, $params)
    {
        if (!is_array($params)) parent::assertObjectHasAttribute($params, $object_test);
        else {
            foreach ($params as $param) {
                parent::assertObjectHasAttribute($param, $object_test);
            }
        }
    }

}

?>