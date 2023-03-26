<?php

    if (!empty($aluno)) {
        ?>
        <div class="fieldTop">
            Classe: 
            <?php echo current($aluno)['codigo_classe'] ?>
            <br /><br />
        </div>
        <div class="row">
            <?php
            $c = 1;
            foreach ($aluno as $v) {
                ?>
                <div class="col-sm-3">
                    <table class="table table-bordered fieldWhite">
                        <tr>
                            <td style="text-align: center">
                                RSE: 
                                <?php echo $v['id_pessoa'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center">

                                <button style="width: 100%" onclick="fotoNovo('<?php echo $v['id_pessoa'] ?>')" class="btn btn-default">
                                    Trocar Foto
                                </button>
                                <!--
                                <input onclick="foto('<?php echo $v['id_pessoa'] ?>')" style="width: 100%" class="btn btn-default" type="submit" value="Trocar Foto com Webcam" />
                                -->
                                <br /><br />
                                <?php
                                if (file_exists(ABSPATH . "/pub/fotos/" . $v['id_pessoa'] . ".jpg")) {
                                    ?>
                                    <img src="<?php echo HOME_URI ?>/pub/fotos/<?php echo $v['id_pessoa'] ?>.jpg?id=<?php echo uniqid() ?>" height="180" alt="foto"/>
                                    <?php
                                } else {
                                    ?>
                                    <img src="<?php echo HOME_URI ?>/pub/fotos/anonimo.png" height="180" alt="foto"/>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 50px;text-align: center">
                                <form action="<?php echo HOME_URI ?>/sed/aluno" id="pessoa<?php echo $v['id_pessoa'] ?>" method="POST">
                                    <input type="hidden" name="aba" value="esc" />
                                    <input type="hidden" name="id_pessoa" value="<?php echo $v['id_pessoa'] ?>" />
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
                </div>
                <?php
                if ($c % 4 == 0) {
                    ?>
                </div>
                <br /><br />
                <div class="row">
                    <?php
                }
                $c++;
            }
            ?>
        </div>
        <?php
    } else {
        tool::alert("Classe Vazia");
    }
    ?>
    <form id="tirafoto" action="<?php echo HOME_URI ?>/sed/foto" method="POST">
        <input type="hidden" name="activeNav" value="3" />
        <input id="id" type="hidden" name="id_pessoa" value="" />
        <input id="id" type="hidden" name="id_turma" value="<?php echo @$_POST['id_turma'] ?>" />
    </form>
    <form id="subUp" method="POST" enctype="multipart/form-data">
        <input style="display: none" id="cpt" type=file
               accept="image/*"
               capture=environment
               onchange="$('#subUp').submit();"
               tabindex=-1 name="arquivo">
        <input id="id_pessoa" type="hidden" name="id_pessoa" value="" />
        <input type="hidden" name="upFoto" value="1" />
        <input type="hidden" name="proc" value="1" />
        <input type="hidden" name="id_turma" value="<?= @$_POST['id_turma'] ?>" />
        <input type="hidden" name="ano" value="<?= $ano ?>" />
    </form>
    <script>
        function foto(idPessoa) {
            document.getElementById("id").value = idPessoa;
            document.getElementById("tirafoto").submit();
        }

        function fotoNovo(id) {
            document.getElementById('id_pessoa').value = id;
            $('#cpt').click()
        }
    </script>