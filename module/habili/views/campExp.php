<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="Body">
    <div class="fieldTop">
        Cadastro de Campo de Experiência 
    </div>
    <br />
    <div class="row">
        <div class="col-sm-4">
            <?php echo formErp::button('Novo Cadastro', null, "cad()"); ?>
        </div>
    </div>
    <br /><br />
    <?php
    $grup = coordena::campExp();
    $form['array'] = formErp::submitAcessarApagar($grup, 'coord_campo_experiencia',NULL, NULL, NULL, NULL, 'cad(%)');
    $form['fields'] = [
        'ID' => 'id_ce',
        'Grupo' => 'n_gh',
        'Camp. Experiêcia' => 'n_ce',
        'Ativo?' => 'at_ce',
        '||1' => 'apagar',
        '||2' => 'acessar'
    ];

    report::simple($form);

    if (!empty($_POST['id_ce'])) {
        $atv = 1;
        $hidden = $_POST;
    }
    javaScript::iframeModal('cad', HOME_URI . '/habili/def/formCampExp.php', @$atv, NULL, NULL, 'width: 100%; height: 50vh');
    ?>
</div>
