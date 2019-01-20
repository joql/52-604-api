<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/1/9
 * Time: 下午1:04
 */

namespace EasySwoole;

use App\Process\TimeTask;
use \EasySwoole\Core\AbstractInterface\EventInterface;
use EasySwoole\Core\Component\Logger;
use EasySwoole\Core\Swoole\Process\ProcessManager;
use \EasySwoole\Core\Swoole\ServerManager;
use \EasySwoole\Core\Swoole\EventRegister;
use \EasySwoole\Core\Http\Request;
use \EasySwoole\Core\Http\Response;
use EasySwoole\Core\Component\Pool\PoolManager;
use App\Utility\MysqlPool2;
use EasySwoole\Core\Swoole\Time\Timer;

Class EasySwooleEvent implements EventInterface {

    public static function frameInitialize(): void
    {
        // TODO: Implement frameInitialize() method.
        date_default_timezone_set('Asia/Shanghai');
    }

    public static function mainServerCreate(ServerManager $server,EventRegister $register): void
    {
        // TODO: Implement mainServerCreate() method.
        // 数据库协程连接池
        // @see https://www.easyswoole.com/Manual/2.x/Cn/_book/CoroutinePool/mysql_pool.html?h=pool
        // ------------------------------------------------------------------------------------------
//        if (version_compare(phpversion('swoole'), '2.1.0', '>=')) {
//            PoolManager::getInstance()->registerPool(MysqlPool2::class, 3, 10);
//        }
//        $register->add($register::onWorkerStart, function (\swoole_server $server, $workerId){
//            //为第一个进程添加定时器
//            if($workerId == 0){
//                Timer::loop(1000*10,function (){
//                    Logger::getInstance()->console('timer run');
//                    ProcessManager::getInstance()->writeByProcessName('timeTask', time());
//                });
//            }
//        });

        // 定时任务
        //ProcessManager::getInstance()->addProcess('timeTask', TimeTask::class);
    }

    public static function onRequest(Request $request,Response $response): void
    {
        // TODO: Implement onRequest() method.
    }

    public static function afterAction(Request $request,Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}