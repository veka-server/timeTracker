<?php
namespace App\model;

use VekaServer\Container\Container;

class Model extends \VekaServer\Framework\Model
{
    public static function exec(string $sql, array $data = array()){
        $debugbar = Container::getInstance()->get('DebugBar');
        $debugbar['time']->startMeasure('sql request', debug_backtrace()[1]['class'].'::'.debug_backtrace()[1]['function']);
        $sql = 'SELECT * FROM utilisateur';
        $rs = parent::exec($sql);
        $debugbar['time']->stopMeasure('sql request');
        return $rs;
    }
}