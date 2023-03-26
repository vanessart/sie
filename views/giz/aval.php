<?php
$qt = $model->quest();
$value = $model->value();
$descri = $model->descri();


$id_prof = filter_input(INPUT_POST, 'id_prof', FILTER_SANITIZE_NUMBER_INT);
$prof = $model->inscricoes(@$id_prof, 'id_prof');
$notas = sql::get('giz_notas', '*', ['id_prof' => $id_prof], 'fetch');
$id_pessoa = $prof['id_pessoa'];
$n_pessoa = $prof['n_pessoa'];
$titulo = $prof['titulo'];
?>
<div class="fieldBody">
    <div class="fieldTop">
        Análise do Projeto: <?php echo stripslashes($titulo) ?>
        <br /><br />
        Prof. <?php echo $n_pessoa ?>
    </div>
    <br />
    <div style="text-align: right">
        <a href="<?php echo HOME_URI ?>/giz/banca?id_cate=<?php echo @$_POST['id_cate'] ?>">
            Voltar
        </a>
    </div>
    <br />
    <form method="POST">
        <table class="table table-bordered table-hover table-striped fieldBorder2" style="font-weight: bold">
            <tr>
                <td colspan="3" style="font-size: 20px;">
                    Projeto
                </td>
            </tr>
            <tr>
                <td>
                    Critérios 
                </td>
                <td>
                    Pontos
                </td>
                <td>
                    Evidências
                </td>
            </tr>
            <tr>
                <td style="width: 40%">
                    Pontuar de 0 á 2 Com no máximo 2 casas decimais
                </td>
                <td style="width: 10%">
                    <input onblur="conf('2', this)" style="width: 60px" type="text" name="nota_projeto" value="<?php echo str_replace(".", ",", $notas['nota_projeto']) ?>" />
                </td>
                <td style="width: 50%">
                    <textarea name="just_projeto" style=" width: 100%;"><?php echo $notas['just_projeto'] ?></textarea>
                </td>
            </tr>
        </table>
        <br /><br />
        <table class="table table-bordered table-hover table-striped fieldBorder2" style="font-weight: bold">
            <tr>
                <td colspan="3" style="font-size: 20px;">
                    Portfolio
                </td>
            </tr>
            <tr>
                <td>
                    Critérios 
                </td>
                <td>
                    Pontos
                </td>
                <td>
                    Evidências
                </td>
            </tr>
            <tr>
                <td style="width: 40%">
                    Pontuar de 0 á 3 Com no máximo 2 casas decimais
                </td>
                <td style="width: 10%">
                    <input onblur="conf('3', this)" style="width: 60px" type="text" name="nota_portfolio" value="<?php echo str_replace(".", ",", $notas['nota_portfolio']) ?>" />
                </td>
                <td style="width: 50%">
                    <textarea name="just_portfolio" style=" width: 100%;"><?php echo $notas['just_portfolio'] ?></textarea>
                </td>
        </table>
        <br /><br />
        <table class="table table-bordered table-hover table-striped fieldBorder2" style="font-weight: bold">
            <tr>
                <td colspan="4" style="font-size: 20px;">
                    In Loco
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <?php
                    foreach ($qt as $key1 => $v1) {
                        ?>
                        <div style=" background: #C6D1DD;padding: 20px">
                            <div style="font-size: 25px; text-align: center; padding: 20px">
                                <?php echo $key1 ?>
                            </div>
                            <table style="width: 100%">
                                <tr>
                                    <td class="fieldrow3">
                                        Critérios
                                    </td>
                                    <td class="fieldrow3" style="width: 100px">
                                        Nível Aferido
                                    </td>
                                    <td class="fieldrow3" style="width: 60px; text-align: center">
                                        Pontos
                                    </td>
                                    <td class="fieldrow3">
                                        Evidências
                                    </td>
                                </tr>
                                <?php
                                $pontos = 0;
                                $k = 'eixo';
                                foreach ($qt[$key1] as $key2 => $v2) {
                                    $pontos += @$notas['eixo_v' . $key2];
                                    ?>
                                    <tr>
                                        <td class="fieldrow3" style="text-align: left">
                                            <?php echo $v2 ?>
                                        </td>
                                        <td class="fieldrow3">
                                            <?php
                                            $model->nivel($k . $key2, $value[$key2], @$notas[$k . $key2], $descri[$key2]);
                                            ?>
                                        </td>
                                        <td class="fieldrow3" style="text-align: center">
                                            <input readonly id="<?php echo $k . $key2 ?>" type="text" name="<?php echo $k . '_v' . $key2 ?>" value="<?php echo @$notas[$k . '_v' . $key2] ?>" size="4" style="text-align: center" />
                                        </td>
                                        <td style="width: 60%">
                                            <textarea id="<?php echo $k . $key2 ?>descri"  name="<?php echo $k . '_t' . $key2 ?>" style="width: 100%; height: 80px"><?php echo @$notas[$k . '_t' . $key2] ?></textarea>
                                        </td>
                                    </tr>

                                    <?php
                                }
                                ?>
                                <tr>
                                    <td class="fieldrow3" colspan="4">
                                        Total de ponto da análise do projeto neste eixo: 
                                        <?php echo $pontos ?>
                                    </td>
                                </tr>
                            </table>

                        </div>
                        <div style="height: 50px">
                        </div>
                        <?php
                    }
                    ?>
                </td>
            </tr>
        </table>
        <br /><br />
        <table class="table table-bordered table-hover table-striped fieldBorder2" style="font-weight: bold">
            <tr>
                <td style="font-size: 20px;">
                    Observações Finais 
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo formulario::textarea('obs', $notas['obs'], NULL, 1) ?>
                </td>
            </tr>
        </table>
        <br /><br />
        <div style="text-align: center">
            <?php echo DB::hiddenKey('lancNotas') ?>
            <input type="hidden" name="id_cate" value="<?php echo @$_POST['id_cate'] ?>" />
            <input type="hidden" name="id_prof" value="<?php echo $id_prof ?>" />
            <input class="btn btn-success" type="submit" value="Salvar" />
        </div>
    </form>
</div>

<script>
    function conf(v, t) {
        tt = t.value.replace(',', '.');
        if (tt > v) {
            alert('Valor máximo = ' + v);
            t.value = '';
        }

    }
</script>

