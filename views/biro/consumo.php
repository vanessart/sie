
<div class="fieldBody">
    <br /><br />
    <div class="fieldBorder2">
        <form target="_blank" action="<?php echo HOME_URI ?>/biro/extrato" method="POST">
            Extrato por período
            <br /><br />
            <div class="row">
                <div class="col-sm-3">
                    <?php echo formulario::selectDB('biro_contrato', 'fk_id_con', 'Contrato', @$dados['fk_id_con']) ?>
                </div>
                <div class="col-sm-3">
                    <?php echo formulario::input('inicio', 'Início', NULL, NULL, formulario::dataConf(1) . ' id="1"') ?>
                </div>
                <div class="col-sm-3">
                    <?php echo formulario::input('fim', 'Fim', NULL, NULL, formulario::dataConf(2) . ' id="2"') ?>
                </div>
                <div class="col-sm-3">
                    <input class="btn btn-success" type="submit" value="Enviar" />
                </div>
            </div>

        </form>
    </div>
    <br /><br />
    <div class="fieldBorder2">
        <form target="_blank" action="<?php echo HOME_URI ?>/biro/saldo" method="POST">
           Saldo por Contrato e por Item
            <br /><br />
            <div class="row">
                <div class="col-sm-3">
                    <?php echo formulario::selectDB('biro_contrato', 'fk_id_con', 'Contrato', @$dados['fk_id_con']) ?>
                </div>
                <div class="col-sm-3">
                    <input class="btn btn-success" type="submit" value="Enviar" />
                </div>
            </div>

        </form>
    </div>

</div>