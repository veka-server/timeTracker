<?php
namespace App\controller;

use App\classe\Contrainte;
use VekaServer\Framework\Lang;
use VekaServer\TableForm\Forms;
use VekaServer\TableForm\Input;
use VekaServer\TableForm\Tableau;
use VekaServer\TableForm\Validation;

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
            , 'data_to_send' => ['id_utilisateur'] /* liste des variable a envoyer lors de la requete */
            , 'confirmation_msg' => null /* si besoin d'une popin de confirmation */
            , 'icone' => '<i class="far fa-edit"></i>' /* html de l'icone */
            , 'couleur' => 'bleu' /* voir css */
            , 'type_popin_retour' => 'dialog' /* dialog ou alert */
        ]);

        /** ajout du bouton de suppression */
        $this->tableau->addAction([
            'url' => '/utilisateur/delete'
            , 'label' => Lang::get('supprimer')
            , 'data_to_send' => ['id_utilisateur'] /* liste des variable a envoyer lors de la requete */
            , 'confirmation_msg' => Lang::get('confirmation_suppression_utilisateur') /* si besoin d'une popin de confirmation */
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
                ->setKey('id_utilisateur')
                ->setType('hidden')
                ->setContrainte(['numeric'])

            ,(new Input())
                ->setKey('nom')
                ->setType('text')
                ->setLabel('nom')
                ->setIcon('fa fa-user fa-lg fa-fw')
                ->setSize('col-6') // 12=100% | 6=50% | 4=30% ...
                ->setPlaceholder('Doe')
                ->setContrainte(['required', 'alphanumeric'])

            ,(new Input())
                ->setKey('prenom')
                ->setType('text')
                ->setLabel('prenom')
                ->setIcon('fa fa-user fa-lg fa-fw')
                ->setSize('col-6') // 12=100% | 6=50% | 4=30% ...
                ->setPlaceholder('John')
                ->setContrainte(['required', 'alphanumeric'])

            ,(new Input())
                ->setKey('email')
                ->setType('text')
                ->setLabel('email')
                ->setIcon('fa fa-envelope fa-lg fa-fw')
                ->setSize('col-6') // 12=100% | 6=50% | 4=30% ...
                ->setPlaceholder('john.doe@gmail.com')
                ->setContrainte(['required', 'email', [Contrainte::class, 'check_email_doublon'] ])

            ,(new Input())
                ->setKey('telephone')
                ->setType('text')
                ->setLabel('telephone')
                ->setIcon('fa fa-phone fa-lg fa-fw')
                ->setSize('col-6') // 12=100% | 6=50% | 4=30% ...
                ->setPlaceholder('0123456789')
                ->setContrainte(['required', 'telephone'])

        ];
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
    public function ajax_liste(): bool|string
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
    public function ajax_export(): bool|string
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
    public function ajax_delete(): bool|string
    {
        return $this->getTableau()->setFonctionData(function($arrayForJson){

            $validation = (new Validation($_POST))
                ->addFieldsFromArray($this->getFormulaire())
                ->runByKey('id_utilisateur');

            \App\model\Utilisateur::delete($validation->get('id_utilisateur'));

            $arrayForJson['success_msg'] = Lang::get('utilisateur_supprimé');

            return $arrayForJson;
        }, false);
    }

    /** retourne le json a l'ajax d'édition */
    public function ajax_edit(): bool|string
    {
        return $this->getTableau()->setFonctionData(function($arrayForJson){

            $validation = (new Validation($_POST))
                ->addFieldsFromArray($this->getFormulaire())
                ->runByKey('id_utilisateur');

            $utilisateur = \App\model\Utilisateur::getByID($validation->get('id_utilisateur'));

            $forms = (new Forms())
                ->setMethod('POST')
                ->setUrl('/utilisateur/save-edit') // default current url
                ->setSize('600px')
                ->addFieldsFromArray($this->getFormulaire())
                ->addData($utilisateur[0]);

            $arrayForJson['html'] = $forms->getHtml();
            $arrayForJson['success_titre'] = Lang::get('user_popin_edition');

            return $arrayForJson;
        }, false);
    }

    /** retourne le json a l'ajax de creation */
    public function ajax_add(): bool|string
    {
        return $this->getTableau()->setFonctionData(function($arrayForJson){

            $forms = (new Forms())
                ->setMethod('POST')
                ->setUrl('/utilisateur/save-add') // default current url
                ->setSize('600px')
                ->setHideErrorMessageAtStart(true)
                ->addFieldsFromArray($this->getFormulaire());

            $arrayForJson['html'] = $forms->getHtml();
            $arrayForJson['success_titre'] = Lang::get('user_popin_creation');

            return $arrayForJson;
        }, false);
    }

    public function ajax_save_edit(): bool|string
    {
        return $this->getTableau()->setFonctionData(function($arrayForJson){

            $validation = (new Validation($_POST))
                ->addFieldsFromArray($this->getFormulaire())
                ->run();

            $list_fields = [];
            foreach ($this->getFormulaire() as $item){
                if($item->getKey() == 'id_utilisateur'){
                    continue;
                }
                $list_fields[$item->getKey()] = $validation->get($item->getKey());
            }

            \App\model\Utilisateur::update($validation->get('id_utilisateur'), $list_fields);

            $arrayForJson['success_msg'] = Lang::get('user_saved');

            return $arrayForJson;
        }, false);
    }

    public function ajax_save_add(): bool|string
    {
        return $this->getTableau()->setFonctionData(function($arrayForJson){

            $validation = (new Validation($_POST))
                ->addFieldsFromArray($this->getFormulaire())
                ->run();

            $list_fields = [];
            foreach ($this->getFormulaire() as $item){
                $list_fields[$item->getKey()] = $validation->get($item->getKey());
            }

            $new_password = \App\classe\Utilisateur::randomPassword();
            $list_fields['password'] = $new_password;

            \App\model\Utilisateur::add($list_fields);

            $arrayForJson['success_msg'] = Lang::get('user_saved');
            $arrayForJson['success_msg'] .= '<br/>'.Lang::get('password').' = '.$new_password;

            return $arrayForJson;
        }, false);
    }

}