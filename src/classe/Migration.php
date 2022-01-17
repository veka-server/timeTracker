<?php

namespace App\classe;

use VekaServer\Framework\Model;

class Migration
{

    private $path_folder_migration;

    public function __construct($path_folder_migration){
        $this->path_folder_migration = $path_folder_migration;
    }

    public function upgrade(){

        /** creer la table de migration si elle n'existe pas encore */
        $sql = 'CREATE TABLE migration (
                    id_migration integer NOT NULL AUTO_INCREMENT,
                    filename text NOT NULL,
                    date_upgrade datetime DEFAULT NOW(),
                    PRIMARY KEY(id_migration)
                );';
        Model::exec($sql);

        /** recuperer les migrations qui n'ont pas encore été executé */

    }

    public function downgrade(){
        /** @todo a faire */
    }

}