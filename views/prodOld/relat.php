<?php ?>
<div class="fieldBody">
    <div class="fieldTop">
        Relatórios
    </div>
    <br /><br />
    <?php
    $esc = escolas::idInst();
    $esc[13] = 'Secretaria de Educação';
    if (user::session('id_nivel') == 14 || user::session('id_nivel') == 2) {
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        echo formulario::select('id_inst', $esc, 'Escola', $id_inst, 1);
    } else {
        $id_inst = tool::id_inst();
    }
    if (!empty($id_inst)) {
        ?>
        <br /><br />
        <div class="row">
            <div class="col-sm-6">
                <form target="_blank" action="<?php echo HOME_URI ?>/prod/relatpdf" method="POST">
                    <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                    <input class="btn btn-success" type="submit" value="Relatório geral (<?php echo @$esc[$id_inst] ?>)" />
                </form>
            </div>
            <div class="col-sm-3">
                <a class="btn btn-primary" target="_blank" href="<?php echo HOME_URI ?>/views/prod/doc/lei_complementar_431_de_2018_reformula-abono-produtividade_revoga.pdf">
                    Lei 431
                </a>
            </div>
             <div class="col-sm-3">
                 <a class="btn btn-primary" target="_blank" href="<?php echo HOME_URI ?>/views/prod/doc/lei_complementar_n_438.pdf">
                    Lei 438
                </a>
            </div>
        </div>
        <div class="row">
            <br /><br />
        <div class="col-sm-12">
          Agradecemos a contribuição da rede em apontar a falta de algumas turmas nos dados da produtividade, informação crítica e de apuração complexa e que pode gerar distorções no resultado final.
          <br /><br />
          Informamos que as turmas já foram incluídas e as notas revistas sem impacto negativo no resultado final.
            
        </div>
    </div>

        <?php
    }
    ?>
</div>
