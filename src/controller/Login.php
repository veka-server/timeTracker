<?php
namespace App\controller;

class Login extends Controller
{

    public function show_page(){

        $params = [
            'surcharge_template' => 'common/template_login.twig'
        ];

        return $this->show($params);
    }

}