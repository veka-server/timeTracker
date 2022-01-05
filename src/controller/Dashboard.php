<?php
namespace App\controller;

use App\classe\test;

class Dashboard extends \App\Controller\Controller
{

    public function show_page(){

        $params = [
            'content' => 'hello world '.test::test()
            ,'titre' => 'titre de la page en cours'
        ];

        return $this->show($params);
    }

}