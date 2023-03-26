<?php
if (!defined('ABSPATH'))
    exit;
$bimestre = filter_input(INPUT_POST, 'bimestre', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$id_pdi = filter_input(INPUT_POST, 'id_pdi', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa_aluno = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);

$hab = sql::get('apd_pdi_hab', 'id_pdi_hab, fk_id_hab, recursos, didatica, obs,habilidade', ['fk_id_pdi' => $id_pdi, 'atualLetiva' => $bimestre ]);

if ($hab) {
    $sqlkey = formErp::token('apd_pdi_hab', 'delete');
    foreach ($hab as $k => $v) {
        $hab[$k]['del'] = formulario::submit('Excluir', $sqlkey, ['1[id_pdi_hab]' => $v['id_pdi_hab'],'id_pdi' => $id_pdi, 'activeNav' => 2, 'bimestre' => $bimestre,'id_turma' => $id_turma,'id_pessoa' => $id_pessoa_aluno,'n_pessoa' => $n_pessoa,'id_turma_AEE' => $id_turma_AEE],null,null,null, 'btn btn-outline-danger');
        $fk_id_hab = $v['fk_id_hab']?:'null';
        $hab[$k]['edit'] = '<button class="btn btn-outline-primary" onclick="edit(' . $v['id_pdi_hab'] . ',' . $fk_id_hab . ' )">Editar</button>';
    }
    $form['array'] = $hab;
    $form['fields'] = [
        'Habilidade' => 'habilidade',
        'Recursos' => 'recursos',
        'Situação / Sequência Ditática' => 'didatica',
        'Observações' => 'obs',
        '||2' => 'del',
        '||1' => 'edit',
    ];
}?>

<div class="body">
    <div class="row">
        <div class="col-md-2">
            <button class="btn btn-outline-info" onclick="novoHab()">
               Nova Habilidade
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
        <input type="hidden" name="id_pdi_hab" id="id_pdi_hab" value="" />
        <input type="hidden" name="id_hab" id="id_hab" value="" />
        <input type="hidden" name="edit" id="edit" value="" />
        <?=
        formErp::hidden([
            'id_pessoa' => $id_pessoa_aluno,
            'n_pessoa' => $n_pessoa,
            'activeNav' => 2,
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
    function edit(id, id_hab){

        if (id){
            document.getElementById("id_pdi_hab").value = id;
            document.getElementById("id_hab").value = id_hab;
            document.getElementById("edit").value = 1;
        }
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "<?= $n_pessoa ?>";
        document.getElementById("form").action = '<?= HOME_URI ?>/apd/hab';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    } 

    function novoHab(){
        document.getElementById("id_pdi_hab").value = "";
        document.getElementById("id_hab").value = "";
        document.getElementById("edit").value = '';
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "<?= $n_pessoa ?>";
        document.getElementById("form").action = '<?= HOME_URI ?>/apd/hab';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    } 
</script>
