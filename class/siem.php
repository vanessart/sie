<?php

class siem {

    public static $instance;

    private function __construct() {
        //
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            @self::$instance = new PDO('dblib:host=187.72.135.136:1436;dbname=Db_Educacao_SecEdu', 'SecEdu', 's@edu2014#');
//            @self::$instance = new PDO('dblib:host=200.160.203.246:1436;dbname=Db_Educacao_SecEdu', 'SecEdu', 's@edu2014#');
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
        }

        return self::$instance;
    }

}