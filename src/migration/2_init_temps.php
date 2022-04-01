<?php

use App\model\Model;

return new class() extends Model implements \VekaServer\Interfaces\MigrationInterface {

    /**
     * EXECUTER EN PREMIER
     * a utiliser pour les methode d'autocommit
     * Create / ALTER (sans breaking change)
     *
     * SONT DOWNGRADE ASSOCIÉ DOIT ETRE : downgrade_nettoyage
     */
    public function upgrade_creation()
    {

        $sql = 'CREATE TABLE IF NOT EXISTS temps (
                    id_temps integer NOT NULL AUTO_INCREMENT,
                    tache character varying(255) NOT NULL,
                    projet character varying(255),
                    client character varying(255),
                    temps character varying(20) NOT NULL,
                    PRIMARY KEY (id_temps)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;';
        self::exec($sql);

    }

    /**
     * EXECUTER EN SECOND
     * a utiliser pour les methodes supportant les transactions
     * INSERT / UPDATE / DELETE / SELECT
     *
     * SONT DOWNGRADE ASSOCIÉ DOIT ETRE : downgrade_data
     */
    public function upgrade_data()
    {
        for ($i=0;$i<=150; $i++){
            $temps = rand(150,55555);
            $sql = 'INSERT INTO temps (tache, projet, client, temps) VALUES ( \'ma tache\', \'Lease16\', \'VEOLIA\', \''.$temps.'\')';
            self::exec($sql);
        }
    }

    /**
     * EXECUTER EN TROISIEME
     * a utiliser pour les methode d'autocommit IRREVERSIBLE
     * Create / DROP / TRUNCATE / ALTER (avec breaking change)
     *
     * SONT DOWNGRADE ASSOCIÉ DOIT ETRE : downgrade_creation
     */
    public function upgrade_nettoyage()
    {
        // TODO: Implement upgrade_nettoyage() method.
    }

    /**
     * EXECUTR EN PREMIER
     * a utiliser pour les methode d'autocommit
     * Create / ALTER (sans breaking change)
     */
    public function downgrade_creation()
    {
        // TODO: Implement downgrade_creation() method.
    }

    /**
     * EXECUTER EN SECOND
     * a utiliser pour les methodes supportant les transactions
     * INSERT / UPDATE / DELETE / SELECT
     */
    public function downgrade_data()
    {
        // TODO: Implement downgrade_data() method.
    }

    /**
     * EXECUTER EN TROISIEME
     * a utiliser pour les methode d'autocommit IRREVERSIBLE
     * Create / DROP / TRUNCATE / ALTER (avec breaking change)
     */
    public function downgrade_nettoyage()
    {
        $sql = 'DROP TABLE temps ;';
        self::exec($sql);
    }
};