<?php
/**
 * A utiliser avec le classe de rooter de veka-server/rooter
 * Ce fichier doit obligatoirement retourner un middleware
 */

$config = \VekaServer\Config\Config::getInstance();

return (new \App\classe\Rooter())

    // Page de login
    ->get($config->get('login_page'),function(){echo (new \App\controller\Login())->show_page();}, false, ['user_connected_require' => false])
    ->post($config->get('login_page'),function(){echo (new \App\controller\Login())->login();}, false, ['user_connected_require' => false])

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

    // Page de gestion des temps
    ->get('/temps',function(){echo (new \App\controller\Temps())->liste();})
    ->post('/temps/liste',function(){echo (new \App\controller\Temps())->ajax_liste();})
    ->post('/temps/delete',function(){echo (new \App\controller\Temps())->ajax_delete();})

    // Page dashboard
    ->get('/',function(){echo (new \App\controller\Dashboard())->show_page();})

    // Gestion des contraintes JS en AJAX sur les formulaires
    ->post('/js_check_input',function(){ echo (new \App\classe\Contrainte())->ajax();})

    // add route for font awesome
    ->get('/fontawesome/(.+)',function($filename){\VekaServer\FontAwesome\Autoload::getFontByName($filename);}, false, ['user_connected_require' => false])

    // page de gestion des erreurs Javascript
    ->post('/error_js_caught',function(){(new \App\controller\JsErrorHandler())->catchJsError();}, false, ['user_connected_require' => false])

    /** Page d'erreur 500 @todo creer une page static serait plus judicieux */
    ->get('/500',function(){ echo 'Error 500 : custom page'; }, false, ['user_connected_require' => false])

;