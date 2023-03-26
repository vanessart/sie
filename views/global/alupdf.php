<style>
    td{
        padding: 4px;
    }
    th{
        padding: 4px;
        color: white;
        background-color: black;
    }
</style>
<?php
if (!empty($_POST['id_inst'])) {
    $id_inst = $_POST['id_inst'];
} else {
    $id_inst = tool::id_inst();
}
ob_start();
?>
<div style="text-align: center; font-size: 20px">
    Notas dos Alunos da <?php echo sql::get('instancia', 'n_inst', ['id_inst' => $id_inst], 'fetch')['n_inst'] ?>
</div>
<br /><br />
<?php
$sql = "SELECT "
        . " p.id_pessoa, p.n_pessoa, t.n_turma, otica, redacao, a.nota "
        . " FROM prod.aa_alunos a "
        . " JOIN prod.aa_turmas t on t.id_turma = a.idturma "
        . " JOIN ge.pessoa p on p.id_pessoa = a.fk_id_pessoa "
        . " WHERE  t.fk_id_inst = $id_inst "
        . " ORDER BY t.n_turma, `p`.`n_pessoa` ASC ";
$query = $model->db->query($sql);
$alu = $query->fetchAll();
?>
<table border=1 cellspacing=0 cellpadding=1 style="width: 100%">
    <thead>
        <tr>
            <th style="padding: 5px;">
                Turma
            </th>
            <th style="padding: 5px;">
                RSE
            </th>
            <th style="padding: 5px;">
                Nome
            </th>
            <th style="padding: 5px;">
                Redação
            </th>
            <th style="padding: 5px;">
                Ótica
            </th>
            <th style="padding: 5px;">
                Nota
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($alu as $v) {
            ?>
            <tr>
                <td style="padding: 5px;text-align: center">
                    <?php echo $v['n_turma'] ?>
                </td>
                <td style="padding: 5px;text-align: center">
                    <?php echo $v['id_pessoa'] ?>
                </td>
                <td style="padding: 5px;">
                    <?php echo $v['n_pessoa'] ?>
                </td>
                <td style="padding: 5px;text-align: center">
                    <?php echo $v['redacao'] ?>
                </td>
                <td style="padding: 5px;text-align: center">
                    <?php echo $v['otica'] ?>
                </td>
                <td style="padding: 5px;text-align: center">
                    <?php echo $v['nota'] ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
<?php
tool::pdfEscola('P', $id_inst);
