<?php
/**
 * Created by PhpStorm.
 * User: Joql
 * Date: 2018/10/23
 * Time: 15:51
 */
namespace App\Process;


use App\Model\Income;
use App\Model\UserSpacePerson;
use App\Model\WechatUser;
use App\Model\WechatUserSpace;
use EasySwoole\Config;
use EasySwoole\Core\Component\Pool\PoolManager;
use EasySwoole\Core\Swoole\Process\AbstractProcess;
use Swoole\Process;

class TimeTask extends AbstractProcess
{
    protected $db_pool;  //读写库连接池
    protected $db_slave_pool;  //读库连接池
    protected $_db;
    protected $_db_use = 0;  // 0 未使用  1：读写库使用中  2： 读库使用中
    protected $redis_pool;
    protected $_redis;
    protected $_redis_use = 0;

    public function run(Process $process)
    {
        // TODO: Implement run() method.

        $this->init();
        $this->addTick(1000*10,function (){
            var_dump('this is '.$this->getProcessName().' process tick time:'.time());
        });
    }
    public function onShutDown()
    {
        // TODO: Implement onShutDown() method.

        $this->db_pool->freeObj($this->_db);
        $this->redis_pool->freeObj($this->_redis);
    }
    public function onReceive(string $str, ...$args)
    {
        // TODO: Implement onReceive() method.
        var_dump('process rec'.$str);
    }

    /**
     * use for:init
     * auth: Joql
     * date:2018-10-23 16:11
     */
    public function init(){
        $this->db_pool = PoolManager::getInstance()->getPool('App\Utility\MysqlPool2');
        $this->redis_pool = PoolManager::getInstance()->getPool('App\Utility\RedisLssPool');
        $this->_db = $this->db_pool->getObj();
        $this->_redis = $this->redis_pool->getObj();
    }

    public function updateRankList(){
        $model_usp = new UserSpacePerson($this->_db);
        foreach ($model_usp->getMore([], 'id') as $v){
            $model_i = new Income($this->_db);
            $reward_rank_list = $model_i->getRandList($v['id']);
            foreach($reward_rank_list as $key => $value){
                $reward_rank_list[$key]['reward_money_sum'] = floor($value['reward_money_sum'] * 100) / 100;
                if($key == 0){
                    $reward_rank_list[$key]['rank'] = Config::getInstance()->getConf('WEBCONFIG.source_domain').'/Public/Home/Images/Rank/gold.png';
                }elseif($key == 1){
                    $reward_rank_list[$key]['rank'] = Config::getInstance()->getConf('WEBCONFIG.source_domain').'/Public/Home/Images/Rank/silver.png';
                }elseif($key == 2){
                    $reward_rank_list[$key]['rank'] = Config::getInstance()->getConf('WEBCONFIG.source_domain').'/Public/Home/Images/Rank/bronze.png';
                }else{
                    $reward_rank_list[$key]['rank'] = $key+1;
                }

                if(mb_strlen($value['from_client_name'],'utf-8') > 5){
                    $reward_rank_list[$key]['from_client_name'] = mb_substr($value['from_client_name'],0,3,'utf-8').'…';
                }
            }
            $model_wu = new WechatUser($this->_db);
            $model_wus = new WechatUserSpace($this->_db);
            $invite_rank_list = $model_wu->getRankList($v['id']);
            foreach($invite_rank_list as $key => $value){
                if($key == 0){
                    $invite_rank_list[$key]['rank'] = Config::getInstance()->getConf('WEBCONFIG.source_domain').'/Public/Home/Images/Rank/gold.png';
                }elseif($key == 1){
                    $invite_rank_list[$key]['rank'] = Config::getInstance()->getConf('WEBCONFIG.source_domain').'/Public/Home/Images/Rank/silver.png';
                }elseif($key == 2){
                    $invite_rank_list[$key]['rank'] = Config::getInstance()->getConf('WEBCONFIG.source_domain').'/Public/Home/Images/Rank/bronze.png';
                }else{
                    $invite_rank_list[$key]['rank'] = $key+1;
                }
                $row = $model_wus->get(array(
                    'openid'=>$value['inviter_openid']
                ), 'photo,name');
                if(empty($row)){
                    continue;
                }
                $invite_rank_list[$key]['client_photo'] = $row['photo'];
                if(mb_strlen($row['name'],'utf-8') > 5){
                    $invite_rank_list[$key]['from_client_name'] = mb_substr($row['name'],0,3,'utf-8').'…';
                }else{
                    $invite_rank_list[$key]['from_client_name'] = $row['name'];
                }
                $invite_rank_list[$key]['invite_count'] = $value['count(*)'];
            }

            //存入redis
            $data = serialize([
                'reward_rank' => $reward_rank_list,
                'invite_rank' => $invite_rank_list,
            ]);
            $this->_redis->exec('hset','');
        }
    }

}