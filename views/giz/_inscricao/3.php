<?php
$aba = @$_POST['aba'];
if (empty($aba)) {
    $act1 = 'class="active"';
    $name = 'objgeral';
} elseif (@$aba == 2) {
    $act2 = 'class="active"';
    $name = 'objespec';
} elseif (@$aba == 3) {
    $act3 = 'class="active"';
    $name = 'justifica';
} elseif (@$aba == 4) {
    $act4 = 'class="active"';
    $name = 'metodo';
} elseif (@$aba == 5) {
    $act5 = 'class="active"';
    $name = 'cronograma';
}
?>
<br /><br /><br />
<div style="text-align: center; font-size: 18px">
    Preencha os cinco itens abaixo para que a Aba "Validação" seja liberada
</div>
<br /><br />
<ul class="nav nav-tabs">
    <li role="presentation" <?php echo @$act1 ?> >
        <a style="cursor: pointer" onclick="document.getElementById('ab').value = '';document.atv.submit()" href="#">Objetivos Gerais</a>
    </li>
    <li role="presentation" <?php echo @$act2 ?> >
        <a style="cursor: pointer" onclick="document.getElementById('ab').value = '2';document.atv.submit()" href="#">Objetivos Específicos</a>
    </li>
    <li role="presentation" <?php echo @$act3 ?> >
        <a style="cursor: pointer" onclick="document.getElementById('ab').value = '3';document.atv.submit()" href="#">Justificativa</a>
    </li>
    <li role="presentation" <?php echo @$act4 ?> >
        <a style="cursor: pointer" onclick="document.getElementById('ab').value = '4';document.atv.submit()" href="#">Metodologia</a>
    </li>
    <li role="presentation" <?php echo @$act5 ?> >
        <a style="cursor: pointer" onclick="document.getElementById('ab').value = '5';document.atv.submit()" href="#">Cronograma de Atividades</a>
    </li>
</ul>
<br /><br />
<form name="atv" method="POST">
    <?php echo formulario::textarea('1['.$name.']', @$proj[$name], NULL, 1) ?>
    <input id="ab" type="hidden" name="aba" value="<?php echo $aba ?>" />
    <input type="hidden" name="activeNav" value="3" />
    <?php echo formulario::hidden($hidden) ?>
    <?php echo DB::hiddenKey('itens') ?>
    <input type="hidden" name="1[id_prof]" value="<?php echo $proj['id_prof'] ?>" />
    <br /><br />
    <div style="text-align: center">
        <input class="btn btn-success" type="submit" value="Salvar" />
    </div>
</form>