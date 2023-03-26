<?php
if (empty($robot)) {
    ?>
    <br />
    <form method="POST">
        <div class="row">
            <div class="col-4">
                <?= formErp::input('data[0]', 'Data Início', @$data[0], null, null, 'date') ?>
            </div>
            <div class="col-4">
                <?= formErp::input('data[1]', 'Data Final', @$data[1], null, null, 'date') ?>
            </div>
            <div class="col-3">
                <?=
                formErp::hidden([
                    'activeNav' => $activeNav,
                ])
                . formErp::button('Processar')
                ?>
            </div>
        </div>
    </form>
    <?php
}
if (!empty($data[0])) {  
    $funcs = rhImport::funcionarios($data);
    if ($funcs && @$funcs['status'] != 'error') {
        foreach ($funcs as $func) {
            $func['id_funcionario'] = $func['idfuncionario'];
            unset($func['idfuncionario']);
            $id_funcionario = $model->db->ireplace('cit_funcionario', $func, 1);
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
            print_r($funcs)
            ?>
        </pre>
        <?php
    } else {
       echo @$funcs['message']; 
    }
}
if(empty($funcs['message'])){
    $txt = 'fim';
} else {
    $txt = $funcs['message'];
}
$sql = "INSERT INTO `cit_erro` (`id_erro`, `n_erro`) VALUES (NULL, '$txt');";
try {
    $query = pdoSis::getInstance()->query($sql);
} catch (Exception $exc) {
    echo $exc->getTraceAsString();
}
