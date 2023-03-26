<?php
if (!defined('ABSPATH'))
    exit;

$registro = sql::get(['profe_projeto_reg', 'coord_hab'], 'id_reg, situacao, fk_id_hab, descricao, fk_id_pessoa, dt_inicio, dt_fim ', 'WHERE fk_id_projeto =' . $id_projeto." ORDER BY dt_inicio", NULL, 'left');
$hab = $model->getHab(-1,$id_ciclo,$id_disc,3);

if ($registro) {

    $token = formErp::token('profe_projeto_reg', 'delete');
    foreach ($registro as $k => $v) {
         $habil = $model->getHabReg(-1,$v['id_reg']);
         $habilidades = '';
        if (!empty($habil)) {
            foreach ($habil as $i) {
                if (!empty($hab[$i])) {
                   $habilidades  = $habilidades.'<br><br>'.$hab[$i]; 
                }
            }
        }
        $registro[$k]['habilidades'] = $habilidades;
        $registro[$k]['edit'] = '<button class="btn btn-info" onclick="edit(' . $v['id_reg'] . ')">Editar</button>';
        $registro[$k]['fk_id_hab'] = $registro[$k]['fk_id_hab'];
        $registro[$k]['n_pessoa'] = toolErp::n_pessoa($registro[$k]['fk_id_pessoa']);
        $registro[$k]['descricao'] = nl2br($v['situacao']);
        $registro[$k]['del'] = formErp::submit('Apagar', $token, ['1[id_reg]' => $v['id_reg'], 'activeNav' => 2, 'fk_id_projeto' => $id_projeto, 'fk_id_ciclo' => $id_ciclo, 'fk_id_disc' => $id_disc, 'fk_id_turma' => $id_turma, 'n_projeto' => $n_projeto]);
        $registro[$k]['pdf'] = '<button class="btn btn-outline-info" onclick="pdf(' . $v['id_reg'] . ')">Impressão</button>';
    }

    $form['array'] = $registro;
    $form['fields'] = [
        'Início' => 'dt_inicio',
        'Fim' => 'dt_fim',
        'Habilidades' => 'habilidades',
        'Professor' => 'n_pessoa',
        'Situação de Aprendizagem' => 'descricao',
        //'||2' => 'del',
        '||3' => 'pdf',
        '||1' => 'edit',
    ];
}
?>

<div class="body">
    <div class="alert alert-warning" style="padding-top:  10px; padding-bottom: 0">
        <div class="row">
            <div class="col">
             <p style="font-weight: bold; font-size: 16px">Mensagem do Coordenador:</p>
            <p style=" font-size: 14px"><?= $msg_coord ?></p>
        </div>
        </div>
    </div>
    <button class="btn btn-info" onclick="edit()">
        Novo Registro
    </button>
    <br><br>
    <div>
        <form id="form" target="frame" action="<?= HOME_URI ?>/profe/def/projetoReg.php" method="POST">
            <?= formErp::hidden($hidden) ?>
            <input type="hidden" name="id_reg" id="id_reg" value="" />
            <input type="hidden" name="msg_coord" value="<?= $msg_coord ?>" />
            <input type="hidden" name="fk_id_projeto" id="fk_id_projeto" value="<?= $id_projeto ?>" />
            <input type="hidden" name="n_projeto" id="n_projeto" value="<?= $n_projeto ?>" />
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
    <form id="formPDF" method="POST" target="_blank" action="<?= HOME_URI ?>/profe/pdf/regQuinzPDF.php">
        <input type="hidden" name="id_projeto" id="id_projetoPDF" value="<?= $id_projeto ?>" />
        <input type="hidden" name="n_turma" id="n_turmaPDF" value="<?= $n_turma ?>" />
        <input type="hidden" name="id_reg" id="id_regPDF" value="" />
    </form>
    <script>
        $('#myModal').on('hidden.bs.modal', function () {
            document.getElementById("form").action = '<?= HOME_URI ?>/profe/def/projetoReg.php';
        });

        function edit(id) {
            if (id) {
                document.getElementById("id_reg").value = id;
            } else {
                document.getElementById("id_reg").value = "";
            }
            document.getElementById("form").submit();
            $('#myModal').modal('show');
            $('.form-class').val('');
        }
        function pdf(id_reg){
            if (id_reg){
                document.getElementById("id_regPDF").value = id_reg;
            }
            document.getElementById("formPDF").submit();
        }

    </script>
