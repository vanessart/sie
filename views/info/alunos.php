<?php
$escolas = escolas::idInst('%4%', 'fk_id_tp_ens');
asort($escolas);
?>

<div class="fieldBody">
    <div class="fieldTop">
        Exportação de Alunos por Escola (Fundamental)
    </div>
    <div>
        <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/alunos.php" method="POST">
            <br /><br />
            <div class="panel panel-default">
                <div class="panel panel-heading">
                    Colunas
                </div>
                <div class="panel panel-body">

                    <div class="row">
                        <div class="col-lg-2">
                            <label>
                                <input type="checkbox" name="colunas[]" value="n_inst as Escola" />
                                Escola
                            </label> 
                        </div>
                        <div class="col-lg-2">
                            <label>
                                <input type="checkbox" name="colunas[]" value="codigo as Código" />
                                Classe (código)
                            </label> 
                        </div>
                        <div class="col-lg-2">
                            <label>
                                <input type="checkbox" name="colunas[]" value="substring(codigo_classe, 4,1) as serie" />
                                Serie
                            </label> 
                        </div>
                        <div class="col-lg-2">
                            <label>
                                <input type="checkbox" name="colunas[]" value="n_pessoa as Nome" />
                                Nome
                            </label> 
                        </div>
                        <div class="col-lg-2">
                            <label>
                                <input type="checkbox" name="colunas[]" value="dt_nasc Nasc" />
                                D. Nasc.
                            </label> 
                        </div>
                        <div class="col-lg-2">
                            <label>
                                <input type="checkbox" name="colunas[]" value="ra as RA" />
                                RA
                            </label> 
                        </div>                       
                        <div class="col-lg-2">
                            <label>
                                <input type="checkbox" name="colunas[]" value="rg, rg_oe, rg_uf, dt_rg" />
                                RG
                            </label> 
                        </div>
                        <div class="col-lg-2">
                            <label>
                                <input type="checkbox" name="colunas[]" value="id_pessoa as RSE" />
                                RSE/ID
                            </label> 
                        </div>
                        <div class="col-lg-2">
                            <label>
                                <input type="checkbox" name="colunas[]" value="chamada as Chamada" />
                                Chamada
                            </label> 
                        </div>
                        <div class="col-lg-2">
                            <label>
                                <input type="checkbox" name="colunas[]" value="sexo as Sexo" />
                                Sexo
                            </label> 
                        </div>
                        <div class="col-lg-2">
                            <label>
                                <input type="checkbox" name="colunas[]" value="emailgoogle as Email_Google" />
                                Email_Google
                            </label> 
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 text-center">
                    <a href="">
                        <input class="btn btn-danger" type="button" value="Limpar" />
                    </a>
                </div> 
                <div class="col-lg-6 text-center">
                    <input class="btn btn-info" type="submit" value="Exportar" />
                </div> 
            </div>
            <br /><br />
            <div class="panel panel-default">
                <div class="panel panel-heading text-center">
                    Atenção! Selecione no máximo 10 ESCOLAS por vez.
                </div>
                <div class="panel panel-body">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <td style="font-weight: bold">
                                    Escola
                                </td>
                                <td style="font-weight: bold">
                                    Todos
                                </td>
                                <td style="font-weight: bold">
                                    1º Ano
                                </td>
                                <td style="font-weight: bold">
                                    2º Ano
                                </td>
                                <td style="font-weight: bold">
                                    3º Ano
                                </td>
                                <td style="font-weight: bold">
                                    4º Ano
                                </td>
                                <td style="font-weight: bold">
                                    5º Ano
                                </td>
                                <td style="font-weight: bold">
                                    6º Ano
                                </td>
                                <td style="font-weight: bold">
                                    7º Ano
                                </td>
                                <td style="font-weight: bold">
                                    8º Ano
                                </td>
                                <td style="font-weight: bold">
                                    9º Ano
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($escolas as $k => $v) {
                                $sql = "select id_turma, n_turma, letra from ge_turmas "
                                        . "where fk_id_inst = $k "
                                        . "and periodo_letivo = '" . date("Y") . "' "
                                        . "order by letra";
                                $query = $model->db->query($sql);
                                $cl = $query->fetchAll();
                                foreach ($cl as $c) {
                                    $classes[$k][$c['n_turma'][0]][$c['letra']] = $c['id_turma'];
                                }
                                ?>
                                <tr>
                                    <td style="font-size: 20px; width: 30%">
                                        <?php echo $v ?>
                                    </td>
                                    <td>
                                        <label>
                                            <script>
                                                function setar<?php echo $k ?>() {
    <?php
    for ($i = 1; $i <= 9; $i++) {
        if (!empty($classes[$k][$i])) {
            foreach ($classes[$k][$i] as $key => $ci) {
                ?>
                                                                document.getElementById("<?php echo $ci ?>").checked = true;
                <?php
            }
        }
    }
    ?>
                                                }

                                            </script>
                                            <input onclick="setar<?php echo $k ?>()" type="checkbox" name="" value="ON" />
                                            Todos
                                        </label>

                                    </td>
                                    <?php
                                    for ($i = 1; $i <= 9; $i++) {
                                        ?>
                                        <td>
                                            <?php
                                            if (!empty($classes[$k][$i])) {
                                                foreach ($classes[$k][$i] as $key => $ci) {
                                                    ?>
                                                    <label>
                                                        <input id="<?php echo $ci ?>" type="checkbox" name="cla[]" value="<?php echo $ci ?>" />
                                                        <?php echo $i . ' ' . $key ?>
                                                    </label>
                                                    <br />
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </td>
                                        <?php
                                    }
                                    ?>


                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="text-center">
                <input class="btn btn-info" type="submit" value="Exportar" />
            </div>
        </form>

    </div>

</div>