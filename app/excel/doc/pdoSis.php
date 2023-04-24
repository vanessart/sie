<?php

class pdoSis {

    public static $instance;

    private function __construct() {
        //
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new PDO('mysql:host=187.84.96.133;dbname='.'ge2', 'root', 'i91j6f8v!!',
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
