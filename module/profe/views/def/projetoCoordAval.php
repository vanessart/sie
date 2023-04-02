<?php
if (!defined('ABSPATH'))
    exit;

$id_projeto = filter_input(INPUT_POST, 'id_projeto', FILTER_SANITIZE_NUMBER_INT);
$n_projeto = filter_input(INPUT_POST, 'n_projeto', FILTER_UNSAFE_RAW);
$autores = filter_input(INPUT_POST, 'autores', FILTER_UNSAFE_RAW);
$data = filter_input(INPUT_POST, 'data', FILTER_UNSAFE_RAW);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_UNSAFE_RAW);
$id_pessoa = toolErp::id_pessoa();

$reg = $model->getProjetoReg($id_projeto);
$aval = $model->getProjetoAval($id_projeto);
$fotos = $model->getProjetoFotos($id_projeto);


echo "<pre>";

print_r($reg);

echo "</pre><br><br>";

echo "<pre>";

print_r($aval);

echo "</pre><br><br>";

echo "<pre>";

print_r($fotos);

echo "</pre><br><br>";



$reg = "";

if ($reg) {

    foreach ($projetos as $k => $v) {

        if ($v['fk_id_projeto_status'] == 1) {
            $btn_ver = "outline-secondary disabled";
            //$btn_ver = "info";
        }else{
            $btn_ver = ($v['fk_id_projeto_status'] <> 2) ? 'warning' : 'info';
        }
        $projetos[$k]['edit'] = '<button class="btn btn-'. $btn_ver .'" onclick="edit(' . $v['id_projeto'] . ')">Acessar</button>';
        $projetos[$k]['autores'] = $model->autores($v['autores']);
    }

    $form['array'] = $projetos;
    $form['fields'] = [
        'Título' => 'n_projeto',
        'Autores' => 'autores',
        'Início' => 'dt_inicio',
        'Término' => 'dt_fim',
        '||3' => 'edit',
    ];
}
?>

<div class="body">
    <div class="fieldTop">
        id: <?= $id_projeto ?><br>
        Projeto: <?= $n_projeto ?><br>
        autores: <?= $autores ?><br>
        data: <?= $data ?><br>
        n_turma: <?= $n_turma ?><br>
        <button class="btn btn-outline-info" onclick="proj(<?= $id_projeto ?>)">Projeto</button><br>
        <button class="btn btn-outline-info" onclick="aval(<?= $id_projeto ?>)">Avaliação</button><br>
        
    </div>
</div>

<form id="form" target="frame" method="POST">
    
    <input type="hidden" name="id_projeto" id="id_projeto" value="" />
</form>

<?php toolErp::modalInicio(); ?>
<iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
<?php toolErp::modalFim(); ?>

<script>
    function proj(id) {
        document.getElementById("id_projeto").value = id;
        document.getElementById("form").action = '<?= HOME_URI ?>/profe/def/projetoCad.php';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }

    function aval(id) {
        document.getElementById("id_projeto").value = id;
        document.getElementById("form").action = '<?= HOME_URI ?>/profe/def/projetoAval.php';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>

    
