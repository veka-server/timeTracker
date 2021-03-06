<?php
namespace App\controller;

use App\model\Utilisateur;

class Login extends Controller
{

    public function show_page(){

        if(\App\classe\Utilisateur::isConnected()){
            header('Location: /');
            die();
        }

        $params = [
            'surcharge_template' => 'login/template_login.twig'
        ];

        return $this->show($params);
    }

    public function login(){

        $rs = Utilisateur::getByEmail($_POST['email']??'');
        if( empty($rs) || \App\classe\Utilisateur::verifyHash($_POST['password'] ?? '', $rs[0]['password']) === false){
            return $this->show_page();
        }

        $_SESSION['utilisateur'] = $rs[0];
        header('Location: /');
        die();
    }

    public function logout(){
        session_destroy();
        header('Location: /login');
        die();
    }
}