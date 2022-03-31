<?php
namespace App\controller;

use VekaServer\Framework\Log;

class JsErrorHandler extends Controller
{

    /** @var string $menu menu gauche actif */
    protected static $menu = 'dashboard';

    /**
     * @throws \Exception
     */
    public function catchJsError()
    {
        try {
            $error = json_decode($_POST['error']);

            if(empty($error)){
                throw new \Exception("rrr");
            }

            Log::error('ERREUR JS ', ['error' => $error]);
        }catch (\Exception $e){
            $error = $_POST['error'] ?? '';
            Log::error('ERROR JS '.PHP_EOL.$error);
        }
    }

}