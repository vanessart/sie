<?php

// Caminho para a raiz
define('ABSPATH', dirname(__FILE__));

// Caminho para a pasta de uploads
define('UP_ABSPATH', ABSPATH . '/views/_uploads');

// URL da home
define('SISTEMA_NOME', 'SIE');
define('HOME_URI', '/sie');

include ABSPATH . '/_config/settings.php';

// pasta de includes
define('INCLUDE_FOLDER', 'includes/'. NUM_SYS);

// Charset da conexão PDO
define('DB_CHARSET', 'utf8');

// Se você estiver desenvolvendo, modifique o valor para true
if (@$_SESSION['userdata']['id_pessoa'] == 1 || @$_SESSION['userdata']['id_pessoa'] == 6488 || @$_SESSION['userdata']['id_pessoa'] == 36) {
    $debug = "TRUE";
} else {
    $debug = "FALSE";
}
define('DEBUG', $debug);

#######################################################
//seta as configurações de acesso do autenticador

define('AUT_HOME_URI', HOME_URI);

define('AUT', 0);

// Nome do host da base de dados
define('AUT_HOSTNAME', HOSTNAME);

define('NOME3', '');

// Nome do DB
//define( 'DB_NAME', 'tcpixcom_sis' );
define('AUT_DB_NAME', DB_NAME);

// Usuário do DB
//define( 'DB_USER', 'tcpixcom_sis' );
define('AUT_DB_USER', DB_USER);

// Senha do DB
//define( 'DB_PASSWORD', 'spg9$5fpl5uf' );
define('AUT_DB_PASSWORD', DB_PASSWORD);

##########################################################



/**
 * Não edite daqui em diante
 */
// Carrega o loader, que vai carregar a aplicação inteira
require_once ABSPATH . '/loader.php';
?>
