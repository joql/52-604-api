<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/3/10
 * Time: 下午5:56
 */
namespace App\Utility;
use EasySwoole\Config;
use EasySwoole\Core\Component\Pool\AbstractInterface\Pool;
use EasySwoole\Core\Component\Trigger;
use EasySwoole\Core\Swoole\Coroutine\Client\Mysql;
class MysqlSlavePool extends Pool
{
    function getObj($timeOut = 0.1):?Mysql
    {
        return parent::getObj($timeOut); // TODO: Change the autogenerated stub
    }
    protected function createObject()
    {
        // TODO: Implement createObject() method.
        $conf = Config::getInstance()->getConf('MYSQL_SLAVE');
        if($conf['ENABLE'] === false){
            return null;
        }
        $slave_num = count($conf['HOSTS']);
        if($slave_num <= 0){
            return null;
        }
        try{
            $db = new Mysql([
                'host' => $conf['HOSTS'][$this->queue->count()/$slave_num]['HOST'],
                'username' => $conf['HOSTS'][$this->queue->count()/$slave_num]['USER'],
                'password' => $conf['HOSTS'][$this->queue->count()/$slave_num]['PASSWORD'],
                'db' => $conf['HOSTS'][$this->queue->count()/$slave_num]['DB_NAME']
            ]);
            if (isset($conf['HOSTS'][$this->queue->count()/$slave_num]['names'])) {
                $db->rawQuery('SET NAMES ' . $conf['HOSTS'][$this->queue->count()/$slave_num]['names']);
            }
            return $db;
        }catch (\Throwable $throwable){
            Trigger::throwable($throwable);
            return null;
        }
    }
}