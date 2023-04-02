<?php
if (!defined('ABSPATH'))
    exit;
$per = filter_input(INPUT_POST, 'per', FILTER_UNSAFE_RAW);
if ($per) {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    $esc = escolas::idInst();
    $salaAula = $model->salaAula($per, $id_inst);
}
$periodos = ['M' => 'Manhã', 'T' => 'Tarde', 'N' => 'Noite', 'I' => 'Integral'];
?>
<div class="body">
    <div class="fieldTop">
        Ocupação das Salas de aula
    </div>
    <br /><br />
    <div class="row">
        <div class="col">
            <?= formErp::select('per', $periodos, 'Período', $per, 1) ?>
        </div>
        <div class="col">
            <?php
            if ($per) {
                echo formErp::select('id_inst', $esc, ["Escola", 'Todas'], $id_inst, 1, ['per' => $per]);
            }
            ?>
        </div>
    </div>
    <br />
</div>
<?php
if ($per) {
    ?>
    <table class="table table-bordered table-hover table-striped">
        <?php
        foreach ($salaAula as $id_inst => $salas) {
            ?>
            <tr>
                <td colspan="10">
                    <?= current($salas)['n_inst'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Sala
                </td>
                <td>
                    Tipo
                </td>
                <td>
                    Largura
                </td>
                <td>
                    Comprimento
                </td>
                <td>
                    Piso
                </td>
                <td>
                    Acessibilidade
                </td>
                <td>
                    Turma
                </td>
                <td>
                    Capacidade
                </td>
                <td>
                    Ocupação
                </td>
                <td>
                    Disponível
                </td>
            </tr>
            <?php
            foreach ($salas as $v) {
                ?>
                <tr>
                    <td>
                        <?= $v['n_sala'] ?>
                    </td>
                    <td>
                        <?= $v['n_ts'] ?>
                    </td>
                    <td>
                        <?= $v['largura'] ?>
                    </td>
                    <td>
                        <?= $v['comprimento'] ?>
                        '                </td>
                    <td>
                        <?= $v['piso'] ?>
                    </td>
                    <td>
                        <?= $v['cadeirante'] ?>
                    </td>
                    <td>
                        <?= @$v['turma']['n_turma'] ?>
                    </td>
                    <td>
                        <?= $v['alunos_sala'] ?>
                    </td>
                    <td>
                         <?= @$v['turma']['alunos'] ?>
                    </td>
                    <td>
                        <?= $v['alunos_sala'] - @$v['turma']['alunos']?>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
    <?php
}
