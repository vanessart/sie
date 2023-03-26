<?php

class pdoConsulta {

    public static $instance;

    private function __construct() {
        //
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new PDO('mysql:host='.HOSTNAME.';dbname=consulta', DB_USER, DB_PASSWORD,
 array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
        }

        return self::$instance;
    }

}
/**
 * $sql = "select * tabela"
 * $query = seDB::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC); multiplos array
        $array = $query->fetch(PDO::FETCH_ASSOC); unico array
 */
