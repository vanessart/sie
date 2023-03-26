<?php
if (!defined('ABSPATH'))
    exit;
if (!empty($id_protocolo)) {
    $protocolo = $model->getProtocolo($id_protocolo);
    $anexos = sql::get('protocolo_up', 'id_up,link,n_up', ['fk_id_protocolo' => $id_protocolo]);
    if ($id_pessoa) {
        $aluno = $model->alunoAeeGet($id_pessoa);
        $aluno_matriculadoAEE = $model->alunoAEE($id_pessoa);
    }?>
    <style type="text/css">
         /* Esconde o input */
        input[type='file'] {
            display: none
        }
        /* Aparência que terá o seletor de arquivo */
        .labelSet {
            background-color: #3498db;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            margin: 0 auto;
            padding: 6px;
            width: 300px;
            text-align: center
        }
        .titulo { 
            color: #888;
            font-size: 16px;
            padding-bottom: 5px;
        }
    </style>
    <?php 
    if (!empty($aluno)) {
        if (!empty($protocolo) && !empty($protocolo['n_inst_aee']) ) { ?>
            <div class="alert alert-warning" style="padding-top: 10px; padding-bottom: 0">
                <div class="row" style="padding-bottom: 15px;">
                    <div class="col">
                        <?php
                        $_OA = concord::oa($aluno['id_pessoa'], 1);
                        $_oa = concord::oa($aluno['id_pessoa']);
                        ?>
                       <?php echo $_OA ?> alun<?php echo $_oa ?> <?= $aluno['n_pessoa'] ?> foi encaminhad<?php echo $_oa ?> para o polo AEE <?= $protocolo['n_inst_aee'] ?> na turma <?= $protocolo['n_turma_aee'] ?>
                    </div>
                </div>
            </div>
            <?php 
        } 
        if (!empty($aluno_matriculadoAEE)) {
           echo toolErp::divAlert('warning', 'Informação Importante: este aluno já está matriculado em uma Turma AEE'); 
        }?>
        <div class="alert alert-info" style="padding-top:  10px; padding-bottom: 0">
            <div class="row" style="padding-bottom: 15px;">
                <div class="col">
                   <b>Alun<?php echo concord::oa($aluno['id_pessoa']) ?>:</b> <?= $aluno['n_pessoa'] ?>
                </div>
                <div class="col">
                  <b>RSE:</b> <?= $aluno['id_pessoa'] ?>
                </div>
            </div>
            <div class="row" style="padding-bottom: 15px;">
                <div class="col">
                   <b>Deficiência:</b> <?= $aluno['def'] ?>
                </div>
                <div class="col">
                   <b>Turma:</b> <?= $aluno['n_turma'] ?> - <?= $aluno['n_inst'] ?>
                </div>
            </div>
        </div>
        <?php
    }?>
    <br><br>
    <form id="form" enctype="multipart/form-data" action="<?= HOME_URI ?>/apd/protocoloGerente" method="POST">
        <div class="card p-4">
            <div class="row">
                <div class="col">
                    <?= formErp::selectDB('ge_necessidades_especiais', '1[fk_id_ne]', 'Deficiência', @$protocolo['fk_id_ne']) ?>
                </div>
            
                    <div class="col-4">
                        <label class="labelSet" id="label" for='selecao-arquivo' style="font-size: 18px;height: 38px;">Anexar Documento</label>
                        <input class="btn btn-outline-info" type="file" name="__arquivo" value="" id='selecao-arquivo' multiple />
                        <input type="hidden" name="up" id='up' value="0">
                    </div>
                    <div class="col">
                        <input type="hidden" id="_file_names">
                        <div id="_input_files"></div>
                        <div id="mostra-arquivo" class="titulo"></div>
                    </div>
                </div>
            <br><br>
            <div class="row">
                <div class="col col-form-label">
                    <div><strong>ANEXOS</strong></div> 
                    <?php if (empty($anexos)) { ?>
                        Não há anexos
                    <?php } else { ?>
                        <table class="table">
                            <tr>
                                <th align="center" style="width:8%">Item</th>
                                <th>Nome</th>
                                <th colspan="2" width="10%">Anexo</th>
                            </tr>
                            <?php 
                            $sqlkey = formErp::token('protocolo_up', 'delete');
                            foreach ($anexos as $key => $value) {
                                $existeAnexo = 1;
                                $hiddenUp = [
                                    '1[id_up]' => $value['id_up'],
                                    'activeNav' => 1,
                                ];?>
                            <tr>
                                <td align="center"><?= $key+1 ?></td>
                                <td><?= $value['n_up'] ?></td>
                                <td>
                                <a href="<?= HOME_URI . '/pub/protocoloDoc/' . $value['link'] ?>" target="_blank" class="btn btn-outline-info">
                                    &#8681;
                                </a>
                            </td>
                            </tr>
                            <?php } ?>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div>

        <br><br>
        <div class="row">
            <div class="col text-center">
                <?=
                formErp::hidden([
                    'id_area' => $id_area,
                    '1[fk_id_area]' => $id_area,
                    'id_pessoa' => $id_pessoa,
                    '1[fk_id_pessoa]' => $id_pessoa,
                    'id_protocolo' => $id_protocolo,
                    '1[id_protocolo]' => $id_protocolo,
                    '1[fk_id_status]' => $protocolo['fk_id_status']
                ])
                . formErp::hiddenToken('protocoloCad');
                ?> 
                <button id='botao' type="button" onclick="salvar()" class="btn btn-lg btn-success">Salvar</button>      
            </div>
        </div>
    </form>
    <script>
      jQuery(function($){
        $('#selecao-arquivo').change(function(){
            var input = document.getElementById("selecao-arquivo");
            var nome, _file_names = document.getElementById('_file_names').value;
            if (input.files.length>0) {
                document.getElementById("up").value = 1;
                let exists = false;

                for (var i = 0 ; i < input.files.length; i++) {
                    if (nome) {
                        nome = input.files[i].name+'<br> '+nome;
                    }else{
                        nome = input.files[i].name;
                    }
                }

                if (!exists) {
                    let i = $("#_input_files input[type='file']").length;
                    let d = $(this).clone();
                    d.attr({'name': 'arquivo[]', 'id': '_f'+i});
                    d.appendTo( $( "#_input_files" ) );;
                }
                document.getElementById("_file_names").value += nome.replace(/<br>/g, "|") +'|';
            } else {
                if (document.getElementById("_file_names").value.length == 0) {
                    document.getElementById("up").value= 0;
                    nome = "Nenhum Arquivo selecionado.";
                    document.getElementById("_file_names").value = '';
                }
            }
            htm = '<div>'+document.getElementById("_file_names").value.replace(/\|/g, '<br>')+'</div>';
            htm += '<div><a style="color:red;" href="javascript:void(0)" class="removeUp" onClick="zremoveUp()">REMOVER</a></div>';
            $('#mostra-arquivo').html(htm);
        });

    });

    function zremoveUp(){
        document.getElementById("up").value= 0;
        document.getElementById("_file_names").value = '';
        document.getElementById("_input_files").innerHTML = '';
        $('#selecao-arquivo').val('');
        $('#mostra-arquivo').html('Nenhum arquivo selecionado');
    };
    function salvar(){
        let fk_id_ne = document.getElementById('fk_id_ne_');
        let up = document.getElementById("up");
        if (fk_id_ne.value == '') {
             alert('É preciso informar a Deficiência conforme consta no laudo do aluno.');
             return false;
        }
        document.getElementById('form').submit();
    }
    </script>
<?php  } ?>