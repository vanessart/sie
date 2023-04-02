<?php
$nome = filter_input(INPUT_POST, 'nome', FILTER_UNSAFE_RAW);
?>
<div class="fieldBody">
    <div class="fieldTop">
        Lista Branca
    </div>
    <br /><br />
    <?php echo form::submit('Incluir Aluno', NULL, ['incluir' => 1]) ?>
    <br /><br />
    <?php
$model->listaBranca()
?>
    <?php
    if (!empty($_POST['incluir'])) {
        tool::modalInicio();
        ?>
        <form method="POST">
            <div class="row">
                <div class="col-sm-8">
                    <?php echo form::input('nome', 'Nome', $nome) ?>
                </div>
                <div class="col-sm-4">
                    <input type="hidden" name="incluir" value="1" />
                    <?php echo form::button('Buscar'); ?>
                </div>
            </div>
            <br /><br />
        </form>
        <?php
        if (!empty($nome)) {
            $result = alunos::busca($nome);
            $token = DB::sqlKey('transp_lita_branca', 'replace');
            foreach ($result as $k => $v) {
                if (!empty($v['cursoAtivo'])) {
                    $result[$k]['n_inst'] = @$v['cursoAtivo'][0]['n_inst'];
                    $result[$k]['codigo'] = @$v['cursoAtivo'][0]['codigo'];
                }
                $result[$k]['ac'] = form::submit('Incluir', $token, ['1[id_pessoa]' => $v['id_pessoa']]);
            }
            $form['array'] = $result;
            $form['fields'] = [
                'RA' => 'ra',
                'Nome' => 'n_pessoa',
                'Escola' => 'n_inst',
                'Turma' => 'codigo',
                '||'=>'ac'
            ];
            tool::relatSimples($form);
        }
        tool::modalFim();
    }
    ?>
</div>