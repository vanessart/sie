<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$cota_m = filter_input(INPUT_POST, 'cota_m', FILTER_SANITIZE_NUMBER_INT);
$cota_t = filter_input(INPUT_POST, 'cota_t', FILTER_SANITIZE_NUMBER_INT);
$cota_n = filter_input(INPUT_POST, 'cota_n', FILTER_SANITIZE_NUMBER_INT);
$m = filter_input(INPUT_POST, 'm', FILTER_SANITIZE_NUMBER_INT);
$t = filter_input(INPUT_POST, 't', FILTER_SANITIZE_NUMBER_INT);
$n = filter_input(INPUT_POST, 'n', FILTER_SANITIZE_NUMBER_INT);
@$diponivel = $cota_m + $cota_t + $cota_n;

if (empty($id_inst) || empty($id_pl)) {
    exit();
}
$esc = new escola($id_inst);
$aE = $esc->alunos();
foreach ($aE as $v) {
    if (empty($v['fk_id_tas']) && in_array($v['fk_id_ciclo'], [4,5,6,7,8,9])) {
        $alunosEsc[$v['id_pessoa']] = $v['id_pessoa'] . ' - ' . $v['n_pessoa'] . ' (' . $v['n_turma'] . ')';
    }
}
if ($diponivel > 0) {
    $destino = [3 => 'Fila de Espera', 2 => 'Aguardando Matrícula'];
} else {
    $destino = [3 => 'Fila de Espera'];
}
######################## trancado para enviar para matrícula  ##################
//$destino = [3 => 'Fila de Espera'];
################################################################################
?>
<div class="body">
    <form action="<?= HOME_URI ?>/maker/cadAlunos" target="_parent" method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::select('1[fk_id_pessoa]', $alunosEsc, 'Aluno', null, null, null, ' required ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('1[fk_id_mc]', sql::idNome('maker_ciclo'), 'Ciclo', '$post', null, null, ' required ', null, 1) ?>
            </div>
            <div class="col">
                <div id="sit1">
                    <?= formErp::select('1[fk_id_as]', $destino, 'Situação', 2, null, null, ' required ') ?>
                </div>
                <div class="alert alert-primary" style="font-weight: bold; display: none" id="sit2">
                    Aguardando Matrícula    
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-4">
                <?= formErp::checkbox('1[transporte]', 1, 'Necessita de Transporte Escolar') ?>
            </div>
            <div class="col-3" >
                <?= formErp::checkbox('1[alergia_alimento]', 1, 'Alergia Alimentar', null, ' onclick="aler(this)" ') ?>
            </div>
            <div class="col-5" style="display: none" id="descr">
                <?= formErp::input('1[alergia_alimento_descr]', 'Qual?') ?>
            </div>
        </div>
        <br />
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td colspan="6" style="text-align: center">
                    Preferência para o dia de participação
                </td>
            </tr>
            <?php
            foreach (range(1, 2) as $v) {
                ?>
                <tr>
                    <td>
                        <?= $v ?>ª Opção
                    </td>
                    <td>
                        <?= formErp::radio('1[opt_semana_' . $v . ']', 2, 'Segunda') ?>
                    </td>
                    <td>
                        <?= formErp::radio('1[opt_semana_' . $v . ']', 3, 'Terça') ?>
                    </td>
                    <td>
                        <?= formErp::radio('1[opt_semana_' . $v . ']', 4, 'Quarta') ?>
                    </td>
                    <td>
                        <?= formErp::radio('1[opt_semana_' . $v . ']', 5, 'Quinta') ?>
                    </td>
                    <td>
                        <?= formErp::radio('1[opt_semana_' . $v . ']', 6, 'Sexta') ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <div style="text-align: center; padding: 30px">
            <?=
            formErp::hidden([
                'id_inst' => $id_inst,
                '1[fk_id_inst]' => $id_inst,
                '1[fk_id_pl]' => $id_pl,
                '1[fk_id_polo]' => $id_polo,
                'm' => $m,
                't' => $t,
                'n' => $n
            ])
            . formErp::hiddenToken('makerAlunoSalva')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
<script>
    function aler(i) {
        if (i.checked === true) {
            descr.style.display = '';
        } else {
            descr.style.display = 'none';
        }
    }
    function fk_id_mc(id) {
        if (id == '2') {
            sit1.style.display = 'none';
            sit2.style.display = '';
        } else {
            sit1.style.display = '';
            sit2.style.display = 'none';
        }
    }
</script>