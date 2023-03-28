<?php
if (!defined('ABSPATH'))
    exit;
ob_start();
$pdf = new pdf();
$pdf->id_inst = 13;
$pdf->headerAlt = '<img src="' . ABSPATH . '/includes/images/topo.jpg"/>';
$n_equipamento = filter_input(INPUT_POST, 'n_equipamento', FILTER_SANITIZE_STRING);
$serial = filter_input(INPUT_POST, 'n_serial', FILTER_SANITIZE_STRING);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_move = filter_input(INPUT_POST, 'id_move', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
if (empty($id_inst) || empty($id_move) || empty($id_pessoa)) {
    echo 'Não tenho a menor ideia do que você esta fazendo aqui :(';
    exit();
}
$data = date("Y-m-d");
$dados = sql::get('recurso_movimentacao', 'obs,dt_update', ['id_move' => $id_move ], 'fetch');
$itens = $model->itensGet(null,null,$id_move);
$e = new escola($id_inst);
$func = sql::get(['pessoa', 'ge_funcionario'], 'n_pessoa, rm, cpf, pessoa.emailgoogle, sexo ', ['id_pessoa' => $id_pessoa], 'fetch');
if (!empty($func)) {
   $nome = $func['n_pessoa'].' - '.$func['rm']; 
   $cpf = 'CPF <span style="font-weight: bold">'.$func['cpf'].'</span>'; 
} else {
    $alu = new aluno($id_pessoa);
    $nome = $alu->_nome. ' - '.$alu->_ra;
    $cpf = ' Responsável: '.$alu->_responsavel.', CPF do Responsável: <span style="font-weight: bold">'.$alu->_responsCpf.'</span>';
}?>
<style>
    td{
        padding: 3px;
    }
</style>
<div style="text-align: center; font-size: 22px; font-weight: bold">
    Devolução do Equipamento <?= $n_equipamento ?>
</div>
<br /><br /><br />
<div style="text-align: justify">
    <span style="font-weight: bold"><?= $nome ?></span>, <?= $cpf ?>, 
    devolveu o equipamento descrito abaixo.
</div>
<br />
<table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
    <tr>
        <td>
            Local de Devolução
        </td>
        <td>
            <?= $e->_nome ?>
        </td>
    </tr>
    <tr>
        <td>
            Data de Devolução
        </td>
        <td>
            <?= dataErp::converteBr($dados['dt_update']) ?>
        </td>
    </tr>
    <tr>
        <td>
            Número de Série
        </td>
        <td>
            <?= $serial ?>
        </td>
    </tr>
    <tr>
        <td>
            Modelo
        </td>
        <td>
            <?= $n_equipamento ?>
        </td>
    </tr>
    <tr>
        <td>
            Itens Devolvidos junto com o equipamento  
        </td>
        <td>
            <?= $itens ?>
        </td>
    </tr>
</table>
<br />
<div style="text-align: justify">
    <?php
    if (!empty($dados['obs'])) {?>
        Observações:
        <br /><br />
        <?= $dados['obs'] ?>
        <?php
    }?>
</div>
<div style="text-align: right; padding: 50px">
    <?= CLI_CIDADE ?>, <?= data::porExtenso($data) ?>
</div>

<div style="padding: 35px; width: 65%; margin-left: 35%">
    <hr>
    (Assinatura e carimbo do responsável do setor ou autenticação digital)
</div>

<?php
$pdf->exec();
