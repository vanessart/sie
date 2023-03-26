<?php
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

$buscar = @$_POST['buscar'];
if (!empty($_POST['1'])) {
    $func = $_POST;
    $none = NULL;
} else {
    $none = 'none';
    $func = NULL;
}
$noneCad = 'none';
if (!empty($_POST['cpf'])) {
    $cpf = $_POST['cpf'];
    $sql = "select id_pessoa, n_pessoa from pessoa "
            . "where cpf = '$cpf'";
    $query = $model->db->query($sql);
    $array = $query->fetch();
    if (empty($array['id_pessoa'])) {
        tool::alert("Pessoa não encontrada");
        $pesq = 1;
    } else {
        $verFunc = sql::get('ge_funcionario', 'fk_id_pessoa', ['fk_id_pessoa' => $array['id_pessoa']], 'fetch');
        if (empty($verFunc['fk_id_pessoa'])) {
            $inst = sql::get('instancia', 'fk_id_tp, n_inst, id_inst', ['id_inst' => $_POST['id_inst']], 'fetch');
            $cadFunc = $array;
        } else {
            tool::alert("Já é Funcionário");
            $pesq = 1;
        }
    }
}
if (!empty($_POST['pesq'])) {
    $pesq = $_POST['pesq'];
}
?>
<script>
    function cad() {
        if (document.getElementById('cad').style.display == '') {
            document.getElementById('cad').style.display = 'none';
            document.getElementById('rel').style.display = 'none';
        } else {
            document.getElementById('rel').style.display = 'none';
            document.getElementById('cad').style.display = ''
        }
    }
</script>
<div class="fieldBody">
    <div class="row">
        <div class="col-md-4 text-center">
            <?php echo formulario::submit('Funcionários sem Instância', NULL, ['buscar' => 'semInst',]) ?>
        </div>
        <div class="col-md-4 text-center">
            <?php echo formulario::submit('Cadastrar Funcionário', NULL, ['pesq' => 1,]) ?>
        </div>
        <div class="col-md-4 text-center">
            <?php echo formulario::submit('Funcionários Cadastrados', NULL, ['pesqFunc' => 1,]) ?>
        </div>
    </div>
    <br /><br />
    <?php
    if (!empty($func)) {
        ?>
        <div class="fieldWhite" style="display: <?php echo $none ?>">
            <div class="row">
                <div class="col-md-3">
                    Nome: 
                    <?php echo $func['FUNCIONARIO'] ?>

                </div>
                <div class="col-md-4">
                    Função: 
                    <?php echo $func['funcao'] ?>

                </div>
                <div class="col-md-5">
                    Local de trabalho: 
                    <?php echo $func['SUB_SECAO_TRABALHO'] ?>

                </div>
            </div>
            <form method="POST">
                <div class="row">
                    <div class="col-md-4">
                        <input type="hidden" name="2[id_func]" value="<?php echo $func['id_func'] ?>" />
                        <?php echo formulario::selectDB('instancia', '2[fk_id_inst]', 'Instância') ?>

                    </div>
                    <div class="col-md-4">
                        <input type="hidden" name="buscar" value="semInst" />
                        <?php echo DB::hiddenKey('ge_funcionario', 'replace') ?>
                        <button type="submit" class="btn btn-success">
                            Salvar
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <?php
    } elseif (!empty($cadFunc)) {
        ?>
        <div class="row fieldWhite" id="cad">
            <form method="POST">
                <div class="col-md-6">
                    <?php formulario::input(NULL, 'Nome', NULL, $cadFunc['n_pessoa']) ?>
                </div>
                <div class="col-md-6">
                    <?php formulario::input(NULL, 'Instância', NULL, $inst['n_inst'], 'readonly') ?>
                </div>
                <div class="col-md-4">
                    <?php formulario::input('4[funcao]', 'Função') ?>
                </div>
                <?php
                if ($inst['fk_id_tp'] == 5) {
                    $rmt = 't' . uniqid();
                }
                ?>
                <div class="col-md-4">
                    <?php formulario::input('4[rm]', 'Matrícula', NULL, @$rmt, 'required') ?>
                </div>

                <div class="col-md-4">
                    <?php echo DB::hiddenKey('ge_funcionario', 'replace') ?>

                    <input type="hidden" name="4[fk_id_inst]" value="<?php echo $inst['id_inst'] ?>" />
                    <input type="hidden" name="4[fk_id_pessoa]" value="<?php echo $cadFunc['id_pessoa'] ?>" />
                    <button type="submit" class="btn btn-success">
                        Incluir
                    </button>
                </div>
            </form>
        </div>
        <?php
    } elseif (!empty($pesq)) {
        ?>
        <div class="row fieldWhite" id="cad">
            <form method="POST">
                <div class="row">
                    <div class="col-md-4">
                        <?php formulario::input('cpf', 'CPF', NULL, @$_POST['cpf_'], 'required') ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo formulario::selectDB('instancia', 'id_inst', 'Instância', NULL, 'required') ?>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success">
                            Pesquisar
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <?php
    } elseif (!empty($_POST['pesqFunc'])) {
        funcionarios::autocomplete(NULL, 1);
        ?>
        <div class="row fieldWhite" id="cad">
            <div class="col-md-6">
                <?php formulario::input(NULL, 'Nome', NULL, NULL, ' id="busca" onkeypress="complete()" ') ?>
            </div>
            <div class="col-md-3">
                <?php echo formulario::input(NULL, 'Matrícula', NULL, NULL, ' id="rm" ') ?>
            </div>
            <div class="col-md-3">
                <?php echo formulario::input(NULL, 'ID/RSE', NULL, NULL, ' id="id_pessoa" ') ?>
            </div>
            <div class="col-md-6">
                <?php formulario::input(NULL, 'E-mail', NULL, NULL, ' id="emailPessoa" ') ?>
            </div>
            <div class="col-md-3">
                <?php echo formulario::input(NULL, 'Telefone', NULL, NULL, ' id="tel1" ') ?>
            </div>
            <div class="col-md-3">
                <?php echo formulario::input(NULL, 'Telefone', NULL, NULL, ' id="tel2" ') ?>
            </div>
            <div class="col-md-6">
                <?php echo formulario::input(NULL, 'Local de Trabalho', NULL, NULL, ' id="n_inst" ') ?>
            </div>
            <div class="col-md-6">
                <?php echo formulario::input(NULL, 'E-mail Trabalho', NULL, NULL, ' id="email" ') ?>
            </div>
        </div>
        <?php
    }
    ?>
    <br /><br />
    <?php
    if (!empty($buscar)) {
        if ($buscar == 'semInst') {
            $sql = "SELECT id_func, gf.funcao, f.SUB_SECAO_TRABALHO, f.FUNCIONARIO, rm FROM ge_funcionario gf "
                    . " join funcionarios f on f.MATRICULA = gf.rm"
                    . " WHERE `fk_id_inst` IS NULL "
                    . "and gf.funcao not like '%guarda%' "
                    . "and gf.funcao not like '%motorista%' "
                    . "order by f.FUNCIONARIO ";
            $query = $model->db->query($sql);
            $array = $query->fetchAll();
            foreach ($array as $k => $v) {
                $v['buscar'] = 'semInst';
                $array[$k]['ed'] = formulario::submit('editar', NULL, $v);
            }

            $form['array'] = $array;
            $form['fields'] = [
                'Nome' => 'FUNCIONARIO',
                'Matrícula' => 'rm',
                'Função' => 'funcao',
                'Local de Trabalho' => 'SUB_SECAO_TRABALHO',
                '||1' => 'ed'
            ];
            ?>
            <div id="rel">
                <?php
                tool::relatSimples($form);
                ?>
            </div>
            <?php
        }
    }
    ?>
</div>