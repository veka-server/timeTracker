<?php
namespace App\model;

class Utilisateur extends Model
{

    public static function getAllUtilisateur(){
        $sql = 'SELECT * FROM utilisateur';
        return self::exec($sql);
    }

    public static function getUtilisateurByID($id_utilisateur){
        $sql = 'SELECT * FROM utilisateur WHERE id_utilisateur = :id_utilisateur';
        return self::exec($sql, ['id_utilisateur' => $id_utilisateur]);
    }

}