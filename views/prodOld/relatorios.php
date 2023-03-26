<?php
$id_pa = filter_input(INPUT_POST, 'id_pa', FILTER_SANITIZE_NUMBER_INT);
$visita = filter_input(INPUT_POST, 'visita', FILTER_SANITIZE_NUMBER_INT);
?>
<br /><br />
<div class="fieldBody">
    <div class="row">
        <div class="col-sm-8">
        </div>
        <div class="col-sm-4">
            <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                <?php
                $tokenSql = substr((date("yhdm") / 3.5288 * 68), 0, 20);
                $sql = "SELECT p.n_pessoa, pv.rm, i.n_inst, t.n_turma, pv.nota FROM prod1_visita pv JOIN pessoa p on p.id_pessoa = pv.fk_id_pessoa JOIN instancia i on i.id_inst = pv.fk_id_inst JOIN ge_turmas t on t.id_turma = pv.fk_id_turma";
                echo form::hidden(['sql' => $sql, 'tokenSql' => $tokenSql]);
                echo form::button('Exportar Professores');
                ?>
            </form> 
        </div>
    </div>
    <br /><br />
    <div class="fieldBorder2">
        Resultados finais
        <br /><br />
        <form target="_blank" action="<?= HOME_URI ?>/prod/finalpdf" method="POST">
            <div class="row">
                <div class="col-sm-8">
                    <?= form::select('id_inst', escolas::idEscolas(), 'Escola') ?>
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-info">
                        PDF
                    </button>
                </div>
            </div>
            <br />
        </form>
    </div>
    <br /><br />
    <div class="fieldBorder2">
        professores (menos informática) não avaliados
        <br /><br />
        <form target="_blank" action="<?= HOME_URI ?>/prod/finalnopdf" method="POST">
            <div class="row">
                <div class="col-sm-8">
                    <?= form::select('id_inst', escolas::idEscolas(), 'Escola') ?>
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-info">
                        PDF
                    </button>
                </div>
            </div>
        </form>
    </div>
    <br /><br />
     <div class="fieldBorder2">
        professores de informática não avaliados
        <br /><br />
        <form target="_blank" action="<?= HOME_URI ?>/prod/finalnoinfpdf" method="POST">
            <div class="row">
                <div class="col-sm-8">
                    <?= form::select('id_inst', escolas::idEscolas(), 'Escola') ?>
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-info">
                        PDF
                    </button>
                </div>
            </div>
        </form>
    </div>
    <br /><br />
     <div class="fieldBorder2">
        ADIs  não avaliadas
        <br /><br />
        <form target="_blank" action="<?= HOME_URI ?>/prod/finalnoadipdf" method="POST">
            <div class="row">
                <div class="col-sm-8">
                    <?= form::select('id_inst', escolas::idEscolas(), 'Escola') ?>
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-info">
                        PDF
                    </button>
                </div>
            </div>
        </form>
    </div>
    <br /><br />
    <div class="fieldBorder2">
        <form target="_blank" action="<?php echo HOME_URI ?>/prod/fichapdf" method="POST">
            <div class="row">
                <div class="col-sm-3">
                    Ficha de Avaliação em Branco
                </div>
                <div class="col-sm-5">
                    <?php echo form::selectDB('prod1_aval', 'id_pa', 'tipo de Avaliação', $id_pa, NULL, NULL, NULL, ['ano' => date("Y")]) ?>
                </div>
                <div class="col-sm-2">
                    <?php echo form::selectNum('visita', [1, 3], 'Visita', $visita) ?>
                </div>
                <div class="col-sm-2">
                    <?php echo form::button('Gerar PDF') ?>
                </div>
            </div>
        </form>
    </div>
</div>

