<?php

namespace App\classe;

use App\exception\ClientException;
use App\exception\ValidationException;
use VekaServer\Framework\Lang;
use VekaServer\Framework\Log;

class Contrainte
{

    public function __construct(){
    }

    public function ajax() :string
    {
        $retour = [];
        try{

            $methode = base64_decode($_POST['check'] ?? '');
            if (method_exists(__CLASS__, $methode) === false) {
                throw new \Exception('skiiip');
            }

            call_user_func(array(__CLASS__, $methode));

            $retour['success'] = true;
            $retour['contrainte_failed'] = false;
            $retour['contrainte_msg'] = '';
        }catch (ValidationException $e){
            /** ici le traitement est toujours en success mais le champ doit passer en error */
            $retour['success'] = true;
            $retour['contrainte_failed'] = true;
            $retour['contrainte_msg'] = $e->getMessage();
        }catch (ClientException $e){
            $retour['success'] = false;
            $retour['error_msg'] = $e->getMessage();
        }catch (\Exception $e){
            Log::error($e);
            $retour['success'] = false;
            $retour['error_msg'] = Lang::get('erreur_generic');
        }

        return json_encode( $retour);
    }

    public static function check_email_doublon(){
        $is_email_already_exist = \App\model\Utilisateur::isEmailAlreadyExist($_POST['email'] ?? null, $_POST['id_utilisateur'] ?? null);
        if($is_email_already_exist === true){
            throw new ValidationException(Lang::get('validation::check_email_doublon::email'));
        }
    }

}