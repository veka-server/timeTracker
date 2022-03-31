<?php
namespace App\model;

use VekaServer\Container\Container;

class Model extends \VekaServer\TableForm\Model
{
    public static function exec(string $sql, array $data = array()){
        $debugbar = Container::getInstance()->get('DebugBar');
        $debugbar['time']->startMeasure('sql request', debug_backtrace()[1]['class'].'::'.debug_backtrace()[1]['function']);
        $rs = parent::exec($sql, $data);
        $debugbar['time']->stopMeasure('sql request');
        return $rs;
    }
}