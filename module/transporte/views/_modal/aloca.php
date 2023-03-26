<?php
if (!defined('ABSPATH'))
    exit;
    
$id_alu = filter_input(INPUT_POST, 'id_alu', FILTER_SANITIZE_NUMBER_INT);

if (!empty($_POST['cancelaLinha'])) {
    $aluno = transporteErp::aluAluno($id_alu);
    ?>
    <div style="text-align: center; font-weight: bold">
        Cancelar o Transporte d<?php echo toolErp::sexoArt($aluno['sexo']) ?> alun<?php echo toolErp::sexoArt($aluno['sexo']) ?> 
        <br />
        <span style="font-size: 18px; font-weight: bold">
            <?php echo $aluno['n_pessoa'] ?>
        </span>
    </div>
    <br /><br />
    <form method="POST">
        <div class="row">
            <div class="col-sm-6">
                <?php echo formErp::selectDB('transporte_motivo', '1[fk_id_mot]', 'Motivo', NULL, NULL, NULL, NULL, NULL, 'required') ?>
            </div>
            <div class="col-sm-6">
                <?php
                echo formErp::hidden([
                    '1[id_alu]' => $id_alu,
                    '1[dt_solicita_fim]' => date("Y-m-d"),
                    'id_inst' => $_POST['id_inst'],
                    'id_li' => $_POST['id_li'],
                    'id_turma' => $_POST['id_turma'],
                    'DBDegub' => 1
                ]);
                echo formErp::hiddenToken('transporte_aluno', 'ireplace');
                ?>
                <button class="btn btn-success">
                    Solicitar
                </button>
            </div>
        </div>
        <br /><br />
    </form>
    <?php
}

if (!empty($_POST['novoDeferimento'])) {
    $aluno = transporteErp::aluAluno($id_alu);
    ?>
    <div style="text-align: center; font-weight: bold">
        Solicitar Novo Deferimento d<?php echo toolErp::sexoArt($aluno['sexo']) ?> alun<?php echo toolErp::sexoArt($aluno['sexo']) ?> 
        <br />
        <span style="font-size: 18px; font-weight: bold">
            <?php echo $aluno['n_pessoa'] ?>
        </span>
    </div>
    <br /><br />
    <form method="POST" id="formEnvia" target="_parent" action="<?php echo HOME_URI ?>/transporte/aloca">
        <div class="row">
            <div class="col-sm-6">
                <?php formErp::textarea('1[justificativa]', null, 'Justificativa') ?>
            </div>
            <div class="col-sm-6">
                <?php
                echo formErp::hidden([
                    '1[id_alu]' => $id_alu,
                    '1[fk_id_sa]' => '0',
                    '1[dt_solicita]' => date('Y-m-d'),
                    'id_inst' => $_POST['id_inst'],
                    'id_li' => $_POST['id_li'],
                    'id_turma' => $_POST['id_turma'],
                    'DBDegub' => 1
                ]);
                echo formErp::hiddenToken('transporte_aluno', 'ireplace', null, null, 1);
                ?>
                <a class="btn btn-success" onclick="validate()" id="saveButton">
                    Solicitar
                </a>
            </div>
        </div>
        <br /><br />
    </form>
    <?php
}
?>
<script type="text/javascript">
function validate(){
	justificativa = document.getElementsByName('1[justificativa]')[0].value;

	if(justificativa == ""){
        alert("Descreva uma Justificativa.");
        document.getElementsByName('1[justificativa]')[0].focus();
        return false;
    }

	var el = document.getElementById('saveButton');
    _funcDisableButton(el);
    document.getElementById('formEnvia').submit();
}
</script>