<?php

$root_path = dirname(__DIR__, 2) ;

// utilisation du loader de composer
require $root_path.'/vendor/autoload.php';

new class($root_path) extends \VekaServer\Framework\Console {

    public function run($params){

        $migration = new \App\classe\Migration($this->root_path.'/src/migration/');

        switch($params['direction'] ?? ''){

            case 'upgrade' :
                $migration->upgrade();
                break;

            case 'downgrade' :
                $migration->downgrade();
                break;

            default :
                echo 'parametre direction manquant'.PHP_EOL;
                echo 'Exemple :'.PHP_EOL;
                echo 'php '.basename($_SERVER['SCRIPT_NAME']).' direction=upgrade'.PHP_EOL;
                echo 'ou'.PHP_EOL;
                echo 'php '.basename($_SERVER['SCRIPT_NAME']).' direction=downgrade'.PHP_EOL;
                break;
        }

    }

};