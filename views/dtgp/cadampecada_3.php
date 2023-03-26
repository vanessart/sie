<?php
if (empty($cargoId)) {
    tool::alert("Escolha ao menos um Cargo");
} else {
    ?>
    <br /><br />
    <div style="text-align: center">
        <form action="<?php echo HOME_URI ?>/dtgp/acumulo" target="_blank" method="POST">
            <input type="hidden" name="id_cad" value="<?php echo @$dados['id_cad'] ?>" />
            <input type="hidden" name="class" value="<?php echo @$_POST['class'] ?>" />
            <input type="hidden" name="fk_id_sel" value="<?php echo @$dados['fk_id_sel'] ?>" />
            <input class="btn btn-info" style="width: 300px" type="submit" value="Declaração de Acumulo" />
        </form>
        <br /><br /><br />
        <form action="<?php echo HOME_URI ?>/dtgp/ficha" target="_blank" method="POST">
            <input type="hidden" name="fk_id_sel" value="<?php echo @$dados['fk_id_sel'] ?>" />
            <input type="hidden" name="class" value="<?php echo @$_POST['class'] ?>" />
            <input type="hidden" name="id_cad" value="<?php echo @$dados['id_cad'] ?>" />
            <input class="btn btn-info" style="width: 300px" type="submit" value="Ficha de Cadastro" />
        </form>
        <br /><br /><br />
        <div style="width: 500px; margin: 0 auto">
            <?php
            foreach ($cargoId as $v) {
                $car[@$cg_[$v]] = @$cg_[$v];
            }
            ?>
            <form action="<?php echo HOME_URI ?>/dtgp/termo" target="_blank" method="POST">
                <input type="hidden" name="disciplina" value="<?php echo implode(' / ', $car) ?>" />
                <input type="hidden" name="fk_id_sel" value="<?php echo @$dados['fk_id_sel'] ?>" />
                <input type="hidden" name="id_cad" value="<?php echo @$dados['id_cad'] ?>" />
                <input type="hidden" name="class" value="<?php echo @$_POST['class'] ?>" />
                <input class="btn btn-info" style="width: 300px" type="submit" value="Termo de Compromisso " />
            </form>
        </div>
        <br /><br /><br />
        <div class="fieldBorder2" style="width: 500px; margin: 0 auto">
            <form action="<?php echo HOME_URI ?>/dtgp/comprovante" target="_blank" method="POST">
                <div style="width: 50%">
                    <?php
                    foreach ($cargoId as $v) {
                        $car1[@$v] = @$cg_[$v];
                    }
                    formulario::select('id_cargo', $car1, 'Disciplina');
                    ?>
                </div>
                <br /><br />
                <input type="hidden" name="fk_id_sel" value="<?php echo @$dados['fk_id_sel'] ?>" />
                <input type="hidden" name="id_cad" value="<?php echo @$dados['id_cad'] ?>" />
                <input type="hidden" name="class" value="<?php echo @$_POST['class'] ?>" />
                <input class="btn btn-info" style="width: 300px" type="submit" value="Escolas Cadastradas" />
            </form>
        </div>
        <br /><br /><br />

    </div>
    <?php
}
?>