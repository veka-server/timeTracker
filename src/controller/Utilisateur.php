<?php
namespace App\controller;

use App\exception\TableauException;
use VekaServer\Framework\Lang;
use VekaServer\Framework\Log;

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
        $this->tableau->setFullWidth(true); /* le tableau doit-il prendre toute la largeur disponible ? */

        /** definition des actions global */
        $this->tableau->setUrlListe('/utilisateur/liste'); /* obligatoire */
        $this->tableau->setUrlExport('/utilisateur/export');
        $this->tableau->setUrlNewRow('/utilisateur/add');

        /** definition des colonnes */
        $this->tableau->addColumn(['label' => Lang::get('nom'),'key' => 'nom', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('prenom'),'key' => 'prenom', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('email'),'key' => 'email', 'sort' => true]);
        $this->tableau->addColumn(['label' => Lang::get('date_creation'),'key' => 'date_creation', 'sort' => true]);

        /** ajout du bouton d'édition */
        $this->tableau->addAction([
            'url' => '/utilisateur/edit'
            , 'label' => Lang::get('editer')
            , 'confirmation_msg' => null /* si besoin d'une popin de confirmation */
            , 'icone' => '<i class="far fa-edit"></i>' /* html de l'icone */
            , 'couleur' => 'bleu' /* voir css */
        ]);

        /** ajout du bouton de suppression */
        $this->tableau->addAction([
            'url' => '/utilisateur/delete'
            , 'label' => Lang::get('supprimer')
            , 'confirmation_msg' => Lang::get('confirmation_suppression_utilisateur') /* si besoin d'une popin de confirmation */
            , 'icone' => '<i class="far fa-trash-alt"></i>' /* html de l'icone */
            , 'couleur' => 'rouge' /* voir css */
        ]);

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

    /** retourne le json a l'ajax de recuperation de l'export */
    public function ajax_export()
    {
        /** Algo de recuperation des données pour le tableau */
        return $this->getTableau()->setFonctionData(function($arrayForJson){

            /** recupere les données sans pagination */
            $utilisateurs = \App\model\Utilisateur::getAll();

            $csv = new \App\classe\CSV();
            $header = [
                'id_utilisateur' => Lang::get('export::column::id_utilisateur'),
                'nom' => Lang::get('export::column::nom'),
                'prenom' => Lang::get('export::column::prenom'),
                'telephone' => Lang::get('export::column::telephone'),
                'email' => Lang::get('export::column::email'),
                'date_creation' => Lang::get('export::column::date_creation'),
                'disable' => Lang::get('export::column::disable'),
                'lang' => Lang::get('export::column::lang'),
                'timezone' => Lang::get('export::column::timezone')
            ];

            /** generation du code html du tableau */
            $arrayForJson['header'] = $csv::HEADER;
            $arrayForJson['text'] =  $csv->arrayToContent($utilisateurs, $header);
            $arrayForJson['filename'] = 'export_utilisateur.csv';

            return $arrayForJson;
        }, false);
    }

    /** retourne le json a l'ajax de recuperation de la suppression */
    public function ajax_delete()
    {
        /** Algo de recuperation des données pour le tableau */
        return $this->getTableau()->setFonctionData(function($arrayForJson){

            $validation = new \App\model\Validation($_POST);
            $validation->addField('id_utilisateur', ['type' => 'numeric', 'required' => true]);

            if($validation->numeric('id_utilisateur')){
                throw new TableauException(Lang::get('validation::id_utilisateur'));
            }

            /** recupere les données sans pagination */
//            \App\model\Utilisateur::delete($validation->get['id_utilisateur']);

            return $arrayForJson;
        }, false);
    }

}