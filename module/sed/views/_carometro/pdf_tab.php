<?php
if (!empty($aluno[0]['codigo_classe'])) {
    ?>
    <table style=" width: 100%">
        <tr>
            <td colspan="4" class="text-center" style="font-size: 16px; font-weight: bold">
                Classe: 
                <?php echo $aluno[0]['n_turma'] ?>
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
                                <img src="<?= $model->fotoEnd($v['id_pessoa']) ?>" width="150" height="180" alt="foto"/>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 50px;text-align: center">
                                <form action="<?php echo HOME_URI ?>/gestao/cadaluno" id="pessoa<?php echo $v['id_pessoa'] ?>" method="POST">
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
}