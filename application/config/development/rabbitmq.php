<?php
/*
| -------------------------------------------------------------------
| RabbitMQ配置（开发环境）
| -------------------------------------------------------------------
*/

// 支付中心队列
$config['pay_center']['host'] = '192.168.1.70';
$config['pay_center']['port'] = 5672;
$config['pay_center']['login'] = 'NewHouse';
$config['pay_center']['password'] = 'newhouse@10';
$config['pay_center']['vhost'] = '/pay';

// 本地测试机
$config['localhost']['host'] = '192.168.80.159';
$config['localhost']['port'] = 5672;
$config['localhost']['login'] = 'guest';
$config['localhost']['password'] = 'guest';
$config['localhost']['vhost'] = '/';