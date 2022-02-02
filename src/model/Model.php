<?php
namespace App\model;

use VekaServer\Container\Container;

class Model extends \VekaServer\Framework\Model
{
    private static $paginationData = [];
    
    public static function exec(string $sql, array $data = array()){
        $debugbar = Container::getInstance()->get('DebugBar');
        $debugbar['time']->startMeasure('sql request', debug_backtrace()[1]['class'].'::'.debug_backtrace()[1]['function']);
        $rs = parent::exec($sql, $data);
        $debugbar['time']->stopMeasure('sql request');
        return $rs;
    }


    /**
     * Modifie une requete select pour l'utiliser avec la pagination
     * La requete doit commencer par select
     * elle ne doit pas avoir de limit deja existant
     * Si la requete ne commence pas par select on l'execute de facon classic
     * @param $sql
     * @param array $param_sql
     * @return array
     * @throws \Exception
     */
    public static function exec_pagination($sql, $param_sql=[]){

        $clean_sql = trim($sql);

        if(substr( $clean_sql, 0, strlen( 'SELECT' ) ) !== 'SELECT'){
            return self::exec($sql,$param_sql);
        }

        $clean_sql = self::addFiltre($clean_sql, $param_sql);

        if($_REQUEST['page_curr'] != 'last'){
            self::$paginationData['page_curr'] = (int) ($_REQUEST['page_curr'] ?? 1);
        } else {
            $sql_for_count = 'SELECT '.self::getSelectSQL().' FROM ('.$clean_sql.') main ';
            $rs = self::exec($sql_for_count,$param_sql);
            $nb_line = self::getNbLineFromRS($rs);
            $_REQUEST['page_curr'] =  ceil($nb_line / (int) $_REQUEST['page_size']);
            self::$paginationData['page_curr'] = $_REQUEST['page_curr'] ?? 1;
            self::$paginationData['last'] = true;
        }

        if(self::$paginationData['page_curr'] <= 0 ){
            self::$paginationData['page_curr'] = 1;
        }

        $clean_sql = self::getSQL($sql, $param_sql);
        $rs = self::exec($clean_sql,$param_sql);

        // si page nb et pas de donnée on se redirige vers la derniere page
        if(empty($rs) && $_REQUEST['page_curr'] > 1){
            $_REQUEST['page_curr'] = 'last';
            return self::exec_pagination($sql, $param_sql);
        }

        if(empty($rs)){
            self::$paginationData['page_nb'] = 1;
            return [];
        }

        self::$paginationData['page_nb'] = 1;

        if( count($rs) < (int) $_REQUEST['page_size'] ){
            self::$paginationData['last'] = true;
        }

        if(isset(self::$paginationData['last']) && self::$paginationData['last']){
            self::$paginationData['page_nb'] = self::$paginationData['page_curr'];
        }
        self::$paginationData['no_nb_page'] = true;

        if(self::$paginationData['page_nb'] <= 0 ){
            self::$paginationData['page_nb'] = 1;
        }

        return $rs;
    }


    /**
     * Modifie une requete select pour l'utiliser avec la pagination
     * La requete doit commencer par select
     * elle ne doit pas avoir de limit deja existant
     * Si la requete ne commence pas par select on l'execute de facon classic
     * et retourne la requete
     * @param $sql
     * @param array $param_sql
     * @return string
     * @throws \Exception
     */
    public static function getSQL( $sql, &$param_sql=[]){

        $clean_sql = trim($sql);

        if(substr( $clean_sql, 0, strlen( 'SELECT' ) ) !== 'SELECT'){
            return $sql;
        }

        $clean_sql = self::addFiltre($clean_sql, $param_sql);
        $clean_sql .= self::getOrderBySQL();
        $clean_sql .= self::getLimitSQL();

        return $clean_sql;
    }

    /**
     * @param string $clean_sql
     * @param $param_sql
     * @return string
     */
    private static function addFiltre(string $clean_sql, &$param_sql)
    {
        if(!isset($_REQUEST['filtre']) || empty($_REQUEST['filtre'])) {
            return $clean_sql;
        }

        $where = [];
        foreach ($_REQUEST['filtre'] as $name => $value){
            $value = trim($value);
            if(empty($value) || $name == 'order_by'){
                continue;
            }

            if(strpos($name, '-') === false){
                $name = 's-'.$name;
            }

            list($prefix, $name) = explode('-',$name);

            $name = preg_replace('/[^a-zA-Z0-9_-]/s', '', $name);

            switch ($prefix){

                case 'i' :
                    $where[] = 'LOWER('.$name.'::text) LIKE :'.$name.'';
                    $param_sql['s-'.$name] = '%'.(int)$value.'%';
                    break;

                case 's' :
                    $where[] = 'LOWER('.$name.') LIKE LOWER(:'.$name.')';
                    $param_sql['s-'.$name] = '%'.$value.'%';
                    break;

                case 'd' :
                    $where[] = 'LOWER('.$name.'::text) LIKE LOWER(:'.$name.')';
                    $param_sql['s-'.$name] = '%'.$value.'%';
                    break;

            }

        }

        return 'SELECT main.* FROM ('.$clean_sql.') main '.( count($where) > 0 ? ' WHERE ' : '').implode(' AND ', $where);
    }

    /**
     * @return string
     */
    public static function getOrderBySQL()
    {
        if(empty($_REQUEST['filtre']['order_by']) ){
            return '';
        }

        list($order_by,$sort) = explode('-', $_REQUEST['filtre']['order_by']);

        $order_by = preg_replace('/[^a-zA-Z0-9_-]/s', '', $order_by);

        if(!in_array($sort, ['asc', 'desc'])){
            return '';
        }

        return ' ORDER BY '.$order_by .' '.$sort;
    }

    /**
     * @return string
     */
    public static function getSelectSQL()
    {
        return ' count(*) OVER() AS count_pagination ';
    }

    /**
     * @param $rs
     * @return int
     */
    public static function getNbLineFromRS(&$rs)
    {
        foreach ($rs as $key => $line){
            $nb_mail = $rs[$key]['count_pagination'];
            unset($rs[$key]['count_pagination']);
        }
        return (int) ($nb_mail ?? 0 );
    }

    /**
     * @return string
     */
    public static function getLimitSQL()
    {

        if($_REQUEST['page_curr'] <= 0 ){
            $_REQUEST['page_curr'] = 1;
        }

        if($_REQUEST['page_size'] <= 0 ){
            $_REQUEST['page_size'] = 1;
        }

        return ' LIMIT '.(int) $_REQUEST['page_size'].' OFFSET '.((int) $_REQUEST['page_curr']*(int)$_REQUEST['page_size'] - (int)$_REQUEST['page_size']);
    }

    public static function getPaginationData()
    {
        return self::$paginationData;
    }
}