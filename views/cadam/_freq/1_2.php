<?php
@$cadam = current(current($model->cadampesPorEscola(1, $id_cargo, $diaSemana . $periodo, 1, $idJa1, implode(',', $idnao))));

tool::modalInicio('width:95%', NULL, 'cadcadrede');
if (!empty(@$cadam['id_cad'])) {
    $id_cad = @$cadam['id_cad'];
} else {
    $convoca = sql::get('cadam_convoca', '*', ['id_con' => @$_POST['id_con']], 'fetch');
    $id_cad = $convoca['fk_id_cad'];
}
?>
<form method="POST">
    <?php echo formulario::hidden($_POST) ?>
    <div class="row">
        <div class="col-sm-12" style="text-align: center; font-size: 20px">
            Próximo Cadampe disponível para <?php echo $nomeDiaSemana[$diaSemana] ?>, dia <?php echo $dia ?> de <?php echo data::mes($mesSet) ?> de <?php echo date("Y") ?>
        </div>
    </div>
    <br /><br />
    <table class="table table-bordered">
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
            No cadastro de recusa patati patata
            <br />
            <textarea name="justifica" style="width: 100%; height: 40px"></textarea>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div style="text-align: center" class="col-sm-12">
            <input type="hidden" name="id_cad" value="<?php echo @$id_cad ?>" />
            <input type="hidden" name="id_con" value="<?php echo @$_POST['id_con'] ?>" />
            <input class="btn btn-success" name="solicitarCapampe" type="submit" value="Continuar" />
        </div>
    </div>
</form>
<br /><br />
<?php
tool::modalFim();
