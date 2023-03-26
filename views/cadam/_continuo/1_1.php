<?php
if (empty($_POST['editar'])) {
    @$cadam = current(cadamp::cadampesPorEscola($id_inst, $id_cargo));
} else {
    @$cadam = cadamp::get(@$_POST['id_cad']);
    $convocacao = sql::get('cadam_convoca', '*', ['id_con' => @$_POST['id_con']], 'fetch');
}
$id_cad = @$cadam['id_cad'];
?>
<br /><br />
<div class="fieldBorder2">
    <form style="text-align: right" method="POST">
        <?php echo formulario::hidden($hidden) ?>
        <input class="btn btn-warning" type="submit" value="Fechar" />
    </form>
    <form method="POST">
        <?php echo formulario::hidden($hidden) ?>
        <div style="text-align: center; font-size: 18px">
            Próximo Cadampe  disponível
        </div>
        <br /><br />
        <table style="font-size: 18px; width: 100%">
            <tr>
                <th>
                    Cad. Municipal
                </th>
                <th>
                    Nome
                </th>
                <th>
                    Telefones
                </th>
                <th>
                    E-mail
                </th>
            </tr>
            <tr>
                <td>
                    <?php echo $cadam['cad_pmb'] ?>
                </td>
                <td>
                    <?php echo $cadam['n_pessoa'] ?>
                </td>
                <td>
                    <?php echo $cadam['tel1'] . ' - ' . @$cadam['tel2'] . ' - ' . @$cadam['tel3'] ?>
                </td>
                <td>
                    <?php echo $cadam['email'] ?>
                </td>
            </tr>
        </table>
        <br /><br />
        <div class="row">
            <div class="col-sm-4 text-center">
                <label>
                    <input required  <?php echo @$convoca['contato'] == 1 ? 'checked' : '' ?> onclick="document.getElementById('just').style.display = 'none';" type="radio" name="contato" value="1" />
                    Contactado com sucesso e aceitou as aulas
                </label>
            </div>
            <div class="col-sm-4 text-center">
                <label>
                    <input required <?php echo @$convoca['contato'] == 2 ? 'checked' : '' ?> onclick="document.getElementById('just').style.display = '';" type="radio" name="contato" value="2" />
                    Não foi possível Contactar
                </label>
            </div>
            <div class="col-sm-4 text-center">
                <label>
                    <input required <?php echo @$convoca['contato'] == 3 ? 'checked' : '' ?> onclick="document.getElementById('just').style.display = '';" type="radio" name="contato" value="3" />
                    Contactado com sucesso, mas recusou as aulas
                </label>
            </div>
        </div>
        <div class="row">
            <div  id="just" style="display: none" class="col-sm-12">
                No cadastro das tentativas fracassadas deverão constar ainda: solicitante, data, horário, números chamados, sms, ou outros meios de comunicação tentados.
                <br />
                <textarea name="justifica" style="width: 100%; height: 40px"><?php echo @$convocacao['justifica'] ?></textarea>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div style="text-align: center" class="col-sm-12">
                <input type="hidden" name="uniqid" value="<?php echo $uniqid ?>" />
                <input type="hidden" name="id_cad" value="<?php echo @$id_cad ?>" />
                <input type="hidden" name="id_con" value="<?php echo @$_POST['id_con'] ?>" />
                <input class="btn btn-success" name="solicitarCapampe" type="submit" value="Continuar" />
            </div>
        </div>
    </form>
</div>
<br /><br />
