<?php

class pdoSis {

    public static $instance;

    private function __construct() {
        //
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            try {
                self::$instance = new PDO('mysql:host=' . HOSTNAME . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD,
                        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            } catch (PDOException $e) {
                die();
            }
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
        }

        return self::$instance;
    }

    public static function fetch($sql, $fetch = 'fetchAll') {
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->$fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function action($sql) {
        $query = pdoSis::getInstance()->query($sql);
        //retorna o último ID
        return pdoSis::getInstance()->lastInsertId();
    }

}

/**
 * $sql = "select * tabela"
 * $query = seDB::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC); multiplos array
        $array = $query->fetch(PDO::FETCH_ASSOC); unico array
 */
