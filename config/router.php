<?php
/**
 * A utiliser avec le classe de rooter de veka-server/rooter
 * Ce fichier doit obligatoirement retourner un middleware
 */

return (new \VekaServer\Rooter\Rooter())

    // Page de login
    ->getAndPost('/login',function(){echo (new \App\controller\Login())->show_page();})

    // Page dashboard
    ->get('/',function(){echo (new \App\controller\Dashboard())->show_page();})

    /** Page d'erreur 500 @todo creer une page static serait plus judicieux */
    ->get('/500',function(){ echo 'Eroor 500 : custom page'; })

;