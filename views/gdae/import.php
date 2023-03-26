<?php
/**
  $sql = "SELECT outDigitoRA, outUF, outRA FROM `gdae_pessoa`";
  $query = pdoSis::getInstance()->query($sql);
  $array = $query->fetchAll(PDO::FETCH_ASSOC);
  foreach ($array as $v) {
  echo '<br />'. $sql = "UPDATE `gdae_pessoa` SET
  `outDigitoRA` = '" . trim($v['outDigitoRA']) . "'
  WHERE `gdae_pessoa`.`outRA` =  '" . $v['outRA'] . "'
  AND `gdae_pessoa`.`outDigitoRA` = '" . $v['outDigitoRA'] . "'
  AND `gdae_pessoa`.`outUF` = '" . $v['outUF'] . "'
  ";
  $query = pdoSis::getInstance()->query($sql);
  }
 * 
 */
if (!defined('ABSPATH'))
    exit;
?>
<div class="Body">
    <fieldset>
        <legend>
            Importar classes
            <br />
            alimenta a tabela gdae_classe e 
            sincroniza as classes do SED com o SIEB
        </legend>
        <form method="POST">
            <div class="row">
                <div class="col-sm-4">
                    <?php
                    $esc = escolas::idEscolas();
                    echo form::select('id_inst', $esc, 'Escola');
                    ?>
                </div>
                <div class="col-sm-4">
                    <?php echo form::selectNum('ano', [2000, (date("Y") + 1)], 'Ano') ?>
                </div>
                <div class="col-sm-4">
                    <input name="classesGdae" class="btn btn-success" type="submit" value="Enviar" />
                </div>
            </div>
        </form>
    </fieldset>
    <br /><br />

    <fieldset>
        <legend>
            Importar Matrículados
            <br />
            alimenta a tabela gdae_aluno
        </legend>
        <form method="POST">
            <div class="row">
                <div class="col-sm-4">
                    <?php
                    $esc = escolas::idEscolas();
                    echo form::select('id_inst', $esc, 'Escola');
                    ?>
                </div>
                <div class="col-sm-4">
                    <?php echo form::selectNum('ano', [2000, (date("Y") + 1)], 'Ano') ?>
                </div>
                <div class="col-sm-4">
                    <input name="matriculasGdae" class="btn btn-success" type="submit" value="Enviar" />
                </div>
            </div>
        </form>
        <br /><br />
        <div style="text-align: center">
            <form method="POST">
                <input type="submit" value="matriculasErro" name="matriculasErro" />
            </form>
        </div>
    </fieldset>
    <br /><br />
    <fieldset>
        <legend>
            Importar Pessoas
            <br />
            alimenta a tabela gdae_pessoa
        </legend>
        <form method="POST">
            <div class="row">
                <div class="col-sm-4">
                    <?php
                    $esc = escolas::idEscolas();
                    echo form::select('id_inst', $esc, 'Escola');
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php echo form::selectNum('ano', [2000, (date("Y") + 1)], 'Ano') ?>
                </div>
                <div class="col-sm-2">
                    <label>
                        <input type="checkbox" name="novo" value="1" /> Só Novos
                    </label>
                </div>
                <div class="col-sm-3">
                    <input name="atualizarPessoa" class="btn btn-success" type="submit" value="Enviar" />
                </div>
            </div>
        </form>
    </fieldset>
    <br /><br />
    <fieldset>
        <legend>
            Atualizar Dados da Matriculas
            <br />
            alimenta a tabela gdae_matricula
        </legend>
        <form method="POST">
            <div class="row">
                <div class="col-sm-5">
                    <?php
                    $esc = escolas::idEscolas();
                    echo form::select('id_inst', $esc, 'Escola');
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php echo form::selectNum('ano', [2000, (date("Y") + 1)], 'Ano') ?>
                </div>
                <div class="col-sm-2">
                    <?php echo form::selectNum('semestre', [0, 2], 'Semestre') ?>
                </div>
                <div class="col-sm-2">
                    <?php echo form::select('id_pl', gtMain::periodosPorSituacao(), 'Período') ?>
                </div>
                <div class="col-sm-1">
                    <input name="atualizarMatriculas" class="btn btn-success" type="submit" value="Enviar" />
                </div>
            </div>
        </form>
    </fieldset>
    <br /><br />
    <fieldset>
        <legend>
            Atualizar dados dos alunos/classe
        </legend>
        <form method="POST">
            <div class="row">
                <div class="col-sm-6">
                    <?php
                    $esc = escolas::idEscolas();
                    echo form::select('id_inst', $esc, 'Escola');
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php echo form::selectNum('ano', [2000, (date("Y") + 1)], 'Ano') ?>
                </div>
                <div class="col-sm-3">
                    <input name="atualizarAlunos" class="btn btn-success" type="submit" value="Enviar" />
                </div>
            </div>
        </form>
    </fieldset>
    <br /><br />
    <div style="text-align: center">
        <form method="POST">
            <div class="row">
                <div class="col-sm-3">
                    <?php echo form::selectNum('ano', [2000, (date("Y") + 1)], 'Ano', @$_REQUEST['ano']) ?>
                </div>
                <div class="col-sm-5">
                    <input class="btn btn-primary" type="submit" value="Atualizar a tabela pessoa com os dados da tabela gdae_pessoa" name="atulizarTabelaPessoa" />
                </div>
            </div>
        </form>
    </div>
    <br /><br />
    <div style="text-align: center">
        <form method="POST">
            <div class="row">
                <div class="col-sm-3">
                    <?php echo form::selectNum('ano', [2000, (date("Y") + 1)], 'Ano', @$_REQUEST['ano']) ?>
                </div>
                <div class="col-sm-5">
                    <input class="btn btn-warning" type="submit" value="Importar para tabela gdae_hist" name="atulizarTabelaHist" />
                </div>
            </div>
        </form>
    </div>
    <!--
    <div style="text-align: center">
        <form method="POST">
            limit <input type="text" name="limit" value="<?php echo @$_POST['limit'] ?>" />
            <input type="submit" value="arrumar" />
        </form>
    <?php
    /**
      if (!empty($_POST['limit'])) {
      $cont = 1;
      $limit = $_POST['limit'];
      $sql = "SELECT * FROM `a` limit $limit";
      $query = pdoSis::getInstance()->query($sql);
      $array = $query->fetchAll(PDO::FETCH_ASSOC);
      foreach ($array as $v) {
      $sql = "SELECT * FROM `pessoa` WHERE `ra` = '" . $v['RA'] . "' AND (`ra_uf` IS NULL OR `ra_uf` = '')";
      $query = pdoSis::getInstance()->query($sql);
      $erro = $query->fetch(PDO::FETCH_ASSOC);
      if (!empty($erro)) {
      echo '<br />' . $sql = "UPDATE `pessoa` SET "
      . " `ra_dig` = '" . $v['digitoRA'] . "', "
      . " `ra_uf` = '" . $v['UF'] . "' "
      . "where `ra` = '" . $v['RA'] . "'";
      $query = pdoSis::getInstance()->query($sql);
      echo '<br />' . $cont++;
      }
      }
      }
     * 
     */
    ?>
    </div>
    -->
</div>
