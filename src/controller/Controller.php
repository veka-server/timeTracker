<?php
namespace App\controller;

use VekaServer\Config\Config;

class Controller extends \VekaServer\Framework\Controller
{
    /** @var string $menu menu gauche actif */
    protected static $menu = '';

    public function show($params=[]){

        $config = [
            'content' => $params['content'] ?? ''
            ,'titre' => $params['titre'] ?? ''
            ,'app_name' => Config::getInstance()->get('APP_NAME')
            ,'version' => Config::getInstance()->get('VERSION')
            ,'active_menu' => static::$menu
        ];

        $template = $params['surcharge_template'] ??'common/template/template.twig';

        return $this->getView($template,$config);
    }

}