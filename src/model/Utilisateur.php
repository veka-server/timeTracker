<?php
namespace App\model;

class Utilisateur extends Model
{

    public static function getAll(){
        $sql = 'SELECT id_utilisateur,
                    nom,
                    prenom,
                    telephone,
                    email,
                    date_creation,
                    disable,
                    lang,
                    timezone FROM utilisateurs';
        return self::exec_pagination($sql);
    }

    public static function getByEmail($email){
        $sql = 'SELECT * FROM utilisateurs WHERE email = :email';
        return self::exec($sql, ['s-email' => $email]);
    }

    public static function getByID($id_utilisateur){
        $sql = 'SELECT * FROM utilisateurs WHERE id_utilisateur = :id_utilisateur';
        return self::exec($sql, ['i-id_utilisateur' => $id_utilisateur]);
    }

    public static function add(array $values){

        $list_fields=[];
        $list_fields_params=[];
        $list_values=[];
        foreach ($values as $key => $value){
            $list_fields[] = $key;
            $list_fields_params[] = ':'.$key;
            $list_values['s-'.$key] = $value;
        }

        $sql = 'INSERT INTO utilisateurs ('.implode(',',$list_fields).') VALUES ('.implode(',',$list_fields_params).') ';
        return self::exec($sql, $list_values);
    }

    public static function delete($id_utilisateur){
        $sql = 'DELETE FROM utilisateurs WHERE id_utilisateur = :id_utilisateur';
        self::exec($sql, ['id_utilisateur' => $id_utilisateur]);
    }

    public static function update($id_utilisateur, $values){
        $list_fields=[];
        $list_values=[];
        foreach ($values as $key => $value){
            $list_fields[] = $key.' = :'.$key;
            $list_values['s-'.$key] = $value;
        }

        $list_values['i-id_utilisateur'] = $id_utilisateur;

        $sql = 'UPDATE utilisateurs SET '.implode(',',$list_fields).' WHERE id_utilisateur = :id_utilisateur';
        self::exec($sql, $list_values);
    }

}