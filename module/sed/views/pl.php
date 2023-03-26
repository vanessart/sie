<?php
if (!defined('ABSPATH'))
    exit;
$todos = filter_input(INPUT_POST, 'todos', FILTER_SANITIZE_NUMBER_INT);
?>
<div class="Body">
    <div class="fieldTop">
        Períodos Letivos
    </div>
    <div class="row">
        <div class="col-sm-6 text-center">
            <button class="btn btn-info" onclick="pl()">
                Novo Período
            </button>
        </div>
        <div class="col-sm-6 text-center">
            <form method="POST">
                <?php
                if (empty($todos)) {
                    ?>
                    <input type="hidden" name="todos" value="1" />
                    <input class="btn btn-warning" type="submit" value="Mostrar os Períodos Inativos" />
                    <?php
                } else {
                    ?>
                    <input class="btn btn-warning" type="submit" value="Ocultar os Períodos Inativos" />
                    <?php
                }
                ?>
            </form>
        </div>
    </div>
    <br /><br />
    <?php
    $model->relatPer();
    ?>
    <form action="<?= HOME_URI ?>/sed/def/formPl.php" id="form" target="frame" method="POST">
        <input type="hidden" name="id_pl" id="id_pl" value="" />
    </form>
    <?php
    toolErp::modalInicio();
    ?>
    <iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
    <script>
        function pl(id) {
        if (id) {
        document.getElementById("id_pl").value = id;
        } else {
        document.getElementById("id_pl").value = '';
        }
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
        }
    </script>
</div>