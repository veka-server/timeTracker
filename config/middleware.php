<?php
/**
 * Ce fichier doit obligatoirement retourner un un tableau avec en premier parametre un dispatcher et en second la request
 */

use VekaServer\Config\Config;

/**
 * Creation de la request (ServerRequestFactory) a partir de Nyholm
 */
$psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
$creator = new \Nyholm\Psr7Server\ServerRequestCreator($psr17Factory,$psr17Factory,$psr17Factory,$psr17Factory);

$tableau_middleware = [];

/** DebugBar */
if(Config::getInstance()->get('ENV') == 'DEV') {
    $debugbarRenderer = \VekaServer\Container\Container::getInstance()->get('DebugBar')->getJavascriptRenderer('/phpdebugbar');
    $middleware_phpbar = new PhpMiddleware\PhpDebugBar\PhpDebugBarMiddleware($debugbarRenderer, $psr17Factory, $psr17Factory);
    $tableau_middleware[] = $middleware_phpbar;
}

/** Redirection Erreur 500 */
$tableau_middleware[] = new VekaServer\RedirectErrorPage\RedirectErrorPage('/500');

/** Whoops */
if(Config::getInstance()->get('ENV') == 'DEV') {
    $middleware_whoops = new Middlewares\Whoops();
    $tableau_middleware[] = $middleware_whoops;
}

/** DiscordLog */
$tableau_middleware[] = new VekaServer\DiscordLog\DiscordLog(
    $psr17Factory
    ,Config::getInstance()->get('DISCORD_CHANNEL')
    ,Config::getInstance()->get('DISCORD_APP_NAME')
);

/** router */
$tableau_middleware[] = require_once('router.php');

$dispatcher = new Middlewares\Utils\Dispatcher($tableau_middleware);
$request = $creator->fromGlobals();

return [$dispatcher,$request];