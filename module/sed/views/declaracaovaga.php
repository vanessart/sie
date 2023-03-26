<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="body">
    <div class="fieldTop">
        Cadastro - Declaração de Vaga/Comparecimento
    </div>
    <div class="row">
        <div class="col">
            <button onclick="edit()" class="btn btn-info">
                Nova Declaração
            </button>

        </div>
    </div>
    <br />
    <form target="frame" id="form" action="<?= HOME_URI ?>/sed/def/formDeclara.php" method="POST">
        <input type="hidden" name="id_vaga_c" id="id_vaga_c" />
    </form>

    <?php
    $dec = sql::get('ge_decl_vaga_comp', '*', ['fk_id_inst' => tool::id_inst(), '<' => 'id_vaga_c']);

    foreach ($dec as $key => $v) {
        if ($v['tipo'] == "Declaração de Vaga") {
            $caminho = '/sed/pdf/decl_vaga.php';
        } else {
            $caminho = '/gestao/decl_comp';
        }
        $dec[$key]['decl'] = ' <button onclick="edit(' . $v['id_vaga_c'] . ')" class="btn btn-primary">Editar</button>';
        $dec[$key]['acessar'] = formulario::submit('Imprimir', null, ['id_vaga_c' => $v['id_vaga_c']], HOME_URI . $caminho, 1);
    }
    $form['array'] = $dec;
    $form['fields'] = [
        'Nome Solicitante' => 'nome_solicitante',
        'R.G.' => 'rg',
        'Sexo' => 'sexo',
        'Tipo Declaração' => 'tipo',
        'Data Comp' => 'dt_comp',
        'Nome Aluno' => 'nome_aluno',
        '||1' => 'decl',
        '||2' => 'acessar'
    ];
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>

<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            id_vaga_c.value = id;
        } else {
            id_vaga_c.value = "";
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>