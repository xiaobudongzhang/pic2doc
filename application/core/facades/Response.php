<?php

class Response
{
    // 定义数据返回格式常量
    const FORMAT_TEXT = 'text';
    const FORMAT_JSON = 'json';
    const FORMAT_XML = 'xml';
    const FORMAT_PB = 'pb';
    const FORMAT_THRIFT = 'thrift';

    /**
     * 动态输出格式数据
     *
     * @param  array   $data    结果数据
     * @param  integer $total   总行数
     * @param  string  $code    状态码
     * @param  string  $message 消息
     * @param  string  $format  输出格式
     * @return mixed
     */
    public static function output($data, $total = 0, $code = '0', $message = '', $format = self::FORMAT_JSON)
    {
        if (is_array(@$data[0]) && $total > 0)
        {
            $data = [
                'code' => $code,
                'results' => $data,
                'total' => $total,
                'message' => $message
            ];
        }

        return self::$format($data);
    }

    /**
     * thrift 数据类型强制转换
     *
     * @param  object $structObject thrift 结构化对象
     * @return object
     */
    public static function thriftRape($structObject)
    {
        $rStruct = new ReflectionClass($structObject);
        $types = $rStruct->getStaticPropertyValue('_TSPEC');

        foreach ($types as $item)
        {
            $key = $item['var'];
            $tType = $item['type'];

            if (is_null($structObject->$key))
            {
                continue;
            }

            if ($tType == 12) // struct object
            {
                $structObject->$key = self::thriftRape($structObject->$key);
            }
            elseif ($tType == 15) // list
            {
                $eType = $item['etype'];

                foreach ((array) $structObject->$key as $subRef)
                {

                    if ($eType == 12) // struct object
                    {
                        $subRef = self::thriftRape($subRef);
                    }
                    else
                    {
                        $subRef = self::convertType($eType, $subRef);
                    }
                }
            }
            else
            {
                $structObject->$key = self::convertType($tType, $structObject->$key);
            }
        }

        return $structObject;
    }

    /**
     * thrift 数据类型映射
     *
     * @param  integer $type
     * @param  mixed   $value
     * @return object
     */
    public static function convertType($type, $value)
    {
        if ($type == 4) // double
        {
            $value = (double) $value;
        }
        elseif ($type == 6 || $type == 8 || $type == 10) // i16 || i32 || i64
        {
            $value = (int) $value;
        }
        elseif ($type == 2) // bool
        {
            $value = (boolean) $value;
        }
        elseif ($type == 11) // string
        {

            $value = (string) $value;
        }

        return $value;
    }

    /**
     * JSON 格式化
     *
     * @param  array $data 结果数据
     * @return string
     */
    public static function json($data, $jsonpCallback = false)
    {
        header('Content-type:text/json');

        if (is_string($jsonpCallback) && strlen($jsonpCallback) > 1)
        {
            echo $jsonpCallback . "(" . json_encode($data) . ")";exit;
        }

        echo json_encode($data);exit;
    }


    /**
     * 纯文本（text） 格式化
     *
     * @param  array $data 结果数据
     * @return string
     */
    public static function text($data)
    {
        echo $data;exit;
    }

    /**
     * XML 格式化
     *
     * @param  array $data 结果数据
     * @return string
     */
    public static function xml($data)
    {
        // TODO
    }
}