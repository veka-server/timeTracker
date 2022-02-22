<?php

namespace App\classe;

use App\exception\ValidationException;
use VekaServer\Framework\Lang;
use VekaServer\Framework\Log;

class Validation
{
    /**
     * @var array
     */
    private $datas;
    private $list_keys;

    /**
     * @param array $datas
     */
    public function __construct(array $datas)
    {
        $this->datas = $datas;
        return $this;
    }

    /**
     * @return array
     */
    public function getDatas(): array
    {
        return $this->datas;
    }

    /**
     * @param array $datas
     */
    public function setDatas(array $datas): Validation
    {
        $this->datas = $datas;
        return $this;
    }

    public function get(string $key)
    {
        return $this->datas[$key];
    }

    /**
     * @throws ValidationException
     */
    public function run()
    {
        foreach ($this->list_keys as $field => $input){
            $this->runByKey($field);
        }

        return $this;
    }

    /**
     * @throws ValidationException
     */
    public function runByKey($key)
    {
        $contraintes = $this->list_keys[$key]->getContrainte();
        foreach ($contraintes as $contrainte){

            if($contrainte !== 'required' && empty($this->datas[$key]) && $this->datas[$key] !== 0 ){
                continue;
            }

            switch (trim($contrainte) ?? null){

                default :
                    Log::notice('VALIDATION : la contrainte "'.$contrainte.'" n\'existe pas');
                    break;

                case 'required':
                    if(empty($this->datas[$key]) && $this->datas[$key] != 0){
                        throw new ValidationException(Lang::get('validation::required::'.$key. ' '.$this->datas[$key]));
                    }
                    break;

                case 'numeric':
                    if($this->numeric($this->datas[$key]) === false){
                        throw new ValidationException(Lang::get('validation::numeric::'.$key));
                    }
                    break;

                case 'alphanumeric':
                    if($this->alphanumeric($this->datas[$key]) === false){
                        throw new ValidationException(Lang::get('validation::alphanumeric::'.$key));
                    }
                    break;

                case 'email':
                    if($this->email($this->datas[$key]) === false){
                        throw new ValidationException(Lang::get('validation::alphanumeric::'.$key));
                    }
                    break;

                case 'telephone':
                    if($this->telephone($this->datas[$key]) === false){
                        throw new ValidationException(Lang::get('validation::alphanumeric::'.$key));
                    }
                    break;

            }

        }

        return $this;
    }

    public function addFieldsFromArray(array $array_input): Validation
    {
        foreach ($array_input as $input){
            $this->list_keys[$input->getKey()] = $input;
            
        }
        return $this;
    }

    public function regex(string $regex, string $value)
    {
        $check = preg_match( $regex, $value);
        if( $check === 0 || $check === false){
            return false;
        }
        return true;
    }

    public function alphanumeric(string $value)
    {
        return $this->regex('/^[a-z0-9]+$/i', $value);
    }

    public function email(string $value)
    {
        return $this->regex('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/i', $value);
    }

    public function numeric(string $value)
    {
        return $this->regex('/^0|[1-9]\d*$/', $value);
    }

    public function telephone(string $value)
    {
        return $this->regex('/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/', $value);
    }

}