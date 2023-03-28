<?php
// Nome do host da base de dados
define( 'HOSTNAME', 'ge2' );

// Nome do DB
define( 'DB_NAME', 'ge2' );

// Usuário do DB
define( 'DB_USER', 'user_mysql' );

// Senha do DB
define( 'DB_PASSWORD', 'V0c3J@s@B3qU@l3' );

//logar com o google
define('API_GOOGLE', '3238010239-qbj2p0h3fdujd7a2s2dv95htvqnvkhft.apps.googleusercontent.com');

//mongoDB
define('MONGO_HOSTNAME', 'localhost:27017');
define('MONGO_DB_NAME', 'ng');
define('MONGO_DB_USER', null);
define('MONGO_DB_PASSWORD', null);

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
