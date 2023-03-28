<?php
if (!defined('ABSPATH'))
    exit;
ob_start();
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
            padding-top: 2px;
            padding-bottom: 2px;
        }
    </style>
</head>
<?php
$sit = @$_SESSION['TMP']['SIT'];
$id_evento = @$_SESSION['TMP']['FORM'];
$id_cate = @$_SESSION['TMP']['CATE'];
$cpf = @$_SESSION['TMP']['CPF'];

if ($sit) {
    $fields = ' id_ec, id_cpf, nome, nome_social, pai, mae, filhos, rg, rg_dig, rg_oe, dt_nasc, '
            . ' fk_id_civil, sexo, email, logradouro, num, complemento, '
            . ' bairro, cidade, uf, cep, fixo, celular, whatsapp, '
            . ' c.fk_id_sit, dt_inscr, '
            . ' n_cate, descr_cate ';
    
    $sql = "SELECT $fields FROM inscr_incritos_$id_evento i "
            . " JOIN inscr_evento_categoria c on c.fk_id_cpf = i.id_cpf "
            . " JOIN inscr_categoria ct on ct.id_cate = c.fk_id_cate "
            . " WHERE i.id_cpf LIKE '$cpf' "
            . " AND c.fk_id_cate = $id_cate "
            . " AND c.fk_id_sit !=1";

    $query = pdoSis::getInstance()->query($sql);
    $dados = $query->fetchAll(PDO::FETCH_ASSOC);
}

$pdf = new pdf();

$pdf->cabecalhoSecretaria();

$pdf->mgl = 15;
$pdf->mgr = 15;
$pdf->mgt = 28;
$pdf->mgb = 10;
$pdf->mgh = 10;
$pdf->mgf = 10;

if (!empty($dados)) {
    foreach ($dados as $v) {
        ?>
        <div class=" body" style="border: 1px solid">
            <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
                Cadastro Municipal de Professor Eventual - CADAMPE
            </div>
            <table style="width:100%">
                <tr>
                    <td class="topo" style="width: 40%">
                        Processo Seletivo
                    </td>
                    <td class="topo" style="width: 60%">
                        <?= 'PROCESSO SELETIVO CADAMPE' ?>
                    </td>
                </tr>
                <tr>
                    <td class="topo" style="width: 40%">
                        Nome
                    </td>
                    <td class="topo" style="width: 60%">
                        <?= $v['nome'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="topo" style="width: 40%">
                        Inscrição
                    </td>
                    <td class="topo" style="width: 60%">
                        <?= str_pad($v['id_ec'], 5, '0', STR_PAD_LEFT) . '/' . date('Y') ?>
                    </td>
                </tr>
                <tr>
                    <td class="topo" style="width: 40%">
                        Tipo de Cadastro
                    </td>
                    <td class="topo" style="width: 60%">
                        <?= $v['n_cate'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="topo" style="width: 40%">
                        Data da Inscrição (Data/Hora)
                    </td>
                    <td class="topo" style="width: 60%">                       
                        <?php
                        $dd = date_create($v['dt_inscr']);
                        echo date_format($dd, "d/m/Y H:i:s");
                        ?>
                    </td>
                </tr>
                <br />
                <tr>
                    <td colspan="2" class="topo" style="color: red">
                        Dados Pessoais
                    </td>
                </tr>
                <tr>
                    <td class="topo" style="width: 40%">
                        Endereço
                    </td>
                    <td class="topo" style="width: 60%">
                        <?= $v['logradouro'] . ', Nº ' . $v['num'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="topo" style="width: 40%">
                        Complemento
                    </td>
                    <td class="topo" style="width: 60%">
                        <?= $v['complemento'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="topo" style="width: 40%">
                        Bairro
                    </td>
                    <td class="topo" style="width: 60%">
                        <?= $v['bairro'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="topo" style="width: 40%">
                        Cidade
                    </td>
                    <td class="topo" style="width: 60%">
                        <?= $v['cidade'] . ' - ' . $v['uf'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="topo" style="width: 40%">
                        CEP
                    </td>
                    <td class="topo" style="width: 60%">
                        <?= $v['cep'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="topo" style="width: 40%">
                        Telefones
                    </td>
                    <td class="topo" style="width: 60%">
                        <?= $v['fixo'] . " (Fixo) " . $v['celular'] . " (Celular) " . " WhatsApp " . ($v['whatsapp'] == 1 ? "Sim" : "Não") ?>
                    </td>
                </tr>
                <tr>
                    <td class="topo" style="width: 40%">
                        E-mail
                    </td>
                    <td class="topo" style="width: 60%">
                        <?= $v['email'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="topo" style="width: 40%">
                        RG
                    </td>
                    <td class="topo" style="width: 60%">
                        <?= $v['rg'] . '-' . $v['rg_dig'] . ' ' . $v['rg_oe'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="topo" style="width: 40%">
                        CPF
                    </td>
                    <td class="topo" style="width: 60%">
                        <?= $v['id_cpf'] ?>
                    </td>
                </tr>
                <!--
                <tr>
                    <td class="topo" style="width: 40%">
                        Quantidade de filhos menores de 18 anos
                    </td>
                    <td class="topo" style="width: 60%">
                        <?= $v['filhos'] ?>
                    </td>
                </tr>
                -->
                <tr>
                    <td colspan="2" class="topo" style="color: red">
                        Declaração
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="topo">
                        Li e confirmo as informações.
                    </td>
                </tr>
            </table>
                <br /><br /><br /><br /><br />
                <div class="topo" style="text-align: right; border-style: none; "><?= CLI_CIDADE ?>, <?php echo date('d') . ' ' . data::mes(date('m')) . ' ' . date('Y') . '.' ?></div>
                <br /><br /><br /><br />
                <div style="text-align: center">_____________________________________________________________</div>
                <div style="text-align:center"><?= $v['nome'] ?></div>
                <br /><br /><br /><br /><br />               
        </div>
        <?php
    }
}
$pdf->exec();
?>