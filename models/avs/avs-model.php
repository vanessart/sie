<?php
class avsModel extends MainModel
{
    public $db;
    /**
	 * Construtor para essa classe
	 *
	 * Configura o DB, o controlador, os parâmetros e dados do usuário.
	 *
	 * @since 0.1
	 * @access public
	 * @param object $db Objeto da nossa conexão PDO
	 * @param object $controller Objeto do controlador
	 */
	public function __construct( $db = false, $controller = null ) {
		// Configura o DB (PDO)
		$this->db = new DB();
		
		// Configura o controlador
		$this->controller = $controller;

		// Configura os parâmetros
		//$this->parametros = $this->controller->parametros;

		// Configura os dados do usuário
		$this->userdata = $this->controller->userdata;
                
		
	}
	
	// Crie seus próprios métodos daqui em diante
}