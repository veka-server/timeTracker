<?php

namespace App\classe;

class Minifier
{

    public static function getCss($public_directory){
        $path =realpath($public_directory.'/asset/css/');
        $fileList = glob($path.'/*');
        header('Content-type: text/css');
        $str = '';
        foreach($fileList as $filename){
            $str .= file_get_contents($filename);
        }
        echo $str;
    }

    public static function getJs($public_directory){
        $path =realpath($public_directory.'/asset/js/');
        $fileList = glob($path.'/*');
        header('Content-type: text/plain');
        $str = '';
        foreach($fileList as $filename){
            $str .= file_get_contents($filename);
        }
        echo $str;
    }

}