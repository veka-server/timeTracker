<?php

namespace App\classe;

class Git
{

    /** recuperation de la version git */
    public static function getVersion(){
        exec('git describe --always',$version_mini_hash);
        if(isset($version_mini_hash[0])){
            $version = $version_mini_hash[0];
        }
        return $version ?? '';
    }

}