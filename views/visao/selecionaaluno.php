<?php
$id_turma = @$_POST['id_turma'];
?>
<div class="fieldBody">
    <div class="fieldTop">
        Registro dos Exames
        <br /><br />
    </div>
    <div class="row">
        <div class="col-md-4">
        </div>
        <div class="col-md-4">
            <form method="POST">
                <input type="submit" style="width: 45%" class ="art-button" value="Atualizar Tabela" name="Atualizar" />
            </form>
        </div>
        <div class="col-md4">
            <?php
            $turmas = $model->pegaclassevisao();
            if (!empty($turmas)) {
                formulario::select('id_turma', $turmas, 'Selecione a Classe', $id_turma, 1);
            }
            ?>
        </div>
    </div>

    <br /><br />
    <?php
    if (!empty($id_turma)) {

        $classe = $model->pegaalunovisao($id_turma);

        foreach ($classe as $k => $v) {
            $v['activeNav'] = 1;
            $v['titulo'] = 'Registro dos Exames - Teste';
            $classe[$k]['regteste'] = formulario::submit('Registrar Teste', null, $v, HOME_URI . '/visao/digitacao');
              $v['activeNav'] = 2;
              $v['titulo'] = 'Registro dos Exames - Reteste';
            if ($v['situacao_teste'] == 'FALHA') {
                $classe[$k]['regreteste'] = formulario::submit('Registrar Reteste', null, $v, HOME_URI . '/visao/digitacao');
            } else {
                $classe[$k]['regreteste'] = '<button type="button">Reteste</button>';
            }
            if ($v['reteste_sit'] == 'FALHA'){
                $v['activeNav'] = 3;
                $v['titulo'] = 'Acompanhamento do Aluno';
                $classe[$k]['prontuario'] = formulario::submit('Acompanhamento', null, $v, HOME_URI . '/visao/digitacao');            
            }else{
                $classe[$k]['prontuario'] = '<button type="button">Acompanhamento</button>';
            }
            
        }

        $form['array'] = $classe;
        $form['fields'] = [
            'Chamada' => 'chamada',
            'RSE' => 'id_pessoa',
            'Nome Aluno' => 'n_pessoa',
            'Situação Teste' => 'situacao_teste',
            'Situação Reteste' => 'reteste_sit',
            '||1' => 'regteste',
            '||2' => 'regreteste',
            '||3' => 'prontuario'
        ];
        tool::relatSimples($form);
    }
    ?>
</div>
