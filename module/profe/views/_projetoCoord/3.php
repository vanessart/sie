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
        '||1' => 'pdf',
    ];
}
?>

<div class="body">
    <div>
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

        function pdf(id_reg){
            if (id_reg){
                document.getElementById("id_regPDF").value = id_reg;
            }
            document.getElementById("formPDF").submit();
        }

    </script>
