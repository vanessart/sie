<?php
$id_turma = @$_POST['id_turma'];
?>
<div class="fieldBody">
    <div class="fieldTop">
        Atualizações
        <br /><br />
    </div>
    <div class="row">
        <div class="col-md-4">
        </div>
        <div class="col-md-4">
        </div>
        <div class="col-md4">
            <?php
            if ($model->verificastatus() == 'Pendente') {
                $turmas = turma::option(tool::id_inst(), NULL, 'fk_id_inst', '9');
                formulario::select('id_turma', $turmas, 'Selecione a Classe', $id_turma, 1);
            }
            ?>
        </div>
    </div>

    <br /><br />
    <?php
    if (!empty($id_turma)) {
        //Somente deferida e indeferida na finalização
        $classe = sql::get('mrv_beneficiado', 'id_pessoa, n_pessoa, status_ben, turma_ben, num_chamada_ben, ra_ben, dt_nasc, rg_aluno_ben, fk_id_turma, categoria', ['fk_id_turma' => $id_turma, '>' => 'num_chamada_ben']);
        // $classe = $model->alunoinscricao($id_turma);

        foreach ($classe as $k => $v) {
            $classe[$k]['atzcad'] = formulario::submit('Dados Pessoais', null, $v, HOME_URI . '/mrv/atzcadastro');
            if (($v['categoria'] == 99) || ($v['categoria'] == 98)) {
                $classe[$k]['atznotas'] = '<button type="button">Notas</button>';
            } else {
                $classe[$k]['atznotas'] = formulario::submit('Notas', null, $v, HOME_URI . '/mrv/atznotas');
            }
        }

        $form['array'] = $classe;
        $form['fields'] = [
            'Chamada' => 'num_chamada_ben',
            'Cód. Classe' => 'turma_ben',
            'RSE' => 'id_pessoa',
            'Nome Aluno' => 'n_pessoa',
            'Status' => 'status_ben',
            '||1' => 'atzcad',
            '||2' => 'atznotas'
        ];
        tool::relatSimples($form);
    }
    ?>
</div>