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

        $sql = 'CREATE TABLE IF NOT EXISTS utilisateurs (
                    id_utilisateur integer NOT NULL AUTO_INCREMENT,
                    nom character varying(64) NOT NULL,
                    prenom character varying(64) NOT NULL,
                    telephone character varying(30),
                    email character varying(128) NOT NULL UNIQUE,
                    password character varying(60) NOT NULL,
                    date_creation datetime DEFAULT now() NOT NULL,
                    disable boolean DEFAULT false NOT NULL,
                    lang character varying(8) DEFAULT \'fr\',
                    timezone character varying(255) DEFAULT \'Europe/Paris\',
                    PRIMARY KEY (id_utilisateur)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;';
        self::exec($sql);

        $sql = 'CREATE TABLE IF NOT EXISTS permissions (
                    id_permission integer NOT NULL AUTO_INCREMENT,
                    title char(64) NOT NULL UNIQUE,
                    descritpion text NOT NULL,
                    PRIMARY KEY  (id_permission)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;';
        self::exec($sql);

        $sql = 'CREATE TABLE IF NOT EXISTS roles (
                  id_role integer NOT NULL AUTO_INCREMENT,
                  title char(64) NOT NULL UNIQUE,
                  descritpion text NOT NULL,
                  PRIMARY KEY  (id_role)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;';
        self::exec($sql);

        $sql = 'CREATE TABLE IF NOT EXISTS utilisateurs__roles (
                  id_utilisateur integer NOT NULL,
                  id_role integer NOT NULL,
                  date_ajout datetime DEFAULT now() NOT NULL,
                  PRIMARY KEY  (id_utilisateur, id_role),
                  FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur),
                  FOREIGN KEY (id_role) REFERENCES roles(id_role)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;';
        self::exec($sql);

        $sql = 'CREATE TABLE IF NOT EXISTS roles__roles (
                  id_role_receive integer NOT NULL,
                  id_role_give integer NOT NULL,
                  PRIMARY KEY  (id_role_receive, id_role_give),
                  FOREIGN KEY (id_role_receive) REFERENCES roles(id_role),
                  FOREIGN KEY (id_role_give) REFERENCES roles(id_role)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;';
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
        $pass = \App\classe\Utilisateur::hash('0000');
        $sql = 'INSERT INTO utilisateurs (nom, prenom, email, password) VALUES ( \'dupond\', \'nicolas\', \'test@test.fr\', \''.$pass.'\')';
        self::exec($sql);

        for ($i=0;$i<=150; $i++){
            $pass = \App\classe\Utilisateur::hash('0000'.$i);
            $sql = 'INSERT INTO utilisateurs (nom, prenom, email, password) VALUES ( \'dupond\', \'nicolas '.$i.'\', \'test'.$i.'@test.fr\', \''.$pass.'\')';
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
        $sql = 'DROP TABLE roles__roles ;';
        self::exec($sql);

        $sql = 'DROP TABLE utilisateurs__roles ;';
        self::exec($sql);

        $sql = 'DROP TABLE roles ;';
        self::exec($sql);

        $sql = 'DROP TABLE permissions ;';
        self::exec($sql);

        $sql = 'DROP TABLE utilisateurs ;';
        self::exec($sql);
    }
};