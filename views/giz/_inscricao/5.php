<br /><br /><br />
<?php
if ($proj['gestor'] == 1) {
    ?>
    <div class="alert alert-info" style="text-align: center; font-size: 18px">
        O seu projeto está esperando a avaliação da Equipe de Gestão da sua escola.
    </div>
    <?php
} elseif ($proj['gestor'] == 2) {
    ?>
    <div class="alert alert-info" style="text-align: center; font-size: 18px">
        O seu projeto foi aprovado pela Equipe de Gestão da sua escola e está participando do Premio Giz de Ouro 2018
    </div>
    <?php
} else {
    ?>
    <br /><br />
    <div style="text-align: center">
        <form target="_blank" action="<?php echo HOME_URI ?>/giz/projeto" method="POST">
            <input type="hidden" name="id_prof" value="<?php echo $proj['id_prof'] ?>" />
            <input class="btn btn-success" type="submit" value="Imprimir Projeto" />
        </form>  
    </div>
    <br /><br />
    <form method="POST">
        <input type="hidden" name="activeNav" value="5" />
        <?php echo formulario::hidden($hidden) ?>
        <?php echo DB::hiddenKey('giz_prof', 'replace') ?>
        <input type="hidden" name="1[id_prof]" value="<?php echo $proj['id_prof'] ?>" />
        <input type="hidden" name="1[gestor]" value="1" />
        <br /><br />
        <div style="text-align: center">
            <input id="conf"  class="btn btn-warning" type="submit" value="Enviar a minha Inscrição para a Equipe de Gestão" />
        </div>
    </form>
    <?php
}
?>
<script>

    function concordo() {
        if (document.getElementById('conc').checked == true) {
            document.getElementById('conf').disabled = false;
        } else {
            document.getElementById('conf').disabled = true;
        }
    }
</script>
