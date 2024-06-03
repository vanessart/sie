<?php
// Nome do host da base de dados
define( 'HOSTNAME', 'mysql_sie' );

// Nome do DB
define( 'DB_NAME', 'ge2' );

// Usuário do DB
define( 'DB_USER', 'user_mysql' );

// Senha do DB
define( 'DB_PASSWORD', 'V0c3J@s@B3qU@l3' );

//logar com o google
define('API_GOOGLE', '791747530577-5iml28vrbbgg6sisbqla0832iui4ci4l.apps.googleusercontent.com');

//mongoDB
define('MONGO_HOSTNAME', 'mongo_sie:27017');
define('MONGO_DB_NAME', 'ng');
define('MONGO_DB_USER', 'sie_user');
define('MONGO_DB_PASSWORD', 'sie_pwd');

// SED / REST
$sedHabiente = 'p';
$sedProd = 'https://integracaosed.educacao.sp.gov.br/ncaapi/api';
$sedHom = 'https://homologacaointegracaosed.educacao.sp.gov.br/ncaapi/api';
define('SED_USER', 'rg254821765sp');
define('SED_PASSWORD', 'dominio#13');
if ($sedHabiente == 'p') {
    define('SED_URL', $sedProd);
} else {
    define('SED_URL', $sedHom);
}

define('BASE_URL', 'https://parnaibamakerlabs.app.br/');
define('BASE_URL_HAB', 'https://parnaibamakerlabs.app.br/hab');

define('CLI_NOME', 'PREFEITURA MUNICIPAL DE SANTANA DE PARNAÍBA');
define('CLI_URL', 'https://santanadeparnaiba.sp.gov.br');
define('CLI_END', 'Rua Prof. Edgar de Moraes, nº. 880');
define('CLI_BAIRRO', 'Jardim Frediani');
define('CLI_CEP', '06502-165');
define('CLI_CIDADE', 'SANTANA DE PARNAÍBA');
define('CLI_UF', 'SP');
define('CLI_FONE', '(11) 4622-8600');

define('CLI_MAIL_DOMINIO', 'santanadeparnaiba.sp.gov.br');
define('CLI_MAIL', 'gabinete@'. CLI_MAIL_DOMINIO);
define('CLI_MAIL_TEC', 'ti@'. CLI_MAIL_DOMINIO);
define('CLI_MAIL_HOST', 'smtp.gmail.com');
define('CLI_MAIL_USERNAME', 'sie@'. CLI_MAIL_DOMINIO);
define('CLI_MAIL_PASSWORD', 'aptN@3@ui');
define('CLI_MAIL_PORT', 587);

define('CLI_INTEGRACAO', 'santanaParnaiba');
define('PROJETO_TDICS', 'Projeto Parnaíba Maker labs');
