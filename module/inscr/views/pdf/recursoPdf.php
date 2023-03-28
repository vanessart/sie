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
$id_rec = filter_input(INPUT_POST, 'id_rec', FILTER_SANITIZE_NUMBER_INT);

$pdf = new pdf();

$pdf->cabecalhoSecretaria();

$pdf->mgl = 15;
$pdf->mgr = 15;
$pdf->mgt = 28;
$pdf->mgb = 10;
$pdf->mgh = 10;
$pdf->mgf = 10;

if ($id_rec) {
    $sql = "SELECT * FROM inscr_recurso r "
            . " JOIN inscr_evento_categoria c on c.id_ec = r.fk_id_ec "
            . " join inscr_categoria cat on cat.id_cate = c.fk_id_cate "
            . " JOIN inscr_incritos_" . $model->evento . " i on i.id_cpf = c.fk_id_cpf "
            . " WHERE r.id_rec = $id_rec ";
    $query = pdoSis::getInstance()->query($sql);
    $ar = $query->fetch(PDO::FETCH_ASSOC);
    if ($ar) {
        ?>
        <div class=" body" style="border: 1px solid">
            <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
                Cadastro Municipal de Professor Eventual - CADAMPE
            </div>
            <br />
            <div style="text-align: center">
                Processo Seletivo Se Nº 002/2022   
            </div>
            <div style="padding: 25px">
                <p>
                    Requerimento De Recurso 
                </p>
                <p>
                    À Comissão Especial da Secretaria de Educação.
                </p>
                <p>
                    Número da inscrição:   <?= str_pad($ar['id_ec'], 4, "0", STR_PAD_LEFT) ?>/2022
                </p>
                <p>
                    Nome completo: <?= $ar['nome'] ?>
                </p>
                <p>
                    Função:   <?= $ar['n_cate'] ?>
                </p>
                <p>
                    otivo do indeferimento: <?= $ar['motivo'] ?>
                </p>
            </div>
            <br /><br /><br /><br /><br />
            <div class="topo" style="text-align: right; border-style: none; padding-right: 140px"><?= CLI_CIDADE ?>, <?php echo date('d') . ' ' . data::mes(date('m')) . ' ' . date('Y') . '.' ?></div>
            <br /><br /><br /><br />
            <div style="text-align: center">_____________________________________________________________</div>
            <div style="text-align:center"><?= $ar['nome'] ?></div>
            <br /><br /><br /><br /><br />   
            <?php
        } else {
            exit();
        }
        $pdf->exec();
    }