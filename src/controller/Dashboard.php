<?php
namespace App\controller;

use App\model\Utilisateur;

class Dashboard extends Controller
{

    /**
     * @throws \Exception
     */
    public function show_page(): string
    {

        $rs = Utilisateur::getAllUtilisateur();
        $msg = 'connecté : '.$rs[0]['nom'];

        $params = [
            'content' => $msg
            ,'titre' => 'titre de la page en cours'
        ];

        return $this->show($params);
    }

}