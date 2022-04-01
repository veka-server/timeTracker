<?php

namespace App\classe;

class Rooter extends \VekaServer\Rooter\Rooter
{

    public function optionsRooter($options){

        // required user connected par default
        if(($options['user_connected_require'] ?? TRUE) == TRUE){
            $login_page = \VekaServer\Config\Config::getInstance()->get('login_page');
            \App\classe\Utilisateur::RedirectIfNotConnected($login_page);
        }

    }

    public function surchargeCallback($callback, $options): \Closure
    {
        return function() use($callback, $options){
            $this->optionsRooter($options);
            call_user_func_array($callback, func_get_args());
        };
    }

    public function get($regex, $handler, $forceString = false, $options = []): Rooter|static
    {
        parent::get($regex, $this->surchargeCallback($handler, $options), $forceString);
        return $this;
    }

    public function getAndPost($regex, $handler, $forceString = false, $options = []): Rooter|static
    {
        parent::getAndPost($regex, $this->surchargeCallback($handler, $options), $forceString);
        return $this;
    }

    public function post($regex, $handler, $forceString = false, $options = []): Rooter|static
    {
        parent::post($regex, $this->surchargeCallback($handler, $options), $forceString);
        return $this;
    }

}