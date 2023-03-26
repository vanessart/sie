
<?php
if ($_SESSION['userdata']['id_pessoa'] == 1 || $_SESSION['userdata']['id_pessoa'] == 6) {
    $aluno->endereco();
    $e = new escola();
    $maps = tool::distancia((trim($aluno->_latitude) . ',' . trim($aluno->_longitude)), (trim($e->_latitude) . ',' . trim($e->_longitude)));
    $distancia = $maps[0];
    $tempo = $maps[1];
    $aluno->tempoEscola($tempo, $distancia);
    $setDist = str_replace(',', '.', explode(' ', $distancia)[0]);
    $transLinha = tool::idName(transporte::search(tool::id_inst()), 'id_li', 'n_li');
    $linha = transporte::alunoLinha($id_pessoa);
    $aluno->vidaEscolar(NULL, tool::id_inst());
    $id_li_set = filter_input(INPUT_POST, 'fk_id_li_set', FILTER_SANITIZE_NUMBER_INT);
    if (!empty($id_li_set)) {
        $linhaSet = sql::get(['transp_linha', 'transp_veiculo'], '*', ['id_li' => $id_li_set], 'fetch');
    }

    if (@$aluno->_situacao == "Frequente" && @$aluno->_id_inst == tool::id_inst()) {
        ?>
        <br /><br />
        <div class="fieldTop">
            Trasporte Escolar
        </div>
        <br />
        <div class="row">
            <div class="col-sm-6">
                <?php
                if (in_array($aluno->_id_ciclo, [1, 2, 3, 4, 19, 20])) {
                    $dist = 0.8;
                } else {
                    $dist = 1.1;
                }
                if ($setDist <= $dist) {
                    ?>
                    <div class="alert alert-danger" style="text-align: center; font-weight: bold; ">
                        Este Aluno mora a 800 metros ou menos da escola.<br><br>Não necessita de transporte.
                    </div>
                    <?php
                }
                ?>
                <div class="row">
                    <table class="table table-bordered table-striped" style="font-weight: bold; font-size: 15px; width: 90%; margin: 0 auto">
                        <?php
                        if (!empty($aluno->_longitude) && !empty($aluno->_latitude)) {
                            ?>
                            <tr>
                                <td>
                                    Distância da Escola: 
                                </td>
                                <td>
                                    <?php echo @$distancia ?> (<?php echo @$tempo ?>)
                                    <?php
                                    $model->db->ireplace('endereco', ['id_end' => $aluno->_id_end, 'distancia' => @$distancia, 'tempo' => @$tempo], 1)
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    <br /><br />
                </div>
                <?php
                if (!empty($linha['id_li'])) {
                    ?>
                    <div class="fieldBorder2">
                        <div class="row">
                            <div class="col-sm-8" style="font-weight: bold">
                                Situação: <?php echo $linha['n_sa'] ?>
                                <br /><br />
                                Motorista: <?php echo $linha['motorista'] ?>
                                <br /><br />
                                Monitor: <?php echo $linha['monitor'] ?>
                                <br /><br />
                                Veículo: <?php echo $linha['n_tv'] ?> (<?php echo $linha['placa'] ?>)
                                <br /><br />
                                Período: <?php echo $linha['periodo'] ?>
                                <br /><br />
                                Abrangência: <?php echo $linha['abrangencia'] ?>
                            </div>
                            <div class="col-sm-4"  style="padding-top: 50px" >
                                <button onclick="if(confirm('Tem Certeza?')){document.getElementById('cancel').submit();}" class="btn btn-danger">
                                    Cancelar ônibus
                                </button>
                                <form id="cancel" method="POST">

                                    <?php echo DB::hiddenKey('cancelOnibus') ?>
                                    <input type="hidden" name="id_alu" value="<?php echo @$linha['id_alu'] ?>" />
                                    <input type="hidden" name="fk_id_li" value="<?php echo $linha['id_li'] ?>" />
                                    <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
                                    <input type="hidden" name="activeNav" value="7" />
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" style="font-weight: bold">
                                Telefones: <?php echo $linha['tel1'] . '; ' . $linha['tel2'] ?>

                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    $hidden = [
                        'id_pessoa' => $id_pessoa,
                        'activeNav' => 7
                    ];
                    echo form::select('fk_id_li_set', $transLinha, 'Linha', $id_li_set, 1, $hidden);
                    ?>
                    <br /><br />
                    <?php
                    if (!empty($linhaSet)) {
                        ?>
                        <div class="row fieldBorder2">
                            <div class="col-sm-8" style="font-weight: bold">

                                Motorista: <?php echo $linhaSet['motorista'] ?>
                                <br /><br />
                                Monitor: <?php echo $linhaSet['monitor'] ?>
                                <br /><br />
                                Viagem: <?php echo $linhaSet['viagem'] ?>
                                <br /><br />
                                Veículo: <?php echo $linhaSet['n_tv'] ?> (<?php echo $linhaSet['placa'] ?>)
                                <br /><br />
                                Período: <?php echo $linhaSet['periodo'] ?>
                                <br /><br />
                                Abrangência: <?php echo $linhaSet['abrangencia'] ?>
                            </div>
                            <div class="col-sm-4"  style="padding-top: 50px" >
                                <form method="POST">

                                    <?php echo DB::hiddenKey('gt_aluno', 'replace') ?>
                                    <input type="hidden" name="1[id_alu]" value="<?php echo @$linha['id_alu'] ?>" />
                                    <input type="hidden" name="1[fk_id_pessoa]" value="<?php echo $id_pessoa ?>" />
                                    <input type="hidden" name="1[fk_id_sa]" value="" />
                                    <input type="hidden" name="1[fk_id_li]" value="<?php echo $linhaSet['id_li'] ?>" />
                                    <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
                                    <input type="hidden" name="activeNav" value="7" />
                                    <input class="btn btn-success" type="submit" value="Escolher esta Linha" />
                                </form>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="col-sm-6">
                <table class="table table-bordered table-striped" style="font-weight: bold; font-size: 15px">

                    <tr>
                        <td>
                            Latitude: <?php echo $aluno->_latitude ?>
                        </td>
                        <td>
                            Longitude: <?php echo $aluno->_longitude ?>
                        </td>
                    </tr>
                    <?php
                    if (empty($disable) && !empty($aluno->_logradouro) && false) {
                        ?>
                        <tr>
                            <td colspan="2" style="text-align: center">
                                <a onclick="document.getElementById('geo').submit()" class="btn btn-info" href="#">
                                    Geolocalização
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    if (!empty($e->_maps) && !empty($aluno->_latitude) && !empty($aluno->_longitude)) {
                        ?>
                        <tr>
                            <td colspan="2">
                                <iframe
                                    frameborder="0" style="border:0; width: 100%; height: 500px"
                                    src="https://www.google.com/maps/embed/v1/directions?key=AIzaSyB3qDDsCe1EvLrCX06sFthYGGrIhx33KJk&origin=<?php echo trim($aluno->_latitude) ?>, <?php echo trim($aluno->_longitude) ?>&destination=<?php echo $e->_maps ?>&mode=walking" allowfullscreen>
                                </iframe>
                            </td>
                        </tr> 
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>
        <?php
    }
}
?>