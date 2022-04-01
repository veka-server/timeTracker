<?php

namespace App\model;

class Temps extends Model
{

    public static function getAll(){
        $sql = 'SELECT id_temps
                        , tache
                        , projet
                        , client
                        , temps FROM temps';
        return self::exec_pagination($sql);
    }

    public static function delete($id_temps){
        $sql = 'DELETE FROM temps WHERE id_temps = :id_temps';
        self::exec($sql, ['id_temps' => $id_temps]);
    }
}