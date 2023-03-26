<div class="fieldBody">
    <div class="fieldTop">
        Lançamentos - Assistentes
        <br /><br />
        
        Nota Máxima: 10
    </div>
    <br /><br />
    <?php
    
    
    $assist = $model->assist(tool::id_inst());
    if (!empty($assist)) {
        ?>
        <form method="POST">
            <table class="table table-bordered table-hover table-striped" style="width: 800px">
                <tr style="background-color: black; color: white">
                    <td>
                        Matrícula
                    </td>
                    <td>
                        Funcionário
                    </td>
                    <td style="width: 50px">
                        Nota
                    </td>
                </tr>
                <?php
                $c = 0;
                foreach ($assist as $v) {
                    ?>
                    <tr style="background-color: <?php echo $cor[$c++ % 2] ?>">
                        <td>
                            <?php echo $v['rm'] ?>
                        </td>
                        <td>
                            <?php echo $v['n_pessoa'] ?>
                        </td>
                        <td>
                            <input style="width: 50px" type="text" name="n[<?php echo $v['rm'] ?>]" value="<?php echo str_replace('.', ',', @$v['nota']) ?>" />
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <br /><br />
            <div style="text-align: center">
                <?php echo DB::hiddenKey('assist') ?>
                <input type="hidden" name="activeNav" value="<?php echo $activeNav ?>" />
                <input class="btn btn-success" type="submit" value="Salvar" />
            </div>
        </form>
        <?php
    }
    ?>
</div>
