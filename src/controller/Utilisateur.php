<?php
namespace App\controller;

use App\exception\TableauException;

class Utilisateur extends Controller
{

    private $tableau = null;

    /** definie la structure du tableau a afficher a l'écran */
    public function getTableau():Tableau
    {
        if(!empty($this->tableau)){
            return $this->tableau;
        }

        $this->tableau = new Tableau();
        $this->tableau->setUrlListe('/utilisateur/liste');
        $this->tableau->addColumn(['label' => 'Nom','key' => 'nom']);
        $this->tableau->addColumn(['label' => 'Prenom','key' => 'prenom']);
        $this->tableau->addColumn(['label' => 'Email','key' => 'email']);
        $this->tableau->addColumn(['label' => 'Date de création','key' => 'date_creation']);
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
            ,'titre' => 'Gestion des utilisateurs'
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

//            throw new TableauException('echec volontaire de recup des données');

            /** ajoute les données aux tableau */
            $this->getTableau()->setData($utilisateurs);

            /** generation du code html du tableau */
            $arrayForJson['html'] = $this->getTableau()->getHtmlData();

            return $arrayForJson;
        });
    }

}