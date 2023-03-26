<?php
if (!defined('ABSPATH'))
    exit;
$sistema = $model->getSistema('22','48,2,18,53,54,55,24,56');
$id_ciclo = filter_input(INPUT_POST, 'fk_id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'fk_id_disc', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_projeto = filter_input(INPUT_POST, 'fk_id_projeto', FILTER_SANITIZE_NUMBER_INT);
$n_projeto = filter_input(INPUT_POST, 'n_projeto', FILTER_SANITIZE_STRING);
$activeNav = filter_input(INPUT_POST, 'activeNav', FILTER_SANITIZE_NUMBER_INT);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = toolErp::id_pessoa();

if ($id_turma) {
    $projetos = sqlErp::get(['profe_projeto', 'profe_projeto_status'], 'coord_vizualizar, fk_id_projeto_status, n_projeto_status, id_projeto, n_projeto, dt_inicio, dt_fim, autores, msg_coord', 'WHERE fk_id_turma =' . $id_turma, NULL, "left");
} else {
    ?>
    <script>
        window.location = '<?= HOME_URI ?>/<?= $sistema ?>/projetoCoord'
    </script>
    <?php
}

if ($projetos) {

    foreach ($projetos as $k => $v) {
        if ($v['fk_id_projeto_status'] == 1) {
            $btn_ver = "outline-secondary";
            //$btn_ver = "info";
        }else{
            $btn_ver = ($v['fk_id_projeto_status'] <> 2) ? 'warning' : 'info';
        }
        $hidden['id_projeto'] = $v['id_projeto'];
        $hidden['n_projeto'] = $v['n_projeto'];
        $hidden['msg_coord'] = $v['msg_coord'];
        $hidden['data'] = "De ".data::converteBr($v['dt_inicio'])." a ".data::converteBr($v['dt_fim']);
        $projetos[$k]['autores'] = $model->autores($v['autores']);
        $hidden['autores'] = $projetos[$k]['autores'];
        $projetos[$k]['edit'] = formErp::submit('Acessar', null, $hidden+['activeNav' => 2], null, null, null, 'btn btn-'. $btn_ver);
        $projetos[$k]['pdf'] = '<button class="btn btn-outline-info" onclick="pdf(' . $v['id_projeto'] . ')">Impressão</button>';
        $projetos[$k]['registro'] = formErp::submit('Registro Quinzenal', null, $hidden + ['fk_id_projeto' => $v['id_projeto'], 'activeNav' => 3, 'n_projeto' => $v['n_projeto']], null, null, null, 'btn btn-outline-success');
    }

    $form['array'] = $projetos;
    $form['fields'] = [
        'Título' => 'n_projeto',
        'Autores' => 'autores',
        'Início' => 'dt_inicio',
        'Término' => 'dt_fim',
        'Situação' => 'n_projeto_status',
        '||3' => 'registro',
        '||2' => 'pdf',
        '||1' => 'edit',
    ];
}
?>

<div class="body">
    <div>
         <form id="form" target="frame" action="<?= HOME_URI ?>/profe/def/projetoCoordAval.php" method="POST">
            <?= formErp::hidden($hidden) ?>
            <input type="hidden" name="id_projeto" id="id_projeto" value="" />
            <input type="hidden" name="id_turma" id="id_projeto" value="<?= $id_turma ?>" />
            <input type="hidden" name="autores" id="autores" value="" />
            <input type="hidden" name="n_projeto" id="n_projeto" value="" />
            <input type="hidden" name="data" id="data" value="" />
        </form>

        <?php  
        $coord = '';
        if (toolErp::id_nilvel()==22) {
            $coord = '(Coordenador)';
        }?>
        <div class="row">
            <div class="col-md-5" style="text-align:right; width: 100%; margin-bottom:5px;">
                <button  class="btn btn-info" style="width: 20px; height: 20px;"></button> Disponível para Alterações <?= $coord ?>
                <button  class="btn btn-warning" style="width: 20px; height: 20px;"></button> Indisponível para Alterações <?= $coord ?>
                <button  class="btn btn-outline-secondary" style="width: 20px; height: 20px;"></button> Não Enviado
            </div>
        </div>
        <?php
        if (!empty($form)) {
            report::simple($form);
        }

        toolErp::modalInicio();
        ?>
        <iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
            <?php
            toolErp::modalFim();
            ?>
    </div>
</div>
<form id="formPDF" method="POST" target="_blank" action="<?= HOME_URI ?>/profe/projetoPdf">
    <input type="hidden" name="id_projeto" id="id_projetoPDF" value="" />
    <input type="hidden" name="n_turma" id="n_turmaPDF" value="<?= $n_turma ?>" />
</form>
<script>
    function pdf(id_projeto){
        if (id_projeto){
            document.getElementById("id_projetoPDF").value = id_projeto;
        }
        document.getElementById("formPDF").submit();
    }
    function edit(id, autores, n_projeto, data) {
        if (id) {
            document.getElementById("id_projeto").value = id;
            document.getElementById("autores").value = autores;
            document.getElementById("n_projeto").value = n_projeto;
            document.getElementById("data").value = data;
        } else {
            document.getElementById("id_projeto").value = "";
        }
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>
