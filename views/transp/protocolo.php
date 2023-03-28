<?php
$id_at = filter_input(INPUT_POST, 'id_at', FILTER_SANITIZE_NUMBER_INT);
$dados = $model->protocolo($id_at);
$destino_ = unserialize(base64_decode($model->_setup['destino']));
foreach ($destino_ as $k => $v) {
    $destino[$k] = $v['nome'];
}
?>
<div style="text-align: center; font-weight: bold; font-size: 16px ">
    Departamento Técnico de Conservação de Patrimonio e Transporte Escolar 
</div>
<div style="text-align: center; font-weight: bold; ">
    Solicitação de Transporte adaptado
</div>
<style>
    td{
        padding: 3px
    }
</style>
<br /><br />
<table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
    <tr>
        <td colspan="3">
            Nome: <?= $dados['n_pessoa'] ?> 
        </td>
        <td>
            Idade: <?= data::converteBr($dados['dt_nasc']) ?> (<?= data::idade($dados['dt_nasc']) ?> Anos)
        </td>
    </tr>
    <tr>
        <td>
            Cadeirante? <?= tool::simnao($dados['cadeirante']) ?>
        </td>
        <td colspan="3">
            Tipo de deficiência: <?= $dados['tp_def'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            Responsável: <?= $dados['responsavel'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            CPF: (respons.) <?= $dados['cpf_respons'] ?>
        </td>
        <td colspan="2">
            RG: (respons.): <?= $dados['rg_respons'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            Tel. Res.: (respons.) <?= $dados['tel1'] ?>
        </td>
        <td colspan="2">
            Tel. Cel.: (respons.): <?= $dados['tel2'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            End: <?= $dados['logradouro'] ?>, <?= $dados['num'] ?>
        </td>
    </tr>
    <tr>
        <td>
            CEP: <?= $dados['cep'] ?>
        </td>
        <td colspan="3">
            Bairro: <?= $dados['bairro'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            Escola: <?= $dados['n_inst'] ?>
        </td>
    </tr>
</table>
<br />
<table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
    <tr>
        <td colspan="4">
            Destino: <?= $destino[$dados['destino']] ?>
        </td>
        <td colspan="6">
            Status: <?= $dados['n_sa'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            2º Feira
        </td>
        <td colspan="2">
            3º Feira
        </td>
        <td colspan="2">
            4º Feira
        </td>
        <td colspan="2">
            5º Feira
        </td>
        <td colspan="2">
            6º Feira
        </td>
    </tr>
    <tr>
        <td>
            Entrada
        </td>
        <td>
            Saída
        </td>
        <td>
            Entrada
        </td>
        <td>
            Saída
        </td>
        <td>
            Entrada
        </td>
        <td>
            Saída
        </td>
        <td>
            Entrada
        </td>
        <td>
            Saída
        </td>
        <td>
            Entrada
        </td>
        <td>
            Saída
        </td>
    </tr>
    <tr>
        <td>
            <?= @$dados['e2'] ?>
        </td>
        <td>
            <?= @$dados['s2'] ?>
        </td>
        <td>
            <?= @$dados['e3'] ?>
        </td>
        <td>
            <?= @$dados['s3'] ?>
        </td>
        <td>
            <?= @$dados['e4'] ?>
        </td>
        <td>
            <?= @$dados['s4'] ?>
        </td>
        <td>
            <?= @$dados['e5'] ?>
        </td>
        <td>
            <?= @$dados['s5'] ?>
        </td>
        <td>
            <?= @$dados['e6'] ?>
        </td>
        <td>
            <?= @$dados['s6'] ?>
        </td>
    </tr>
</table>
<br />
<div style="border: #000000 solid 1px; width: 100%; padding: 5px; text-align: justify">
    É de minha responsabilidade, conduzir o aluno da residência até a Unidade Escolar, sabendo que o condutor não está autorizado a entrar na minha residência, bem como, só poderão aguardar até 5 (cinco) minutos após o horário previsto de embarque, não podendo desviar da rota prevista ou realizar paradas que não sejam exclusivas deste serviço. 
    Comprometo-me a acompanhar o aluno durante o trajeto realizado pelo Transporte Escolar Adaptado. 
    Em caso de falta do aluno, o condutor deverá ser comunicado com antecedência. Em caso de manutenção do veículo, o responsável será comunicado, caso não haja outra forma de transporte para o aluno.
</div>
<br /><br />
<div style="text-align: right; padding: 10px">
    <?= CLI_CIDADE ?>, <?= intval(date("d")) ?> de <?= data::mes(date("m")) ?> de <?= date("Y") ?>
</div>
<br /><br /><br /><br /><br /><br />
<div style="width: 300px; text-align: center">
    <hr>
    <br />
    Assinatura do Responsável pelo aluno
</div>
<?php
tool::pdfEscola();
