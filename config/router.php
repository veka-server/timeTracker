<?php
/**
 * A utiliser avec le classe de rooter de veka-server/rooter
 * Ce fichier doit obligatoirement retourner un middleware
 */

use VekaServer\Config\Config;

return (new \VekaServer\Rooter\Rooter())

    // Page de login
    ->getAndPost('/login',function(){echo (new \App\controller\Login())->show_page();})

    // Page dashboard
    ->get('/',function(){echo (new \App\controller\Dashboard())->show_page();})

    // minifier css / js
    ->get('/css',function(){\App\classe\Minifier::getCss(Config::getInstance()->get('PUBLIC_DIR'));})
    ->get('/js',function(){\App\classe\Minifier::getJs(Config::getInstance()->get('PUBLIC_DIR'));})

    /** Page d'erreur 500 @todo creer une page static serait plus judicieux */
    ->get('/500',function(){ echo 'Eroor 500 : custom page'; })

;