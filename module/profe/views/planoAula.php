<?php
if (!defined('ABSPATH'))
    exit;
if (toolErp::id_nilvel() == 24) {
    $id_pessoa = tool::id_pessoa();
} else {
    $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
}
$plano = filter_input(INPUT_POST, 'plano', FILTER_SANITIZE_NUMBER_INT);
$atualLetivaSet = filter_input(INPUT_POST, 'atualLetivaSet', FILTER_SANITIZE_NUMBER_INT);
$activeNav = filter_input(INPUT_POST, 'activeNav', FILTER_SANITIZE_NUMBER_INT);
$id_pls = ng_main::periodosAtivos();
$cicloDiscEsc = ng_professor::ciclosDisc($id_pessoa, NULL, NULL, $id_pls);
if ($cicloDiscEsc) {
    $c = 1;
    foreach ($cicloDiscEsc as $v) {
        $hiddenAba[$c] = $v;
        $abas[$c++] = ['nome' => $v['n_ciclo'] . ' - ' . $v['n_disc'] . '<br />' . $v['n_inst'], 'hidden' => $v];
    }
    if ($activeNav) {
        $cde = $hiddenAba[$activeNav];
    } else {
        $cde = current($hiddenAba);
    }
    foreach (range(1, (empty($cde['qt_letiva']) ? 1 : $cde['qt_letiva'])) as $v) {
        $al[$v] = $v . 'º ' . $cde['un_letiva'];
    }
    $atualLetivaSet = (empty($atualLetivaSet) ? $cde['atual_letiva'] : $atualLetivaSet);

    $n_turmas = ng_professor::turmasDiscEsc($id_pessoa, $cde['id_ciclo'], $cde['id_disc'], $cde['id_inst']);
    $id_turmas = array_keys($n_turmas);
    $sql = " SELECT * FROM coord_plano_aula pa "
            . " JOIN coord_plano_aula_turmas pt on pt.fk_id_plano = pa.id_plano "
            . " WHERE pt.fk_id_turma in (" . (implode(',', $id_turmas)) . ") "
            . " AND pa.iddisc LIKE '" . ($cde['id_disc']) . "' "
            . " AND pa.atualLetiva = $atualLetivaSet ";
    $query = pdoSis::getInstance()->query($sql);
    $plans = $query->fetchAll(PDO::FETCH_ASSOC);
    if ($plans) {
        foreach ($plans as $v) {
            $t[$v['id_plano']][$v['fk_id_turma']] = $n_turmas[$v['fk_id_turma']];
            $v['turmas'] = $t[$v['id_plano']];
            $planos[$v['id_plano']] = $v;
        }
        foreach ($planos as $k => $v) {
            $planos[$k]['turmas'] = toolErp::virgulaE($v['turmas']);
            $planos[$k]['ac'] = formErp::submit('Acessar', null, $cde + ['plano' => 1, 'atualLetivaSet' => $atualLetivaSet, 'id_plano' => $v['id_plano']]);
        }

        $form['array'] = $planos;
        $form['fields'] = [
            'ID' => 'id_plano',
            'Início' => 'dt_inicio',
            'Término' => 'dt_fim',
            'Turmas' => 'turmas',
            'Aulas' => 'qt_aulas',
            '||1' => 'ac'
        ];
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Plano de aula
    </div>
    <?php
    if (toolErp::mobile()) {
        ?>
        <div class="alert alert-danger">
            Parece que você está acessando esta página num celular.
            <br />
            Se possível, use um DESKTOP
        </div>
        <?php
    }
    if (empty($plano)) {
        ?>
        <div style="text-align: center; width: 500px; margin: auto">
            <?php
            if ($cicloDiscEsc) {
                $activeNav = report::dropdown($abas);
            } else {
                ?>
                <div class="alert alert-danger">
                    Não há turma alocada
                </div>
                <?php
            }
            ?>
        </div>
        <br />
        <div style="text-align: center; width: 500px; margin: auto">
            <?php
            if ($cicloDiscEsc) {
                echo formErp::select('atualLetivaSet', $al, 'Unidade Letiva', $atualLetivaSet, 1, $cde + ['activeNav' => $activeNav]);
            }
            ?>
        </div> 
        <?php
        if (!empty($cde)) {
            ?>
            <div class="row">
                <div class="col-4">
                    <form method="POST">
                        <?=
                        formErp::hidden($cde)
                        . formErp::hidden([
                            'atualLetivaSet' => $atualLetivaSet,
                            'id_pessoa' => $id_pessoa,
                            'plano' => 1,
                            'activeNav' => $activeNav
                        ])
                        ?>
                        <button class="btn btn-primary">
                            Novo Plano de Aula
                        </button>
                    </form>
                </div>
            </div>
            <br /><br />
            <?php
            if (!empty($form)) {
                report::simple($form);
            }
        }
    } else {
        ?>
        <div class="border">
            <?php
            include ABSPATH . '/module/profe/views/_planoAula/plano.php';
            ?>
        </div>
        <?php
    }
    ?>
</div>


