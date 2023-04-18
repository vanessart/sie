<?php
/**
 * Verifica chaves de arrays.
 *
 * Verifica se a chave existe no array e se ela tem algum valor.
 * Obs.: Essa função está no escopo global, pois, vamos precisar muito da mesma.
 *
 * @param array  $array O array
 * @param string $key   A chave do array
 *
 * @return null|string O valor da chave do array ou nulo
 */
function chk_array($array, $key)
{
    // Verifica se a chave existe no array
    if (isset($array[$key]) && !empty($array[$key])) {
        // Retorna o valor da chave

        return $array[$key];

    }

    // Retorna nulo por padrão
    return null;
} // chk_array


/**
 * Função para carregar automaticamente todas as classes padrão
 * Ver: http://php.net/manual/pt_BR/function.autoload.php.
 * Nossas classes estão na pasta classes/.
 * O nome do arquivo deverá ser class-NomeDaClasse.php.
 * Por exemplo: para a classe TutsupMVC, o arquivo vai chamar class-TutsupMVC.php
 */
function m_autoload( $class_name ) {
	$exception = [
		'Mpdf\Mpdf'
	];

	if ( !in_array($class_name, $exception) ) {
	    $file = ABSPATH.'/class/'.$class_name.'.php';
	    echo $class_name. "<br>";
	    print_r($exception);
	    echo "<br>";
	    if (!file_exists($file)) {
	        require_once ABSPATH.'/includes/404.php';

	        return;
	    }
	    require_once $file;
	}
}
spl_autoload_register('m_autoload');

function clearCpf($cpf) {
   return preg_replace('/[^0-9]/is', '', $cpf);
}

function validateCpf($value)
{
    $c = preg_replace('/[^\d]/', '', clearCpf($value));

    if (11 != mb_strlen($c) || preg_match("/^{$c[0]}{11}$/", $c)) {
        return false;
    }

    for (
                $s = 10, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--
        ) {
    }

    if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
        return false;
    }

    for (
                $s = 11, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--
        ) {
    }

    if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
        return false;
    }

    return true;
}


function pre($valor) {
    echo '<pre>';
    print_r($valor);
    echo '</pre>';
}

function validaCertidao($cn) {
	
	if(strlen($cn) > 32){
		return false;
		exit;
	}

	//inicia verificação do dígito verificador
	$cartorio = substr($cn, 0, 6);
	$acervo = substr($cn, 6, 2);
	$rcpn = substr($cn, 8, 2);
	$ano = substr($cn, 10, 4);
	$tipo = substr($cn, 14, 1);
	$livro = substr($cn, 15, 5);
	$folha = substr($cn, 20, 3);
	$termo = substr($cn, 23, 7);
	$d1 = substr($cn, 30, 1);
	$d2 = substr($cn, 31, 1);

	//102414 01 55 2011 1 31262 881 1518431-33

	/*$cartorio = "115840";
	$acervo = '01';
	$rcpn = '55';
	$ano = '2017';
	$tipo = '1';
	$livro = '00273';
	$folha = '043';
	$termo = '0162296';*/

	//cartorio
	$t1 = (int) substr($cartorio, 0, 1);
	$t2 = (int) substr($cartorio, 1, 1);
	$t3 = (int) substr($cartorio, 2, 1);
	$t4 = (int) substr($cartorio, 3, 1);
	$t5 = (int) substr($cartorio, 4, 1);
	$t6 = (int) substr($cartorio, 5, 1);
	//acervo
	$t7 = (int) substr($acervo, 0, 1);
	$t8 = (int) substr($acervo, 1, 1);
	//rcpn
	$t9 = (int) substr($rcpn, 0, 1);
	$t10 = (int) substr($rcpn, 1, 1);
	//ano
	$t11 = (int) substr($ano, 0, 1);
	$t12 = (int) substr($ano, 1, 1);
	$t13 = (int) substr($ano, 2, 1);
	$t14 = (int) substr($ano, 3, 1);
	//tipo
	$t15 = (int) substr($tipo, 0, 1);
	//livro
	$t16 = (int) substr($livro, 0, 1);
	$t17 = (int) substr($livro, 1, 1);
	$t18 = (int) substr($livro, 2, 1);
	$t19 = (int) substr($livro, 3, 1);
	$t20 = (int) substr($livro, 4, 1);
	//folha
	$t21 = (int) substr($folha, 0, 1);
	$t22 = (int) substr($folha, 1, 1);
	$t23 = (int) substr($folha, 2, 1);
	//termo
	$t24 = (int) substr($termo, 0, 1);
	$t25 = (int) substr($termo, 1, 1);
	$t26 = (int) substr($termo, 2, 1);
	$t27 = (int) substr($termo, 3, 1);
	$t28 = (int) substr($termo, 4, 1);
	$t29 = (int) substr($termo, 5, 1);
	$t30 = (int) substr($termo, 6, 1);

	$dv1 = ($t1 * 2) + ($t2 * 3) + ($t3 * 4) + ($t4 * 5) + ($t5 * 6) + ($t6 * 7) + ($t7 * 8) + ($t8 * 9) + ($t9 * 10) + ($t10 * 0) + ($t11 * 1) + ($t12 * 2) + ($t13 * 3) + ($t14 * 4) + ($t15 * 5) + ($t16 * 6) + ($t17 * 7) + ($t18 * 8) + ($t19 * 9) + ($t20 * 10) + ($t21 * 0) + ($t22 * 1) + ($t23 * 2) + ($t24 * 3) + ($t25 * 4) + ($t26 * 5) + ($t27 * 6) + ($t28 * 7) + ($t29 * 8) + ($t30 * 9);

	$res = $dv1 % 11;

	if($res > 9){
		$res = 1;
	}

	$dig1 = $res;

	$t31 = $res;

	$dv2 = ($t1 * 1) + ($t2 * 2) + ($t3 * 3) + ($t4 * 4) + ($t5 * 5) + ($t6 * 6) + ($t7 * 7) + ($t8 * 8) + ($t9 * 9) + ($t10 * 10) + ($t11 * 0) + ($t12 * 1) + ($t13 * 2) + ($t14 * 3) + ($t15 * 4) + ($t16 * 5) + ($t17 * 6) + ($t18 * 7) + ($t19 * 8) + ($t20 * 9) + ($t21 * 10) + ($t22 * 0) + ($t23 * 1) + ($t24 * 2) + ($t25 * 3) + ($t26 * 4) + ($t27 * 5) + ($t28 * 6) + ($t29 * 7) + ($t30 * 8) + ($t31 * 9);

	$res2 = $dv2 % 11;

	if($res2 > 9){
		$res2 = 1;
	}

	$dig2 = $res2;


	//var_dump( $cn );
	//var_dump( strlen($cn) );
	//var_dump( "$d1 != $dig1" );
	//var_dump( "$d2 != $dig2" );


	if( ($d1 != $dig1) || ($d2 != $dig2)) {
		return FALSE;
	} else {
		return TRUE;
	}
}


function dateView($date, $format = 'Y-m-d H:i:s')
{
	$d = DateTime::createFromFormat($format, $date, new DateTimeZone('America/Sao_paulo'));

	return $d->format('Y-m-d');
}

function dateMySql($date, $format = 'd/m/Y')
{
	$d = DateTime::createFromFormat($format, $date, new DateTimeZone('America/Sao_paulo'));

	return $d->format('Y-m-d H:i:s');
}

function data($data){
    return date("d/m/Y", strtotime($data));
}