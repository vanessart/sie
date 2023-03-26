<?php
if (!defined('ABSPATH'))
    exit;
$sql = "SELECT p.n_pessoa, p.id_pessoa, p.cpf, f.rm FROM pessoa p "
        . " JOIN ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
        . " join sed_lista_branca_prof_esc ps on ps.id_pessoa = p.id_pessoa ";
$query = pdoSis::getInstance()->query($sql);
$funcs = $query->fetchAll(PDO::FETCH_ASSOC);
$token = formErp::token('sed_lista_branca_prof_esc', 'delete');
foreach ($funcs as $k => $v) {
    $funcs[$k]['ins'] = formErp::submit('excluir', $token, ['1[id_pessoa]' => $v['id_pessoa']]);
}
if ($funcs) {
    $form['array'] = $funcs;
    $form['fields'] = [
        'Matrícula' => 'rm',
        'Nome' => 'n_pessoa',
        'CPF' => 'cpf',
        '||1' => 'ins'
    ];
}
?>
<div class="body">
    <div class="fieldTop alert alert-danger">
        Esta página cadastra usuários que aperecerão em todos os cadastros de professores no perfil Escola do subsistema Gestão Educacional
    </div>
    <br /><br />
    <form action="<?= HOME_URI ?>/sed/def/liberado.php" target="frame" method="POST">
        <button class="btn btn-primary" onclick="$('#myModal').modal('show');$('.form-class').val('');">
            Novo Cadastro
        </button>
    </form>
    <br /><br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>

<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 50vh; border: none" name="frame"></iframe>
<?php
toolErp::modalFim();
?>