<?php
$aluno = turmas::classe(@$_POST['id_turma']);
$rel = @$_POST['rel'];
if (!empty($_POST['modal'])) {
    $id_turma = $_POST['id_turma'];
    $id_inst = $_POST['id_inst'];
    //if (!empty($escolas[$id_inst]['turmas'][$id_turma])) {
    tool::modalInicio('width: 90%');
    include ABSPATH . '/views/hab/' . $rel . '.php';
    tool::modalFim();
    //}
} else {

    if (!empty($aluno[0]['codigo_classe'])) {
        ?>
        <table style=" width: 100%">
            <tr>
                <td colspan="4" class="text-center" style="font-size: 16px; font-weight: bold">
                    Classe: 
                    <?php echo $aluno[0]['codigo_classe'] ?>
                    <br />&nbsp;<br />
                </td>
            </tr>
            <tr>
                <?php
                $c = 1;
                foreach ($aluno as $v) {
                    ?>
                    <td style="text-align: center">
                        <table class="table table-bordered">
                            <tr>
                                <td style="text-align: center">
                                    RSE: 
                                    <?php echo $v['id_pessoa'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center">
                                    <?php
                                    if (file_exists(ABSPATH . "/pub/fotos/" . $v['id_pessoa'] . ".jpg")) {
                                        ?>
                                        <img src="<?php echo HOME_URI ?>/pub/fotos/<?php echo $v['id_pessoa'] ?>.jpg" width="150" height="180" alt="foto"/>
                                        <?php
                                    } else {
                                        ?>
                                        <img src="<?php echo HOME_URI ?>/pub/fotos/anonimo.png" width="150" height="180" alt="foto"/>
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="height: 50px;text-align: center">
                                    <form action="carometro_tab" id="pessoa<?php echo $v['id_pessoa'] ?>" method="POST">
                                        <input type="hidden" name="rel" value="<?php echo $rel ?>" />
                                        <input type="hidden" name="modal" value="1" />
                                        <input type="hidden" name="id_pessoa_a" value="<?php echo $v['id_pessoa'] ?>" />
                                        <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                                        <input type="hidden" name="id_turma" value="<?php echo $_POST['id_turma'] ?>" />

                                    </form>
                                    <a href="#" onclick="document.getElementById('pessoa<?php echo $v['id_pessoa'] ?>').submit()">
                                        Nº 
                                        <?php echo $v['chamada'] ?>
                                        - 
                                        <?php echo $v['n_pessoa'] ?>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <?php
                    if ($c % 4 == 0) {
                        ?>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-center" style="font-size: 16px; font-weight: bold">
                            <?php
                            if ($c % 12 == 0) {
                                if (!empty($esc)) {
                                    echo $esc->cabecalho();
                                    ?>
                                    <br />&nbsp;
                                    Classe: 
                                    <?php echo $aluno[0]['codigo_classe'] ?>
                                    <br />&nbsp;
                                    <?php
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <?php
                    }
                    $c++;
                }
                ?>
            </tr>
        </table>
        <?php
    } else {
        tool::alert("Classe Vazia");
    }
}
?>