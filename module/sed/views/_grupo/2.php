<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = tool::id_inst();
$periodos = array_keys(ng_main::periodosPorSituacao()['Ativo']);
$turmas = gtTurmas::idNome($id_inst, implode(',', $periodos));
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
?>
<br />
<div>
    
    <form id="id_turmaForm" method="POST" action="<?= HOME_URI ?>/sed/def/alunoGrupoSel.php" target="ags">
        <div class="row">
            <div class="col-4">
                <select name="id_turma" class="form-select" onchange="setTurma(this)">
                    <option value="" selected>Turmas</option>
                    <?php
                    foreach ($turmas as $k => $v) {
                        ?>
                        <option value="<?= $k ?> <?= $k == $id_turma ? 'selected' : '' ?>"><?= $v ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <?=
        formErp::hidden([
            'id_gr' => $id_gr,
            'activeNav' => 2,
            'id_gr' => $id_gr
        ])
        ?>
    </form>
</div>
<br />
<div class="row">
    <div class="col" style="display: none" id="agsCol">
        <iframe id="ags" name="ags" src="<?= HOME_URI ?>/sed/def/alunoGrupoSel.php?id_gr=<?= $id_gr ?>" style="height: 50vh; width: 90%; overflow: auto; display: none" class="border"></iframe>
    </div>
    <div class="col">
        <iframe name="ag" src="<?= HOME_URI ?>/sed/def/alunoGrupo.php?id_gr=<?= $id_gr ?>" style="height: 50vh;  width: 90%; overflow: auto;" class="border"></iframe>
    </div>
</div>
<script>
    function setTurma(e) {
        if (e.value) {
            agsCol.style.display = 'block';
            document.getElementById('ags').style.display = 'block';
            document.getElementById('id_turmaForm').submit();
        } else {
            document.getElementById('ags').style.display = 'none';
            agsCol.style.display = 'none';
        }
    }
</script>
