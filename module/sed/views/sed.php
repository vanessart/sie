<?php
if (!defined('ABSPATH'))
    exit;
if (@$_SESSION['userdata']['id_pessoa'] == 1 || @$_SESSION['userdata']['id_pessoa'] == 6) {

    $ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_NUMBER_INT);
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    $consulta = filter_input(INPUT_POST, 'consulta', FILTER_SANITIZE_NUMBER_INT);
    $inst = escolas::idEscolas();
    $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
    $pl = gtMain::periodos();
    $pl = array_reverse($pl);
    $prodesp = filter_input(INPUT_POST, 'prodesp', FILTER_SANITIZE_STRING);
    $ra = filter_input(INPUT_POST, 'ra', FILTER_SANITIZE_STRING);
    $dig = filter_input(INPUT_POST, 'dig', FILTER_SANITIZE_STRING);
    $uf = filter_input(INPUT_POST, 'uf', FILTER_SANITIZE_STRING);
    ?>
    <div class="fieldBody">
        <br /><br />
        <div class="fieldTop">
            Pesquisa Prodesp via REST
        </div>
        <br /><br />
        <div class="border">
            <form method="POST">
                <div class="fieldTop">
                    Baixar Classes
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <?= formErp::selectNum('ano', [2000, date("Y")], "Ano", $ano) ?>
                    </div>
                    <div class="col-sm-8">
                        <?= formErp::select('id_inst', $inst, 'Escola', $id_inst) ?>
                    </div>
                </div>
                <br />
                <div style="text-align: center">
                    <?=
                    formErp::hidden(['baixarClasses' => 1])
                    . formErp::button('Enviar');
                    ?>
                </div>
            </form>
        </div>
        <br /><br />
        <div class="border">
            <?php
            if (empty($_POST['turmas'])) {
                ?>
                <form method="POST">
                    <div class="fieldTop">
                        Baixar Turma
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <?= formErp::select('id_pl', $pl, "Período Letivo", $id_pl) ?>
                        </div>
                        <div class="col-sm-8">
                            <?= formErp::select('id_inst', $inst, 'Escola', $id_inst) ?>
                        </div>
                    </div>
                    <br />
                    <div style="text-align: center">
                        <?=
                        formErp::hidden(['turmas' => 1])
                        . formErp::button('Continuar');
                        ?>
                    </div>

                </form>
                <?php
            } else {
                ?>
                <div class="row" style="font: bold">
                    <div class="col-sm-8">
                        Escola: <?= $inst[$id_inst] ?>
                    </div>
                    <div class="col-sm-4">
                        Período: <?= $pl[$id_pl] ?> (<?= $id_pl ?>)
                    </div>
                </div>
                <br />
                <?php
            }
            if (!empty($_POST['turmas']) && !empty($id_inst)) {
                $escola = new escola($id_inst);

                $tumas_ = $escola->turmas();
                $tumas = tool::idName($tumas_, 'prodesp', 'n_turma');
                ?>
                <form method="POST">
                    <div class="row">
                        <div class="col-sm-8">
                            <?= formErp::select('prodesp', $tumas, 'Turma', $prodesp) ?>
                        </div>
                        <div class="col-sm-4">
                            <?=
                            formErp::hidden([
                                'baixarTurmaAluno' => 1,
                                'id_inst' => $id_inst,
                                'id_pl' => $id_pl,
                                'turmas' => 1
                            ])
                            . formErp::button('Enviar')
                            ?>
                        </div>
                    </div>
                    <br />
                </form>
                <form>
                    <button type="submit" class="btn btn-warning">
                        Limpar
                    </button>
                </form>
                <?php
            }
            ?>
        </div>
        <br /><br />
        <div class="border">
            <form method="POST">
                exibirFichaAluno
                <div class="row">
                    <div class="col">
                        <?= formErp::input('ra', 'RA', $ra) ?>
                    </div>
                    <div class="col">
                        <?= formErp::input('dig', 'Dig.', $dig) ?>
                    </div>
                    <div class="col">
                        <?= formErp::input('uf', 'UF', empty($uf)?'SP':$uf) ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col text-center">
                        <?=
                        formErp::hidden(['exibirFichaAluno' => 1])
                        . formErp::button('enviar')
                        ?>
                    </div>
                </div>
                <br />
            </form>
        </div>
        <?php
        if (!empty($_POST['baixarClasses'])) {
            if (!empty($ano) && !empty($id_inst)) {
                $dados = restImport::baixarClasses($ano, $id_inst);
            }
        } elseif (!empty($_POST['baixarTurmaAluno'])) {
            if (@$_SESSION['userdata']['id_pessoa'] == 1 || @$_SESSION['userdata']['id_pessoa'] == 6) {
                $dados = restImport::baixarTurmaAluno($prodesp, NULL, NULL, 1);
            }
        } elseif (!empty($_POST['exibirFichaAluno'])) {
            $dados = rest::exibirFichaAluno($ra, $uf, $dig);
            ##################            
?>
  <pre>   
    <?php
      print_r($dados);
    ?>
  </pre>
<?php
###################
        }
        ?>
    </div>
    <?php
}