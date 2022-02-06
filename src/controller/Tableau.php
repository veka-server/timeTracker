<?php
namespace App\controller;

use App\exception\TableauException;
use App\model\Model;

class Tableau extends Controller
{

    private $columns = [];
    private $datas = [];
    private $urlListe = null;
    private $cleaned_data = [];
    private $full_width = false;

    public function addColumn(array $array)
    {
        $this->columns[] = $array;
    }

    /** retourne le html du tableau sans les données */
    public function getHtmlWithoutData():string
    {
        return $this->getView('common/table/table.twig',[
            'columns' => $this->columns
            ,'urlListe' => $this->urlListe
            ,'full_width' => $this->full_width
        ]);
    }

    /** defini l'url ajax pour recuperer les données */
    public function setUrlListe(string $string)
    {
        $this->urlListe = $string;
    }

    /** nettoyer les donénes brut pour ne conserver que les données utiles */
    public function setData(array $datas)
    {
        $this->datas = $datas;
        $this->cleaned_data = [];
        foreach ($datas as $data){
            $current_data = [];
            foreach ($this->columns as $column){
                $current_data[$column['key']] = $data[$column['key']];
            }
            $this->cleaned_data[] = $current_data;
        }
    }

    public function getHtmlData()
    {
        return $this->getView('common/table/data.twig',[
            'cleaned_data' => $this->cleaned_data
            ,'data' => $this->cleaned_data
        ]);
    }

    public function setFonctionData(\Closure $callback)
    {
        $retour = [];
        try{
            $retour = $callback($retour);
            $retour['success'] = true;
        }catch (TableauException $e){
            $retour['success'] = false;
            $retour['error_msg'] = $e->getMessage();
        }catch (\Exception $e){
            $retour['success'] = false;
            $retour['error_msg'] = 'Une erreur s\'est produite';
        }

        return json_encode( array_merge($retour, Model::getPaginationData()));
    }

    /**
     * @param bool $full_width
     */
    public function setFullWidth(bool $full_width): void
    {
        $this->full_width = $full_width;
    }

}