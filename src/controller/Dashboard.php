<?php
namespace App\controller;

use VekaServer\Container\Container;

class Dashboard extends Controller
{

    /**
     * @throws \Exception
     */
    public function show_page(): string
    {

        /** @var \VekaServer\Interfaces\BddInterface $bdd Récupération de l'objet BDD */
        $bdd = Container::getInstance()->get('Bdd');

        $sql = 'SELECT * FROM utilisateur';
        $rs = $bdd->exec($sql, []);

        $msg = 'connecté : '.$rs[0]['nom'];

        $params = [
            'content' => $msg
            ,'titre' => 'titre de la page en cours'
        ];

        return $this->show($params);
    }

}