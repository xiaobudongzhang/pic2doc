<?
// 打印出调试代码
if (!function_exists('dump')) {
    function dump($str)
    {
        echo '<pre>';
        print_r($str);
        echo '</pre>';
    }
}

function getFormatOut(){
    return [
        'code'=>'00000',
        'data'=>[],
        'msg'=>'ok'
    ];
}