<?php
namespace App\controller;

use VekaServer\Config\Config;

class Controller extends \VekaServer\Framework\Controller
{

    public function show($params=[]){

        $config = [
            'content' => $params['content'] ?? ''
            ,'titre' => $params['titre'] ?? ''
            ,'app_name' => Config::getInstance()->get('APP_NAME')
            ,'version' => Config::getInstance()->get('VERSION')
        ];

        $template = $params['surcharge_template'] ??'common/template.twig';

        return $this->getView($template,$config);
    }

}