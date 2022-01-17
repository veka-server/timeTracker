<?php

namespace App\classe;

class Migration
{

    private $path_folder_migration;

    public function __construct($path_folder_migration){
        $this->path_folder_migration = $path_folder_migration;
    }

    public function upgrade(){

        /** creer la table de migration si elle n'existe pas encore */

        /** recuperer le numero de la derniere migration */

        /** recuperer les migrations qui n'ont pas encore été executé */

    }

    public function downgrade(){

        /** creer la table de migration si elle n'existe pas encore */

        /** recuperer le numero de la derniere migration */

        /** recuperer les migrations qui n'ont pas encore été executé */

    }

}