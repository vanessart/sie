<?php
@$id_predio = $_POST['id_predio'];
formulario::telefonesScript($id_predio, ['id_predio' => $id_predio]);

if (!empty($_POST['id_sala'])) {
    $sala = sql::get('salas', '*', ['id_sala' => $_POST['id_sala']], 'fetch');
}
?>
<div class="fieldBody">
    <div class="fieldTop">
        Prédios e Espaços Físicos
    </div>
    <br />
    <div class="container">
        <div class="row"  >
            <div class="col-lg-3">
                <?php
                $predio = $model->selectPredio(NULL, NULL, 1);

                if (!empty($id_predio)) {
                    $predio = predio::get($id_predio);
                } else {
                    @$id_predio = $predio['fk_id_predio'];
                }
                ?>
            </div>
            <?php
            if (!empty($predio)) {
                ?>
                <form method="POST">
                    <div class="col-lg-3">
                        <button name="setPredio" value="1" class="btn btn-info">
                            Editar dados do prédio
                        </button>
                    </div>
                </form>
                <form method="POST">
                    <div class="col-lg-3 text-center" >
                        <button name="setSalas" value="1" class="btn btn-info">
                            Incluir um Novo Espaço Físico
                        </button>
                    </div>
                </form>
            <form action="<?php echo HOME_URI ?>/gestao/salapdf" target="_blank" method="POST">
                    <div class="col-lg-3 text-center" >
                        <button class="btn btn-warning">
                            Imprimir
                        </button>
                    </div>
                </form>
            <?php } ?>
        </div>
        <br /><br />
        <?php
        if (!empty($_POST['setPredio'])) {
            ?>
            <form method="POST" >
                <div class="row">
                    <div class="col-lg-12">
                        Prédio: 
                        <?php echo @$predio['n_predio'] ?>
                    </div>            
                </div>
                <div class="row">
                    <div class="col-lg-2">
                        <?php formulario::input('1[cep]', 'CEP:', NULL, @$predio['cep']) ?>
                    </div>
                    <div class="col-lg-8">
                        <?php formulario::input('1[logradouro]', 'Logradouro:', NULL, @$predio['logradouro']) ?>
                    </div>
                    <div class="col-lg-2">
                        <?php formulario::input('1[num]', 'Nº:', NULL, @$predio['num']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?php formulario::input('1[complemento]', 'Complemento:', NULL, @$predio['complemento']) ?>
                    </div>            
                </div>
                <div class="row">
                    <div class="col-lg-5">
                        <?php formulario::input('1[bairro]', 'Bairro:', NULL, @$predio['bairro']) ?>
                    </div>
                    <div class="col-lg-5">
                        <?php formulario::input('1[cidade]', 'Cidade:', NULL, @$predio['cidade']) ?>
                    </div>
                    <div class="col-lg-2">
                        <?php formulario::input('1[uf]', 'UF:', NULL, @$predio['uf']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?php formulario::input('1[descricao]', 'Obs:', NULL, @$predio['descricao']) ?>
                    </div>            
                </div>


                <div class="row" style="margin-top: 10px">
                    <div class="col-md-12 art-button">
                        <span style="color: white" aria-hidden="true">
                            Telefones
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <input type="hidden" name="novo" value="1" />
                        <?php
                        if (!empty($id_predio)) {
                            $tel = sql::get('telefones', '*', ['fkid ' => $id_predio, 'fk_id_tp' => 1]);
                        }
                        formulario::telefones(@$tel);
                        ?>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-lg-6 text-center">
                        <a href="">
                            <button type="button" onclick="document.getElementById('ep').style.display = 'none';document.getElementById('princ').style.display = '';document.getElementById('salas').style.display = ''"  class="btn btn-danger">
                                Fechar
                            </button>
                        </a>
                    </div>
                    <div class="col-lg-6 text-center">
                        <input type="hidden" name="1[id_predio]" value="<?php echo @$predio['id_predio'] ?>" />
                        <input type="hidden" name="id_predio" value="<?php echo @$predio['id_predio'] ?>" />
                        <?php echo DB::hiddenKey('setPred') ?>
                        <button class="btn btn-success">
                            Salvar Dados do Prédio
                        </button>
                    </div>
                </div>
            </form> 
            <?php
        } elseif (!empty($_POST['setSalas'])) {
            ?>
            <div class="row" style="margin-top: 10px">
                <div class="col-md-12 art-button">
                    <span style="color: white" aria-hidden="true">
                        Espaço físico
                    </span>
                </div>
            </div>
            <form method="POST">
                <div class="row">
                    <div class="col-lg-4">
                        <?php echo formulario::input('1[n_sala]', 'Nome do Espaço Físico:', NULL, @$sala['n_sala']) ?>
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
                            Capacidade do Espaço Físico
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
                    <input type="hidden" name="1[fk_id_predio]" value="<?php echo $id_predio ?>" />
                    <input type="hidden" name="id_predio" value="<?php echo $id_predio ?>" />
                    <input type="hidden" name="id_inst" value="<?php echo tool::id_inst() ?>" />
                    <input type="hidden" name="aba" value="sala" />
                    <div class="col-lg-6 text-center">
                        <a href="">
                            <button type="button" onclick="document.getElementById('nova').style.display = 'none';document.getElementById('no').style.display = '';document.getElementById('princ').style.display = ''" class="btn btn-danger">
                                Fechar
                            </button>
                        </a>
                    </div>
                    <div class="col-lg-6 text-center">
                        <input type="hidden" name="1[id_sala]" value="<?php echo @$_POST['id_sala'] ?>" />
                        <?php echo $model->db->hiddenKey('salas', 'replace') ?>
                        <button class="btn btn-success">
                            Salvar
                        </button>
                    </div>
                </div>
            </form>
            <form name="limp" method="POST">
                <input type="hidden" name="id_predio" value="<?php echo $id_predio ?>" />
                <input type="hidden" name="limp" value="1" />
            </form>
            <br />

            <?php
        }
        ?> 
        <div id="salas" class="row">
            <div class="col-lg-12">
                <?php $model->lisSalas(@$id_predio) ?>
            </div>
        </div>
    </div>
</div>
