<?php
/**
 * Rassemble la liste des librairie necessaire au framework
 */

$config = \VekaServer\Config\Config::getInstance();

$bdd = new \VekaServer\BddMysql\Bdd([
    'host' => $config->get('db_host')
    ,'port' => $config->get('db_port')
    ,'login' => $config->get('db_user')
    ,'password' => $config->get('db_pass')
    ,'dbname' => $config->get('db_name')
    ,'charset' => $config->get('db_charset')
]);

return [

    /**
     * Moteur de template qui doit étendre VekaServer\Interfaces
     */
    "Renderer" => new \VekaServer\TwigRenderer\TwigRenderer(
        \VekaServer\Framework\Plugin::getInstance()->getAllViewFolders(),
        false /* $config->get('ROOT_DIR').'\../cache/' */
        ,[\VekaServer\Framework\Lang::class, 'get'] /* methode du framework appelé pour les traductions */
    )

    /**
     * Moteur de Bdd qui doit étendre VekaServer\Interfaces
     */
    ,"Bdd" => $bdd

    /**
     * Gestionnaire de LOG PSR-3
     */
    ,"Log" => new \VekaServer\Discord\Discord(
        $config->get('DISCORD_CHANNEL')
        ,$config->get('DISCORD_APP_NAME')
    )

    /**
     * Gestionnaire de traductions
     */
    ,"Lang" => new \VekaServer\Lang\Lang($config->get('DEFAULT_LANG'), $bdd)

    ,"DebugBar" => new DebugBar\StandardDebugBar()

];