<?php
if (!defined('ABSPATH'))
    exit;
$sgc = coordena::setGrupCurso();
$curso = ng_main::cursosSeg();
$grup = coordena::grupHab();
?>
<div class="body">
    <div class="fieldTop">
        Alocação Habilidades/Curso
    </div>
    <br /><br />
    <form method="POST">
        <table style="margin: 0 auto; width: 60%" class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    Curso
                </td>
                <td>
                    Grupo de Habilidade
                </td>
            </tr>
            <?php
            foreach ($curso as $v) {
                ?>
                <tr>
                    <td>
                        <?php echo $v['n_cur'] ?>
                    </td>
                    <td>
                        <select style="width: 100%" name="gr[<?php echo $v['id_cur'] ?>]">
                            <option></option>
                            <?php
                            foreach ($grup as $g) {
                                if ($g['fk_id_seg'] == $v['id_seg']) {
                                    ?>
                                    <option <?php echo @$sgc[$v['id_cur']] == @$g['id_gh'] ? 'selected' : '' ?> value="<?php echo @$g['id_gh'] ?>"><?php echo @$g['n_gh'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <?php
            }
            ?>
        </table> 
        <br /><br />
        <div style="text-align: center">
            <?php
            echo formErp::hiddenToken('SalvarGrupCurso');
            echo formErp::button('Salvar');
            ?>
        </div>
    </form>
</div>
