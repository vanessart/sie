
<br />
<form method="POST">
    <div style="width: 95%; margin: 0 auto">
        <div class="row">
            <div class="col-sm-12">
                <?php echo formErp::input(null, 'Nome*', NULL, ' disabled id="n_pessoa" ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-sm-3">
                <?php echo formErp::select('1[cadeirante]', [0 => 'Não', 1 => 'Sim'], 'Cadeirante?', null, null, null, ' id="cadeirante" required ') ?>
            </div>
            <div class="col-sm-3">
                <?php echo formErp::input(null, 'Idade*', NULL, ' disabled id="idade" ') ?>
            </div>
            <div class="col-sm-6">
                <?php echo formErp::input('1[tp_def]', 'Tipo de Dificiência', NULL, ' id="tp_def" required') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-sm-9">
                <?php echo formErp::input('1[logradouro]', 'Endereço', null, ' id="logradouro" required') ?>
            </div>
            <div class="col-sm-3">
                <?php echo formErp::input('1[num]', 'Número', null, ' id="num" required') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-sm-4">
                <?php echo formErp::input('1[cep]', 'CEP', null, ' id="cep"  ') ?>
            </div>
            <div class="col-sm-8">
                <?php echo formErp::input('1[bairro]', 'Bairro', null, ' id="bairro" required ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-sm-8">
                <?php echo formErp::input(NULL, 'Responsável*', NULL, ' disabled id="responsavel" ') ?>
            </div>
            <div class="col-sm-4">
                <?php echo formErp::input(NULL, 'Data Nasc.', NULL, ' disabled id="dt_nasc" ', null, 'date') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-sm-8">
                <?php echo formErp::input(NULL, 'CPF Resp.*', NULL, ' disabled id="cpf_respons" ') ?>
            </div>
            <div class="col-sm-4">
                <?php echo formErp::input('1[rg_respons]', 'RG Resp.', NULL, ' id="rg_respons" ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-sm-6">
                <?php echo formErp::input('1[tel1]', 'Tel Res.', null, ' id="tel1" ') ?>
            </div>
            <div class="col-sm-6">
                <?php echo formErp::input('1[tel2]', 'Celular.', null, ' id="tel2" ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-sm-12">
                <?php echo formErp::input(NULL, 'Escola.*', $escola['nome'], NULL, '  disabled ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-sm-12">
                <?php echo formErp::input(NULL, 'Local.*', $escola['logradouro'] . ', ' . $escola['num'] . ' - ' . $escola['bairro'], NULL, '  disabled ') ?>
            </div>
        </div>
        <br />
        <div class="fieldBorder2">
            Destino<?php echo count($destino) > 1 ? 's' : '' ?>
            <?php
            foreach ($destino as $k => $v) {
                ?>
                <table class="table table-bordered table-hover table-responsive table-striped">
                    <tr>
                        <td colspan="10">
                            <div class="row">
                                <div class="col-sm-4">
                                    Destino: <label>
                                        <input id="ch<?php echo $k ?>" onclick="destOculta(<?php echo $k ?>)" type="checkbox" name="2[destino][<?php echo $k ?>]" value="" />
                                        <?php echo $v['nome'] ?>
                                    </label> 
                                </div>
                                <div class="col-sm-4">
                                    <span id="spamStatus<?= $k  ?>" style="display: none">
                                        <input id="statushidden<?php echo $k ?>" type="hidden" name="<?php echo '2[status]['. $k . ']' ?>" value="" />
                                        <?php 
                                        $options = sqlErp::idNome('transporte_status_aluno');
                                        echo formErp::select('2[status]['. $k . ']', $options, 'Status', null, NUll, null, 'id="status'.$k .'" '.( user::session('id_nivel') == 10?'':'disabled'));
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr id="destOcultoA<?php echo $k ?>" style="display: none">
                        <?php
                        foreach (range(2, 6) as $d) {
                            ?>
                            <td colspan="2" style="text-align: center">
                                <?php echo $d ?>º Feira
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    <tr id="destOcultoB<?php echo $k ?>" style="display: none">
                        <?php
                        foreach (range(2, 6) as $d) {
                            foreach (['Entrada', 'Saída'] as $ke => $e) {
                                ?>
                                <td>
                                    <?php echo $e ?>
                                </td>
                                <?php
                            }
                        }
                        ?> 
                    </tr>
                    <tr id="destOcultoC<?php echo $k ?>" style="display: none">
                        <?php
                        foreach (range(2, 6) as $d) {
                            foreach (['e', 's'] as $ke => $e) {
                                ?>
                                <td>
                                    <input id="<?php echo $k . '_' . $e . $d ?>" style="width: 100%" type="time" name="2[desttime][<?php echo $k ?>][<?php echo $e . $d ?>]" value="" />
                                </td>
                                <?php
                            }
                        }
                        ?> 
                    </tr>
                </table>
                <?php
            }
            ?>
        </div>
        <br />
        <div style="text-align: center">
            <?php echo formErp::hidden(['1[fk_id_inst]' => $id_inst, 'id_inst' => $id_inst, 'cadAdaptado' => 1]) ?>
            <input type="hidden" name="1[id_pessoa]" value="" id="id_pessoa" />
            <button type="submit" class="btn btn-success">
                Salvar
            </button>
        </div>
    </div>
</form>
<footer>
    * Dados Importados do SIEB/SED
</footer>
