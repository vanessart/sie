<?php
if (!defined('ABSPATH'))
    exit;
$substring = filter_input(INPUT_POST, 'substring', FILTER_SANITIZE_STRING);
$rangeMi = filter_input(INPUT_POST, 'rangeMi', FILTER_SANITIZE_STRING);
$rangeMa = filter_input(INPUT_POST, 'rangeMa', FILTER_SANITIZE_STRING);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_cm = filter_input(INPUT_POST, 'id_cm', FILTER_SANITIZE_NUMBER_INT);
$modelo = sql::idNome('lab_chrome_modelo');
$escola = escolas::idEscolas();
$hoje = date("Y-m-d");
if (!empty($id_cm)) {
    @$lote = sql::get('lab_chrome', 'count(id_ch) as ct', ['fk_id_cm' => $id_cm], 'fetch')['ct'];
}
?>
<style>
    #frame{
        width: 100%;
        height: 60vh; 
        border: solid 1px #000;
        border-radius: 3px;
    }

</style>
<div class="body">
    <form method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::select('id_cm', $modelo, 'Modelo', $id_cm, null, null, ' required ') ?>
            </div>
            <div class="col">
                <?= formErp::select('id_inst', $escola, ['Escola', 'Secretaria de Educação'], $id_inst) ?>
            </div>
            <input type="hidden" name="tipo" value="2" />
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::input('substring', 'Prefixo', $substring) ?>
            </div>
            <div class="col">
                <?= formErp::input('rangeMi', 'Caracteres Mínimos', $rangeMi, null, null, 'number') ?>
            </div>
            <div class="col">
                <?= formErp::input('rangeMa', 'Caracteres Máximos', $rangeMa, null, null, 'number') ?>
            </div>
            <div class="col">
                <?= formErp::button('Continuar') ?>
            </div>
        </div>
        <br />
    </form>
    <br />
    <?php
    if (!empty($id_cm)) {
        if (empty($id_inst)) {
            $fk_id_cd = 5;
        } else {
            $fk_id_cd = 1;
        }
        ?>
        <form id="form" onsubmit="enviar()" target="frame" method="POST" action="<?= HOME_URI ?>/lab/def/loteItens.php">

            <?=
            formErp::hidden([
                '1[fk_id_cm]' => $id_cm,
                '1[fk_id_inst]' => $id_inst,
                '1[fk_id_cs]' => 1,
                '1[fk_id_cd]' => $fk_id_cd,
                '1[fk_id_pessoa_lanc]' => toolErp::id_pessoa(),
                '1[dt_cad]' => $hoje,
                '1[fk_id_modem]' => 1,
                'substring' => $substring,
                'rangeMi' => $rangeMi,
                'rangeMa' => $rangeMa
            ]);
            ?>
            <div class="row">
                <div class="col-4 text-center">
                    <?= formErp::input('1[serial]', 'Nº de Série', null, ' id="sr" autofocus ') ?>
                    <br />
                    <?= formErp::button('Salvar') ?>
                </div>
                <div id="lote" class="col-4"></div>
                <div id="loteEsc" class="col-4"></div>
            </div>
            <br />
        </form>
        <iframe id="frame" src="<?= HOME_URI ?>/lab/def/loteItens.php?id_inst=<?= $id_inst ?>" name="frame"></iframe>
    </div>
    <script>
        $(document).ready(function () {
            enviar()
        });
        function enviar() {
            document.getElementById('loteEsc').innerHTML = '...';
            document.getElementById('lote').innerHTML = '...';
            setTimeout(function () {
                document.getElementById('sr').value = '';
            }, 1000);
            setTimeout(function () {
                const dadosMd = 'id_cm=<?= $id_cm ?>&modelo=<?= $modelo[$id_cm] ?>';
                fetch(
                        '<?= HOME_URI ?>/lab/def/contLote.php'
                        , {
                            method: "POST",
                            body: dadosMd,
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            }
                        }
                )
                        .then(resp => resp.text())
                        .then(resp => {
                            document.getElementById('lote').innerHTML = resp;
                        });
                const dados = 'id_inst=<?= $id_inst ?>';
                fetch(
                        '<?= HOME_URI ?>/lab/def/contLoteEsc.php'
                        , {
                            method: "POST",
                            body: dados,
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            }
                        }
                )
                        .then(resp => resp.text())
                        .then(resp => {
                            document.getElementById('loteEsc').innerHTML = resp;
                        });
            }, 2000);
        }
    </script>

    <?php
}