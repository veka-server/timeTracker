<?php

namespace App\classe;

use App\exception\ValidationException;
use VekaServer\Framework\Lang;

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

    public function numeric(string $key, $required = false)
    {
        if(!isset($this->datas[$key]) && $required === true){
            return false;
        }
        return is_numeric($this->datas[$key]);
    }

    public function get(string $key)
    {
        return $this->datas[$key];
    }

    public function addField(string $key, array $array): Validation
    {
        $this->list_keys[$key] = $array;
        return $this;
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
        $item = $this->list_keys[$key]->getValidation();
        switch ($item['type'] ?? null){

            case 'numeric':
                if($this->numeric($key, ($item['required'] ?? false)) === false){
                    throw new ValidationException(Lang::get('validation::'.$key));
                }
                break;

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

}