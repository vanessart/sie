<?php
ob_start();
if (!defined('ABSPATH'))
    exit;

$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_STRING);

$cor = '#F5F5F5';

$subTitulo = "";
$periodo = null;
if (!empty($mes)) {
    $periodo = date('Y') .'-'. $mes;
    $subTitulo = "<br>Período: ".$mes."/".date('Y');
}

if (!empty($id_inst)) {
    $escola = new ng_escola($id_inst);
    $subTitulo = "<br>Escola: ". $escola->_nome;
}

$dados = transporteErp::getListaEspera($periodo, $id_inst);
if (empty($dados)) {
    ?>
    <div>
        Não Existem Dados referente a esta consulta.
    </div>
    <?php
    return;
}

$dEmp = [];
foreach ($dados as $k => $v) {
    if (empty($v['email'])) {
        continue;
    }

    if (!isset($dEmp[$v['id_em']])) {
        $dEmp[$v['id_em']] = [];
        $dEmp[$v['id_em']]['email'] = $v['email'];
        $dEmp[$v['id_em']]['nome_contato'] = $v['nome_contato'];
        $dEmp[$v['id_em']]['n_em'] = $v['n_em'];
        $dEmp[$v['id_em']]['razao_social'] = $v['razao_social'];
        $dEmp[$v['id_em']]['inst'] = [];
    }

    if (!isset($dEmp[$v['id_em']]['inst'][$v['id_inst']])) {
        $dEmp[$v['id_em']]['inst'][$v['id_inst']] = [];
        $dEmp[$v['id_em']]['inst'][$v['id_inst']]['id_inst'] = $v['id_inst'];
        $dEmp[$v['id_em']]['inst'][$v['id_inst']]['n_inst'] = $v['n_inst'];
        $dEmp[$v['id_em']]['inst'][$v['id_inst']]['nome_contato'] = $v['nome_contato'];
        $dEmp[$v['id_em']]['inst'][$v['id_inst']]['dados'] = [];
    }

    if (!isset($aOcup[$v['id_li']]) ) {
        $aOcup[$v['id_li']] = transporteErp::ocupacao($v['id_li'], 1);
    }

    $v['vagas'] = $v['capacidade'] - $aOcup[$v['id_li']];
    $dEmp[$v['id_em']]['inst'][$v['id_inst']]['dados'][] = $v;
}

foreach ($dEmp as $k => $v) {
    $email_dest = explode(";", $v['email']);

    foreach ($v['inst'] as $id_inst => $aInst) {
        $aInst['mes'] = $mes;
        foreach ($email_dest as $email) {
            if (mailer::enviaEmailTransporteListaEspera($email, $aInst)) {
                toolErp::divAlert("success", "E-mail enviado com sucesso para <strong>$email</strong>");
            } else {
                toolErp::divAlert("danger", "Falha no envio de E-mail para <strong>$email</strong>");
            }
        }
    }
}
            
