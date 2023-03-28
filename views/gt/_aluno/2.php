<?php
$disable = NULL;
$aluno->endereco();
$e = new escola();
$maps = tool::distancia((trim($aluno->_latitude) . ',' . trim($aluno->_longitude)), (trim($e->_latitude) . ',' . trim($e->_longitude)));
$distancia = $maps[0];
$tempo = $maps[1];
$aluno->tempoEscola($tempo, $distancia);

?>
<br /><br /><br />
<div class="row">
    <div class="col-sm-6">
        <form id="endMain" method="POST">
            <div style="font-size: 18px">
                <table style="font-size: 18px" class="table table-bordered table-hover table-striped">
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
                            <!--
                            <input disabled <?php echo $disable ?> type="text" name="1[logradouro_gdae]" value="<?php echo $aluno->_logradouro_gdae ?>" />
                            -->
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Número
                        </td>
                        <td>
                            <?php echo @$aluno->_num_gdae ?>
                            <!--
                            <input disabled <?php echo $disable ?> type="text" name="1[num_gdae]" value="<?php echo $aluno->_num ?>" placeholder="Nº" />
                            -->
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 35%">
                            Endereço Indicativo:    
                        </td>
                        <td>
                            <input  type="text" name="1[logradouro]" value="<?php echo $aluno->_logradouro ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Número
                        </td>
                        <td>
                            <input <?php echo $disable ?> type="text" name="1[num]" value="<?php echo $aluno->_num ?>" placeholder="Nº" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Condomínio: 
                        </td>
                        <td>
                            <table style="width: 100%">
                                <tr>
                                    <td>
                                        Bloco
                                    </td>
                                    <td>
                                        Torre
                                    </td>
                                    <td>
                                        Apartamento
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input <?php echo $disable ?> type="text" name="1[torre]" value="<?php echo $aluno->_torre ?>" />
                                    </td>
                                    <td>
                                        <input <?php echo $disable ?> type="text" name="1[bloco]" value="<?php echo $aluno->_bloco ?>" />
                                    </td>
                                    <td>
                                        <input <?php echo $disable ?> type="text" name="1[apart]" value="<?php echo $aluno->_apart ?>" />
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Complemento: 
                        </td>
                        <td>
                            <input <?php echo $disable ?> type="text" name="1[complemento]" value="<?php echo $aluno->_complemento ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Bairro:
                        </td>
                        <td>
                            <input <?php echo $disable ?> type="text" name="1[bairro]" value="<?php echo $aluno->_bairro ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Cidade: 
                        </td>
                        <td>
                            <input placeholder="<?= CLI_CIDADE ?>" <?php echo $disable ?> type="text" name="1[cidade]" value="<?php echo $aluno->_cidade ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Estado: 
                        </td>
                        <td>
                            <input placeholder="SP" <?php echo $disable ?> type="text" name="1[uf]" value="<?php echo $aluno->_uf ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            CEP: 
                        </td>
                        <td>
                            <input <?php echo $disable ?> type="text" name="1[cep]" value="<?php echo $aluno->_cep ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Latitude:
                        </td>
                        <td>
                            <input id="inputlat" readonly type="text" name="1[latitude]" value="<?php echo $aluno->_latitude ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Longitude:
                        </td>
                        <td>
                            <input id="inputlong" readonly type="text" name="1[longitude]" value="<?php echo $aluno->_longitude ?>" />
                        </td>
                    </tr>
                    <?php
                //     if (empty($disable) && !empty($aluno->_logradouro)&&false) {
                   if (NULL) {
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
                        <td>
                            Mora em <?= CLI_CIDADE ?> desde
                        </td>
                        <td>
                            <input type="text" name="1[dt_barueri]" value="<?php echo data::converteBr($aluno->_dt_barueri) ?>" <?php echo formulario::dataConf() ?> />

                    </tr>
                </table>

                <div style="text-align: center">
                    <br />
                    <?php echo formulario::hidden($hidden) ?>
                    <input type="hidden" name="1[fk_id_tp]" value="1" />
                    <input type="hidden" name="1[fk_id_pessoa]" value="<?php echo @$id_pessoa ?>" />
                    <input type="hidden" name="activeNav" value="2" />
                    <input type="hidden" name="1[id_end]" value="<?php echo $aluno->_id_end ?>" />
                    <input type="hidden" name="id_pessoa" value="<?php echo @$id_pessoa ?>" />
                    <?php echo DB::hiddenKey('endereco', 'replace') ?>
                    <button class="btn btn-success">
                        Salvar
                    </button>
                </div>

            </div>
        </form>

    </div>
    <div class="col-sm-6">
        <?php
        if (!empty($e->_maps) && !empty($aluno->_latitude) && !empty($aluno->_longitude)) {
            ?>
            <iframe
                frameborder="0" style="border:0; width: 100%; height: 500px"
                src="https://www.google.com/maps/embed/v1/directions?key=AIzaSyB3qDDsCe1EvLrCX06sFthYGGrIhx33KJk&origin=<?php echo trim($aluno->_latitude) ?>, <?php echo trim($aluno->_longitude) ?>&destination=<?php echo $e->_maps ?>&mode=walking" allowfullscreen>
            </iframe> 
            <?php
        }
        ?>
    </div>
</div>
<br /><br />
<form id="geo" method="POST">
    <?php echo formOld::hidden($hidden) ?>
    <input type="hidden" name="activeNav" value="2" />
    <input type="hidden" name="geomap" value="1" />
</form>
<?php
if (!empty($_POST['geomap'])) {
    if (!empty($aluno->_longitude) && !empty($aluno->_latitude)) {
        $lat = $aluno->_latitude;
        $long = $aluno->_longitude;
    } else {
        $address = str_replace(' ', '+', $aluno->_logradouro) . ',' . $aluno->_cidade . ',' . $aluno->_uf . ',brasil';
        $end = maps::geoLocal($address);

        @$lat = $end->lat;
        @$long = $end->lng;
    }
    if (!empty($lat) && !empty($long)) {
        tool::modalInicio();
        include ABSPATH . '/views/gt/end.php';
        tool::modalFim();
    } else {
        tool::modalInicio(NULL, 1);
        ?>
        <div style="text-align: center; font-size: 20px">
            Estamos com dificudade para acessar o Google Maps. Pro favor tente novamente.
        </div>
        <?php
        tool::modalFim();
    }
}
?> 
