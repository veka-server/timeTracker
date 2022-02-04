<?php
namespace App\controller;

use VekaServer\Framework\Lang;

class Utilisateur extends Controller
{

    /** @var string $menu menu gauche actif */
    protected static $menu = 'utilisateur';

    private $tableau = null;

    /** definie la structure du tableau a afficher a l'écran */
    public function getTableau():Tableau
    {
        if(!empty($this->tableau)){
            return $this->tableau;
        }

        $this->tableau = new Tableau();
        $this->tableau->setUrlListe('/utilisateur/liste');
        $this->tableau->addColumn(['label' => Lang::get('nom'),'key' => 'nom', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('prenom'),'key' => 'prenom', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('email'),'key' => 'email', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('date_creation'),'key' => 'date_creation', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('date_creation1'),'key' => 'date_creation1', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('date_creation2'),'key' => 'date_creation2', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('date_creation3'),'key' => 'date_creation3', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('date_creation4'),'key' => 'date_creation4', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('date_creation5'),'key' => 'date_creation5', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('date_creation6'),'key' => 'date_creation6', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('date_creation7'),'key' => 'date_creation7', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('date_creation8'),'key' => 'date_creation8', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('date_creation9'),'key' => 'date_creation9', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('date_creation10'),'key' => 'date_creation10', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('date_creation11'),'key' => 'date_creation11', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('date_creation12'),'key' => 'date_creation12', 'sort' => true, 'filter' => true]);

        return $this->tableau;
    }

    /**
     * retourne le HTML de la page qui liste les utilisateurs
     * @throws \Exception
     */
    public function liste(): string
    {
        $params = [
            'content' => $this->getTableau()->getHtmlWithoutData()
            ,'titre' => Lang::get('user_management')
        ];

        return $this->show($params);
    }

    /** retourne le json a l'ajax de recuperation de données */
    public function ajax_liste()
    {
        /** Algo de recuperation des données pour le tableau */
        return $this->getTableau()->setFonctionData(function($arrayForJson){

            /** recupere les données brut */
            $utilisateurs = \App\model\Utilisateur::getAll();

            /** ajoute les données aux tableau */
            $this->getTableau()->setData($utilisateurs);

            /** generation du code html du tableau */
            $arrayForJson['html'] = $this->getTableau()->getHtmlData();

            return $arrayForJson;
        });
    }

}