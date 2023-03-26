<?php
if (!defined('ABSPATH'))
    exit;
$substring = filter_input(INPUT_POST, 'substring', FILTER_SANITIZE_STRING);
$rangeMi = filter_input(INPUT_POST, 'rangeMi', FILTER_SANITIZE_STRING);
$rangeMa = filter_input(INPUT_POST, 'rangeMa', FILTER_SANITIZE_STRING);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_equipamento = filter_input(INPUT_POST, 'id_equipamento', FILTER_SANITIZE_NUMBER_INT);
$equipamento = $model->equipamentoSelect();
$escola = $model->getInst();
//$escola = escolas::idEscolas();
$hoje = date("Y-m-d");
if (!empty($id_equipamento)) {
    @$lote = sql::get('recurso_serial', 'count(id_serial) as ct', ['fk_id_equipamento' => $id_equipamento], 'fetch')['ct'];
}
if (empty($id_inst)) {
    $id_inst = 13;
}
$n_inst_get = sql::get('instancia', 'n_inst', ['id_inst' => $id_inst], 'fetch');
    if (!empty($n_inst_get)) {
        $n_inst = $n_inst_get['n_inst'];
    }
?>

<div class="body">
    <div class="fieldTop">
        Categoria: <?= $_SESSION['userdata']['n_categoria'] ?>
        <?= $model->info("Para alterar a Categoria, utilize a página 'Início' no menu") ?>
    </div>
    <form method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::select('id_equipamento', $equipamento, 'Modelo/Lote', $id_equipamento, null, null,' required onchange="esconde()"') ?>
            </div>
            <div class="col">
                <?= formErp::select('id_inst', $escola, ['Escola', 'Secretaria de Educação'], $id_inst,null,null, 'onchange="esconde()"') ?>
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
if (!empty($id_equipamento)) {
        ?>
    <div id='itens'>
        <form id="form" onsubmit="enviar()" target="frame" method="POST" action="<?= HOME_URI ?>/recurso/def/loteItens.php">

            <?=
            formErp::hidden([
                '1[fk_id_equipamento]' => $id_equipamento,
                '1[fk_id_inst]' => $id_inst,
                '1[fk_id_pessoa_aloca]' => toolErp::id_pessoa(),
                '1[dt_update]' => $hoje,
                '1[pode_excluir]' => 1,
                'substring' => $substring,
                'rangeMi' => $rangeMi,
                'rangeMa' => $rangeMa
            ]);
            ?>
            <div class="row">
                <div class="col-4 text-center">
                    <?= formErp::input('1[n_serial]', 'Nº de Série', null, ' id="sr" autofocus ') ?>
                    <br />
                    <?= formErp::button('Salvar') ?>
                </div>
                <div  class="col-4">
                    <div class=" mensagens">
                        <div class="mensagem mensagemLinha-0" >
                            <div>
                                 <p class="tituloBox box-0"><?= $equipamento[$id_equipamento] ?></p>
                                 <p id="lote" class="tituloBox box-0" style="font-size: 45px;margin-bottom: 6px;margin-top: -6px;">. . .</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div  class="col-4">
                    <div class=" mensagens">
                        <div class="mensagem mensagemLinha-1" >
                            <div>
                                 <p class="tituloBox box-1"><?= $n_inst ?></p>
                                 <p id="loteEsc" class="tituloBox box-1" style="font-size: 45px;margin-bottom: 6px;margin-top: -6px;">. . .</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br />
        </form>
        <div class="row">
            <div  class="col">
                <div class=" mensagens">
                    <div class="mensagem mensagemLinha-0" >
                        <iframe id="frame" src="<?= HOME_URI ?>/recurso/def/loteItens.php?id_inst=<?= $id_inst ?>&id_equipamento=<?= $id_equipamento ?>" name="frame"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>
        $(document).ready(function () {
            enviar()
        });
        function enviar() { 
            setTimeout(function () {
                document.getElementById('sr').value = '';
            }, 1000);
        }
        function esconde(){
            var itens = document.getElementById('itens');
            itens.style.display = "none";
        }
    </script>

    <?php
}