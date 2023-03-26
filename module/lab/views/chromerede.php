<?php
if (!defined('ABSPATH'))
    exit;
$escola = $model->escolasOpt();
$serial = filter_input(INPUT_POST, 'serial', FILTER_SANITIZE_STRING);
$modelo = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_NUMBER_INT);
$destino = filter_input(INPUT_POST, 'destino', FILTER_SANITIZE_NUMBER_INT);
$situacao = filter_input(INPUT_POST, 'situacao', FILTER_SANITIZE_STRING);
$resp = filter_input(INPUT_POST, 'resp', FILTER_SANITIZE_STRING);
$cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
$rm = filter_input(INPUT_POST, 'rm', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$sitList = sql::idNome('lab_chrome_status');
$resumoTp = filter_input(INPUT_POST, 'resumoTp', FILTER_SANITIZE_NUMBER_INT);
$id_ch = filter_input(INPUT_POST, 'id_ch', FILTER_SANITIZE_NUMBER_INT);
unset($sitList[0]);
unset($sitList[3]);
$sitList['x'] = 'Indefinido';
$par = [
    'serial' => $serial,
    'modelo' => $modelo,
    'destino' => $destino,
    'situacao' => $situacao,
    'resp' => $resp,
    'cpf' => $cpf,
    'rm' => $rm,
    'email' => $email,
    'id_inst' => $id_inst,
    'resumoTp' => $resumoTp,
    'id_ch' => $id_ch,
];
?>
<div class="body">
    <div class="fieldTop">
        <?php
        if (empty($resumo)) {
            echo 'Consulta Geral - Chromebooks';
        } else {
            echo 'Resumo - Chromebooks';
        }
        ?>

    </div>
    <?php
    if (!empty($resumo)) {
        $abas[1] = ['nome' => "Escolas", 'ativo' => 1, 'hidden' => ['resumoTp' => 1]];
        $abas[2] = ['nome' => "Secretaria de Educação", 'ativo' => 1, 'hidden' => ['resumoTp' => 2]];
        $aba = report::abas($abas, ["secondary", "primary", "outline-secondary"]);
        $resumoTp = $par['resumoTp'] = empty($resumoTp) ? 1 : $resumoTp;
        ?>
        <br /><br />
        <?php
    }
    ?>
    <form method="POST">
        <div class="border5">
            <div class="row">
                <div class="col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                Nº de Série
                            </div>
                            <input class="form-control" type="text" name="serial" list="opt" value="<?= $serial ?>" onclick="$(this).select()">
                            <datalist id="opt">
                                <option value="0">Carregando...</option>
                            </datalist> 
                        </div>
                    </div>
                </div>
                <div class="col">
                    <?= formErp::selectDB('lab_chrome_modelo', 'modelo', 'Modelo', $modelo) ?>
                </div>
                <div class="col">
                    <?= formErp::selectDB('lab_chrome_destino', 'destino', 'Destino', $destino) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col">
                    <?= formErp::input('resp', 'Responsável', $resp) ?>
                </div>
                <div class="col">
                    <?= formErp::input('cpf', 'CPF (só número)', $cpf) ?>
                </div>

            </div>
            <br />
            <div class="row">
                <div class="col">
                    <?= formErp::input('rm', 'Matrícula', $rm) ?>
                </div>
                <div class="col">
                    <?= formErp::input('email', 'E-mail', $email) ?>
                </div>
            </div>
        </div>
        <br />
        <?php
        if (empty($resumoTp) || $resumoTp == 1) {
            ?>
            <div class="border5">
                <div class="row">
                    <div class="col">
                        <?= formErp::select('id_inst', $escola, 'Escola', $id_inst); ?>
                    </div>
                    <div class="col">
                        <?= formErp::select('situacao', $sitList, 'Situação na Escola', $situacao) ?>
                    </div>
                </div>
            </div>
            <br />
            <?php
        }
        ?>
        <div class="row">
            <div class="col text-center">
                <button onclick="document.getElementById('limpar').submit();" class="btn btn-warning" type="button">
                    Limpar
                </button>
            </div>
            <?= formErp::hidden(['activeNav' => @$aba, 'resumoTp' => $resumoTp]) ?>
            <div class="col text-center">
                <button class="btn btn-info" type="submit">
                    Filtrar
                </button>
            </div>
            <div class="col text-center">
                <button type="button" onclick="document.getElementById('export').submit();" class="btn btn-primary" >
                    Exportar (Excel)
                </button>
            </div>
        </div>
        <br />
    </form>
    <form id="limpar">
    </form>
    <form id="export" target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
        <?php
        $sql = $model->chromeRedeSql($par)
        ?>
        <input type="hidden" name="tokenSql" value="<?php echo tool::tokenSql() ?>" />
        <input type="hidden" name="sql" value="<?php echo $sql ?>" />

    </form> 
    <br /><br />
    <?php
    if (empty($resumo)) {
        $model->relatGeral($par);
    } else {
        include ABSPATH . '/module/lab/views/resumo.php';
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/lab/def/formChromeRede.php" target="frame" id="formFrame" method="POST">
    <input id="id_ch" type="hidden" name="id_ch" value="" />
</form>
<?php
toolErp::modalInicio(null, null, null, 'Chromebook');
?>
<iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    // In your Javascript (external .js resource or <script> tag)

    window.onload = function () {
        fetch('https://portal.educ.net.br/ge/api/chrome/serialSelect')
                .then(resp => resp.text())
                .then(resp => {
                    document.getElementById('opt').innerHTML = resp;
                })

    };
    function ch(id) {
        document.getElementById('id_ch').value = id;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }

</script>
