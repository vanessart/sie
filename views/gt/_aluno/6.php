<?php
$id_re = filter_input(INPUT_POST, 'id_re', FILTER_SANITIZE_NUMBER_INT);


$aluno->endereco();
$e = new escola();
$maps = tool::distancia((trim($aluno->_latitude) . ',' . trim($aluno->_longitude)), $e->_maps);
$distancia = $maps[0];
$tempo = $maps[1];
$aluno->tempoEscola($tempo, $distancia);
$setDist = str_replace(',', '.', explode(' ', $distancia)[0]);

//tool::manutencao();
?>
<br /><br /><br />
<table class="table table-bordered table-striped table-hover">
    <tr>
        <td>
            Telefone Residencial:
            <?php echo @$aluno->_tel3 ?>
        </td>
        <td>
            Telefone Recado:
            <?php echo @$aluno->_tel2 ?>
        </td>
        <td>
            Telefone Celular:
            <?php echo @$aluno->_tel1 ?>
        </td>
    </tr>
</table>
<br /><br />
<?php
$autorizado = $aluno->responsaveis(@$_POST['desativados']);
?>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr style="font-weight: bold">
            <td colspan="7">
                <div class="row">
                    <div class="col-sm-4">
                        Pessoas autorizadas a retirar <?php echo tool::sexoArt($aluno->_sexo) ?> alun<?php echo tool::sexoArt($aluno->_sexo) ?>
                    </div>
                    <div class="col-sm-4">
                        <form method="POST">
                            <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
                            <input type="hidden" name="activeNav" value="6" />
                            <?php
                            if (empty($_POST['desativados'])) {
                                ?>
                                <input style="float: right" class="btn btn-warning" type="submit" name="desativados" value="Mostrar Desativados" />   
                                <?php
                            } else {
                                ?>
                                <input style="float: right" class="btn btn-primary" type="submit" value="Ocultar Desativados" />   
                                <?php
                            }
                            ?>
                        </form> 
                    </div>
                    <div class="col-sm-4">
                        <?php
                        if (false) {
                            ?>
                            <form method="POST">
                                <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
                                <input type="hidden" name="activeNav" value="6" />
                                <input style="float: right" class="btn btn-info" type="submit" name="edit" value="Nova Autorização" />   
                            </form> 
                            <?php
                        }
                        ?>
                    </div>
                </div>

            </td>
        </tr>
        <tr style="background-color: silver; font-weight: bold">
            <td>
                Nome
            </td>
            <td>
                Parentesco
            </td>
            <td>
                Documento
            </td>
            <td>
                Tipo Doc.
            </td>
            <td>
                Telefones
            </td>
            <td>
                Foto
            </td>
            <td>

            </td>
        </tr>
    </thead>
    <tbody>

        <?php
        if (!empty($autorizado)) {
            foreach ($autorizado as $v) {
                ?>
                <tr <?php echo $v['ativo'] == 0 ? ' style="background-color: red; color: white"' : '' ?>>
                    <td>
                        <?php
                        echo $v['n_re'];
                        if ($v['ativo'] == 0) {
                            ?>
                            <br><br>
                            <div class="alert alert-danger" style="text-align: center; font-weight: bold">
                                Não Autorizado
                            </div>
                            <?php
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo $v['parente'] ?>
                    </td>
                    <td>
                        <?php echo $v['doc'] ?>
                    </td>
                    <td>
                        <?php echo $v['n_doc'] ?>
                    </td>
                    <td>
                        <?php echo $v['telefones'] ?>
                    </td>
                    <td style="text-align: center" >
                        <?php
                        if (file_exists(ABSPATH . "/pub/fotoresp/" . $v['id_re'] . ".jpg")) {
                            ?>
                            <form method="POST">
                                <input type="hidden" name="desativados" value="<?php echo @$_POST['desativados'] ?>" />
                                <input type="hidden" name="activeNav" value="6" />
                                <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
                                <input type="hidden" name="id_re" value="<?php echo $v['id_re'] ?>" />
                                <button name="verfoto" value="1" class="btn btn-link" type="submit">
                                    <img src="<?php echo HOME_URI ?>/pub/fotoresp/<?php echo $v['id_re'] ?>.jpg?ass=<?php echo uniqid() ?>" style="width: 100px" alt="foto"/>
                                </button>
                            </form>
                            <?php
                        } else {
                            ?>
                            <img src="<?php echo HOME_URI ?>/includes/images/an.jpg" style="width: 100px"  alt = "aluno"/>
                            <?php
                        }
                        ?>
                        <form method="POST">
                            <input type="hidden" name="activeNav" value="6" />
                            <input type="hidden" name="novaFoto" value="6" />
                            <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
                            <input type="hidden" name="id_re" value="<?php echo $v['id_re'] ?>" />
                            <button class="btn btn-link" type="submit">
                                Trocar
                            </button>
                        </form>
                    </td>
                    <td style="width: 10px">
                        <form method="POST">
                            <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
                            <input type="hidden" name="activeNav" value="6" />
                            <input type="hidden" name="id_re" value="<?php echo $v['id_re'] ?>" />
                            <input name="edit" class="btn btn-success" type="submit" value="Editar" />
                        </form>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>
<br><br>
<?php
if (!empty($_POST['edit'])) {
    tool::modalInicio('width: 80%', NULL, 'forma');
    include ABSPATH . '/views/gt/_aluno/_6/form.php';
    tool::modalFim();
} elseif (!empty($_POST['verfoto'])) {
    tool::modalInicio('width: 30%');
    ?>
    <img src="<?php echo HOME_URI ?>/pub/fotoresp/<?php echo $id_re ?>.jpg?ass=<?php echo uniqid() ?>" style="width: 100%" alt="foto"/>
    <?php
    tool::modalFim();
} elseif (!empty($_POST['novaFoto'])) {
    tool::modalInicio('width: 90%');
    include ABSPATH . '/views/gt/_aluno/_6/web.php';
    tool::modalFim();
}
