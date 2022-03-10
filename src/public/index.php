<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

// utilisation du loader de composer
require 'vendor/autoload.php';

try{

    class MyApp extends \VekaServer\Framework\App {

        /**
         * Cette methode sera executer avant le router et le dispatcher
         * @param ServerRequestInterface $request
         * @return mixed
         * @throws Exception
         */
        public function before_router(ServerRequestInterface $request)
        {
            // TODO: Implement before_router() method.
            return ;
        }

        /**
         * Cette methode sera executer apres le router mais avant l'affichage
         * @param ServerRequestInterface $request
         * @param ResponseInterface $response
         * @return mixed
         */
        public function after_router(ServerRequestInterface $request,ResponseInterface $response)
        {
            // TODO: Implement after_router() method.
            return ;
        }

    }

    new MyApp(__DIR__.('/../../'));

}catch (\Throwable $e){
    /**
     * En cas de crash avant la mise en place des middlewares ont arrive ici
     * Dans se cas on essai plusieurs solution pour envoyer l'erreur
     * En prod l'on tombe sur une erreur 500 non custom, pas d'autre choix
     * @todo creer une page 500 en static pour la prod
     */
    \App\classe\ErrorCatch::showError($e);
}
