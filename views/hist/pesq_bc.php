<form method="POST">
    <table class="table table-bordered table-striped">
        <tr>
            <td></td><td></td>
            <td>
                1o Ano
            </td>
            <td>
                2o Ano/<br />1a Série
            </td>
            <td>
                3o Ano/<br />2a Série
            </td>
            <td>
                4o Ano/<br />3a Série
            </td>
            <td>
                5o Ano/<br />4a Série
            </td>
            <td>
                6o Ano/<br />5a Série
            </td>
            <td>
                7o Ano/<br />6a Série
            </td>
            <td>
                8o Ano/<br />7a Série
            </td>
            <td>
                9o Ano/<br />8a Série
            </td>
            <td>
                1B
            </td>
            <td>
                2B
            </td>
            <td>
                3B
            </td>
            <td>
                4B
            </td>
        </tr>
        <?php
        $bc = [9 => 'Língua Portuguêsa', 10 => 'Arte', 11 => 'Educação Física', 6 => 'Matemática', 12 => 'Ciências Naturais', 13 => 'História', 14 => 'Geografia'];
        foreach ($bc as $k => $v) {
            ?>
            <tr>
                <?php
                if (@$primeiro != 1) {
                    ?>
                    <td rowspan="7">
                        BC
                    </td>
                    <?php
                    $primeiro = 1;
                }
                ?>
                <td>
                    <?php echo $v ?>
                </td>
                <?php
                for ($c = 1; $c <= 9; $c++) {
                    ?>
                    <td style="width: 5%">
                        <input type = "text" name = "discNota[<?php echo $c ?>][<?php echo $k ?>]" value = "<?php echo str_replace('.', ',', @$hist['discNota'][$c][$k]) ?>" size = "5" />
                    </td>
                    <?php
                }
                //notas bimestrais
                for ($c = 1; $c <= 4; $c++) {
                    ?>
                    <td style="width: 3%">

                    </td>
                    <?php
                }
                ?>
            </tr>
            <?php
        }
        ?>
    </table>
        <div class="text-center">
        <input type="hidden" name="historico" value="<?php echo $historico ?>" />
        <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
        <?php
        echo DB::hiddenKey('bc');
        ?>
        <input class="btn btn-success" type="submit" value="Salvar" />
    </div>  
</form>
