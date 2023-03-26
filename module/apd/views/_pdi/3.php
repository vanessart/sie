<?php
if (!defined('ABSPATH'))
    exit;
$bimestre = filter_input(INPUT_POST, 'bimestre', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$id_pdi = filter_input(INPUT_POST, 'id_pdi', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa_aluno = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$atend = $model->getAtend($id_pdi,$bimestre,null);

if ($atend) {
    $sqlkey = formErp::token('apd_pdi_atend', 'delete');
    foreach ($atend as $k => $v) {
        if ($v['presenca'] == 1) {
            $atend[$k]['descri'] = $v['acao'];
        }else{
            $atend[$k]['descri'] = $v['justifica'];
        }
        $del['id_atend'] = $v['id_atend'];
        $atend[$k]['del'] = formulario::submit('Excluir', $sqlkey, ['1[id_atend]' => $v['id_atend'],'id_pdi' => $id_pdi, 'activeNav' => 3, 'bimestre' => $bimestre,'id_turma' => $id_turma,'id_pessoa' => $id_pessoa_aluno,'n_pessoa' => $n_pessoa,'id_turma_AEE' => $id_turma_AEE],null,null,null, 'btn btn-outline-danger');
        $atend[$k]['edit'] = '<button class="btn btn-outline-primary" onclick="edit(' . $v['id_atend'] . ' )">Editar</button>';
    }
    $form['array'] = $atend;
    $form['fields'] = [
        'Data' => 'dt_atend',
        'Ações' => 'descri',
        '||2' => 'del',
        '||1' => 'edit',
    ];
}
?>
<div class="body">
   

    <div class="row">
        <div class="col-md-2">
            <button class="btn btn-outline-info" onclick="edit()">
               Novo Atendimento
            </button>
        </div>
    </div>
    <br>
    <?php  
    if (!empty($form)) {
        report::simple($form);
    }
     ?>
    <form id="form" target="frame" action="" method="POST">
        <input type="hidden" name="id_atend" id="id_atend" value="" />
        <?=
        formErp::hidden([
            'id_pessoa' => $id_pessoa_aluno,
            'n_pessoa' => $n_pessoa,
            'activeNav' => 3,
            'bimestre' => $bimestre,
            'id_turma' => $id_turma,
            'id_turma_AEE' => $id_turma_AEE,
            'id_pdi' => $id_pdi ,
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
    function edit(id_atend){

        if (id_atend){
            document.getElementById("id_atend").value = id_atend;
        }else{
            document.getElementById("id_atend").value = "";
        }
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "<?= $n_pessoa ?>";
        document.getElementById("form").action = '<?= HOME_URI ?>/apd/atendimentos';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }

    
</script>