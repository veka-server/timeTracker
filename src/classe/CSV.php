<?php

namespace App\classe;

class CSV
{

    protected $delimiter;
    const HEADER = 'data:text/csv;charset=utf-8,';

    public function __construct($delimiter = ';'){
        $this->delimiter = $delimiter;
    }

    /**
     * @param array $data
     * @param array $custom_header forcer le header avec ce tableau
     * @param bool $with_binary_header
     * @return string
     * @throws \Exception
     */
    public function arrayToContent(array $data, array $custom_header = [], bool $with_binary_header = true): string
    {
        if (empty($data)) {
            return '';
        }

        $array_header = [];

        /** generer le header a partir d'un tableau de donnée clef/trad */
        if (!empty($custom_header)) {
            $fields = array_keys($custom_header);
            foreach ($data[0] as $k => $v) {
                $array_header[$k] = in_array($k , $fields) ? $custom_header[$k] : $k;
            }
        } else{
            foreach ($data[0] as $k => $v) {
                $array_header[$k] = $k;
            }
        }

        /** generer le header en string */
        $string_header = '';
        if(!empty($array_header)){
            $delimiter = $this->getDelimiter();
            $string_header .= '"'.implode('"'.$delimiter.'"', $array_header).'"'.PHP_EOL;
        }

        /** retourner les données csv complète */
        $final_data = $string_header.implode(PHP_EOL, array_map(function($row){
                return '"'.implode('"'.$this->getDelimiter().'"', array_map(function($cell){ /** ajout du separateur */
                        return str_replace('"', '""', $cell.''); /** doubler les guillemet */
                    }, $row)).'"';
            }, $data)).PHP_EOL;

        $final_data = mb_convert_encoding($final_data, "UTF-8", mb_detect_encoding($final_data));

        /** ajouter le header binaire UTF8 si besoin */
        $binary_header = '';
        if($with_binary_header && strtolower(mb_detect_encoding($final_data)) == 'utf-8'){
            $binary_header = pack('CCC', 0xEF, 0xBB, 0xBF); // UTF8  3 bit
        }

        return $binary_header.$final_data;
    }

    /**
     * @return string
     */
    public function getDelimiter(): string
    {
        return $this->delimiter;
    }

    /**
     * @param string $delimiter
     */
    public function setDelimiter(string $delimiter): void
    {
        $this->delimiter = $delimiter;
    }


}