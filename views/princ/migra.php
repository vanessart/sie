<div class="fieldBody">
    <div class="row fieldWhite">
        <div class="col-lg-3">
            <form method="POST">
                <?php echo formulario::button('Importar Funcionários', 'func') ?>
            </form>
        </div>
        <div class="col-lg-9">
            Importa os dados da tabela funcionario para a tabela ge_funcionario
        </div>
    </div>
    <br /><br />
    <div class="row fieldWhite">
        <div class="col-lg-3">
            <form method="POST">
                <?php echo formulario::button('Novo Importar Funcionários', 'Novofunc') ?>
            </form>
        </div>
        <div class="col-lg-9">
            Importa os dados da tabela funcionario_rh para a tabela ge_funcionario
        </div>
    </div>
    <?php
    if (!empty(@$_POST['func'])) {
        migraBarueri::funcionarios();
    } else if (!empty(@$_POST['Novofunc'])) {
        migraRH::funcionarios();
    }
    ?>
    <br /><br />
    <div class="row fieldWhite">
        <div class="col-lg-12">
            Importar Arquivo CSV
        </div>
        <div class="col-lg-12">
            <form method="POST">
                <div class="row">
                    <div class="col-lg-4">
                        <?php echo formulario::input("tabela", "Tabela") ?>
                    </div>
                    <div class="col-lg-4">
                        <input type="file" name="arq" value="Arquivo" />
                    </div>
                    <div class="col-lg-4">
                        <input class="btn btn-success" name="import" type="submit" value="Enviar" />
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php
    if (!empty($_POST['import'])) {

        tool::impotCsv(ABSPATH . '/tmp/' . @$_POST['arq'], $_POST['tabela']);
    }
    ?>
    <br /><br />
    <div class="row fieldWhite">
        <div class="col-lg-3">
            <form method="POST">
                <?php echo formulario::input('tabela', 'Tabela', NULL, 'omr_respostas') ?>
                <?php echo formulario::input('campo', 'Campo', NULL, 'fk_id_tb') ?>
                <?php echo formulario::button('Substituir', 'trocaLetra') ?>
            </form>
        </div>
        <div class="col-lg-9">
            Avaliação Global - Substituir letras por legenda
        </div>
    </div>
    <?php
    if (!empty($_POST['trocaLetra'])) {
        ini_set('memory_limit', '20000M');
        $sql = "SELECT * FROM `global_matriz`";
        $query = $model->db->query($sql);
        $array = $query->fetchAll();
        foreach ($array as $v) {
            $matriz[$v['fk_id_gl']][$v['item']]['A'] = substr($v['A'], 1);
            $matriz[$v['fk_id_gl']][$v['item']]['B'] = substr($v['B'], 1);
            $matriz[$v['fk_id_gl']][$v['item']]['C'] = substr($v['C'], 1);
            $matriz[$v['fk_id_gl']][$v['item']]['D'] = substr($v['D'], 1);
        }
        ?>
        <pre>
            <?php
            print_r($matriz)
            ?>
        </pre>
        <?php
        echo $sql = "select * from " . $_POST['tabela'] . " where q01 != ''";
        $query = $model->db->query($sql);
        $repl = $query->fetchAll();
        foreach ($repl as $v) {

            $up = '';
            for ($c = 1; $c <= 50; $c++) {
                if (!empty($matriz[$v['fk_id_gl']]['q' . str_pad($c, 2, "0", STR_PAD_LEFT)])) {
                    @$res = $matriz[$v['fk_id_gl']]['q' . str_pad($c, 2, "0", STR_PAD_LEFT)][$v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)]];
                    if (!empty($res)) {
                        @$up .= " `q" . str_pad($c, 2, "0", STR_PAD_LEFT) . "` = '" . $res . "', ";
                    } else {
                        @$up .= " `q" . str_pad($c, 2, "0", STR_PAD_LEFT) . "` = '5', ";
                    }
                }
            }
            $up = substr($up, 0, -2);
            $sql = " update " . $_POST['tabela'] . " set " . $up . " "
                    . " where " . $_POST['campo'] . " = " . $v[$_POST['campo']]
                    . " and  fk_id_gl =  " . $v['fk_id_gl'];
            $query = pdoSis::getInstance()->query($sql);
        }
    }
    ?>
    <br /><br />
    <div class="row">
        <div class="col-sm-4">

            <br />
            <div>
                <?php
                echo form::submit('corregir Conselho', null, ['corrigirConselho' => 1]);
                if (!empty($_POST['corrigirConselho'])) {
                    ini_set('memory_limit', '20000M');
                    $sql = "SELECT * FROM hab.`aval_final`  a "
                            . "join ge2.ge_periodo_letivo p on p.id_pl = a.fk_id_pl "
                            . " where at_pl = 1 ";
                    $query = pdoSis::getInstance()->query($sql);
                    $array = $query->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($array as $v) {
                        $contaNotaBaixa = [];
                        foreach ($v as $ky => $y) {
                            if (!empty($y) && substr($ky, 0, 6) == 'media_' && $y < 5) {
                                $contaNotaBaixa[substr($ky, 6)] = 5;
                            }
                        }
                        $ins = [];
                        if (!empty($contaNotaBaixa)) {
                            if (count($contaNotaBaixa) <= 3 && count($contaNotaBaixa) > 0) {
                                foreach ($contaNotaBaixa as $iddisc => $va) {
                                    echo '<br /><br />' . $sql = "UPDATE `aval_final` SET "
                                    . " cons_" . $iddisc . '= 5 '
                                    . " where id_final = " . $v['id_final'];
                                    $query = pdoHab::getInstance()->query($sql);
                                }
                            }
                        }
                    }
                }
                ?>
            </div>

        </div>
    </div>
</div>