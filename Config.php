<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2017/12/30
 * Time: 下午10:59
 */

return [
    'SERVER_NAME'=>"EasySwoole",
    'MAIN_SERVER'=>[
        'HOST'=>'localhost',
        'PORT'=>9501,
        'SERVER_TYPE'=>\EasySwoole\Core\Swoole\ServerManager::TYPE_WEB_SERVER,
        'SOCK_TYPE'=>SWOOLE_TCP,//该配置项当为SERVER_TYPE值为TYPE_SERVER时有效
        'RUN_MODEL'=>SWOOLE_PROCESS,
        'SETTING'=>[
            'task_worker_num' => 8, //异步任务进程
            'task_max_request'=>10,
            'max_request'=>50000,//强烈建议设置此配置项
            'worker_num'=>4,
        ],
    ],
    'DEBUG'=>true,
    'TEMP_DIR'=>EASYSWOOLE_ROOT . '/Temp',//若不配置，则默认框架初始化
    'LOG_DIR'=>EASYSWOOLE_ROOT . '/Log',//若不配置，则默认框架初始化
    'EASY_CACHE'=>[
        'PROCESS_NUM'=>1,//若不希望开启，则设置为0
        'PERSISTENT_TIME'=>0//如果需要定时数据落地，请设置对应的时间周期，单位为秒
    ],
    'CLUSTER'=>[
        'enable'=>false,
        'token'=>null,
        'broadcastAddress'=>['255.255.255.255:9556'],
        'listenAddress'=>'0.0.0.0',
        'listenPort'=>'9556',
        'broadcastTTL'=>5,
        'nodeTimeout'=>10,
        'nodeName'=>'easySwoole',
        'nodeId'=>null
    ],
    'POOL_MANAGER' => [
//        'App\Utility\RedisPool' => [
//            'min' => 15,
//            'max' => 400,
//            'type' => 1
//        ],
        'App\Utility\MysqlPool2' => [
            'min' => 44,
            'max' => 400,
            'type' => 1
        ],
    ],
    //
    'REDIS' => [
        'host' => 'api.fokm.cn', // redis主机地址
        'port' => 6379, // 端口
        'serialize' => false, // 是否序列化php变量
        'dbName' => 0, // db名
        'auth' => 'fokmapi', // 密码
        'pool' => [
            'min' => 15, // 最小连接数
            'max' => 400 // 最大连接数
        ],
        'errorHandler' => function(){
            return null;
        } // 如果Redis重连失败，会判断errorHandler是否callable，如果是，则会调用，否则会抛出异常，请自行try
    ],
    //
    //读写库配置
    'MYSQL' => [
        'HOST' => 'api.fokm.cn', // 数据库地址
        'USER' => 'api', // 数据库用户名
        'PASSWORD' => 'DZR32wYhtDGPpbSY', // 数据库密码
        'DB_NAME' => 'api', // 数据库库名
        'PORT' => 3306, // 数据库端口
        'MIN' => 44, // 最小连接数
        'MAX' => 400 // 最大连接数
    ],
];
