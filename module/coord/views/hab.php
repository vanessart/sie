<?php
$id_inst = @$_POST[1]['id_inst'];
$id_turma = @$_POST[1]['id_turma'];
$id_pessoa = @$_POST[1]['id_pessoa'];
if (!empty($id_inst)) {
    $turma = turma::option($id_inst);
} else {
    $turma = ['' => 'carregando'];
}
if (!empty($id_turma)) {
    $aluno = $model->aluno1($id_turma);
} else {
    $aluno = ['' => 'carregando'];
}
?>
<br /><br /><br /><br />

<form method="POST">
    <?php
    echo form::select('1[id_inst]', escolas::idInst(), 'escola', @$_POST['id_inst'], NULL, NULL, NULL, NULL, ['turma', 'id_turma']);
    echo '<br /><br />';
    echo form::select('1[id_turma]', $turma, 'Turma', @$_POST['id_turma'], NULL, NULL, NULL, NULL, ['aluno', 'id_pessoa']);
    echo '<br /><br />';
    echo form::select('1[id_pessoa]', $aluno, 'Aluno', @$_POST['id_pessoa']);
    ?>

    <br /><br />
    <input type="submit" value="Salvar" />
</form>



