<?php
if (!defined('ABSPATH'))
    exit;
$substring = filter_input(INPUT_POST, 'substring', FILTER_SANITIZE_STRING);
$rangeMi = filter_input(INPUT_POST, 'rangeMi', FILTER_SANITIZE_STRING);
$rangeMa = filter_input(INPUT_POST, 'rangeMa', FILTER_SANITIZE_STRING);
$id_equipamento = filter_input(INPUT_GET, 'id_equipamento', FILTER_SANITIZE_NUMBER_INT);
$ins = @$_POST[1];
if (empty($id_equipamento)) {
    $id_equipamento = filter_input(INPUT_POST, 'id_equipamento', FILTER_SANITIZE_NUMBER_INT);
}
if (empty($id_equipamento)) {
   $id_equipamento = @$ins['fk_id_equipamento'];
}

$id_inst = @$_REQUEST['id_inst'];
if (empty($id_inst)) {
    $id_inst = @$ins['fk_id_inst'];
}
if (!empty($_POST['fim'])) {
    $idsFim = @$_POST[1];
    foreach ($idsFim as $v) {
        if (!empty($v)) {
            $id[] = $v;
        }
    }
    if (!empty($id)) {
        $ids = implode(',', $id);
        $sql = "update recurso_serial set pode_excluir = 0 "
                . " where id_serial in ($ids) ";
        $query = pdoSis::getInstance()->query($sql);
    } else {
        toolErp::alert("É necessário selecionar um equipamento ou mais");
    }
}

?>
<style type="text/css">
    .titulo_anexo{
        color: #888;
        font-weight: bold;
        text-align: center;
        font-size: 30px;
    }
    .sub_anexo{
        font-weight: bold;
        text-align: center;
    }
    .sub2_anexo{
        font-weight: bold;
        text-align: center;
        FONT-SIZE: 14px;

    }s
    .mensagens {}
    .mensagens .mensagem {
        border: 1px solid #e1e1d1;
        box-shadow: #e1e1d1 5px 5px 14px;
       /* margin: 10px auto;*/
        padding: 4px;
        /*min-height: 80px;*/
    }
    .mensagens .nomePessoa {text-transform: lowercase;}
    .mensagens .nomePessoa:first-letter { text-transform: uppercase; }
    .mensagens .nomePessoa { 
        color: #888;
        font-weight: normal;
        font-size: 100%;
    }

    .mensagens .dataMensagem { 
        font-weight: bold;
        color: #888;
        font-size: 18px;
    }

    .tit_table{ 
        font-weight: bold;
        color: #888;
        font-size: 18px;
    }

    .mensagens .tituloHab{
        font-weight: bold;
        color: #7ed8f5;
        font-size: 100%; 
    }
    .mensagens .corpoMensagem {
        display: block;
        margin-bottom: 10px;
        font-weight: normal;        
        white-space: pre-wrap;
        padding: 5px;
        color: #888;
    }
    .mensagens .mensagemLinha-0{border-left: 5px solid #7ed8f5;}
    .mensagens .mensagemLinha-1{border-left: 5px solid #f3b4ef;}
    .mensagens .mensagemLinha-2{border-left: 5px solid #f9ca6e;}
    .esconde .input-group-text{ display: none; }
    .tituloBox{
        font-size: 17px;
        font-weight: bold;
        text-align: center;
    }
    .tituloBox.box-0{ color: #7ed8f5;}
    .tituloBox.box-1{ color: #f3b4ef;}
    .tituloBox.box-2{ color: #f9ca6e;}
</style>

    <?php
    if (!empty($ins['n_serial'])) {
        $ins['n_serial'] = trim($ins['n_serial']);
        $jaTem = sqlErp::get('recurso_serial', 'n_serial', ['n_serial' => $ins['n_serial']], 'fetch');
        $erro = null;
        if ($jaTem) {
            $erro = 1;
            ?>
            <div class="alert alert-danger">
                Erro: Equipamento com nº de Série <span style="font-weight: bold"><?= $ins['n_serial'] ?></span> já cadastrado';
            </div>
            <?php
        }
        if (!empty($substring)) {
            $tan = strlen($substring);
            if (substr($ins['n_serial'], 0, $tan) != $substring) {
                $erro = 1;
                ?>
                <div class="alert alert-danger">
                    Erro: Prefixo - N/S: <?= $ins['n_serial'] ?>
                </div>
                <?php
            }
        }
        if (!empty($rangeMi)) {
            if (strlen($ins['n_serial']) < $rangeMi) {
                $erro = 1;
                ?>
                <div class="alert alert-danger">
                    Erro: Menor que <?= $rangeMi ?> - N/S: <?= $ins['n_serial'] ?>
                </div>
                <?php
            }
        }
        if (!empty($rangeMa)) {
            if (strlen($ins['n_serial']) > $rangeMa) {
                $erro = 1;
                ?>
                <div class="alert alert-danger">
                    Erro: Maior que <?= $rangeMa ?> - N/S: <?= $ins['n_serial'] ?>
                </div>
                <?php
            }
        }
        if (empty($erro)) {
            ?>
            <div class="alert alert-primary">
                cadastrando
                <br />
                <?php
                $id = $model->db->insert('recurso_serial', $ins, 1);
                log::logSet("Cadastrou o N/S: ".$ins['n_serial']." - inst: ".$ins['fk_id_inst']);
                if ($id) {
                    ?>
                    cadastrado com sucesso
                </div>
                <?php
            }
        }
    }

    /////total
    if (!empty($id_equipamento)) {
        $sql = "SELECT count(id_serial) as ct FROM `recurso_serial` WHERE `fk_id_equipamento` = $id_equipamento ";
        $query = pdoSis::getInstance()->query($sql);
        @$ct_total = $query->fetch(PDO::FETCH_ASSOC)['ct'];
    }
    /////instancia
    $n_inst = "Este Lote";

    if (empty($id_inst)) {
        $sql = "SELECT count(id_serial) as ct FROM `recurso_serial` WHERE `fk_id_inst` is null AND fk_id_equipamento = $id_equipamento ";
        
    } else {
        $sql = "SELECT count(id_serial) as ct FROM `recurso_serial` WHERE `fk_id_inst` = $id_inst AND fk_id_equipamento = $id_equipamento ";
    }
    $query = pdoSis::getInstance()->query($sql);
    @$ct_inst = $query->fetch(PDO::FETCH_ASSOC)['ct'];

    $ct_inst = !empty($ct_inst) ? $ct_inst : 0;
    $ct_total = !empty($ct_total) ? $ct_total : 0;

    if (empty($id_inst)) {
        $ch = sql::get(['recurso_serial', 'recurso_equipamento'], '*', ['pode_excluir' => 1, '<' => 'id_serial']);
    } else {
        $ch = sql::get(['recurso_serial', 'recurso_equipamento'], '*', ['pode_excluir' => 1, 'fk_id_inst' => $id_inst, '<' => 'id_serial']);
    }
    if ($ch) {
        $token = formErp::token('recurso_serial', 'delete');
        foreach ($ch as $k => $v) {
            $ch[$k]['del'] = formErp::submit('Excluir', $token, ['1[id_serial]' => $v['id_serial'], 'id_inst' => $id_inst, 'id_equipamento' => $id_equipamento]);
            $ch[$k]['ck'] = '<input id="' . $v['id_serial'] . '" type="checkbox" name="" value="' . $v['id_serial'] . '" />';
        }
        $form['array'] = $ch;
        $form['fields'] = [
            '<input type="checkbox" name="select-all" id="select-all" />' => 'ck',
            'ID' => 'id_serial',
            'Nº de Série' => 'n_serial',
            'Equipamento' => 'n_equipamento',
            '||1' => 'del'
        ];
    }
    if (!empty($form)) {
        report::simple($form);?>
        <form id="formPdf" target="_blank" action="<?= HOME_URI ?>/recurso/lotePdf" method="POST">
            <?php
            foreach ($ch as $k => $v) {?>
                <input id="<?= $v['id_serial'] ?>_" type="hidden" name="1[<?= $v['id_serial'] ?>]" value="" />
                <?php
            }?>
            <input type="hidden" name="id_inst" value="<?= $id_inst ?>" />
        </form>
        <div class="container">
        <div class="row">
            <div class="col text-center">
                <div style="text-align: center">
                    <button class="btn btn-info" onclick="pdf()">
                        Imprimir
                    </button>
                </div>
            </div>
            <div class="col text-center">
                <button class="btn btn-warning" onclick="fim()">
                    Enviar para Escola
                </button>
            </div>
        </div>
        <br />
        <form id="formFim" method="POST">
            <?php
            foreach ($ch as $k => $v) {?>
                <input id="<?= $v['id_serial'] ?>x" type="hidden" name="1[<?= $v['id_serial'] ?>]" value="" />
                <?php
            }?>
            <input type="hidden" name="id_inst" value="<?= $id_inst ?>" />
            <input type="hidden" name="id_equipamento" value="<?= $id_equipamento ?>" />
            <input type="hidden" name="fim" value="1" />
        </form>
</div>
    <?php
    }
    ?>


<script>
    $('#select-all').click(function (event) {
        if (this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function () {
                this.checked = true;
            });
        } else {
            $(':checkbox').each(function () {
                this.checked = false;
            });
        }
    });
    function pdf() {
<?php
foreach ($ch as $k => $v) {
    ?>
            if (document.getElementById("<?= $v['id_serial'] ?>").checked) {
                document.getElementById("<?= $v['id_serial'] ?>_").value = document.getElementById("<?= $v['id_serial'] ?>").value;
            } else {
                document.getElementById("<?= $v['id_serial'] ?>_").value = '';
            }
    <?php
}
?>
        document.getElementById("formPdf").submit();
    }
    function fim() {
<?php
foreach ($ch as $k => $v) {
    ?>
            if (document.getElementById("<?= $v['id_serial'] ?>").checked) {
                document.getElementById("<?= $v['id_serial'] ?>x").value = document.getElementById("<?= $v['id_serial'] ?>").value;
            } else {
                document.getElementById("<?= $v['id_serial'] ?>x").value = '';
            }
    <?php
}
?>
        if (confirm("Esta ação irá tirar os equipamentos da lista e não será mais possível excluí-los nem imprimir o Termo. \n\nDeseja Continuar?")) {
            document.getElementById('formFim').submit();
        }
    }

    window.parent.document.getElementById('lote').innerHTML = '<?= $ct_total ?>';
    window.parent.document.getElementById('loteEsc').innerHTML = '<?= $ct_inst ?>';
</script>