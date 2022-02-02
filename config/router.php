<?php
/**
 * A utiliser avec le classe de rooter de veka-server/rooter
 * Ce fichier doit obligatoirement retourner un middleware
 */

$config = \VekaServer\Config\Config::getInstance();

return (new \VekaServer\Rooter\Rooter())

    // Page de login
    ->get($config->get('login_page'),function(){echo (new \App\controller\Login())->show_page();})
    ->post($config->get('login_page'),function(){echo (new \App\controller\Login())->login();})

    // Page de deconnexion
    ->get('/logout',function(){(new \App\controller\Login())->logout();})

    // Page de gestion des utilisateurs
    ->get('/utilisateur',function(){echo (new \App\controller\Utilisateur())->liste();})
    ->post('/utilisateur/liste',function(){echo (new \App\controller\Utilisateur())->ajax_liste();})

    // Page dashboard
    ->get('/',function(){
        // required user connected
        \App\classe\Utilisateur::RedirectIfNotConnected(\VekaServer\Config\Config::getInstance()->get('login_page'));
        echo (new \App\controller\Dashboard())->show_page();
    })

    /** Page d'erreur 500 @todo creer une page static serait plus judicieux */
    ->get('/500',function(){ echo 'Eroor 500 : custom page'; })

;