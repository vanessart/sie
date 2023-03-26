<?php

/**
 * MainModel - Modelo geral
 *
 * 
 *
 * @package TutsupMVC
 * @since 0.1
 */
class MainModel {

    /**
     * $form_data
     *
     * Os dados de formulários de envio.
     *
     * @access public
     */
    public $form_data;

    /**
     * $form_msg
     *
     * As mensagens de feedback para formulários.
     *
     * @access public
     */
    public $form_msg;

    /**
     * $form_confirma
     *
     * Mensagem de confirmação para apagar dados de formulários
     *
     * @access public
     */
    public $form_confirma;

    /**
     * $db
     *
     * O objeto da nossa conexão PDO
     *
     * @access public
     */
    public $db;

    /**
     * $controller
     *
     * O controller que gerou esse modelo
     *
     * @access public
     */
    public $controller;

    /**
     * $parametros
     *
     * Parâmetros da URL
     *
     * @access public
     */
    public $parametros;

    /**
     * $userdata
     *
     * Dados do usuário
     *
     * @access public
     */
    public $userdata;

    public static $css_file = '';

    public static function _setCSS($path, $orig = null)
    {
        if (!empty($path))
        {
            if (!empty($orig)){
                $path = $orig.$path;
            } else {
                $path = HOME_URI.$path;
            }
            self::$css_file = $path;
            self::_generateCSS();
        }
    }

    public static function _generateCSS()
    {
        if (!empty(self::$css_file)) {
            echo '<link rel="stylesheet" type="text/css" href="'.self::$css_file.'">';
        }
    }
}

// MainModel