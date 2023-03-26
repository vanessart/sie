<?php
$ciclos = sql::get('ge_ciclos', 'n_ciclo, id_ciclo', ['fk_id_curso' => @$_REQUEST['id_curso'], '>' => 'n_ciclo']);
$qtLetiva = sql::get('ge_cursos', 'qt_letiva', ['id_curso' => @$_REQUEST['id_curso']], 'fetch')['qt_letiva'];
$pl = explode('|', @$_POST['periodo']);
?>
<br /><br />
<form method="POST">
    <div class="row">
        <div class="col-md-12 text-center" style="font-size: 18px">
            <?php echo $disciplina ?>
            <?php echo '&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp; Grupo: ' . $grupo ?>
            <?php echo '&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp; Competência: ' . $competencia ?>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-md-12">
            <?php formulario::input('1[n_hab]', 'Habilidade') ?>
        </div>
    </div>    
    <br /><br />
    <div class="row">
        <div class="col-md-3">
            <?php formulario::selectNum('1[ordem]', [1, 50], 'Ordem', empty(@$_POST['ordem']) ? @$ordem : @$_POST['ordem']) ?>
        </div>
        <div class="col-md-4">
            <?php formulario::input('1[cod_hab]', 'Código') ?>
        </div>
        <div class="col-md-5">
            <?php formulario::selectDB('coord_eixo', '1[fk_id_ei]', 'Eixo Cognitivo', @$_POST['fk_id_ei'], NULL, NULL, NULL, NULL, ['fk_id_curso' => @$_REQUEST['id_curso']]) ?>
        </div>
    </div>    
    <br /><br />
    <div class="row">
        <div class="col-md-12">
            <textarea name="1[obs_hab]" style="width: 100%" placeholder="Descrição"><?php echo @$_POST['obs_hab'] ?></textarea>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-md-4">
            <?php echo formulario::selectNum('1[qt_hab]', [1, 20], 'Qt. Avaliações', empty($_POST['qt_hab']) ? 1 : $_POST['qt_hab']) ?>
        </div>
        <div class="col-md-4">
            <?php formulario::checkbox('1[importante]', 1, 'Importante') ?>
        </div>

        <div class="col-md-4">
            <?php echo formulario::selectNum('1[peso]', 20, 'Peso', empty($_POST['qt_hab']) ? 1 : $_POST['qt_hab']) ?>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-md-3">
            <?php formulario::input('1[ini]', 'Início da observação*', NULL, @$_POST['ini'], 'id="ini" onblur="confMensao(\'ini\')"') ?>
        </div>
        <div class="col-md-3">
            <?php formulario::input('1[fim]', 'Final da observação*', NULL, @$_POST['fim'], 'id="fim" onblur="confMensao(\'fim\')"') ?>
        </div>
        <div class="col-md-6" >
            <div class="btni"style="padding: 4px;text-align: center" >
                * Formato: <strong>anos,mês</strong> - exemplos: <strong>1,2</strong>; <strong>0,6</strong>; <strong>3</strong>; <strong>4,10</strong>
            </div>
        </div>
    </div>
    <br /><br />
    <style>
        th{
            background-color: black;
            color: white;
        }
    </style>
    <table class="table table-bordered table-responsive table-striped">
        <thead>
            <tr>
                <td style="text-align: center; border: solid black 1px; font-size: 18px" colspan="<?php echo count($ciclos) + 1 ?>">
                    Período de Aferimento da Habilidade
                </td>
            </tr>
            <tr>
                <th>
                    Ciclos
                </th>
                <?php
                foreach ($ciclos as $c) {
                    ?>
                    <th>
                        <label>
                            <input type="checkbox" name="chkAll" onClick="checkAll(this, '<?php echo $c['id_ciclo'] ?>')" />
                            <?php echo $c['n_ciclo'] ?>
                        </label>
                    </th>
                    <?php
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($p = 1; $p <= $qtLetiva; $p++) {
                ?>
                <tr>
                    <td>
                        <?php echo $p ?>&nbsp;&nbsp; Unid. Letiva
                    </td>
                    <?php
                    foreach ($ciclos as $c) {
                        ?>
                        <td>
                            <label>
                                <input <?php echo in_array($c['id_ciclo'] . '-' . $p, $pl) ? 'checked' : '' ?> class="<?php echo $c['id_ciclo'] ?>" type="checkbox" name="pl[<?php echo $c['id_ciclo'] ?>-<?php echo $p ?>]" value="<?php echo $c['id_ciclo'] ?>-<?php echo $p ?>" />
                                &nbsp;&nbsp;<?php echo $p ?>º PL
                            </label>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <div class="row">
        <div class="col-md-6 text-center">
            <input onclick="$('#limp').submit()" class="btn btn-warning" type="button" value="Limpar" />
        </div>
        <div class="col-md-6 text-center">
            <?php echo DB::hiddenKey('cadHab') ?>
            <input type="hidden" name="id_comp" value="<?php echo @$_REQUEST['id_comp'] ?>" />
            <input type="hidden" name="id_gr" value="<?php echo @$_REQUEST['id_gr'] ?>" />
            <input type="hidden" name="1[fk_id_comp]" value="<?php echo @$_REQUEST['id_comp'] ?>" />
            <input type="hidden" name="1[id_hab]" value="<?php echo @$_REQUEST['id_hab'] ?>" />
            <input type="hidden" name="id_curso" value="<?php echo @$_REQUEST['id_curso'] ?>" />
            <input class="btn btn-success" type="submit" value="Salvar" />
        </div>
    </div>
</form>
<form id="limp" method="POST">
    <div class="col-md-6 text-center">
        <input type="hidden" name="id_gr" value="<?php echo @$_REQUEST['id_gr'] ?>" />
        <input type="hidden" name="modal" value="1" />
        <input type="hidden" name="id_curso" value="<?php echo @$_REQUEST['id_curso'] ?>" />
        <input type="hidden" name="id_comp" value="<?php echo @$_REQUEST['id_comp'] ?>" />
    </div>
</form>
<?php
for ($c = 0; $c <= 17; $c++) {
    @$valores .= "'" . $c . "', ";
    for ($m = 1; $m < 12; $m++) {
        @$valores .= "'" . $c . "," . $m . "', ";
    }
}
?>
<script>
    function confMensao(id) {
        var v = [<?php echo $valores ?>];
        var valor = document.getElementById(id).value;
        var confere = null;
        var i;
        for (i = 0; i < v.length; i++) {
            if (v[i] == valor)
            {
                confere = 1;
            }
        }
        if (confere !== 1 && valor !== '') {
            alert("Valor inválido");
            document.getElementById(id).style.backgroundColor = "#F490B1";
        } else {
            document.getElementById(id).style.backgroundColor = "white";
        }
    }

    function checkAll(o, cl) {
        var boxes = document.getElementsByClassName(cl);
        for (var x = 0; x < boxes.length; x++) {
            var obj = boxes[x];
            if (obj.type == "checkbox") {
                if (obj.name != "chkAll")
                    obj.checked = o.checked;
            }
        }
    }
</script>