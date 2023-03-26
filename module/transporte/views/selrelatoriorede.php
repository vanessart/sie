<?php
if (!defined('ABSPATH'))
    exit;

$id_em = filter_input(INPUT_POST, 'id_em', FILTER_SANITIZE_NUMBER_INT);
$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_STRING);
if (empty($mes)) {
    $mes = date("m");
}
?>
<div class="body">
<div class="row form-control-plaintext">
    <div class="col-sm-12">
        <div class="fieldTop">
            Lista Rede
         </div>
    </div>
</div>

<?php
$a = dataErp::meses();

if (user::session('id_nivel') == 10) {
    ?>
    <form method="POST">
        <div class="row">
            <br /><br />
            <div class="col-sm-2"></div>
            <div class="col-sm-3">
                <?php
                echo formErp::select('mes', $a, 'Selecionar Mês', @$mes);
                ?>
            </div>
            <div class="col-sm-3">
                <?php
                echo formErp::selectDB('transporte_empresa', 'id_em', 'Empresa', $id_em);
                ?>
            </div>
            <div class="col-sm-2">
                <?php echo formErp::button('Continuar') ?>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </form>
    <br /><br /><br /><br />
    <div class="row">
        <div class="col-sm-1">

        </div>
        <div class="col-sm-2">
            <?php
            if (!empty($_POST['mes'])) {
                ?>
                <form target="_blank" action="<?php echo HOME_URI ?>/transporte/movimentacaopdf" method = "POST">

                    <input type="hidden" name="mes" value="<?php echo $_POST['mes'] ?>" />
                    <input type="hidden" name="id_em" value="<?php echo $id_em ?>" />

                    <button class="btn btn-info">
                        Movimentação Geral
                    </button>
                </form>
                <?php
            } else {
                ?>
                <input class="btn btn-outline-info" type="submit" value="Movimentação Geral" name="Movimentacao" />
                <?php
            }
            ?>
            <br /><br />
            <?php
            if (!empty($_POST['mes'])) {
                ?>
                <form target="_blank" action="<?php echo HOME_URI ?>/transporte/tranpsecundariopdf" method = "POST">

                    <input type="hidden" name="mes" value="<?php echo $_POST['mes'] ?>" />

                    <button class="btn btn-info">
                        Transporte Secundário
                    </button>
                </form>
                <?php
            } else {
                ?>
                <input class="btn btn-outline-info" type="submit" value="Transporte Secundário" name="Secundario" />
                <?php
            }
            ?>
        </div>
        <div class="col-sm-2">
            <?php
            if (!empty($_POST['mes'])) {
                ?>
                <form target="_blank" action="<?php echo HOME_URI ?>/transporte/transportadospdf" method = "POST">

                    <input type="hidden" name="mes" value="<?php echo $_POST['mes'] ?>" />
                    <input type="hidden" name="id_em" value="<?php echo $id_em ?>" />
                    <button class="btn btn-info">
                        Alunos Transportados
                    </button>
                </form>
                <?php
            } else {
                ?>
                <input class="btn btn-outline-info" type="submit" value="Alunos Transportados" name="Transportados" />
                <?php
            }
            ?>
            <br /><br />
            <?php
            if (!empty($_POST['mes'])) {
                ?>
                <form target="_blank" action="<?php echo HOME_URI ?>/transporte/linhasviagenspdf" method = "POST">

                    <input type="hidden" name="mes" value="<?php echo $_POST['mes'] ?>" />

                    <button class="btn btn-info">
                        Linhas / Viagens
                    </button>
                </form>
                <?php
            } else {
                ?>
                <input class="btn btn-outline-info" type="submit" value="Linhas / Viagens" name="LinhasViagens" />
                <?php
            }
            ?>
        </div>
        <div class="col-sm-2">
            <?php
            if (!empty($_POST['mes'])) {
                ?>
                <form target="_blank" action="<?php echo HOME_URI ?>/transporte/controlegeralpdf" method = "POST">

                    <input type="hidden" name="mes" value="<?php echo $_POST['mes'] ?>" />
                    <button class="btn btn-info">
                        Resumo Geral
                    </button>
                </form>
                <?php
            } else {
                ?>
                <input class="btn btn-outline-info" type="submit" value="Resumo Geral" name="Resumo" />
                <?php
            }
            ?>
            <br /><br />
            <?php
            if (!empty($_POST['mes'])) {
                ?>
                <form target="_blank" action="<?php echo HOME_URI ?>/transporte/linhasviagens" method = "POST">

                    <input type="hidden" name="mes" value="<?php echo $_POST['mes'] ?>" />

                    <button class="btn btn-info">
                        Linhas / Viagens (Excel)
                    </button>
                </form>
                <?php
            } else {
                ?>
                <input class="btn btn-outline-info" type="submit" value="Linhas / Viagens (Excel)" name="LinhasViagens" />
                <?php
            }
            ?>
        </div>
        <div class="col-sm-2">
            <?php
            if (!empty($_POST['mes'])) {
                ?>
                <form target="_blank" action="<?php echo HOME_URI ?>/transporte/listageralalunopdf" method = "POST">
                    <input type="hidden" name="mes" value="<?php echo $_POST['mes'] ?>" />
                    <button class="btn btn-info">
                        Lista Geral de Alunos
                    </button>
                </form>
                <?php
            } else {
                ?>
                <input class="btn btn-outline-info" type="submit" value="Lista Geral de Alunos" name="Lista" />
                <?php
            }
            ?>
        </div>
        <div class="col-sm-2">
            <?php
            if (!empty($_POST['mes'])) {
                ?>
                <form target="_blank" action="<?php echo HOME_URI ?>/transporte/listageralaluno" method="POST">
                    <input type="hidden" name="mes" value="<?php echo $_POST['mes'] ?>" />
                    <button class="btn btn-info">
                        Lista Geral de Alunos(Excel)
                    </button>
                </form>
                <?php
            } else {
                ?>
                <input class="btn btn-outline-info" type="submit" value="Lista Geral de Alunos(Excel)" name="Excel" />
                <?php
            }
            ?>
        </div>
        <div class="col-sm-3">

        </div>
    </div>
    <?php
}
?>
</div>