<br /><br />
<div style="min-height: 60vh">
<br /><br />
    <form method="POST">
        <div class="row">
            <div class="col-sm-3">
                <?php
                $dia = data::diasUteis($mes_, date("Y"));
                ?>
              
                <div class="input-group btni" >
                <span class="input-group-addon" id="basic-addon1" >  Dia:</span>
                <select name = "1[dia]" style="width: 100%">
                    <?php
                    foreach ($dia as $v) {
                        ?>
                        <option <?php echo empty($_POST['dia']) && intval(date("d")) == $v ? 'selected' : ($v == @$_POST['dia'] ? 'selected' : '') ?>><?php echo $v ?></option>
                        <?php
                    }
                    ?>
                </select>  
                </div>
            </div>
           <div class="col-sm-3">
               <?php echo formulario::selectNum('1[horas]', [1,5], 'Horas', 5) ?>
            </div>
            <div class="col-sm-6">
                <?php formulario::input('1[motivo]', 'Motivo', NULL, empty($_POST['motivo']) ? 'MANDADO DE CITAÇÃO PROCESSO DIGITAL No 1016943-46.2015.8.26.0068' : '') ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-12">
                <?php
                $teas = cadamp::tea($id_inst, NULL, NULL, 'p.n_pessoa');
                
                foreach ($teas as $k => $v) {
                    $teas[$k]['radio'] = '<input ' . (@$_POST['rse'] == $v['rse'] && @$_POST['fk_id_cad'] == $v['fk_id_cad'] ? 'checked' : '') . ' required type="radio" name="id_cad_rse" value="' . $v['fk_id_cad'] . ';' . $v['rse'] . '" />';
                }
                $form['array'] = $teas;
                $form['fields'] = [
                    'Cadastro' => 'cad_pmb',
                    'Cadampe' => 'n_pessoa',
                    'RSE' => 'rse',
                    'Aluno' => 'aluno',
                    'Classe' => 'n_turma',
                    '||' => 'radio'
                ];
                include ABSPATH . '/views/relat/simples1.php';
                ?>

            </div>
         <br /><br />
            <div class="col-sm-12 text-center">
                <input type="hidden" name="mes" value="<?php echo $mes ?>" />
                <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                <input type="hidden" name="1[mes]" value="<?php echo $mes_ ?>" />
                <input type="hidden" name="1[ano]" value="<?php echo date("Y") ?>" />
                <input type="hidden" name="1[fk_id_inst]" value="<?php echo $id_inst ?>" />
                <input type="hidden" name="1[id_fr]" value="<?php echo $id_fr ?>" />
                <input type="hidden" name="1[dt_fr]" value="<?php echo date("Y-m-d") ?>" />
                <input type="hidden" name="1[fk_id_pessoa]" value="<?php echo tool::id_pessoa() ?>" />
                <?php
                echo DB::hiddenKey('lancTea');
                ?>
                <input name="p1" class="btn btn-success" type="submit" value="Salvar" />
                <?php
                ?>

            </div>
        </div>
    </form>
</div>
