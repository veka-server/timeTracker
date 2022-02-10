<?php

namespace App\model;

class Validation
{
    /**
     * @var array
     */
    private $datas;

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
}