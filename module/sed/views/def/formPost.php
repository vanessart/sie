<?php
if (!defined('ABSPATH'))
    exit;
$destino = filter_input(INPUT_POST, 'destino', FILTER_UNSAFE_RAW);
$id_inst = tool::id_inst();
$id_mural = filter_input(INPUT_POST, 'id_mural', FILTER_SANITIZE_NUMBER_INT);
if ($id_mural) {
    $mural = sql::get('sed_mural', '*', ['id_mural' => $id_mural], 'fetch');
    if (empty($mural['fk_id_turma']) && empty($mural['fk_id_gr'])) {
        $destino = 'escola';
    } elseif (!empty($mural['fk_id_turma'])) {
        $destino = 'turma';
    } elseif (!empty($mural['fk_id_gr'])) {
        $destino = 'grupo';
    } else {
        echo 'Algo errado não está certo :(';
        exit();
    }
}
if ($destino == 'turma') {
    $periodos = array_keys(ng_main::periodosPorSituacao()['Ativo']);
    $select = gtTurmas::idNome($id_inst, implode(',', $periodos));
    $field = 'fk_id_turma';
    $titulo = 'Turma';
    $post = @$mural['fk_id_turma'];
} elseif ($destino == 'grupo') {
    $select_ = sqlErp::get('sed_grupo', '*', ['fk_id_inst' => $id_inst, 'at_gr'=>1]);
    if($select_){
        $select = tool::idName($select_);
    } else {
        toolErp::alertModal('Não há grupos cadastrados');  
        exit();
    }
    $field = 'fk_id_gr';
    $titulo = 'Grupo';
    $post = @$mural['fk_id_gr'];
}
?>
<div class="body">
    <?php
    if (empty($id_mural) && empty($destino)) {
        ?>
        <form method="POST">
            <div class="fieldTop">
                Para quem vai esta postagem
            </div>
            <br /><br />
            <div class="row">
                <div class="col text-center">
                    <button name="destino" value="escola" class="btn  btn-cinza">
                        Toda a Escola
                    </button>
                </div>
                <div class="col text-center">
                    <button name="destino" value="turma" class="btn  btn-cinza">
                        Uma Turma
                    </button>
                </div>
                <div class="col text-center">
                    <button name="destino" value="grupo" class="btn  btn-cinza">
                        Um Grupo
                    </button>
                </div>
            </div>
            <br />
        </form>
        <?php
    } else {
        ?>
        <form action="<?= HOME_URI ?>/sed/mural" target="_parent" method="POST">
            <?php
            if (!empty($field)) {
                ?>
                <div class="row">
                    <div class="col-4">
                        <?= formErp::select('1[' . $field . ']', $select, $titulo, $post) ?>
                    </div>
                </div>
                <br />
                <?php
            }
            ?>
            <div class="row">
                <div class="col">
                    <?= formErp::select('1[at_mural]', [1 => 'Sim', 0 => 'Não'], 'Ativo', (empty($mural)?1:$mural['at_mural']), null, null, 'required') ?>
                </div>
                <div class="col">
                    <?= formErp::input('1[dt_inicio]', 'Início', (empty($mural['dt_inicio']) ? date("Y-m-d") : $mural['dt_inicio']), 'required', null, 'date') ?>
                </div>
                <div class="col">
                    <?= formErp::input('1[dt_fim]', 'Final', @$mural['dt_fim'], 'required', null, 'date') ?>
                </div>
            </div>
              <br />
              <?= formErp::input('1[n_mural]', 'Título', @$mural['n_mural'], ' required ') ?>
          <br />
            <?= formErp::textarea('1[msg]', @$mural['msg'], 'Postagem') ?>
            <div style="text-align: center; padding: 10px">
                <?=
                formErp::hidden([
                    '1[fk_id_pessoa]' => tool::id_pessoa(),
                    '1[fk_id_inst]' => $id_inst,
                    '1[id_mural]' => $id_mural
                ])
                 . formErp::hiddenToken('sed_muralSalva')
                . formErp::button('Salvar')
                ?>
            </div>
        </form>
        <?php
    }
    ?>
</div>
