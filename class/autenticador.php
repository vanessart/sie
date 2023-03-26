<?php
/**
 * esta classe dedreciona o menu para o sistema autenticador
 */
class autenticador {

    public static $instance;

    private function __construct() {
        //
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new PDO('mysql:host=' . AUT_HOSTNAME . ';dbname=' . AUT_DB_NAME, AUT_DB_USER, AUT_DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
        }

        return self::$instance;
    }
    
    public static function niveis() {
        $sql = "select * from nivel order by n_nivel";
        $query = autenticador::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }
    
    public static function instancia() {
        $sql = "select * from instancia order by n_inst";
        $query = autenticador::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

}
