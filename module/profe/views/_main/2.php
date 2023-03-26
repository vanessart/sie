<?php
if (!defined('ABSPATH'))
    exit;
$aulaDia = aulaDia($id_pessoa, $diaSem);
?>
<div class="geral">

    <?php
    foreach (['M', 'T', 'N', 'G', 'I'] as $pd) {
        if (!empty($aulaDia[$pd])) {
            $temAula = 1;
            ?>
            <div class="border">
                <div class="text-center">
                    <?= gtMain::periodoDoDia($pd) ?>
                </div>
                <?php
                $aulas = [];
                foreach (range(1, 5) as $v) {
                    if (!empty($aulaDia[$pd][$v])) {
                        @$vl = $tpd[$aulaDia[$pd][$v]];
                        if (empty($aulasPosi[$vl['id_turma'] . '_' . $vl['id_disc']])) {
                            $aulas[$v] = $vl;
                        }
                        $aulasPosi[$vl['id_turma'] . '_' . $vl['id_disc']][] = $v;
                    }
                }


                foreach (range(1, 5) as $v) {
                    if (!empty($aulas[$v])) {
                        $value = $aulas[$v];
                        $numAulas = $aulasPosi[$value['id_turma'] . '_' . $value['id_disc']];
                        ?>
                        <form action="<?= HOME_URI . '/profe/chamada' ?>" method="post" id="form_ids-<?= $value['id_turma'] ?>-<?= $value['id_disc'] ?>">
                            <div class="border" id="border-main" onclick="document.getElementById('form_ids-<?= $value['id_turma'] ?>-<?= $value['id_disc'] ?>').submit()" style="cursor: pointer">
                                <div class="col" align="center">
                                    <h3 ><?= toolErp::virgulaE($numAulas, 'ª') ?> Aula<?= count($numAulas) > 1 ? 's' : '' ?> - <?= $value['nome_turma'] ?></h3>
                                    <h5><?= $value['nome_disc'] ?></h5>
                                    <h5><?= $value['escola'] ?></h5>
                                    <input type="hidden" name="id_inst" value="<?= $value['id_inst'] ?>">
                                    <input type="hidden" name="id_turma" value="<?= $value['id_turma'] ?>">
                                    <input type="hidden" name="id_disc" value="<?= $value['id_disc'] ?>">
                                    <input type="hidden" name="nome_disc" value="<?= $value['nome_disc'] ?>">
                                    <input type="hidden" name="nome_turma" value="<?= $value['nome_turma'] ?>">
                                    <input type="hidden" name="id_pl" value="<?= $value['id_pl'] ?>">
                                    <input type="hidden" name="escola" value="<?= $value['escola'] ?>">
                                    <input type="hidden" name="id_curso" value="<?= $value['id_curso'] ?>">
                                    <input type="hidden" name="id_ciclo" value="<?= $value['id_ciclo'] ?>">
                                    <input type="hidden" name="aulas" value="<?= $value['aulas'] ?>">
                                </div>
                            </div>
                        </form>
                        <?php
                    }
                    /**
                      else {
                      ?>
                      <div class="border" id="border-main">
                      <div class="col" align="center">
                      <h3 ><?= $v ?>ª Aula</h3>
                      <h5>&nbsp;</h5>
                      <h5>&nbsp;</h5>
                      </div>
                      </div>
                      <?php
                      }
                     * 
                     */
                }
                ?>
            </div>
            <br /><br />
            <?php
        }
    }
    if (empty($temAula)) {
        ?>
        <div class="alert alert-warning" style="font-weight: bold; text-align: center">
            Não há aulas neste dia da semana
        </div>
        <?php
    }
    ?>
</div>
<?php

function aulaDia($id_pessoa, $diaSem) {
    $sql = "SELECT t.periodo, h.* FROM ge_aloca_prof ap "
            . " JOIN ge_horario h on h.fk_id_turma = ap.fk_id_turma AND ap.iddisc = h.iddisc "
            . " JOIN ge_funcionario f on f.rm = ap.rm "
            . " join ge_turmas t on t.id_turma = ap.fk_id_turma AND t.fk_id_ciclo != 32"
            . " WHERE f.fk_id_pessoa = $id_pessoa "
            . " AND h.dia_semana = $diaSem "
            . " ORDER BY h.fk_id_turma, h.aula ";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    if ($array) {
        foreach ($array as $v) {
            $aula[$v['periodo']][$v['aula']] = $v['fk_id_turma'] . '_' . $v['iddisc'];
        }
        ksort($aula);
        return $aula;
    } else {
        return;
    }
}
