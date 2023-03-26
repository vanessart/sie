<?php
if (!defined('ABSPATH'))
    exit;
$busca = filter_input(INPUT_POST, 'busca');
if ($busca) {
    $sql = "SELECT p.n_pessoa, p.id_pessoa, p.cpf, f.rm FROM pessoa p "
            . " JOIN ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
            . " WHERE p.n_pessoa LIKE '%$busca%' "
            . " OR p.cpf = '$busca' "
            . " OR f.rm = '$busca'  ";
    $query = pdoSis::getInstance()->query($sql);
    $funcs = $query->fetchAll(PDO::FETCH_ASSOC);
    $token = formErp::token('sed_lista_branca_prof_esc', 'ireplace');
    foreach ($funcs as $k => $v) {
        $funcs[$k]['ins'] = formErp::submit('Incluir', $token, ['1[id_pessoa]' => $v['id_pessoa']], HOME_URI . '/sed/listaLiberadosProfEsc', '_parent');
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
}
?>
<div class="body">
    <?php
    if (empty($funcs)) {
        ?>
        <form method="POST">
            <div class="row">
                <div class="col">
                    <?= formErp::input('busca', 'Nome, Matrícula ou CPF') ?>
                </div>
                <div class="col">
                    <?= formErp::button('Buscar') ?>
                </div>
            </div>
            <br />
        </form>
        <?php
    } else {
        report::simple($form);
    }
    ?>
</div>
