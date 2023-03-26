<?php
if (!defined('ABSPATH'))
    exit;
if (toolErp::id_nilvel() == 24) {
    $turmas = $model->getTurmasProf();
    $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
    $id_inst = toolErp::id_inst();
    $ap_aluno = 0;
    $form=[];

    if (!empty($id_turma)) {
        $ap_aluno = $model->getAlunosProf($id_turma);
    }else{
        foreach ($turmas as $v) {
            $id_turma = $v["id_turma"];
            if ($id_turma) {
                break;
            }
        }
       if (!empty($id_turma)) {
            $ap_aluno = $model->getAlunosProf($id_turma);
        } 
    }

    if (empty($ap_aluno)) {?>
        <div class="alert alert-info" style="padding-top:  10px; padding-bottom: 0">
            <div class="row">
                <div class="col" style="font-weight: bold; text-align: center;">
                    Verifique com a secretaria Escolar se há alunos cadastrados nesta turma
                </div>
            </div>
        </div>
    <?php
        exit();
    }

    $bimestres = sql::get('ge_cursos', 'qt_letiva, un_letiva, atual_letiva', ['id_curso' => 1], 'fetch');
    if (!empty($bimestres)) {
        $bimestre = $bimestres['atual_letiva'];
        $n_bimestre =  $bimestre."º ".$bimestres["un_letiva"];
    }
    //$numAtend = $model->getNumAtend($bimestre,$id_turma);
    $totais = $model->dashGet($id_turma,$bimestre);
    ?>

    <style type="text/css">
        .titulo_anexo{
            color: #888;
            font-weight: bold;
            text-align: center;
            font-size: 30px;
        }
        .sub_anexo{
            font-weight: bold;
            text-align: center;
        }
        .sub2_anexo{
            font-weight: bold;
            text-align: center;
            FONT-SIZE: 14px;

        }s
        .mensagens {}
        .mensagens .mensagem {
            border: 1px solid #e1e1d1;
            box-shadow: #e1e1d1 5px 5px 14px;
           /* margin: 10px auto;*/
            padding: 4px;
            /*min-height: 80px;*/
        }
        .mensagens .nomePessoa {text-transform: lowercase;}
        .mensagens .nomePessoa:first-letter { text-transform: uppercase; }
        .mensagens .nomePessoa { 
            color: #888;
            font-weight: normal;
            font-size: 100%;
        }

        .mensagens .dataMensagem { 
            font-weight: bold;
            color: #888;
            font-size: 18px;
        }

        .tit_table{ 
            font-weight: bold;
            color: #888;
            font-size: 18px;
        }

        .mensagens .tituloHab{
            font-weight: bold;
            color: #7ed8f5;
            font-size: 100%; 
        }
        .mensagens .corpoMensagem {
            display: block;
            margin-bottom: 10px;
            font-weight: normal;        
            white-space: pre-wrap;
            padding: 5px;
            color: #888;
        }
        .mensagens .mensagemLinha-0{border-left: 5px solid #7ed8f5;}
        .mensagens .mensagemLinha-1{border-left: 5px solid #f3b4ef;}
        .mensagens .mensagemLinha-2{border-left: 5px solid #f9ca6e;}
        .esconde .input-group-text{ display: none; }
        .tituloBox{
            font-size: 17px;
            font-weight: bold;
            text-align: center;
        }
        .tituloBox.box-0{ color: #7ed8f5;}
        .tituloBox.box-1{ color: #f3b4ef;}
        .tituloBox.box-2{ color: #f9ca6e;}
    </style>
    <div class="body">
        <?php
        if (!empty($turmas)) {
            foreach ($turmas as $v){
                $n_turmas[$v['id_turma']]= $v['n_turma'];
            }?>
            <div class="row">
                <?php
                foreach ($turmas as $v){
                        $n_turmas[$v['id_turma']]= $v['n_turma'];
                        $outline = "-outline";
                        if ($id_turma == $v['id_turma']) {
                            $outline = "";
                        }
                        ?>
                        <div class="col">
                            <?= formErp::submit($v['n_turma'].' - '.$v['n_inst'], null, ['id_turma' => $v['id_turma']],null,null,null, 'btn btn'.$outline.'-info');?>
                            <?=  toolErp::tooltip('Caso o botão de atendimentos nao esteja disponível para algum aluno, vá no menu "Preencher Documentação", clique no botão PDI do aluno, clique em "Habilidades Iniciais - PDI" e preencha o nome do Coordenador. <br> Após salvar estará disponivel para esse aluno o botão de Atendimentos.', '160vh'); ?>
                        </div>
                        <?php
                }?>
            <div class="col">
                <p class="titulo_anexo"><?= @$n_bimestre ?></p>
            </div>
            </div>
            <br><br>
                <?php
        }else{?>
            <div class="alert alert-info" style="padding-top:  10px; padding-bottom: 0">
                <div class="row">
                    <div class="col" style="font-weight: bold; text-align: center;">
                        Verifique com a secretaria Escolar se há alunos cadastrados nesta turma
                    </div>
                </div>
            </div>
        <?php
        }
            
        if ($ap_aluno) {?>
           
            <!-- <?php //dashBoard::divInicio() ?>
                <div class="row">
                    <div class="col">
                       <?php //dashBoard::divDash_totais('Total de Atendimentos',$totais['1'], 'success') ?>
                    </div>
                    <div class="col">
                       <?php //dashBoard::divDash_totais('Total de Faltas',$totais['0'], 'danger') ?>
                    </div>
                    <div class="col">
                       <?php //dashBoard::divDash_percent('Porcentagem de Faltas',$totais['faltas'], 'warning') ?>
                    </div>
                </div>
                <br>
                <?php //dashBoard::divFim() ?>
             -->

            <?php
            if ($ap_aluno) {
                
                foreach ($ap_aluno as $k => $v) {
                    $hidden = [
                        'id_pessoa' => $v['id_pessoa'], 
                        'id_pessoa_apd' => $v['id_pessoa'], 
                        'n_pessoa' => $v['n_pessoa'],
                        'id_turma' => $v['id_turma'],
                        'n_turma' => $v['n_turma'],
                    ];
                    //$pdi = sql::get('apd_pdi', 'id_pdi', ['fk_id_pessoa' =>  $v['id_pessoa'], 'fk_id_turma' => $v['id_turma']], 'fetch');
                    $pdi = $model->getPDI($v['id_pessoa']);
                    if ($pdi) {
                        $id_pdi = $pdi['id_pdi'];
                        $atend = $model->getAtend($id_pdi,$bimestre,null);
                        $qtd_atend = 0;
                        if (!empty($atend)) {
                            $qtd_atend = count($atend );
                        }
                        $atend = sql::get('apd_pdi_atend', 'id_atend', " WHERE fk_id_pdi = $id_pdi AND atualLetiva = $bimestre AND dt_atend like '".date("Y-m-d")."'", 'fetch');
                        if (!empty($atend)) {
                            $id_atend = $atend['id_atend'];
                        }else{
                            $id_atend = 0;
                        }
                        $ap_aluno[$k]['atend'] =  $qtd_atend;
                        $ap_aluno[$k]['form'] ='<button class="btn btn-outline-info" onclick="edit(' . $id_atend . ',' . $v['id_pessoa'] . ',' . $id_pdi . ',\'' . $v['n_pessoa'] . '\' )">Atendimento</button>';
                        $ap_aluno[$k]['foto'] ='<button class="btn btn-outline-info" onclick="foto(' . $v['id_pessoa'] . ',\'' . $v['n_pessoa'] . '\' )">Fotos</button>';
                    }
                }
                
                $form['array'] = $ap_aluno;
                $form['fields'] = [
                    'RSE' => 'id_pessoa',
                    'Aluno' => 'n_pessoa',
                    'Atendimentos' => 'atend',
                    '||1' => 'form',
                    '||2' => 'foto',
                ];
            }
        }
        
        if (!empty($form)) {
            report::simple($form);
        }?>
        <form id="form" target="frame" action="" method="POST">
            <input type="hidden" name="id_pdi" id="id_pdi" value="" />
            <input type="hidden" name="id_atend" id="id_atend" value="" />
            <input type="hidden" name="id_pessoa" id="id_pessoa" value="" />
            <?=
            formErp::hidden([
                'bimestre' => $bimestre,
                'id_turma' => $id_turma,
                'atalho' => 1,
            ]);
            ?>  
        </form>
        <?php
        toolErp::modalInicio();
        ?>
        <iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
        <?php
        toolErp::modalFim();
        ?>
    </div>
    <script>
        function edit(id_atend,id_pessoa,id_pdi,n_pessoa){
            
            document.getElementById("id_pessoa").value = id_pessoa;
            document.getElementById("id_pdi").value = id_pdi;
            document.getElementById("id_atend").value = id_atend;

            var titulo= document.getElementById('myModalLabel');
            titulo.innerHTML  = n_pessoa;
            document.getElementById("form").action = '<?= HOME_URI ?>/apd/atendimentos';
            document.getElementById("form").submit();
            $('#myModal').modal('show');
            $('.form-class').val('');
        }

        function foto(id_pessoa,n_pessoa){

            document.getElementById("id_pessoa").value = id_pessoa;
            var titulo= document.getElementById('myModalLabel');
            titulo.innerHTML  = n_pessoa;
            document.getElementById("form").action = '<?= HOME_URI ?>/apd/apdFoto';
            document.getElementById("form").submit();
            $('#myModal').modal('show');
            $('.form-class').val('');
        }
    </script>
<?php  } ?>


