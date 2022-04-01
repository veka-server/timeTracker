<?php
namespace App\controller;

use VekaServer\Framework\Lang;
use VekaServer\TableForm\Input;
use VekaServer\TableForm\Tableau;
use VekaServer\TableForm\Validation;

class Temps extends Controller
{

    /** @var string $menu menu gauche actif */
    protected static $menu = 'temps';

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
        $this->tableau->setUrlListe('/temps/liste'); /* obligatoire */

        /** definition des colonnes */
        $this->tableau->addColumn(['label' => Lang::get('tache'),'key' => 'tache', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('projet'),'key' => 'projet', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('client'),'key' => 'client', 'sort' => true, 'filter' => true]);
        $this->tableau->addColumn(['label' => Lang::get('temps'),'key' => 'temps', 'sort' => true, 'filter' => true]);

        /** ajout du bouton de suppression */
        $this->tableau->addAction([
            'url' => '/temps/delete'
            , 'label' => Lang::get('supprimer')
            , 'data_to_send' => ['id_temps'] /* liste des variable a envoyer lors de la requete */
            , 'confirmation_msg' => Lang::get('confirmation_suppression_time') /* si besoin d'une popin de confirmation */
            , 'icone' => '<i class="far fa-trash-alt"></i>' /* html de l'icone */
            , 'couleur' => 'rouge' /* voir css */
            , 'type_popin_retour' => 'alert' /* dialog ou alert, default : alert */
        ]);

        return $this->tableau;
    }

    public function getFormulaire(): array
    {
        return [

            (new Input())
                ->setKey('id_temps')
                ->setType('hidden')
                ->setContrainte(['numeric'])

            ,(new Input())
                ->setKey('tache')
                ->setType('text')
                ->setLabel('tache')
                ->setIcon('fa fa-user fa-lg fa-fw')
                ->setSize('col-6') // 12=100% | 6=50% | 4=30% ...
                ->setPlaceholder('Quelle tache accomplies-tu ?')
                ->setContrainte(['required', 'alphanumeric'])

            ,(new Input())
                ->setKey('projet')
                ->setType('text')
                ->setLabel('projet')
                ->setIcon('fa fa-user fa-lg fa-fw')
                ->setSize('col-6') // 12=100% | 6=50% | 4=30% ...
                ->setContrainte(['required', 'alphanumeric'])

            ,(new Input())
                ->setKey('client')
                ->setType('text')
                ->setLabel('client')
                ->setIcon('fa fa-envelope fa-lg fa-fw')
                ->setSize('col-6') // 12=100% | 6=50% | 4=30% ...
                ->setContrainte(['required', 'alphanumeric' ])

            ,(new Input())
                ->setKey('temps')
                ->setType('text')
                ->setLabel('temps')
                ->setIcon('fa fa-phone fa-lg fa-fw')
                ->setSize('col-6') // 12=100% | 6=50% | 4=30% ...
                ->setContrainte(['required', 'alphanumeric'])

        ];
    }

    /**
     * retourne le HTML de la page qui liste les utilisateurs
     * @throws \Exception
     */
    public function liste(): string
    {
        $content = $this->getView('temps/temps.twig', [
            'tableau_listant_tout_les_temps' => $this->getTableau()->getHtmlWithoutData()
        ]);

        $params = [
            'content' => $content
            ,'titre' => Lang::get('time_management')
        ];

        return $this->show($params);
    }

    /** retourne le json a l'ajax de recuperation de données */
    public function ajax_liste(): bool|string
    {
        /** Algo de recuperation des données pour le tableau */
        return $this->getTableau()->setFonctionData(function($arrayForJson){

            /** recupere les données brut */
            $utilisateurs = \App\model\Temps::getAll();

            /** ajoute les données aux tableau */
            $this->getTableau()->setData($utilisateurs);

            /** generation du code html du tableau */
            $arrayForJson['html'] = $this->getTableau()->getHtmlData();

            return $arrayForJson;
        });
    }

    /** retourne le json a l'ajax de recuperation de la suppression */
    public function ajax_delete(): bool|string
    {
        return $this->getTableau()->setFonctionData(function($arrayForJson){

            $validation = (new Validation($_POST))
                ->addFieldsFromArray($this->getFormulaire())
                ->runByKey('id_temps');

            \App\model\Temps::delete($validation->get('id_temps'));

            $arrayForJson['success_msg'] = Lang::get('temps_supprimé');

            return $arrayForJson;
        }, false);
    }

}