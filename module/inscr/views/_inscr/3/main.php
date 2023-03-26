<?php
if (!defined('ABSPATH'))
    exit;
if (empty($_SESSION['TMP']['CPF'])) {
    ?>
    <script>
        window.location'<?= home ?>/inscr/'.<?= @$form ?>
    </script>
    <?php
    exit();
}

$sql = " SELECT * FROM inscr_upload "
        . " WHERE fk_id_evento = $form "
        . " AND fk_id_cate in (0,$id_cate) "
        . "  ORDER BY fk_id_cate DESC, ordem ASC";
$ups = pdoSis::fetch($sql);
if ($ups) {
    foreach ($ups as $v) {
        if ($v['obrigatorio'] == 1) {
            $obrigatorio[$v['id_up']] = [$v['id_up']];
        }
    }
    $u = sqlErp::get('inscr_inscr_upload', '*', ['cpf' => $_SESSION['TMP']['CPF'], 'fk_id_cate' => $_SESSION['TMP']['CATE']]);
    if ($u) {
        foreach ($u as $v) {
            unset($obrigatorio[$v['fk_id_up']]);
            $upload[$v['fk_id_up']][] = $v;
        }
    }
    if (empty($obrigatorio)) {
        $atFim = 1;
    } else {
        $atFim = null;
    }
} else {
    $atFim = 1;
}

$atDoc = 1;

$camposVer = [
    'nome',
    'mae',
    'rg',
    'dt_nasc',
    'fk_id_civil',
    'sexo',
    'logradouro',
    'num',
    'bairro',
    'cidade',
    'uf',
    'cep',
        // 'rg_dig',
        // 'rg_oe',
        // 'dt_rg'
];
foreach ($camposVer as $v) {
    if (empty($dados[$v])) {
        $atDoc = null;
    }
}
if (empty($atDoc)) {
    toolErp::alertModal('Preencha os Dados Gerais para liberar as outras abas');
    $atFim = null;
    $atDoc = null;
} 
?>
<div class="row">
    <div class="col-10" style="text-align: center; font-weight: bold; font-size: 1.5em">
        <?= $cate['n_cate'] ?>
    </div>
    <div class="col">

    </div>
    <div class="col">
        <form id="form"></form>
        <button class="btn btn-danger" onclick=" $('#sair').modal('show');$('.form-class').val('');">
            Sair
        </button>

    </div>
</div>
<br />
<div class="body">
    <div class="border">
        <?= $cate['descr_cate'] ?>
    </div>
    <br /><br />
    <?php
    if (empty($_POST['activeNav']) && $_SESSION['TMP']['SIT'] != 1) {
        $_POST['activeNav'] = 3;
        $abaFim = 1;
    }
    if ($_SESSION['TMP']['SIT'] == 1) {
        $n2 = 'Anexar Documentos';
        $n3 = "Enviar Cadastro";
    } else {
        $n2 = 'Documentos Anexados';
        $n3 = "Cadastro Enviado";
    }
    if (in_array($_SESSION['TMP']['SIT'], [1, 2]) && $model->recurso != 1) {
        $abas[1] = ['nome' => "Dados Gerais", 'ativo' => 1, 'hidden' => ['id_cate' => $id_cate]];
        $abas[2] = ['nome' => $n2, 'ativo' => $atDoc, 'hidden' => ['id_cate' => $id_cate]];
        $abas[3] = ['nome' => $n3, 'ativo' => $atFim, 'hidden' => ['id_cate' => $id_cate]];
        $aba = report::abas($abas);
        if (!empty($abaFim)) {
            $aba = 3;
        }
        include ABSPATH . "/module/inscr/views/_inscr/$form/_main/$aba.php";
    } elseif (in_array($_SESSION['TMP']['SIT'], [3, 4]) && $model->recurso == 1) {
        include ABSPATH . "/module/inscr/views/_inscr/$form/recurso.php";
    } else {
        ?>
        <div class="alert alert-danger">
            Sistema Fechado
        </div>
        <br /><br />
        <div style="text-align: center">
            <form target="_blank" action="<?= HOME_URI ?>/inscr/pdf/protocolo" method="POST">
                <button type="submit" class="btn btn-primary">
                    Imprimir Ficha de Inscrição
                </button>
            </form>
        </div>
        <?php
    }
    ?>
</div>
<?php
toolErp::modalInicio(null, null, 'sair');
?>
<div class="alert alert-danger" style="text-align: center; font-weight: bold">
    <p>
        A sua INSCRIÇÃO só é concluida após clicar em "Concluir Inscrição" na aba "Enviar Cadastro".
    </p>
    <p>
        A sua SENHA é <span style="color: blue; font-size: 25px">"<?= $dados['pin'] ?>"</span>. &nbsp;&nbsp;&nbsp;Esta SENHA será necessária para o caso de recurso e outros procedimentos.
    </p>
    <p>
        Deseja sair do sistema?
    </p>
    <form>
        <div class="row">
            <div class="col text-center">
                <button class="btn btn-warning" type="button" data-bs-dismiss="modal">
                    Cancelar
                </button>
            </div>
            <div class="col text-center">
                <button class="btn btn-danger" type="submit">
                    Sair
                </button>
            </div>
        </div>
        <br />
    </form>
</div>
<?php
toolErp::modalFim();
?>