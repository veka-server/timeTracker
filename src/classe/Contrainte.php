<?php

namespace App\classe;

use VekaServer\Framework\Lang;
use VekaServer\TableForm\Exception\ValidationException;

class Contrainte Extends \VekaServer\TableForm\Contrainte
{
    public static function check_email_doublon(){
        $is_email_already_exist = \App\model\Utilisateur::isEmailAlreadyExist($_POST['email'] ?? null, $_POST['id_utilisateur'] ?? null);
        if($is_email_already_exist === true){
            throw new ValidationException(Lang::get('validation::check_email_doublon::email'));
        }
    }
}