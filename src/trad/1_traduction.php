<?php

use App\model\Model;
use VekaServer\Framework\Lang;

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

        /** creer la table de traduction si elle n'existe pas encore */
        $sql = 'CREATE TABLE IF NOT EXISTS traduction__key (
                    id_traduction_key int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    uniq_key varchar(128) NOT NULL UNIQUE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;';
        self::exec($sql);

        $sql = 'CREATE TABLE IF NOT EXISTS traduction__lang (
                    id_traduction_lang int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    lang varchar(32) NOT NULL UNIQUE
                )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;';
        self::exec($sql);

        $sql = 'CREATE TABLE IF NOT EXISTS traduction__value (
                    id_traduction_key int NOT NULL,
                    id_traduction_lang int NOT NULL,
                    trad text NOT NULL,
                    PRIMARY KEY(id_traduction_key, id_traduction_lang),
                    FOREIGN KEY (id_traduction_key) REFERENCES traduction__key(id_traduction_key),
                    FOREIGN KEY (id_traduction_lang) REFERENCES traduction__lang(id_traduction_lang)
                )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;';
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
        /** Creer les langues */
        Lang::addLang('FR');
        Lang::addLang('EN');

        /** ajout des traductions */
        Lang::set('user_management', 'FR', 'Gestion des utilisateurs');
        Lang::set('user_management', 'EN', 'User management');

        Lang::set('nb_result_per_page', 'FR', 'nb / page');
        Lang::set('nb_result_per_page', 'EN', 'nb / page');

        Lang::set('nom', 'FR', 'nom');
        Lang::set('nom', 'EN', 'last name');

        Lang::set('prenom', 'FR', 'prénom');
        Lang::set('prenom', 'EN', 'first name');

        Lang::set('email', 'FR', 'email');
        Lang::set('email', 'EN', 'email');

        Lang::set('date_creation', 'FR', 'date de création');
        Lang::set('date_creation', 'EN', 'added date');
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
        $sql = 'DROP TABLE traduction__key ;';
        self::exec($sql);

        $sql = 'DROP TABLE traduction__lang ;';
        self::exec($sql);

        $sql = 'DROP TABLE traduction__value ;';
        self::exec($sql);
    }
};