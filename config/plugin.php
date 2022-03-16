<?php

/**
 * Ajouter ici la liste exaustive des plugin du framework
 * chaque plugin doit avoir une classe Autoload quii implements l'interface VekaServer\Interfaces\PluginInterface
 */

return [

    /**
     *
     * @todo tout ce qui a un numero de version doit utiliser un middleware de cache centralisé
     * @todo ou le router ????
     */

    /** @todo add fontawesome,  */
    /** @todo add css button commun a form et a popin    --- button require fontawesom */

    /** @todo add css button --- button require fontawesom */

    /** @todo dissocier js error handler */

    /** @todo plugin ajout support TRADUCTION */
    /** @todo plugin ajout support MIGRATION */

    /** plugin de gestion des formulaires, tableaux, popin, pagination */
    \VekaServer\TableForm\Autoload::class

];

