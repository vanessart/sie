<?php
if (!defined('ABSPATH'))
    exit;
$id_nota = filter_input(INPUT_POST, 'id_nota', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$id_hte = filter_input(INPUT_POST, 'id_hte', FILTER_SANITIZE_NUMBER_INT);
if ($id_nota) {
    $notas = sql::get('historico_notas', '*', ['id_nota' => $id_nota], 'fetch');
}
$ciclos = $ensinoCiclosSet = $model->ensinoCiclos($id_hte);
if (!empty($notas['fk_id_disc'])) {
    if (empty($id_ciclo)) {
        $sql = " SELECT "
                . " t.fk_id_ciclo "
                . " FROM ge_turma_aluno ta "
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma AND ta.fk_id_pessoa = $id_pessoa "
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if ($array) {
            $id_ciclo_parc = $array['fk_id_ciclo'];
        } else {
            $id_ciclo_parc = '0';
        }
    } elseif ($id_ciclo) {
        $id_ciclo_parc = $id_ciclo;
    } else {
        $id_ciclo_parc = '0';
    }
    $parcial = sql::get('historico_notas_parciais', '*', ['fk_id_pessoa' => $id_pessoa, 'fk_id_ciclo' => $id_ciclo_parc, 'fk_id_disc' => @$notas['fk_id_disc']], 'fetch');
}

?>
<div class="body">
    <form action="<?= HOME_URI ?>/<?= $_SESSION['userdata']['arquivo'] ?>/hist" target="_parent" method="POST">
        <div class="row">
            <div class="col-8">
                <div class="row">
                    <div class="col">
                        <?php
                        if (empty($notas['fk_id_disc'])) {
                            echo formErp::input('1[n_disc]', 'Disciplina', @$notas['n_disc'], ' required ');
                        } else {
                            echo formErp::input(null, 'Disciplina', @$notas['n_disc'], ' readonly ');
                        }
                        ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col">
                        <?= formErp::select('1[ativo]', [0 => 'Não', 1 => 'Sim'], 'Ativo', ($id_nota ? @$notas['ativo'] : 1)) ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col">
                        <?= formErp::selectDB('historico_base', '1[fk_id_base]', 'Base', @$notas['fk_id_base'], null, null, ['id_base' => 'descr_base'], null, ' required ') ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col">
                        <?= formErp::selectNum('1[ordem]', [1, 50], 'Ordem de Exibição', @$notas['ordem'], null, null, ' required ') ?>
                    </div>
                </div>
                <br />
                <?php
                if (!empty($notas['fk_id_disc'])) {
                    foreach (range(1, 4) as $v) {
                        ?>
                        <div class="row">
                            <div class="col">
                                <?= formErp::input('2[b_' . $v . ']', $v . 'º bimestre', @$parcial['b_' . $v]) ?>
                            </div>
                        </div>
                        <br />
                        <?php
                    }
                    ?>
                    <div class="row">
                        <div class="col">
                            <?= formErp::input('2[faltas]', 'Faltas', @$parcial['faltas']) ?>
                        </div>
                    </div>
                    <br />
                    <?php
                }
                ?>
            </div>
            <div class="col-4">
                <?php
                foreach ($ciclos as $k => $v) {
                    ?>
                    <div class="row">
                        <div class="col">
                            <?= formErp::input('1[n_' . $k . ']', $v, @$notas['n_' . $k]) ?>
                        </div>
                    </div>
                    <br />
                    <?php
                }
                ?>
            </div>
        </div>
        <br />

        <?php
        if (empty($notas['fk_id_disc'])) {
            echo formErp::hidden(['1[fk_id_disc]' => uniqid()]);
        }
        if (!empty($notas['fk_id_disc'])) {
            echo formErp::hidden([
                '2[id_np]' => @$parcial['id_np'],
                '2[fk_id_pessoa]' => $id_pessoa,
                '2[fk_id_ciclo]' => @$id_ciclo_parc,
                '2[fk_id_disc]' => $notas['fk_id_disc']
            ]);
        }
        ?>
        <div style="text-align: center; padding: 30px">
            <?=
            formErp::hidden([
                'id_pessoa' => $id_pessoa,
                'activeNav' => 2,
                'id_ciclo' => $id_ciclo,
                '1[id_nota]' => $id_nota,
                '1[fk_id_pessoa]' => $id_pessoa,
            ])
            . formErp::hiddenToken('historico_notasSet')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
