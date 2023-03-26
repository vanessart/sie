<?php
if (!defined('ABSPATH'))
    exit;
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$id_tp_ens = filter_input(INPUT_POST, 'id_tp_ens', FILTER_SANITIZE_NUMBER_INT);
if ($id_tp_ens) {
    if ($id_curso) {
        $curso = sqlErp::get('ge_cursos', '*', ['id_curso' => $id_curso], 'fetch');
    }
    ?>
    <div class="body">
        <form target="_parent" action="<?= HOME_URI ?>/sed/ensino" method="POST">
            <div class="row">
                <div class="col">
                    <?php echo formErp::input('1[n_curso]', 'Curso', @$curso['n_curso']) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col">
                    <?php echo formErp::select('1[ativo]', ['Não', 'Sim'], 'Ativo', @$curso['ativo']) ?>
                </div>
                <div class="col">
                    <?php echo formErp::input('1[sg_curso]', 'Abrev.', @$curso['sg_curso']) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col">
                    <?php echo formErp::input('1[descr_curso]', 'Descrição', @$curso['descr_curso']) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-sm-4">
                    <?php echo formErp::input('1[notas]', 'Notas Possíveis', @$curso['notas']) ?>
                </div>
                <div class="col-sm-4">
                    <?php echo formErp::input('1[notas_legenda]', 'Legendas das notas', @$curso['notas_legenda']) ?>
                </div>
                <div class="col-sm-4">
                    <?php echo formErp::input('1[corte]', 'Nota de Corte', @$curso['corte']) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-sm-4">
                    <?php echo formErp::input('1[un_letiva]', 'Unid. Letiva', @$curso['un_letiva']) ?>
                </div>
                <div class="col-sm-4">
                    <?php echo formErp::input('1[sg_letiva]', 'Abrev. Unid. Letiva', @$curso['sg_letiva']) ?>
                </div>
                <div class="col-sm-4">
                    <?php echo formErp::input('1[qt_letiva]', 'Quant. Unid. Letiva', @$curso['qt_letiva']) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-sm-12 text-center">
                    <?=
                    formErp::hiddenToken('ge_cursos', 'ireplace')
                    . formErp::hidden([
                        'activeNav' => 2,
                        '1[fk_id_tp_ens]' => $id_tp_ens,
                        '1[id_curso]' => $id_curso,
                        'id_tp_ens' => $id_tp_ens,
                    ])
                    ?>
                    <input class="btn btn-success" type="submit" value="Salvar" />
                </div>
            </div>
        </form>
    </div>
    <?php
}