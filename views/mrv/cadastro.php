<?php
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_UNSAFE_RAW);
$model->geratabelaalunoclasse(tool::id_inst());
?>
<div class="fieldBody">
    <div class="fieldTop">
        Inscrição para Reserva de Vagas ITB 2022/2023
        <br /><br />
    </div>
    <div class="row">
        <div class="col-md-4">

        </div>
        <div class="col-md-4">
            <form method="POST">
                
            </form>
        </div>
        <div class="col-md4">
            <?php
            if ($model->verificastatus() == 'Pendente') {
                //$turmas = turma::option(tool::id_inst(), NULL, 'fk_id_inst', '9');
                $turmas = $model->pegaturmanono(tool::id_inst());
                formulario::select('id_turma', $turmas, 'Selecione a Classe', $id_turma, 1);
            }
            ?>
        </div>
    </div>

    <br /><br />
    <?php
    if (!empty($id_turma)) {
        $esc = sql::get('ge_escolas', '*', ['fk_id_inst' => tool::id_inst()], 'fetch');
        $classe = $model->pegaalunocidade($id_turma);
        $sqlkeyNao = DB::sqlKey('naoInteresse');
        
        $i = sql::get('mrv_beneficiado', 'id_pessoa', ['fk_id_turma' => $id_turma]);
        $id_inscritos = [];
        foreach ($i as $v) {
            $id_inscritos[] = $v['id_pessoa'];
        }

        foreach ($classe as $k => $v) {

            $v['cie'] = $esc['cie_escola'];
            $v['id_turma'] = $v['id_turma'];
            $v['escola'] = user::session('n_inst');

            if ($v['cidade'] == CLI_CIDADE or $v['cidade'] == ucfirst(CLI_CIDADE) or $v['cidade'] == strtolower(CLI_CIDADE)) {
                if ($v['situacao'] == 'Frequente' && !in_array($v['id_pessoa'], $id_inscritos)) {
                    $classe[$k]['interesse'] = formulario::submit('Não Há Interesse', $sqlkeyNao, $v);
                    $classe[$k]['inscricao'] = formulario::submit('Inscrição', null, $v, HOME_URI . '/mrv/inscricao');
                } elseif (in_array($v['id_pessoa'], $id_inscritos)) {
                    $classe[$k]['inscricao'] = '<button type="button">Finalizado</button>';
                }
            } else {
                $classe[$k]['interesse'] = '<button type="button">Não Munícipe</button>';
            }
        }
        $form['array'] = $classe;
        $form['fields'] = [
            'CH' => 'chamada',
            'RSE' => 'id_pessoa',
            'Nome Aluno' => 'n_pessoa',
            'Cidade' => 'cidade',
            'Situação' => 'situacao',
            '||1' => 'interesse',
            '||2' => 'inscricao'
        ];
        tool::relatSimples($form);
    }
    ?>
</div>