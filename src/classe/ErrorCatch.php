<?php

namespace App\classe;

use VekaServer\Config\Config;

class ErrorCatch
{

    /**  */
    public static function showError($e){

        /** envoi d'un discord si possible */
        if(class_exists('\\VekaServer\\DiscordLog\\DiscordLog') && !empty(\VekaServer\DiscordLog\DiscordLog::$defaut_chanel)) {
            \VekaServer\DiscordLog\DiscordLog::sendLog($e);
        }

        /** affichage si en DEV */
        if(Config::getInstance()->get('ENV') == 'DEV') {

            /** si whoops existe alors on l'utilise */
            if(class_exists('\\Whoops\\Run')) {
                $whoops = new \Whoops\Run;
                $handle = new \Whoops\Handler\PrettyPageHandler;
                $whoops->pushHandler($handle);
                $whoops->register();
            }
            /** sinon on affiche directement le dump */
            else {
                var_dump($e);
                die();
            }

        }

        /** Exception pour les autres cas (PROD) */
        throw $e;
    }

}