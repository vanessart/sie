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
$sql="select fk_id_pessoa from maker_aluno where fk_id_inst = $id_inst";
$query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $ids = array_column($array, 'fk_id_pessoa');
        
foreach ($aE as $v) {
    if (empty($v['fk_id_tas']) && in_array($v['fk_id_ciclo'], [4, 5, 6, 7, 8, 9]) && !in_array($v['id_pessoa'], $ids)) {
        $alunosEsc[$v['id_pessoa']] = $v['id_pessoa'] . ' - ' . $v['n_pessoa'] . ' (' . $v['n_turma'] . ')';
    }
}

?>
<div class="body">
    <form action="<?= HOME_URI ?>/maker/cadAlunos" target="_parent" method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::select('1[fk_id_pessoa]', $alunosEsc, 'Aluno', null, null, null, ' required ') ?>
            </div>
            <!--
            <div class="col">
                <?= ''//formErp::select('1[fk_id_mc]', sql::idNome('maker_ciclo'), 'Ciclo', '$post', null, null, ' required ', null, 1) ?>
            </div>
            -->
        </div>
        <br />
        <div style="text-align: center; padding: 30px">
            <?=
            formErp::hidden([
                'id_inst' => $id_inst,
                '1[fk_id_inst]' => $id_inst,
                '1[fk_id_pl]' => $id_pl,
                '1[fk_id_polo]' => $id_polo,
                '1[fk_id_as]' => 3,
                '1[fk_id_mc]'=>1,
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
