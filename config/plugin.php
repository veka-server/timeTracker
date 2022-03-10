<?php

/**
 * Ajouter ici la liste exaustive des plugin du framework
 * chaque plugin doit avoir une classe Autoload quii implements l'interface VekaServer\Interfaces\PluginInterface
 */

return [

    /** @todo add jquery,  */
    /** @todo add fontawesome,  */
    /** @todo dissocier popin */
    /** @todo ne plus avoir besoin de l'include du template de la popin dans le head */

    /** @todo plugin ajout support TRADUCTION */
    /** @todo plugin ajout support MIGRATION */

    /** plugin de gestion des formulaires, tableaux, popin, pagination */
    \VekaServer\TableForm\Autoload::class

];

