
<br /><br />
<div style="font-weight:bold; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center">
    Lista Rede
</div>
<?php
$a = data::meses();

if (user::session('id_nivel') == 10) {
    ?>
    <form method="POST">
        <div class="row">
            <br /><br />
            <div class="col-sm-1"></div>
            <div class="col-sm-4">
                <?php
                echo formulario::select('mes', $a, 'Selecionar Mês', @$mes);
                ?>
            </div>
            <div class="col-sm-3">
                <?php
                $id_em = filter_input(INPUT_POST, 'id_em', FILTER_SANITIZE_NUMBER_INT);
                echo form::selectDB('transp_empresa', 'id_em', 'Empresa', $id_em);
                ?>
            </div>
            <div class="col-sm-2">
                <?php echo form::button('Continuar') ?>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </form>
    <br /><br /><br /><br />
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-2">
            <?php
            if (!empty($_POST['mes'])) {
                ?>
                <form target = "_blank" action = "<?php echo HOME_URI ?>/transp/movimentacaopdf ?>" method = "POST">
                    <input type="hidden" name="mes" value="<?php echo $_POST['mes'] ?>" />
                    <input type="hidden" name="id_em" value="<?php echo $id_em ?>" />
                    <button class = "btn btn-info">
                        Movimentação Geral
                    </button>
                </form>
                <?php
            } else {
                ?>
                <input class="btn btn-default" type="submit" value="Movimentação Geral" name="Movimentacao" />
                <?php
            }
            ?>
        </div>
        <div class="col-sm-2">
            <?php
            if (!empty($_POST['mes'])) {
                ?>
                <form target = "_blank" action = "<?php echo HOME_URI ?>/transp/transportadospdf ?>" method = "POST">

                    <input type="hidden" name="mes" value="<?php echo $_POST['mes'] ?>" />
                    <input type="hidden" name="id_em" value="<?php echo $id_em ?>" />
                    <button class = "btn btn-info">
                        Alunos Transportados
                    </button>
                </form>
                <?php
            } else {
                ?>
                <input class="btn btn-default" type="submit" value="Alunos Transportados" name="Transportados" />
                <?php
            }
            ?>
        </div>
        <div class="col-sm-2">
            <?php
            if (!empty($_POST['mes'])) {
                ?>
                <form target = "_blank" action = "<?php echo HOME_URI ?>/transp/controlegeralpdf ?>" method = "POST">
                    <input type="hidden" name="mes" value="<?php echo $_POST['mes'] ?>" />
                    <button class = "btn btn-info">
                        Resumo Geral
                    </button>
                </form>
                <?php
            } else {
                ?>
                <input class="btn btn-default" type="submit" value="Resumo Geral" name="Resumo" />
                <?php
            }
            ?>
        </div>
        <div class="col-sm-2">
            <?php
            if (!empty($_POST['mes'])) {
                ?>
                <form target = "_blank" action = "<?php echo HOME_URI ?>/transp/listageralalunopdf ?>" method = "POST">
                    <input type="hidden" name="mes" value="<?php echo $_POST['mes'] ?>" />
                    <button class = "btn btn-info">
                        Lista Geral de Alunos
                    </button>
                </form>
                <?php
            } else {
                ?>
                <input class="btn btn-default" type="submit" value="Lista Geral de Alunos" name="Lista" />
                <?php
            }
            ?>
        </div>

        <div class="col-sm-1"></div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-1"></div>
        <div  class="col-sm-2">
            <?php
            if (!empty($_POST['mes'])) {
                ?>
                <form target = "_blank" action = "<?php echo HOME_URI ?>/transp/tranpsecundariopdf ?>" method = "POST">

                    <input type="hidden" name="mes" value="<?php echo $_POST['mes'] ?>" />

                    <button class = "btn btn-info">
                        Transporte Secundário
                    </button>
                </form>
                <?php
            } else {
                ?>
                <input class="btn btn-default" type="submit" value="Transporte Secundário" name="Secundario" />
                <?php
            }
            ?>
        </div>
        <div class="col-sm-2">
            <?php
            if (!empty($_POST['mes'])) {
                ?>
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                    <?php
                    $sql = transporte::arquivoexcel($_POST['mes']);
                    ?>
                    <input type="hidden" name="mes" value="<?php echo $_POST['mes'] ?>" />
                    <input type="hidden" name="tokenSql" value="<?php echo tool::tokenSql() ?>" />
                    <input type="hidden" name="sql" value="<?php echo $sql ?>" />
                    <button class = "btn btn-info">
                        Lista Geral de Alunos(Excel)
                    </button>
                </form>
                <?php
            } else {
                ?>
                <input class="btn btn-default" type="submit" value="Lista Geral de Alunos(Excel)" name="Excel" />
                <?php
            }
            ?>
        </div>
        <div class="col-sm-2">
            <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                <?php
                $sql = transporte::wlinhaempresa();
                ?>
                <input type="hidden" name="tokenSql" value="<?php echo tool::tokenSql() ?>" />
                <input type="hidden" name="sql" value="<?php echo $sql ?>" />
                <button class = "btn btn-info">
                    Lista Linha Empresa(Excel)
                </button>
            </form>

        </div>
    </div>
    <?php
}
?>