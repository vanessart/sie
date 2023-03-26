<?php
$rm = filter_input(INPUT_POST, 'rm', FILTER_SANITIZE_NUMBER_INT);
?>
<br />
<form method="POST">
    <div class="row">
        <div class="col-9">
            <?= formErp::input('rm', 'Nº de Matrícula', $rm) ?>
        </div>
        <div class="col-3">
            <?=
            formErp::hidden([
                'activeNav' => $activeNav
            ])
            . formErp::button('Enviar')
            ?>
        </div>
    </div>
</form>
<?php
if ($rm) {
    $func = rhImport::funcionarios(null, $rm);
    if ($func) {
        $func = $func[0];
        $func['id_funcionario'] = $func['idfuncionario'];
        unset($func['idfuncionario']);
        $id_funcionario = $model->db->ireplace('cit_funcionario', $func, 1, null, 1);
        echo '<br><br>';
        if (!$id_funcionario) {
            $inss = json_encode($func);
            $sql = "INSERT INTO `cit_erro` (`id_erro`, `n_erro`) VALUES (NULL, '$inss');";
            try {
                $query = pdoSis::getInstance()->query($sql);
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        } else {
            $id_pessoa = pessoa($func, $model);
            if ($id_pessoa) {
                tel($id_pessoa, $func['telefoneresidencia'], $func['dddresidencia'], 2);
                tel($id_pessoa, $func['telefonecelular'], $func['dddcelular'], 1);
                endereco($id_pessoa, $func);
                ge_funcionario($id_pessoa, $func);
            }
        }
    }
    ?>
    <pre>
        <?php
        print_r($func)
        ?>
    </pre>
    <?php
}
?>
