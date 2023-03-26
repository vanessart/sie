<?php
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$aluno = new aluno($id_pessoa);
$aluno->endereco();
$e = new escola($id_inst);
$maps = toolErp::distancia($aluno->_latitude, $aluno->_longitude, $e->_latitude, $e->_longitude );
$distancia = $maps[0];
$tempo = $maps[1];
$aluno->tempoEscola($tempo, $distancia, $id_inst);
$setDist = str_replace(',', '.', explode(' ', $distancia)[0]);
$transLinha = toolErp::idName(transporteErp::search(toolErp::id_inst()), 'id_li', 'n_li');
$linha = transporteErp::alunoLinha($id_pessoa);
$aluno->vidaEscolar(NULL, toolErp::id_inst());
$id_li_set = filter_input(INPUT_POST, 'fk_id_li_set', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_li_set)) {
    $linhaSet = sqlErp::get(['transporte_linha', 'transporte_veiculo'], '*', ['id_li' => $id_li_set], 'fetch');
}
?>
<div class="body">
<div class="row">
    <div class="col-sm-6">
        <div class="row">
            <table class="table table-bordered table-striped" style="font-weight: bold; font-size: 15px; width: 90%; margin: 0 auto">
                <tr>
                    <td>
                        Latitude: <?php echo $aluno->_latitude ?>
                    </td>
                    <td>
                        Longitude: <?php echo $aluno->_longitude ?>
                    </td>
                </tr>
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
                    <tr>
                        <th colspan="2" style="font-weight: bold; background-color: lightgrey;">
                            Endereço
                        </th>
                    </tr>
                    <tr>
                        <td style="width: 35%">
                            Logradouro:    
                        </td>
                        <td>
                            <?php echo $aluno->_logradouro_gdae ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Número
                        </td>
                        <td>
                            <?php echo $aluno->_num ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 35%">
                            Endereço Indicativo:    
                        </td>
                        <td>
                            <?php echo $aluno->_logradouro ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Número
                        </td>
                        <td>
                            <?php echo $aluno->_num ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Complemento: 
                        </td>
                        <td>
                            <?php echo $aluno->_complemento ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Bairro:
                        </td>
                        <td>
                            <?php echo $aluno->_bairro ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Cidade: 
                        </td>
                        <td>
                            <?php echo $aluno->_cidade ?> - <?php echo $aluno->_uf ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            CEP: 
                        </td>
                        <td>
                            <?php echo $aluno->_cep ?>
                        </td>
                    </tr>
                </table>
            </table>
            <br /><br />
        </div>
    </div>
    
    <?php if (!empty($e->_maps) && !empty($aluno->_latitude) && !empty($aluno->_longitude)) { ?>
    <div class="col-sm-6">
        <?= toolErp::geraMapaRoute([
            ['lat' => $aluno->_latitude, 'long' => $aluno->_longitude], 
            ['lat' => $e->_latitude, 'long' => $e->_longitude]
        ]); ?>
    </div>
    <?php } ?>
</div>
</div>