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
    ->post('/utilisateur/export',function(){echo (new \App\controller\Utilisateur())->ajax_export();})
    ->post('/utilisateur/delete',function(){echo (new \App\controller\Utilisateur())->ajax_delete();})
    ->post('/utilisateur/add',function(){echo (new \App\controller\Utilisateur())->ajax_add();})
    ->post('/utilisateur/edit',function(){echo (new \App\controller\Utilisateur())->ajax_edit();})
    ->post('/utilisateur/save-edit',function(){echo (new \App\controller\Utilisateur())->ajax_save_edit();})
    ->post('/utilisateur/save-add',function(){echo (new \App\controller\Utilisateur())->ajax_save_add();})

    // Page dashboard
    ->get('/',function(){
        // required user connected
        \App\classe\Utilisateur::RedirectIfNotConnected(\VekaServer\Config\Config::getInstance()->get('login_page'));
        echo (new \App\controller\Dashboard())->show_page();
    })

    // Gestion des contraintes JS en AJAX sur les formulaires
    ->post('/js_check_input',function(){ echo (new \App\classe\Contrainte())->ajax();})

    // page de gestion des erreurs Javascript
    ->post('/error_js_caught',function(){(new \App\controller\JsErrorHandler())->catchJsError();})

    /** Page d'erreur 500 @todo creer une page static serait plus judicieux */
    ->get('/500',function(){ echo 'Error 500 : custom page'; })

;