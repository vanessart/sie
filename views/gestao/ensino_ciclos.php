<div class="panel panel-default">
    <div class="panel panel-heading">
        Ciclos do curso <?php echo $curso['n_curso'] ?>
    </div>
    <br />
    <div class="panel panel-body">
        <div id="nvci" class="row field" style="display: <?php echo (!empty(@$_POST['id_ciclo']) || !empty($_POST['limp'])) ? 'none' : '' ?>">
            <div class="col-lg-12">
                <div class="text-center">
                    <button class="btn btn-info" onclick="document.getElementById('ci').style.display = '';document.getElementById('nvci').style.display = 'none';">
                        Novo Ciclo
                    </button>
                </div>
            </div>
        </div>
        <br />
        <div id="ci" class="row" style="display: <?php echo (!empty(@$_POST['id_ciclo']) || !empty($_POST['limp'])) ? '' : 'none' ?>; padding: 8">
            <form method="POST">
                <div class="row">
                    <div class="col-lg-2">
                        <?php echo formulario::input('1[n_ciclo]', 'Ciclo:', NULL, @$ciclo['n_ciclo']) ?>
                    </div>
                    <div class="col-lg-2">
                        <?php echo formulario::input('1[sg_ciclo]', 'Sigla:', NULL, @$ciclo['sg_ciclo']) ?>
                    </div>
                    <div class="col-lg-3">
                        <?php
                        $options = [0 => 'Não', 1 => 'Sim'];
                        formulario::select('1[aprova_automatico]', $options, 'Aprovação Automática', @$ciclo['aprova_automatico']);
                        ?>
                    </div>
                    <!--
                    <div class="col-lg-2">
                    <?php //echo formulario::selectDB('ge_grades', '1[fk_id_grade]', 'Grade', @$ciclo['fk_id_grade'], NULL, NULL, NULL, NULL, ['fk_id_ta' => $curso['fk_id_ta']]) ?>
                    </div>
                    -->
                    <div class="col-lg-3">
                        <?php formulario::select('1[periodicidade]', curso::periodtipo(), 'Periodicidade', @$ciclo['periodicidade']); ?>
                    </div>
                    <div class="col-lg-2">
                        <?php formulario::select('1[ativo]', [1=>'Sim', 0=>'não'], 'Ativo', @$ciclo['ativo'])  ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-lg-12">

                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    <?php
                    echo DB::hiddenKey('ge_ciclos', 'replace');
                    ?>
                    <input type="hidden" name="aba" value="ciclos" />
                    <input type="hidden" name="id_curso" value="<?php echo $id_curso ?>" />
                    <input type="hidden" name="1[id_ciclo]" value="<?php echo @$ciclo['id_ciclo'] ?>" />
                    <input type="hidden" name="1[fk_id_curso]" value="<?php echo $id_curso ?>" />
                    <input type="hidden" name="id_tp_ens" value="<?php echo $_POST['id_tp_ens'] ?>" />
                    <button class="btn btn-success">
                        Salvar
                    </button>
                </div>
            </form>
            <div class="col-lg-4 text-center">
                <form method="POST">
                    <input type="hidden" name="aba" value="ciclos" />
                    <input type="hidden" name="id_tp_ens" value="<?php echo $_POST['id_tp_ens'] ?>" />
                    <input type="hidden" name="id_curso" value="<?php echo $id_curso ?>" />
                    <input type="hidden" name="limp" value="1" />
                    <button class="btn btn-primary">
                        Limpar
                    </button>
                </form>
            </div>
            <div class="col-lg-4 text-center">
                <button type="button" class="btn btn-danger" onclick="document.getElementById('ci').style.display = 'none';document.getElementById('nvci').style.display = '';">
                    Fechar
                </button>               
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-lg-12">
                <?php $model->listCiclos($id_curso) ?>
            </div>
        </div>
    </div>
</div>
