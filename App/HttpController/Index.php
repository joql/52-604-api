<?php
/**
 * Created by PhpStorm.
 * User: Joql
 * Date: 2018/10/18
 * Time: 18:01
                 */

namespace App\HttpController;


use App\Model\Data;

use App\Model\Wb;
use EasySwoole\Config;
use think\Validate;


class Index extends Base
{

    private $redis;
    function onRequest($action): ?bool
    {
        parent::onRequest($action); // TODO: Change the autogenerated stub
        $conf = Config::getInstance()->getConf('REDIS');

        $this->redis = new \Redis();
        $redis_result= $this->redis->connect($conf['host'],$conf['port'],3);
        $this->redis->auth($conf['auth']);
        $this->redis->setOption(\Redis::OPT_PREFIX,'soft_');
        if($redis_result!=true){
            die('redis连接失败');
        }
        return true;
    }


    public function index(){
        \EasySwoole\Core\Component\Logger::getInstance()->log('test api '.$this->request()->getRequestParam('type').'  '.$this->request()->getRequestParam('result') , 'testapi');
        return true;
    }

    /**
     * use for:
     * param:
     * auth: Joql
     * @return bool|void
     * date:2018-10-19 14:07
     */
    public function getData(){

        $validate = Validate::make([
            'id' => 'require',
            'name' => 'require',
        ]);

        if(!$validate->check($this->request()->getRequestParam())){
            $this->response()->write('cc');
            return false;
        }
        $id = $this->request()->getRequestParam('id');
        $name = $this->request()->getRequestParam('name');
        if(!in_array($name,array(
            'nr',
            'time',
            'qq',
        ))){
            $this->response()->write('cc');
            return false;
        }
        $r = $this->redis->get('data_'.$id.'-'.$name);
        if(!empty($r)){
            $this->response()->write($r);
            return true;

        }

        //mysql池获取对象
        if(!$this->initDb()){
            $this->response()->write('cc');
            return;
        }


        $r = $this->_db->where('id',$id)->getValue('soft_data',$name);

        if(empty($r)){
            $this->response()->write('cc');
        }else{
            $this->redis->set('data_'.$id.'-'.$name,$r,5);
            $this->response()->write($r);
        }
        return;
    }

    public function getWb(){

        $validate = Validate::make([
            'id' => 'require',
            'name' => 'require',
        ]);

        if(!$validate->check($this->request()->getRequestParam())){
            $this->response()->write('cc');
            return false;
        }
        $id = $this->request()->getRequestParam('id');
        $name = $this->request()->getRequestParam('name');
        if(!in_array($name,array(
            'name',
            'pwd',
        ))){
            $this->response()->write('cc');
            return false;
        }

        $r = $this->redis->get('wb'.$id.'-'.$name);
        if(!empty($r)){
            $this->response()->write($r);
            return true;

        }
        //mysql池获取对象
        if(!$this->initDb()){
            return;
        }

        $r = $this->_db->where('id',$id)->getValue('soft_wb',$name);
        if(empty($r)){
            $this->redis->set('wb_'.$id.'-'.$name,$r,5);
            $this->response()->write('cc');
        }else{
            $this->response()->write($r);
        }
        return;
    }

    public function state(){
        if($this->initRedis()){
            $this->response()->write('cc');
            return;
        }

        $r = $this->_redis->exec('get', 'state');
        if(empty($r)){
            $this->response()->write(-1);
            return;
        }else{
            $this->_redis->exec('set', 'state', 0);
            $this->response()->write($r);
            return;
        }
    }
}