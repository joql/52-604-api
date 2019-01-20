<?php
/**
 * Created by PhpStorm.
 * User: Joql
 * Date: 2018/10/24
 * Time: 14:00
 */

namespace App\Model;


class Wb extends Model
{
    protected $table = 'wb';

    public function __construct($db){
        parent::__construct($db);
        $this->table = $this->_prefix.$this->table;
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method
        $this->$name($this->table, ...$arguments);
    }


}