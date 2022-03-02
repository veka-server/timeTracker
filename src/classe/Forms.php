<?php

namespace App\classe;

use App\controller\Controller;

class Forms extends Controller
{

    private $datas;
    private $fields;
    private $size;
    private $url;
    private $hideErrorMessageAtStart = false;

    public function __construct()
    {
        return $this;
    }

    public function setMethod(string $method): Forms
    {
        return $this;
    }

    public function setUrl(string $url): Forms
    {
        $this->url = $url;
        return $this;
    }

    public function addField($input): Forms
    {
        $this->fields[$input->getKey()] = $input;
        return $this;
    }

    public function getHtml(): string
    {
        return $this->getView('common/forms/form.twig',[
            'datas' => $this->datas
            ,'fields' => $this->fields
            ,'size' => $this->size
            ,'url' => $this->url
            ,'hideErrorMessageAtStart' => $this->getHideErrorMessageAtStart()
        ]);
    }

    public function addData(array $datas): Forms
    {
        $this->datas = $datas;
        return $this;
    }

    public function addFieldsFromArray(array $array_input)
    {
        foreach ($array_input as $input){
            $this->addField($input);
        }
        return $this;
    }

    public function setSize(string $size)
    {
        $this->size =$size;
        return $this;
    }

    public function setHideErrorMessageAtStart(bool $hideErrorMessageAtStart = false)
    {
        $this->hideErrorMessageAtStart = $hideErrorMessageAtStart;
        return $this;
    }

    public function getHideErrorMessageAtStart()
    {
        return $this->hideErrorMessageAtStart;
    }

}