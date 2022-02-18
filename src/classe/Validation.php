<?php

namespace App\classe;

use App\exception\TableauException;
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
    public function setDatas(array $datas): void
    {
        $this->datas = $datas;
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

    public function addField(string $key, array $array)
    {
        $this->list_keys[$key] = $array;
    }

    /**
     * @throws ValidationException
     */
    public function run()
    {
        foreach ($this->list_keys as $field => $item){

            switch ($item['type'] ?? null){

                case 'numeric':
                    if($this->numeric($field, ($item['required'] ?? false)) === false){
                        throw new ValidationException(Lang::get('validation::'.$field));
                    }
                    break;

            }

        }

    }
}