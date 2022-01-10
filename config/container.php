<?php
/**
 * Rassemble la liste des librairie necessaire au framework
 */

$config = \VekaServer\Config\Config::getInstance();

return [

    /**
     * Moteur de template qui doit étendre VekaServer\Interfaces
     */
    "Renderer" => new \VekaServer\TwigRenderer\TwigRenderer(
        __DIR__.'/../src/view/',
        false /* __DIR__.'\../cache/' */
    )

    /**
     * Moteur de Bdd qui doit étendre VekaServer\Interfaces
     */
    ,"Bdd" => new \VekaServer\BddMysql\Bdd([
        'host' => $config->get('db_host')
        ,'port' => $config->get('db_port')
        ,'login' => $config->get('db_user')
        ,'password' => $config->get('db_pass')
        ,'dbname' => $config->get('db_name')
        ,'charset' => $config->get('db_charset')
    ])

    ,"DebugBar" => new DebugBar\StandardDebugBar()

];