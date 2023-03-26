<?php
if (!defined('ABSPATH'))
    exit;
if ($id_turma) {
    $projetos = sqlErp::get(['profe_projeto', 'profe_projeto_status'], 'coord_vizualizar, fk_id_projeto_status, n_projeto_status, id_projeto, n_projeto, dt_inicio, dt_fim, autores,msg_coord', 'WHERE fk_id_turma =' . $id_turma);
} else {
    ?>
    <script>
        window.location = '<?= HOME_URI ?>/profe/projetoProf'
    </script>
    <?php
}

if ($projetos) {
    //$alunosAEE = $model->ListAlunosAEE($id_turma);

    $token = formErp::token('profe_projeto', 'delete');
    foreach ($projetos as $k => $v) {
        $hidden['msg_coord'] = $v['msg_coord'];
        $btn_ver = ($v['coord_vizualizar'] == 1) ? 'warning' : 'info';

        $projetos[$k]['ava'] = formErp::submit('Avaliação Diária', null, $hidden + ['fk_id_projeto' => $v['id_projeto'], 'activeNav' => 4, 'n_projeto' => $projetos[$k]['n_projeto']], null, null, null, 'btn btn-outline-danger');
        $projetos[$k]['fotos'] = formErp::submit('Registrar Imagens', null, $hidden + ['fk_id_projeto' => $v['id_projeto'], 'activeNav' => 3, 'n_projeto' => $projetos[$k]['n_projeto']], null, null, null, 'btn btn-outline-primary');
        $projetos[$k]['fk_id_projeto'] = formErp::submit('Registro Quinzenal', null, $hidden + ['fk_id_projeto' => $v['id_projeto'], 'activeNav' => 2, 'n_projeto' => $projetos[$k]['n_projeto']], null, null, null, 'btn btn-outline-success');
        $projetos[$k]['edit'] = '<button class="btn btn-'. $btn_ver .'" onclick="edit(' . $v['id_projeto'] . ')">Editar</button>';
        $projetos[$k]['pdf'] = '<button class="btn btn-info" onclick="pdf(' . $v['id_projeto'] . ')">Tela de Impressão</button>';
        $projetos[$k]['avaliar'] = '<button class="btn btn-outline-warning" onclick="aval(' . $v['id_projeto'] . ')">Avaliação Final</button>';
        $projetos[$k]['del'] = formErp::submit('Apagar', $token, $hidden + ['1[id_projeto]' => $v['id_projeto']]);
        $projetos[$k]['autores'] = $model->autores($v['autores']);
        $projetos[$k]['flex'] = formErp::submit('Flexibilização Curricular', null, $hidden + ['fk_id_projeto' => $v['id_projeto'], 'activeNav' => 5, 'n_projeto' => $projetos[$k]['n_projeto']], null, null, null, 'btn btn-outline-secondary');
    }

    $form['array'] = $projetos;

    
    $form['fields'] = [
        'Título' => 'n_projeto',
        'Autores' => 'autores',
        'Início' => 'dt_inicio',
        'Término' => 'dt_fim',
        'Situação' => 'n_projeto_status',
        //'||2' => 'del',
        '||3' => 'edit',
        '||6' => 'pdf',
        '||1' => 'fk_id_projeto',
        '||5' => 'ava',
        '||0' => 'fotos',
        '||4' => 'avaliar',
    ];

    if (!empty($alunosAEE)) {
       $form['fields']['||7'] = 'flex';
    }
}
?>

<div class="body">

    <button class="btn btn-info" onclick="edit()">
        Novo Projeto
    </button>
    <br><br>

            <?php
    if (!empty($form)) {?>
        <div class="row">
            <div class="col-md-5" style="text-align:right; width: 100%; margin-bottom:5px;">
                <button  class="btn btn-info" style="width: 20px; height: 20px;"></button>Editar: Disponível para Alterações
                <button  class="btn btn-warning" style="width: 20px; height: 20px;"></button>Editar: Indisponível para Alterações
            </div>
        </div>
        <?php     
    }?>

    <div>
        <form id="form" target="frame" action="<?= HOME_URI ?>/profe/def/projetoCad.php" method="POST">
            <?= formErp::hidden($hidden) ?>
            <input type="hidden" name="id_projeto" id="id_projeto" value="" />
        </form>
        <form id="form2" target="frame" action="<?= HOME_URI ?>/profe/def/projetoAval.php" method="POST">
            <?= formErp::hidden($hidden) ?>
            <input type="hidden" name="id_projeto_aval" id="id_projeto_aval" value="" />
        </form>

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
    <form id="formPDF" method="POST" target="_blank" action="<?= HOME_URI ?>/profe/projetoPdf">
        <input type="hidden" name="id_projeto" id="id_projetoPDF" value="" />
        <input type="hidden" name="n_turma" id="n_turmaPDF" value="<?= $n_turma ?>" />
    </form>

    <script>
        $('#myModal').on('hidden.bs.modal', function () {
            document.getElementById("form").action = '<?= HOME_URI ?>/profe/def/projetoCad.php';
        });

        function edit(id) {
            if (id) {
                document.getElementById("id_projeto").value = id;
            } else {
                document.getElementById("id_projeto").value = "";
            }
            document.getElementById("form").submit();
            $('#myModal').modal('show');
            $('.form-class').val('');
        }

        $('#Modal2').on('hidden.bs.modal', function () {
            document.getElementById("form2").action = '<?= HOME_URI ?>/profe/def/projetoAval.php';
        });

        function aval(id) {

            document.getElementById("id_projeto_aval").value = id;
            document.getElementById("form2").submit();
            $('#myModal').modal('show');
            $('.form-class').val('');
        }
        function pdf(id_projeto){
            if (id_projeto){
                document.getElementById("id_projetoPDF").value = id_projeto;
            }
            document.getElementById("formPDF").submit();
        }

    </script>
