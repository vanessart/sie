<script>
    function abre(id) {
        if (id != 'cie') {
            document.getElementById('cie').style.display = 'none';
        }
        if (document.getElementById(id).style.display == '') {
            document.getElementById(id).style.display = 'none';
        } else {
            document.getElementById(id).style.display = '';
        }

    }
    function fecha(id) {
        document.getElementById(id).style.display = 'none';
    }
</script>
<div class="field">
    <?php if ($inst['ativo'] == 1) { ?>
        <div class="row">
            <div class="col-lg-6">
                <?php formulario::input(NULL, 'Escola:', NULL, $inst['n_inst'], 'disabled') ?>
            </div>
            <div class="col-lg-6">
                <?php formulario::input(NULL, 'E-mail:', NULL, $inst['email'], 'disabled') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-3" >
                <button onclick="abre('cie')" class="btn btn-info">
                    Editar Dados Complementares
                </button>
            </div>
            <div class="col-md-3">
                <form name="esc" method="POST">
                    <input type="hidden" name="1[classe]" value="<?php echo $escola['classe'] == 1 ? 0 : 1 ?>" />
                    <input type="hidden" name="aba" value="escola" />
                    <input type="hidden" name="id_inst" value="<?php echo @$_POST['id_inst'] ?>" />
                    <input type="hidden" name="1[id_escola]" value="<?php echo $escola['id_escola'] ?>" />
                    <?php echo DB::hiddenKey('ge_escolas', 'replace') ?>
                    <button type="submit" class="btn <?php echo $escola['classe'] == 1 ? 'btn-info' : 'btn-danger' ?>">
                        Classes <?php echo $escola['classe'] == 1 ? 'Abertas' : 'fechadas' ?>
                    </button>
                </form>
            </div>
        </div>
        <br /><br />
        <div id="cie" class="row fieldWhite" style="display: none">

            <form method="POST">
                <div class="row">
                    <div class="col-lg-4">
                        <?php formulario::input('1[cie_escola]', 'CIE:', NULL, @$escola['cie_escola']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?php formulario::input('1[ato_cria]', 'Ato de Criação: ', NULL, @$escola['ato_cria']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?php formulario::input('1[ato_municipa]', 'Ato de Municipalização:', NULL, @$escola['ato_municipa']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        Segmentos:
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        $segmento = explode('|', $escola['fk_id_tp_ens']);
                        $segList = sql::get('ge_tp_ensino');
                        foreach ($segList as $n) {
                            $checked = null;
                            $class = NULL;
                            foreach ($segmento as $a) {
                                if (($n['id_tp_ens'] == $a)) {
                                    $checked = "checked";
                                    $class = "alert-info";
                                }
                            }
                            ?>
                            <div class="col-lg-4">
                                <div class="input-group" style="width: 100%">
                                    <label  style="width: 100%">
                                        <span class="input-group-addon <?php echo $class ?>" style="text-align: left; width: 20px">
                                            <input <?php echo @$checked ?> type="checkbox" aria-label="..." name="fk_id_tp_ens[]" value="<?php echo $n['id_tp_ens'] ?>">
                                        </span>
                                        <span class="input-group-addon <?php echo $class ?>" style="text-align: left">
                                            <?php echo $n['n_tp_ens'] ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <?php
                        }
                       // echo DB::hiddenKey('SalavEscola');
                        ?>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <input type="hidden" name="1[id_escola]" value="<?php echo @$escola['id_escola'] ?>" />
                    <input type="hidden" name="id_inst" value="<?php echo $_POST['id_inst'] ?>" />
                    <input type="hidden" name="1[fk_id_inst]" value="<?php echo $_POST['id_inst'] ?>" />
                    <input type="hidden" name="aba" value="escola" />
                    <button name="SalavEscola" value="1" class="btn btn-success">
                        Salvar
                    </button>

                </div>
            </form>
            <div class="col-lg-6 text-center">
                <button class="btn btn-danger" type="button" onclick="fecha('cie')">
                    fechar
                </button>
            </div>
        </div>
    <?php } else { ?>
        <div class="fieldTop">
            Escola desativada
        </div>
    <?php } ?>
</div>

