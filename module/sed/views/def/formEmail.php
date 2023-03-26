<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
if ($id_pessoa == 1) {
    ?>
    <div class="alert alert-danger">
        Do Marco não pode );
    </div>
    <?php
    exit();
}
$p = sql::get('pessoa', ' n_pessoa, sexo, emailgoogle ', ['id_pessoa' => $id_pessoa], 'fetch');
?>
<div class="body">
    <div style="text-align: center;">
        Redefinir o E-mail d<?php echo tool::sexoArt($p['sexo']) ?> professor<?php echo tool::sexoArt($p['sexo']) == 'o' ? '' : 'a' ?> 
    </div>
    <br />
    <div style="text-align: center; font-size: 18px; font-weight: bold">
        <?php echo $p['n_pessoa'] ?>? 
    </div>
    <br /><br /> 
    <form method="POST" target="_parent" action="<?= HOME_URI ?>/sed/senhaProf">
        <div class="row" style="width: 800px; margin: 0 auto">
            <div class="col-sm-9">
                <?= formErp::input('1[emailgoogle]', 'E-mail', $p['emailgoogle']) ?>
            </div>
            <div class="col-sm-1">
                <?=
                formErp::hidden(['1[id_pessoa]' => $id_pessoa])
                . form::button('Alterar')
                . formErp::hiddenToken('pessoa', 'ireplace')
                ?>
            </div>
        </div>
        <br />

    </form>
</div>
