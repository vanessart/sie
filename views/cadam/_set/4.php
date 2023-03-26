<?php
if (empty($cargoId)) {
    tool::alert("Escolha ao menos um Cargo");
} else {
    ?>
    <br /><br /><br /><br />
    <div class="row">
        <div class="col-sm-6 text-center">
            <form action="<?php echo HOME_URI ?>/cadam/acumulo" target="_blank" method="POST">
                <input type="hidden" name="id_cad" value="<?php echo @$dados['id_cad'] ?>" />
                <input type="hidden" name="class" value="<?php echo @$_POST['class'] ?>" />
                <input type="hidden" name="fk_id_sel" value="<?php echo @$dados['fk_id_sel'] ?>" />
                <input class="btn btn-info" style="width: 300px" type="submit" value="Declaração de Acumulo" />
            </form>
        </div>
        <div class="col-sm-6 text-center">
            <form action="<?php echo HOME_URI ?>/cadam/ficha" target="_blank" method="POST">
                <input type="hidden" name="fk_id_sel" value="<?php echo @$dados['fk_id_sel'] ?>" />
                <input type="hidden" name="class" value="<?php echo @$_POST['class'] ?>" />
                <input type="hidden" name="id_cad" value="<?php echo @$dados['id_cad'] ?>" />
                <input class="btn btn-info" style="width: 300px" type="submit" value="Ficha de Cadastro" />
            </form>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-6 text-center">
            <form action="<?php echo HOME_URI ?>/views/cadam/doc/decreto.pdf" target="_blank" >
                <input class="btn btn-info" style="width: 300px" type="submit" value="Decreto - Credenciamento de Docentes" />
            </form>
        </div>
        <div class="col-sm-6 text-center">
            <?php
            foreach ($cargoId as $v) {
                $car[@$cg_[$v]] = @$cg_[$v];
            }
            ?>
            <form action="<?php echo HOME_URI ?>/cadam/termo" target="_blank" method="POST">
                <input type="hidden" name="disciplina" value="<?php echo implode(' / ', $cargo_e) ?>" />
                <input type="hidden" name="fk_id_sel" value="<?php echo @$dados['fk_id_sel'] ?>" />
                <input type="hidden" name="id_cad" value="<?php echo @$dados['id_cad'] ?>" />
                <input type="hidden" name="class" value="<?php echo @$_POST['class'] ?>" />
                <input class="btn btn-info" style="width: 300px" type="submit" value="Termo de Compromisso " />
            </form>
        </div>
    </div>
    <br /><br />
    <div style="text-align: center">
        <br /><br /><br />
        <div class="fieldBorder2" style="width: 500px; margin: 0 auto">
            <form action="<?php echo HOME_URI ?>/cadam/comprovante" target="_blank" method="POST">
                <div style="width: 50%">
                    <?php
                    formulario::select('id_cargo', $cargo_e, 'Disciplina');
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