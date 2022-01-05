<?php

namespace App\classe;

use VekaServer\DiscordLog\DiscordLog;

class Hack
{

    /**
     * si nous utilisons le server http de PHP
     * ont utilise le hack pour les documents static
     * pour les autres server http il faut configurer le vhost correctement
     */
    public static function phpBuilInServerHttp($public_directory){
        if (php_sapi_name() != 'cli-server') {
            return;
        }

        $public_directory = realpath($public_directory);
        $url = strtok($_SERVER["REQUEST_URI"],'?');
        $file_path = $public_directory.DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, explode('/', $url));
        $file_path = realpath($file_path);
        $request_uri = substr( $file_path, strlen($public_directory));

        if(substr( $request_uri, 0,6) !== DIRECTORY_SEPARATOR.'asset'){
            return ;
        }

        if(!is_file($file_path))
            return ;
        $path_parts = pathinfo($file_path);

        switch($path_parts["extension"]){

            case 'css':
                $ct = 'text/css';
                break;

            default:
                $ct = 'text/plain';
                break;

        }

        header('Content-type: '.$ct);
        readfile($file_path);
        die();
    }

}