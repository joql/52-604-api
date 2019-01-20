<?php
/**
 * Created by PhpStorm.
 * User: Joql
 * Date: 2018/10/12
 * Time: 17:17
 */

namespace App\Model;

use AlidnsDomain;
use EasySwoole\Core\Component\Logger;


class Model
{
    protected $_db;
    protected $_prefix = 'soft_';

    protected function __construct($db)
    {
        $this->_db = $db;
    }

    protected function insert($table, $data){
        return $this->_db
            ->insert($table, $data);
    }

    protected function get($table, $where = [], $coulums ='*', $order =[]){
        foreach ($where as $k=>$v){
            if(is_array($v) && ($key = key($v)) == "val"){
                $this->_db->where($k, $v['val'], $v['cond']);
            }else{
                $this->_db->where($k, $v);
            }
        }
        if($order){
            $this->_db->orderBy($order[0], $order[1]);
        }
        return $this->_db->getOne($table, $coulums);
    }

    protected function getMore($table, $where = [], $coulums ='*', $order =[]){
        foreach ($where as $k=>$v){
            if(is_array($v) && ($key = key($v)) == "val"){
                $this->_db->where($k, $v['val'], $v['cond']);
            }else{
                $this->_db->where($k, $v);
            }
        }
        if($order){
            $this->_db->orderBy($order[0], $order[1]);
        }
        return $this->_db->get($table, null, $coulums);
    }

    /** 获取字段值
     * use for:
     * auth: Joql
     * @param $table
     * @param array $where
     * @param string $coulums
     * @param array $order
     * @return mixed
     * date:2018-10-25 10:19
     */
    protected function getVal($table, $where = [], $coulums ='*', $order =[]){
        foreach ($where as $k=>$v){
            if(is_array($v) && ($key = key($v)) == "val"){
                $this->_db->where($k, $v['val'], $v['cond']);
            }else{
                $this->_db->where($k, $v);
            }
        }
        if($order){
            $this->_db->orderBy($order[0], $order[1]);
        }
        return $this->_db->getValue($table, $coulums);
    }

    /**
     * use for:获取一列
     * auth: Joql
     * @param $table
     * @param array $where
     * @param string $coulums
     * @param array $order
     * @return mixed
     * date:2018-10-25 10:33
     */
    protected function getColumn($table, $where = [], $coulums ='*', $order =[]){
        foreach ($where as $k=>$v){
            if(is_array($v) && ($key = key($v)) == "val"){
                $this->_db->where($k, $v['val'], $v['cond']);
            }else{
                $this->_db->where($k, $v);
            }
        }
        if($order){
            $this->_db->orderBy($order[0], $order[1]);
        }
        return $this->_db->getValue($table, $coulums, null);
    }

    /**
     * use for: 字段求和
     * auth: Joql
     * @param $table
     * @param array $where
     * @param string $coulums
     * @return mixed
     * date:2018-10-25 10:43
     */
    protected function getSum($table, $where = [], $coulums ='*'){
        foreach ($where as $k=>$v){
            if(is_array($v) && ($key = key($v)) == "val"){
                $this->_db->where($k, $v['val'], $v['cond']);
            }else{
                $this->_db->where($k, $v);
            }
        }
        return $this->_db->getValue($table, 'sum('.$coulums.')');
    }

    /**
     * use for:更新
     * auth: Joql
     * @param $table
     * @param array $where
     * @param $data
     * @return mixed
     * date:2018-10-25 17:38
     */
    protected function update($table, $where = [], $data, $limit =1){
        foreach ($where as $k=>$v){
            if(is_array($v) && ($key = key($v)) == "val"){
                $this->_db->where($k, $v['val'], $v['cond']);
            }else{
                $this->_db->where($k, $v);
            }
        }
        return $this->_db->update($table, $data, $limit);
    }

    protected function delete($table, $where = [], $num=1){
        foreach ($where as $k=>$v){
            $this->_db->where($k, $v);
        }
        return $this->_db->delete($table, $num);
    }

//****************************
//********  方法库
//****************************

    protected function parsingList($access_key_id, $access_key_secret, $args){
        //vendor("aliyun.AlidnsDomain");//引入类
        require_once __DIR__.'/../../vendor/aliyun/AlidnsDomain.php';
        $alidns_domain_obj = new AlidnsDomain();
        $result = $alidns_domain_obj->parsingList($access_key_id, $access_key_secret, $args);
        return $result;

    }


    /**
     * use for:生成随机字符串
     * auth: Joql
     * @param $number
     * @return string
     * date:2018-10-18 15:46
     */
    protected function strRandom($number){
        $char = "0,1,2,3,4,5,6,7,8,9,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z";
        $list = explode(",",$char);
        $len = count($list)-1;
        $authnum = "";
        for($i=0;$i<$number;$i++){
            $randnum = rand(0,$len);
            $authnum .= $list[$randnum];
        }
        return $authnum;
    }
}