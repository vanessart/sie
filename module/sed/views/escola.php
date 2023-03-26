<?php
if (!defined('ABSPATH'))
    exit;
$aviso = $model->quadroAvisoEscola(toolErp::id_inst());
$acessoEsc = $model->acessoEsc(toolErp::id_inst());
$log = log::logGet(toolErp::id_pessoa());
$MSGalunoAEE = '';
$alunoSemTurma = $model->alunoAceiteSemTurma();
$alunoComTurmaRecusa = $model->alunoRecusaComTurma();
if (!empty($alunoSemTurma)) {
    foreach ($alunoSemTurma as $key => $value) {
        $oa = concord::oa($value['id_pessoa']);
        $MSGalunoAEE .= 'ATENÇÃO! Tarefa pendente: '.strtoupper($oa).' alun'.$oa.' '. $value['n_pessoa'] .', RSE '. $value['RSE'] .', deve ser matriculad'.$oa.' na turma '. $value['n_turma'] . "<br>";
    }
}
// if (!empty($alunoComTurmaRecusa)) {
//     foreach ($alunoComTurmaRecusa as $key => $value) {
//         $oa = concord::oa($value['id_pessoa']);
//         $MSGalunoAEE .= 'ATENÇÃO! Tarefa pendente: '.strtoupper($oa).' alun'.$oa.' '. $value['n_pessoa'] .', RSE '. $value['RSE'] .', deve ser retirad'.$oa.' da turma AEE em que está matriculado atualmente <br>';
//     }
// }
?>
<div class="body">
    <?php
    /**
      $turmas = gtTurmas::idNome(toolErp::id_inst(), ng_main::periodoSet(), 9);


      ?>
      <div class="border">
      <div class="fieldTop">
      <p>
      Definição de Matrícula para o Ensino Médio 2023
      </p>
      <p>
      ATENÇÃO! Este Relatório deve ser preenchido até quinta-feira (28/07/2022)
      </p>
      </div>
      <br /><br />
      <div class="row">
      <?php
      $c = 1;
      foreach ($turmas as $id_turma => $n_turma) {
      $ids[$id_turma] = $id_turma;
      ?>
      <div class="col-4 text-center">
      <button type="button" onclick="nono(<?= $id_turma ?>)" class="btn btn-primary">
      <?= $n_turma ?>
      </button>
      </div>
      <?php
      if ($c++ % 3 == 0) {
      ?>
      </div>
      <br /><br /><br />
      <div class="row">
      <?php
      }
      }
      ?>
      </div>
      <br />
      <?php
      $sql = "select count(distinct fk_id_pessoa) ct from ge_turma_aluno where fk_id_turma in (" . implode(', ', $ids) . ") and fk_id_tas = 0";
      $query = pdoSis::getInstance()->query($sql);
      $array = $query->fetch(PDO::FETCH_ASSOC);
      $tt = $array['ct'];
      $sql = "SELECT COUNT(`id_pessoa_opt`) ct FROM `aa_nono_opt` WHERE `fk_id_inst` = " . toolErp::id_inst();
      $query = pdoSis::getInstance()->query($sql);
      $array = $query->fetch(PDO::FETCH_ASSOC);
      $ttLanc = $array['ct'];
      $porc = ($ttLanc / $tt) * 100
      ?>
      <div style="font-weight: bold; font-size: 1.4em">
      Formulários Lançados (<?= $ttLanc ?> de <?= $tt ?>)
      </div>
      <br /><br />
      <div class="progress">
      <div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?= intval($porc) ?>%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"><?= intval($porc) ?>%</div>
      </div>
      </div>
      <form id="formNono" action="<?= HOME_URI ?>/sed/def/formNono.php" target="frame" method="POST">
      <input type="hidden" name="id_turma" id="id_turma" value="" />
      </form>

      <?php
      toolErp::modalInicio();
      ?>
      <iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
      <?php
      toolErp::modalFim();
      ?>
      <script>
      function nono(id) {
      id_turma.value = id;
      formNono.submit();
      $('#myModal').modal('show');
      $('.form-class').val('');
      }
      </script>
      <?php
     * 
     */
    ?>
    <div class="caixag" style="font-weight: bold">
        <p>
            <?= toolErp::bom() . ' ' . explode(' ', toolErp::n_pessoa())[0] ?>
        </p>
        <p>
            Este é o módulo de gerenciamento escolar integrado ao SED.
        </p>
        <p>
            Todas as alterações, tanto dos dados pessoais dos alunos como os dados referentes a sua situação na classe e na escola, devem ser realizadas no SED e depois importada para o nosso sistema.
        </p>
        <p>
            <a href="https://sed.educacao.sp.gov.br/" target="_blank">
                Para acessar o SED, clique aqui.
            </a>
        </p>
        <?php
        if (!empty($MSGalunoAEE)) { 
            toolErp::divAlert('danger',$MSGalunoAEE); 
        }?>
    </div>
    <?php
        if ($ensino == 3) {
            if (date("m") > 8) {
                $ano = date("Y") + 1;
            } else {
                $ano = date("Y");
            }
            $dfc = $model->foraDataCicloPrevisto(toolErp::id_inst(), $ano);
            if ($dfc) {
                $tt = 0;
                foreach ($dfc as $v) {
                    $tt += count(current($v));
                }
                ?>
                <a href="<?= HOME_URI ?>/sed/foraDataEsc">
                    <div class="alert alert-danger" style="font-weight: bold; font-size: 1.6em; text-align: center">
                        <p>
                            Sua escola está com <span style="font-size: 1.8em"><?= $tt ?></span> Aluno<?= $tt > 1 ? 's' : '' ?> matriculados fora da data base.
                        </p>
                        <p>
                            Verifique a data de nascimento e as turmas em que foram matriculados.
                        </p>
                        <p>
                            Para ver o relatório completo, clique aqui
                        </p>
                    </div>
                </a>
                <?php
            }
        }
    ?>
    <div class="row">
        <div class="col">
            <div class="border fieldrow" style="padding:  5px; padding-left: 30px; padding-right: 30px; min-height: 40vh">
                <div style="text-align: center; font-weight: bold">
                    Quadro de avisos
                </div>
                <?php
                if (!empty($aviso)) {
                    foreach ($aviso as $v) {
                        ?>
                        <div class="caixag">
                            <div style="text-align: center">
                                <?= $v['n_q'] ?>
                            </div>
                            <div>
                                <?= $v['descr_q'] ?>
                            </div>
                        </div>
                        <br />
                        <?php
                    }
                } else {
                    echo 'Não há avisos para está data';
                }
                ?>
            </div>
        </div>
        <div class="col">
            <table class="table table-bordered table-hover table-striped">
                <?php
                foreach ($acessoEsc as $func => $pessoas) {
                    if ($pessoas) {
                        ?>
                        <tr>
                            <td>
                                Acessa como  <?= $func ?>
                            </td>
                            <td>
                                <?php
                                foreach ($pessoas as $v) {
                                    ?>
                                    <p>
                                        <?= $v ?>
                                    </p>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
        </div>
    </div>
    <div style="height: 30vh; overflow: auto">
        <?php
        if ($log) {
            $form['array'] = $log;
            $form['fields'] = [
                'Data' => 'data',
                'Hora' => 'hora',
                'Descrição' => 'descricacao',
                'Sistema' => 'n_sistema',
                'Instância' => 'n_inst'
            ];
            report::simple($form);
        }
        ?>
    </div>
</div>
