<?php
javaScript::somenteNumero();
$predio = predio::get($_POST['id_predio']);
if (!empty($_POST['id_sala'])) {
    $sala = sql::get('salas', '*', ['id_sala' => $_POST['id_sala']], 'fetch');
}
?>
<div class="fieldBody">
    <div style=" border-bottom: solid #7E9AA7 1px;">
        <div class="row">
            <div class="col-lg-10">
                Prédio: <?php echo $predio['n_predio'] ?>
            </div>
            <div class="col-lg-2">
                Sigla: <?php echo $predio['sigla'] ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                Descrição: <?php echo $predio['descricao'] ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                Endereço: <?php echo $predio['logradouro'] . ', ' . $predio['num'] . ' - ' . $predio['complemento'] ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php echo $predio['bairro'] . ' ' . $predio['cidade'] . ' - ' . $predio['uf'] ?>
            </div>
        </div>

    </div>
    <div id="no" class="row" style="display: <?php echo (!empty($_POST['id_sala']) || !empty($_POST['limp'])) ? 'none' : '' ?>" >
        <div class="col-lg-12 text-center">
            <button onclick="document.getElementById('nova').style.display = '';document.getElementById('no').style.display = 'none';" class="btn btn-info">
                Nova Sala
            </button>
        </div>
    </div>
    <div id="nova" style="display: <?php echo (empty($_POST['id_sala']) && empty($_POST['limp'])) ? 'none' : '' ?>" >
        <form method="POST">
            <div class="row">
                <div class="col-lg-4">
                    <?php echo formulario::input('1[n_sala]', 'Nome da sala:', NULL, @$sala['n_sala']) ?>
                </div>
                <div class="col-lg-4">
                    <?php echo formulario::selectDB('tipo_sala', '1[fk_id_ts]', 'Finalidade de Uso', @$sala['fk_id_ts']); ?>
                </div>

                <div class="col-lg-2">
                    <?php
                    $option = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10];
                    echo formulario::select('1[piso]', $option, 'Piso: ', @$sala['piso'])
                    ?>
                </div>

                <div class="col-lg-2">
                    <?php formulario::checkbox('1[cadeirante]', 1, 'Acesso a cadeirantes', @$sala['cadeirante']) ?>
                </div>
            </div>
            <div class="row" style="display: none">
                <div class="col-lg-3">
                    <?php echo formulario::input('1[tomadas]', 'Quantas Tomadas:', NULL, @$sala['tomadas'], 'onkeypress="return SomenteNumero(event)" ') ?>
                </div>
                <div class="col-lg-3">
                    <?php echo formulario::input('1[computadores]', 'Quantos Computadores:', NULL, @$sala['computadores'], 'onkeypress="return SomenteNumero(event)" ') ?>
                </div>
                <div class="col-lg-3">
                    <?php formulario::checkbox('1[ar]', 1, 'Ar Condicionado', @$sala['ar']) ?>
                </div>
                <div class="col-lg-3">
                    <?php formulario::checkbox('1[datashow]', 1, 'Datashow', @$sala['datashow']) ?>
                </div>
                <div class="col-lg-2">
                    <?php formulario::checkbox('1[cortinas]', 1, 'Cortinas', @$sala['cortinas']) ?>
                </div>
                <div class="col-lg-2">
                    <?php formulario::checkbox('1[wifi]', 1, 'Internete sem Fio', @$sala['wifi']) ?>
                </div>
                <div class="col-lg-2">
                    <?php formulario::checkbox('1[cabo_rede]', 1, 'Internete Cabeada', @$sala['cabo_rede']) ?>
                </div>
            </div>
            <br />
            <div style="background-color: lightsteelblue">
                <div class="row" style="padding: 10px">
                    <div class="col-lg-2">
                        Capacidade da Sala
                    </div>
                    <div class="col-lg-3">
                        <div class="input-group">
                            <span class="input-group-addon " id="basic-addon1" >Capacidade Física:</span>
                            <span class="input-group-addon " id="basic-addon1" >
                                <select required name="1[alunos_sala]">
                                    <?php
                                    for ($c = 0; $c <= 50; $c++) {
                                        ?>
                                        <option <?php echo @$sala['alunos_sala'] == $c ? 'selected' : '' ?>><?php echo $c ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </span>
                            <span class="input-group-addon " id="basic-addon1" >Alunos</span>
                        </div>                     
                    </div>
                    <div class="col-lg-3">
                        <div class="input-group">
                            <span class="input-group-addon " id="basic-addon1" >Largura:</span>
                            <input type="text" name="1[largura]" value="<?php echo str_replace('.', ',', @$sala['largura']) ?>" class="form-control" aria-describedby="basic-addon1" >
                            <span class="input-group-addon " id="basic-addon1" >metros</span>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="input-group">
                            <span class="input-group-addon " id="basic-addon1" >Comprimento:</span>
                            <input type="text" name="1[comprimento]" value="<?php echo str_replace('.', ',', @$sala['comprimento']) ?>" class="form-control" aria-describedby="basic-addon1" >
                            <span class="input-group-addon " id="basic-addon1" >metros</span>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <input type="hidden" name="1[fk_id_predio]" value="<?php echo $_POST['id_predio'] ?>" />
                <input type="hidden" name="id_predio" value="<?php echo $_POST['id_predio'] ?>" />
                <input type="hidden" name="id_inst" value="<?php echo $_POST['id_inst'] ?>" />
                <input type="hidden" name="aba" value="sala" />
                <div class="col-lg-4 text-center">
                    <button type="button" onclick="document.getElementById('nova').style.display = 'none';document.getElementById('no').style.display = '';" class="btn btn-danger">
                        Fechar
                    </button>
                </div>
                <div class="col-lg-4 text-center">
                    <button type="button" onclick="document.limp.submit()" class="btn btn-info">
                        Limpar
                    </button>
                </div>
                <div class="col-lg-4 text-center">
                    <input type="hidden" name="1[id_sala]" value="<?php echo @$_POST['id_sala'] ?>" />
                    <?php echo $model->db->hiddenKey('salas', 'replace') ?>
                    <button class="btn btn-success">
                        Salvar
                    </button>
                </div>
            </div>
        </form>
        <form name="limp" method="POST">
            <input type="hidden" name="id_predio" value="<?php echo $_POST['id_predio'] ?>" />
            <input type="hidden" name="aba" value="sala" />
            <input type="hidden" name="id_inst" value="<?php echo $_POST['id_inst'] ?>" /> 
            <input type="hidden" name="limp" value="1" />
        </form>
    </div>
    <br />
    <?php $model->lisSalas(@$_POST['id_predio']) ?>
</div>
