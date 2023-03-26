<?php
    $sql = "SELECT i.id_item, SUM(t.quant_item) as quant "
            . " FROM biro_trab t "
            . " JOIN biro_item i on i.id_item = t.fk_id_item "
            . " WHERE t.status_biro LIKE 'Concluído' "
            . " GROUP BY i.id_item ";
    $query = $model->db->query($sql);
    $gasto_ = $query->fetchAll();
    foreach ($gasto_ as $v) {
        $contItem[$v['id_item']] = $v['quant'];
    }

$i = sql::get(['biro_item', 'biro_contrato'], '*', ['ativo' => 1]);
foreach ($i as $v) {
    $itens[$v['id_item']] = $v['n_con'] . ' - ' . $v['lote'] . ' - ' . $v['num'] . ' - ' . $v['n_item'].' - (Disp:' .(@$v['quant']-@$contItem[@$v['id_item']]). ')' ;
}
funcionarios::autocomplete(NULL, 1);
inst::autocomplete();
if (!empty($_POST['id_trab'])) {
    $id = $_POST['id_trab'];
} elseif (!empty($model->_last_id)) {
    $id = $model->_last_id;
}
if (!empty($id)) {
    @$campos = sql::get('biro_trab', '*', ['id_trab' => $id], 'fetch');
    $disabled = "disabled";
    if ($campos['tipo'] == 61 || $campos['tipo'] == 61) {
        $d_pg = 'none';
        $d_cd = '';
        $d_fv = 'none';
        $d_cor = 'none';
        $d_fm = 'none';
        $d_aca = 'none';
        $d_cp = 'none';
        $d_tpg = 'none';
        $d_sul = 'none';
    } elseif ($campos['tipo'] == 33 || $campos['tipo'] == 64) {
        $d_pg = 'none';
        $d_cd = '';
        $d_fv = 'none';
        $d_cor = '';
        $d_fm = '';
        $d_aca = '';
        $d_cp = '';
        $d_tpg = 'one';
        $d_sul = '';
    } elseif ($campos['tipo'] == 63) {
        $d_pg = '';
        $d_cd = 'none';
        $d_fv = 'none';
        $d_cor = 'none';
        $d_fm = '';
        $d_aca = 'none';
        $d_cp = 'none';
        $d_tpg = 'none';
        $d_sul = 'none';
    } else {
        $d_cd = 'none';
    }
} else {
    $campos = NULL;
    $d_cd = 'none';
}
?>
<div class="fieldBody">

    <script>
        function mesmo() {
            if (document.getElementById("mesmo1").checked == true) {
                document.getElementById("busca1").value = document.getElementById("busca").value;
                document.getElementById("tel11").value = document.getElementById("tel1").value;
                document.getElementById("email1").value = document.getElementById("email").value;
            } else {
                document.getElementById("busca1").value = "";
                document.getElementById("tel11").value = "";
                document.getElementById("email1").value = "";
            }
        }

        function conta() {

            f1 = document.getElementById("fqg").value;

            if (document.getElementById("ffv").checked) {
                f1 = Math.ceil(f1 / 2);
            }

            f2 = document.getElementById("fcp").value;

            f3 = f1 * f2;


            document.getElementById("td").value = f3;


        }


        function recebedor() {
            if (document.getElementById("retirar").checked == true) {
                document.getElementById("Recebedor").style.display = "";
            } else {
                document.getElementById("Recebedor").style.display = "none";
            }
        }

    </script>    
    <?php ?>
    <div class="fieldTop">
        Serviço <?php echo!empty(@$campos['id_trab']) ? ' - Serviço nº ' . @$campos['id_trab'] : '' ?>
    </div>
    <br />
    <form action="" method="POST" >
        <div class="panel panel-default" >
            <div class="panel panel-heading">
                Solicitante
            </div>
            <div class="panel panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <?php formulario::input('1[n_pessoa]', ' Nome:', NULL, ucwords(strtolower(@$campos['n_pessoa'])), ' id="busca" onkeypress="complete()" ') ?>
                    </div>
                    <div class="col-md-4">
                        <?php formulario::input('1[rm]', ' Matrícula:', NULL, ucwords(strtolower(@$campos['rm'])), ' id="rm" ') ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-12">
                        <?php formulario::input('1[local]', 'Local de Trabalho:', NULL, @$campos['local'], ' id="n_inst"  onkeypress="completeInst()" ') ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-9">
                        <?php formulario::input('1[email]', 'E-mail do Local de Trabalho:', NULL, @$campos['email'], ' id="email" ') ?>
                    </div>
                    <div class="col-md-3">
                        <?php formulario::input('1[tel1]', 'Telefone:', NULL, @$campos['tel1'], ' id="tel1" ') ?>
                    </div>
                </div>
            </div>
        </div>
        <br />
        <div class="panel panel-default" >
            <div class="panel panel-heading">
                Trabalho
            </div>
            <div class="panel panel-body"> 
                <div class="fieldBorder2">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo formulario::select('1[fk_id_item]', $itens, 'Contrato/Lote/Item', @$campos['fk_id_item']) ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-sm-3">
                            <?php echo formulario::input('1[dt_biro]', 'Data', NULL, empty(@$campos['dt_biro']) ? data::converteBr(date("Y-m-d")) : data::converteBr($campos['dt_biro'])) ?>
                        </div>
                        <div class="col-sm-3">
                            <?php echo formulario::input('1[quant_item]', 'Quantidade de Serviços', NULL, @$campos['quant_item']) ?>
                        </div>

                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="input-group-addon">
                                Tipo
                            </div>
                            <select <?php echo @$disabled ?> name="1[tipo]"  onchange="this.options[this.selectedIndex].onclick()">
                                <?php
                                $op = sql::get('biro_list');
                                foreach ($op as $v) {
                                    ?>
                                    <option onclick="tipo(<?php echo $v['id_list'] ?>)" <?php echo @$campos['tipo'] == $v['id_list'] ? 'selected' : '' ?> value="<?php echo $v['id_list'] ?>"><?php echo $v['n_list'] ?></option>
                                    <?php
                                }
                                ?>

                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <?php
                        formulario::select('1[prioridade]', ['Media' => 'Media', 'Alta' => 'Alta', 'Baixa' => 'Baixa'], 'Prioridade:', @$campos['prioridade']);
                        ?> 
                    </div>
                    <div class="col-md-3">
                        <?php
                        formulario::input('1[dt_prev]', ' Previsão de Entrega:', NULL, empty(@$campos['dt_prev']) ? date("d/m/Y") : data::converteBr(@$campos['dt_prev']), formulario::dataConf());
                        ?> 
                    </div>
                    <div class="col-md-3">
                        <?php
                        unset($options);
                        $options = [
                            'Aberto' => 'Aberto',
                            'Em Espera' => 'Em Espera',
                            'Em Execução' => 'Em Execução',
                            'Concluído' => 'Concluído',
                            'Recusado' => 'Recusado',
                            'Cancelado' => 'Cancelado',
                        ];
                        formulario::select('1[status_biro]', $options, 'Status:', @$campos['status_biro']);
                        ?>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <textarea name="1[descr_biro]" style="width: 100%; height: 60px" placeholder="Descrição"><?php echo @$campos['descr_biro'] ?></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3" id="d_pg" style="display: <?php echo @$d_pg ?>">
                        <?php echo formulario::input('1[qt_pg_biro]', 'Páginas: ', NULL, @$campos['qt_pg_biro'], ' id="pg"  onkeyup="calcula()"') ?>
                    </div>
                    <div class="col-md-3" id="d_cd" style="display: <?php echo @$d_cd ?>">
                        <?php echo formulario::input('1[qt_caderno]', 'Quant. cadernos: ', NULL, @$campos['qt_caderno'], ' id="pg"  onkeyup="calcula()"') ?>
                    </div>
                    <div class="col-md-3" id="d_fv" style="display: <?php echo @$d_fv ?>">
                        <?php echo formulario::checkbox('1[frente_verso_biro]', 1, 'Frente e Verso', @$campos['frente_verso_biro'], NULL, 'id="fv" onclick="calcula()"') ?>
                    </div>
                    <div class="col-md-3" id="d_cor" style="display: <?php echo @$d_cor ?>">
                        <?php echo formulario::checkbox('1[cor_biro]', 1, 'Coloridas', @$campos['cor_biro'], NULL, 'id="cor"') ?>
                    </div>
                    <div class="col-md-3" id="d_fm" style="display: <?php echo @$d_fm ?>">
                        <?php echo formulario::selectDB('biro_formato', '1[formato]', 'Formato: ', @$campos['formato'], ' id="fm" ') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3" id="d_aca" style="display: <?php echo @$d_aca ?>">
                        <?php echo formulario::select('1[acabamento_biro]', ['Grampear' => 'Grampear', 'Encadernar' => 'Encadernar'], 'Acabamento: ', @$campos['acabamento_biro'], NULL, NULL, 'id="aca"') ?>
                    </div>
                    <div class="col-md-3" id="d_cp" style="display: <?php echo @$d_cp ?>">
                        <?php echo formulario::input('1[qt_cp_biro]', 'Cópias por página: ', NULL, @$campos['qt_cp_biro'], ' onkeyup="calcula()" id="cp"') ?>
                    </div>
                    <div class="col-md-3" id="d_tpg" style="display: <?php echo @$d_tpg ?>">
                        <?php echo formulario::input('1[total_cp]', 'Total de cópias: ', NULL, @$campos['total_cp'], 'id="tpg" readonly') ?>
                    </div>
                    <div class="col-md-3" id="d_sul" style="display: <?php echo @$d_sul ?>">
                        <?php echo formulario::input('1[qt_folha]', 'Sulfites: ', NULL, @$campos['qt_folha'], ' id="sul" readonly') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <textarea name="1[obs_biro]" style="width: 100%; height: 60px" placeholder="Observações"><?php echo @$campos['obs_biro'] ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <br />
        <div class="panel panel-default" >
            <div class="panel panel-heading">
                Recebedor:&nbsp;&nbsp;
                <label>
                    (&nbsp;
                    <input id="mesmo1" type="checkbox" onclick="mesmo()" value="ON" />
                    &nbsp;
                    O mesmo
                    &nbsp;
                    )
                </label>
            </div>
            <div class="panel panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php formulario::input('1[n_pessoa1]', 'Nome:', NULL, @$campos['n_pessoa1'], 'id="busca1"') ?>       
                    </div>
                    <div class="col-md-4">
                        <?php formulario::input('1[tel_ret]', 'Telefone:', NULL, @$campos['tel_ret'], 'id="tel11"') ?>       
                    </div>
                    <div class="col-md-8">
                        <?php formulario::input('1[email_ret]', 'E-mail:', NULL, @$campos['email_ret'], 'id="email1"') ?>       
                    </div>
                </div>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-md-3">

            </div>
            <div class="col-md-3">
                <?php if (!empty(@$campos['id_trab'])) { ?>
                    <a href="<?php echo HOME_URI; ?>/biro/prot?id=003<?php echo @$campos['rastro'] . @$id ?>&p=1"  target="_blank">
                        <input  style="width: 150px" class="btn btn-primary" type="button" value="Gerar Protocolo" />
                    </a>
                <?php } ?>
            </div>
            <div class="col-md-3">
                <input  style="width: 150px" name="salvBiro" class="btn btn-success" type="submit" value="Salvar" />
            </div>
            <div class="col-md-3">
                <a href="<?php echo HOME_URI; ?>/biro" >
                    <input  style="width: 150px" class="btn btn-danger" type="button" value="Sair (Salve Antes)" />
                </a>
            </div>
        </div>
        <?php echo DB::hiddenKey('biro_trab', 'replace') ?>
        <input type="hidden" name="prev_old" value="<?php echo data::converte(@$campos['dt_prev']) ?>" />
        <input type="hidden" name="status_old" value="<?php echo @$campos['status_biro'] ?>" />
        <input type="hidden" name="1[id_trab]" value="<?php echo @$id ?>" />
        <input type="hidden" name="1[rastro]" value="<?php echo empty(@$campos['rastro']) ? substr(uniqid(), 0, 4) : @$campos['rastro'] ?>" />
        <input id="id_pessoa" style="width: 872px" type="hidden" name="1[fk_id_pessoa]" value="<?php echo @$campos['fk_id_pessoa'] ?>"/>
        <input id="id_inst" style="width: 872px" type="hidden" name="1[fk_id_inst]" value="<?php echo @$campos['fk_id_inst'] ?>"/>
        <input type="hidden" name="id_trab" value="<?php echo @$id ?>" />
    </form>
    <br /><br />
    <?php
    $model->logbiro(@$id);
    ?>
</div>
<script>
    function calcula() {

        paginas = document.getElementById('pg').value;
        copias = document.getElementById('cp').value;

        document.getElementById('tpg').value = (copias * paginas);

        if (document.getElementById('fv').checked) {
            sulfites = Math.ceil(paginas / 2);
        } else {
            sulfites = paginas;
        }

        document.getElementById('sul').value = sulfites * copias;
    }

    function tipo(t) {
        if (t == 61 || t == 62) {
            document.getElementById('d_pg').style.display = 'none';
            document.getElementById('d_cd').style.display = '';
            document.getElementById('d_fv').style.display = 'none';
            document.getElementById('d_cor').style.display = 'none';
            document.getElementById('d_fm').style.display = 'none';
            document.getElementById('d_aca').style.display = 'none';
            document.getElementById('d_cp').style.display = 'none';
            document.getElementById('d_tpg').style.display = 'none';
            document.getElementById('d_sul').style.display = 'none';
        }
        if (t == 33 || t == 64) {
            document.getElementById('d_pg').style.display = '';
            document.getElementById('d_cd').style.display = 'none';
            document.getElementById('d_fv').style.display = '';
            document.getElementById('d_cor').style.display = '';
            document.getElementById('d_fm').style.display = '';
            document.getElementById('d_aca').style.display = '';
            document.getElementById('d_cp').style.display = '';
            document.getElementById('d_tpg').style.display = '';
            document.getElementById('d_sul').style.display = '';
        }
        if (t == 63) {
            document.getElementById('d_pg').style.display = '';
            document.getElementById('d_cd').style.display = 'none';
            document.getElementById('d_fv').style.display = 'none';
            document.getElementById('d_cor').style.display = 'none';
            document.getElementById('d_fm').style.display = '';
            document.getElementById('d_aca').style.display = 'none';
            document.getElementById('d_cp').style.display = 'none';
            document.getElementById('d_tpg').style.display = 'none';
            document.getElementById('d_sul').style.display = 'none';
        }
    }
</script>