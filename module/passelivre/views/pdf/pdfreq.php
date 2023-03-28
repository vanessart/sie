<?php
if (!defined('ABSPATH'))
    exit;

ob_start();
$id_passelivre = filter_input(INPUT_POST, 'id_passelivre', FILTER_SANITIZE_NUMBER_INT);
$dados = sql::get('pl_passelivre', '*', ['id_passelivre' => $id_passelivre], 'fetch');
$st = $model->pegastatus();
$esc = $model->pegaescolas();
?>
<head>
    <style>
        .topo{
            font-size: 8pt;
            font-weight:bold;
            text-align: left;
            border-style: solid;
            border-width: 0.5px;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 5px;
            padding-bottom: 5px;
        }
        .topo2{
            font-size: 8pt;
            font-weight:bold;
            text-align: center;
            border-style: solid;
            border-width: 0.5px;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 5px;
            padding-bottom: 5px;
        }
    </style>
</head>

<table style="width:100%">
    <tr>
        <td colspan="4" class="topo2">
            <div>
                REQUERIMENTO PARA GRATUIDADE DE TRANSPORTE PÚBLICO PARA ESTUDANTES
                <br />
                DECRETO: Nº 9415 DE 14 DE SETEMBRO DE 2021.
                <br />
            </div>
        </td>
        <td colspan="2" class="topo2">
            Nº: <?= str_pad($dados['id_passelivre'], 5, '0', STR_PAD_LEFT) . '/' . date('Y') ?>
        </td>
    </tr>
    <tr>
        <td colspan="4" class="topo">
            NOME: <?= $dados['nome'] ?>
        </td>
        <td colspan="2" class="topo">
            REGISTRO MUNICIPAL: <?= $dados['reg_mun'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="topo" class="topo">
            CPF: <?= $dados['cpf'] ?>
        </td>
        <td colspan="2" class="topo">
            RG: <?= $dados['rg'] ?>
        </td>
        <td colspan="2"  class="topo">
            SEXO: <?= $dados['sexo'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="topo">
            DATA DE NASCIMENTO: <?= data::converteBr($dados['dt_nasc']) ?>
        </td>
        <td colspan="2" class="topo">
            TELEFONE: <?= $dados['tel1'] ?>
        </td>
        <td colspan="2" class="topo">
            CELULAR: <?= $dados['tel2'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="topo">
            ENDEREÇO: <?= $dados['logradouro'] ?>
        </td>
        <td class="topo">
            Nº: <?= $dados['num'] ?>
        </td>
        <td colspan="2" class="topo">
            COMP.: <?= $dados['complemento'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="topo">
            BAIRRO: <?= $dados['bairro'] ?>
        </td>
        <td class="topo">
            CEP: <?= $dados['cep'] ?>
        </td>
        <td colspan="2" class="topo">
            CIDADE: <?= $dados['municipio'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="6" class="topo" >
            MÃE OU RESPONSÁVEL: <?= $dados['nome_mae'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="6" class="topo">
            PAI OU RESPONSÁVEL: <?= $dados['nome_pai'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="topo">
            NÚMERO DE PESSOAS QUE MORAM NA CASA: <?= $dados['qte_pessoa'] ?>
        </td>
        <td colspan="3" class="topo"> 
            RENDA TOTAL DE TODOS QUE MORAM NA CASA: R$ <?= $dados['renda_familiar'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="6" class="topo">
            UTILIZA INTEGRAÇÃO: <?= $dados['util_integracao'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="6" style="text-align: center; border-style: solid; font-weight: bold; font-size: 8pt; border-width: 0.5px; padding: 5px; background-color: #000000; color: #ffffff">
            DADOS DA UNIDADE ESCOLAR
        </td>
    </tr>
    <tr>
        <td colspan="6" style="text-align: left; border-style: solid; font-weight: bold; font-size: 8pt; padding: 5px; border-width: 0.5px">
            NOME DA UNIDADE ESCOLAR: <?= $esc[$dados['cie']] ?>
        </td>   
    </tr>
</table>

<div style="text-align: justify; border: 1px solid; font-weight: bold; font-size: 12px">
    <br /><br />
    À Secretaria de Educação
    <br /><br />
    Requeiro a emissão do cartão “Passe Livre”, para fins da gratuidade no serviço 
    convencional simples a comercial do Sistema de Transporte Coletivo Rodoviário Municipal
    de Passageiros no Município de Barueri.
    <br /><br />
    Afirmo que as informações acima são verdadeiras, sob pena de ser responsabilizado civil
    e criminalmente, inclusive com a perda do benefício.
    <br /><br />
    Nestes termos, peço deferimento.
    <br /><br />
    Em conformidade com o Decreto Nº 9415 de 14 de Setembro de 2021, homologo o presente requerimento,
    autorizando à emissão da carteira “Passe Livre”.
    <br /><br />
</div>
<?php
if (empty($dados['dt_requerimento'])) {
    $d = Date('Y-m-d');
    $data_r = explode('-', $d);
} else {
    $data_r = explode('-', $dados['dt_requerimento']);
}
?>
<table style="width: 100%">
    <tr>
        <td colspan="2" class="topo">
            DATA:
            <br />
            <?= 'Barueri, ' . $data_r[2] . ' de ' . data::mes(date($data_r[1])) . ' de ' . $data_r[0] . '.' ?>          
        </td>
        <td colspan="4" class="topo2">
            SECRETARIA DE EDUCAÇÃO:
            <br /><br /><br /><br />
        </td>
    </tr>
    <?php
    if ($dados['fk_id_pl_status'] != 1) {
        ?>
        <tr>
            <td colspan="6" class="topo" style="color:red">
                <?= 'Status: ' . $st[$dados['fk_id_pl_status']] ?>
            </td>
        </tr>
        <?php
    }
    ?>
    <tr>
        <td colspan="6" style="font-size: 8px">
            <?= CLI_END ?> - <?= CLI_BAIRRO ?> e-mail: <?= CLI_MAIL ?>  Telefone: <?= CLI_FONE ?> 
        </td>
    </tr>
</table>
<br /><br />
<div>
    <p style="text-align: center"> - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</p>
</div>
<br /><br /><br />
<table style="width:100%">
    <tr>
        <td colspan="6" class="topo2"> 
            Protocolo de Requerimento
        </td>
    </tr>
    <tr>
        <td colspan="4" class="topo">
            <div>
                REQUERIMENTO PARA GRATUIDADE DE TRANSPORTE PÚBLICO PARA ESTUDANTES
                <br />
                DECRETO: Nº 9415 DE 14 DE SETEMBRO DE 2021.
                <br />
            </div>
        </td>
        <td colspan="2" class="topo2">
            Nº: <?= str_pad($dados['id_passelivre'], 5, '0', STR_PAD_LEFT) . '/' . date('Y') ?>
        </td>
    </tr>
    <tr>
        <td colspan="4" class="topo">
            NOME: <?= $dados['nome'] ?>
        </td>
        <td colspan="2" class="topo">
            REGISTRO MUNICIPAL: <?= $dados['reg_mun'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="topo">
            Atendente: <?= user::session('nome') ?>
        </td>
        <td colspan="3" class="topo">
            Ligar para confirmação dia: <?= date('d/m/Y', strtotime('+7 days')); ?>
        </td>
    </tr>
    <?php
    if ($dados['fk_id_pl_status'] != 1) {
        ?>
        <tr>
            <td colspan="6" class="topo" style="color:red">
                <?= 'Status: ' . $st[$dados['fk_id_pl_status']] ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
<?php
tool::pdf2();
?>