<?php
if (!defined('ABSPATH'))
    exit;
$cpf = $periodo = filter_input(INPUT_POST, 'cpf');
$func = sql::get(['inscr_evento_categoria', 'inscr_categoria'], 'id_ec, n_cate', ['fk_id_cpf' => $cpf]);
foreach ($func as $v) {
    $cargos[] = str_pad($v['id_ec'], 5, '0', STR_PAD_LEFT) . '/' . date("Y") . ' - ' . $v['n_cate'];
}
$v = sql::get('inscr_incritos_3', '*', ['id_cpf' => $cpf], 'fetch');
ob_start();
$pdf = new pdf();
$pdf->cabecalhoSecretaria();

$pdf->mgl = 15;
$pdf->mgr = 15;
$pdf->mgt = 28;
$pdf->mgb = 10;
$pdf->mgh = 10;
$pdf->mgf = 10;
?>
<div style="font-size: 12px">
    <div style="font-weight:bold; font-size:12px; background-color: #000000; color:#ffffff; text-align: center">
        Cadastro Municipal de Professor Eventual - CADAMPE
    </div>
    <table style="width: 100%;font-size:12px;" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
        <tr>
            <td class="topo" >
                Inscrição
            </td>
            <td class="topo" >
                <?php
                echo '<p>' . implode('</p><p>', $cargos) . '</p>'
                ?>
            </td>
        </tr>
        <tr>
            <td class="topo" >
                Nome
            </td>
            <td class="topo" >
                <?= $v['nome'] ?>
            </td>
        </tr>  
        <tr>
            <td class="topo" >
                D. Nascimento
            </td>
            <td class="topo" >
                <?= data::converteBr($v['dt_nasc']) ?>
            </td>
        </tr>  
        <tr>
            <td class="topo" >
                RG
            </td>
            <td class="topo" >
                <?= $v['rg'] . '-' . $v['rg_dig'] . ' ' . $v['rg_oe'] ?>
            </td>
        </tr>
        <tr>
            <td class="topo" >
                CPF
            </td>
            <td class="topo" >
                <?= $v['id_cpf'] ?>
            </td>
        </tr>
        <tr>
            <td class="topo" >
                PIS/PASEP
            </td>
            <td class="topo" >
                <?= $v['pis'] ?>
            </td>
        </tr>
        <tr>
            <td class="topo" >
                Endereço
            </td>
            <td class="topo" >
                <?= $v['logradouro'] . ', Nº ' . $v['num'] ?>
            </td>
        </tr>
        <tr>
            <td class="topo" >
                Complemento
            </td>
            <td class="topo" >
                <?= $v['complemento'] ?>
            </td>
        </tr>
        <tr>
            <td class="topo" >
                Bairro
            </td>
            <td class="topo" >
                <?= $v['bairro'] ?>
            </td>
        </tr>
        <tr>
            <td class="topo" >
                Cidade
            </td>
            <td class="topo" >
                <?= $v['cidade'] . ' - ' . $v['uf'] ?>
            </td>
        </tr>
        <tr>
            <td class="topo" >
                CEP
            </td>
            <td class="topo" >
                <?= $v['cep'] ?>
            </td>
        </tr>
        <tr>
            <td class="topo" >
                Telefones
            </td>
            <td class="topo" >
                <?= $v['fixo'] . " (Fixo) " . $v['celular'] . " (Celular) " . " WhatsApp " . ($v['whatsapp'] == 1 ? "Sim" : "Não") ?>
            </td>
        </tr>
        <tr>
            <td class="topo" >
                E-mail
            </td>
            <td class="topo" >
                <?= $v['email'] ?>
            </td>
        </tr>

        <tr>
            <td colspan="2" class="topo" style="color: red; text-align: center">
                Conta Bancário
            </td>
        </tr>
        <tr>
            <td colspan="2" class="topo">
                <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
                    <tr>
                        <td>
                            Banco: <?= $v['conta_banco'] ?>
                        </td>
                        <td>
                            Agencia: <?= $v['conta_agencia'] ?>
                        </td>
                        <td>
                            Conta: <?= $v['conta_num'] ?>-<?= $v['conta_dig'] ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
<br><br>
Li e confirmo as informações.
<br /><br /><br /><br /><br />
<div class="topo" style="text-align: right; border-style: none; ">Barueri, <?php echo date('d') . ' ' . data::mes(date('m')) . ' ' . date('Y') . '.' ?></div>
<br /><br /><br /><br />
<div style="text-align: center">_____________________________________________________________</div>
<div style="text-align:center"><?= $v['nome'] ?></div>
<?php
$pdf->exec();
?>

