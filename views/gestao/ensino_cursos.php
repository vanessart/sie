<div class="container">
    <div id="nvcur" class="row field" style="display: <?php echo (!empty(@$_POST['id_curso']) || !empty($_POST['limp'])) ? 'none' : '' ?>">
        <div class="col-lg-12">
            <div class="text-center">
                <button class="btn btn-info" onclick="document.getElementById('cur').style.display = '';document.getElementById('nvcur').style.display = 'none';">
                    Novo Curso
                </button>
            </div>
        </div>
    </div>
    <div id="cur" class="row field" style="display: <?php echo (!empty(@$_POST['id_curso']) || !empty($_POST['limp'])) ? '' : 'none' ?>">
        <form method="POST">
            <div class="row">
                <div class="col-lg-8">
                    <?php echo formulario::input('1[n_curso]', 'Curso:', NULL, @$curso['n_curso']) ?>
                </div>
                <div class="col-lg-2">
                    <?php echo formulario::input('1[sg_curso]', 'Sigla:', NULL, @$curso['sg_curso']) ?>
                </div>
                <div class="col-lg-2">
                    <?php
                    $options = [0 => 'Não', 1 => 'Sim'];
                    formulario::select('1[ativo]', $options, 'Ativo', @$curso['ativo']);
                    ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-lg-3">
                    <?php echo formulario::selectDB('ge_tp_aval', '1[fk_id_ta]', 'Avaliação por:', @$curso['fk_id_ta']) ?>
                </div>
                <div class="col-lg-3">
                    <?php echo formulario::selectDB('ge_calc_aval', '1[fk_id_calcaval]', 'Notas por:', @$curso['fk_id_calcaval']) ?>
                </div>
                <div class="col-lg-3">
                    <?php echo formulario::input('1[corte]', 'Nota de Corte para Reprovação', NULL, @$curso['corte']) ?>
                </div>
                <div class="col-lg-3">
                    <?php formulario::checkbox('1[extra]', 1, 'Curso Extra', @$curso['extra'])  ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-lg-12">
                    <?php echo formulario::input('1[notas]', 'Notas Possíveis Divididas por ";"', NULL, str_replace('.', ',', @$curso['notas'])) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-lg-12">
                    <?php echo formulario::input('1[notas_legenda]', 'Respectivas Legendas Divididas por ";"', NULL, @$curso['notas_legenda']) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-lg-5">
                    <?php echo formulario::input('1[un_letiva]', 'Unidade Letiva: ', NULL, @$curso['un_letiva']) ?>
                </div>
                <div class="col-lg-3">
                    <?php echo formulario::input('1[sg_letiva]', 'Sigla - Unidade Letiva: ', NULL, @$curso['sg_letiva']) ?>
                </div>
                <div class="col-lg-3">
                    <?php echo formulario::selectNum('1[qt_letiva]', 20, 'Quantidade de Unidades Letivas: ', @$curso['qt_letiva']) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-lg-12">
                    <?php echo formulario::input('1[descr_curso]', 'Descrição:', NULL, @$curso['descr_curso']) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-lg-4 text-center">
                    <?php
                    echo DB::hiddenKey('ge_cursos', 'replace');
                    ?>
                    <input type="hidden" name="aba" value="cursos" />
                    <input type="hidden" name="1[id_curso]" value="<?php echo @$curso['id_curso'] ?>" />
                    <input type="hidden" name="id_tp_ens" value="<?php echo $_POST['id_tp_ens'] ?>" />
                    <input type="hidden" name="1[fk_id_tp_ens]" value="<?php echo @@$curso['fk_id_tp_ens'] ?>" />
                    <button class="btn btn-success">
                        Salvar
                    </button>
                </div>
        </form>
        <div class="col-lg-4 text-center">
            <form method="POST">
                <input type="hidden" name="aba" value="cursos" />
                <input type="hidden" name="id_tp_ens" value="<?php echo $_POST['id_tp_ens'] ?>" />
                <input type="hidden" name="limp" value="1" />
                <button class="btn btn-primary">
                    Limpar
                </button>
            </form>
        </div>
        <div class="col-lg-4 text-center">
            <button class="btn btn-danger" onclick="document.getElementById('cur').style.display = 'none';document.getElementById('nvcur').style.display = '';">
                Fechar
            </button>               
        </div>
    </div>
</div>
<br />
<div class="row">
    <div class="col-lg-12">
        <?php $model->listCursos($id_tp_ens) ?>
    </div>
</div>
</div>