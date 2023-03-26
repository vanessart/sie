<?php
if (!defined('ABSPATH'))
    exit;
$cpfs = [];
$resp = $aluno->responsaveis();
if ($resp) {
    foreach ($resp as $v) {
        $cpfs[] = $v['cpf'];
    }
}
$respAnt = sql::get(['gt_retirada', 'tipo_doc'], '*', ['fk_id_pessoa' => $id_pessoa, '>' => 'n_re']);

css::switchButton();
?>
<style>
    #label {
        background-color: #3498db;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        padding: 6px;
        width: 60px;
    }  
</style>
<br />
<table class="table table-bordered table-hover table-striped">
    <tr>
        <td>
            Nome
        </td>
        <td>
            Relação
        </td>
        <td>
            CPF
        </td>
        <td>
            Telefone
        </td>
        <td>
            Foto
        </td>
        <td>
            APP
        </td>
        <td>
            Retirada
        </td>
    </tr>
    <?php
    if ($resp) {
        foreach ($resp as $v) {
            ?>
            <tr>
                <td>
                    <?= $v['n_pessoa'] ?>
                </td>
                <td>
                    <?= $v['n_rt'] ?>
                </td>
                <td>
                    <?= $v['cpf'] ?>
                </td>
                <td>
                    <?php
                    if ($v['tel']) {

                        foreach ($v['tel'] as $t) {
                            echo '<p>' . $t['n_tt'] . ': ' . (empty($t['ddd']) ? '' : '(' . $t['ddd'] . ') ') . $t['num'] . '</p>';
                        }
                    } else {
                        echo 'Não há telefone cadastrado';
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if (file_exists(ABSPATH . "/pub/fotos/" . $v['id_pessoa'] . ".jpg")) {
                        ?>
                        <a  onclick="verFoto()" href="<?php echo HOME_URI ?>/pub/fotos/<?php echo $v['id_pessoa'] ?>.jpg" target="frame">
                            <img style="width: 60px" src="<?php echo HOME_URI ?>/pub/fotos/<?php echo $v['id_pessoa'] ?>.jpg?ass=<?php echo uniqid() ?>" alt="foto"/>
                        </a>
                        <?php
                    } else {
                        ?>
                        <img style="width: 60px" src="<?php echo HOME_URI ?>/includes/images/anonimo.jpg" alt = "aluno"/>
                        <?php
                    }
                    ?>
                    <div id="label" onclick="webcam(<?php echo $v['id_pessoa'] ?>)">
                        Trocar
                    </div>
                </td>
                <td>
                    <input onchange="lanc(<?= $v['id_pessoa'] ?>, 'app', this)" <?= $v['app'] == 1 ? 'checked' : '' ?> id="switch-shadow-app<?= $v['id_pessoa'] ?>" class="switch switch--shadow" type="checkbox">
                    <label for="switch-shadow-app<?= $v['id_pessoa'] ?>"></label>
                </td>
                <td>
                    <input onchange="lanc(<?= $v['id_pessoa'] ?>, 'retirada', this)" <?= $v['retirada'] == 1 ? 'checked' : '' ?> id="switch-shadow-retirada<?= $v['id_pessoa'] ?>" class="switch switch--shadow" type="checkbox">
                    <label for="switch-shadow-retirada<?= $v['id_pessoa'] ?>"></label>
                </td>
            </tr>
            <?php
        }
    }
    foreach ($respAnt as $v) {
        if (!in_array($v['doc'], $cpfs)) {
            $temAntigo = 1;
        }
    }
    if (!empty($temAntigo)) {
        ?>
        <tr>
            <td colspan="7" style="text-align: center; font-weight: bold">
                Importado do SIEB antigo
            </td>
        </tr>
        <?php
        foreach ($respAnt as $v) {
            if (!in_array($v['doc'], $cpfs)) {
                ?>
                <tr>
                    <td>
                        <?= $v['n_re'] ?>
                    </td>
                    <td>
                        <?= $v['parente'] ?>
                    </td>
                    <td>
                        <?= $v['doc'] ?>
                    </td>
                    <td>
                        <?= $v['telefones'] ?>
                    </td>
                    <td></td>
                    <td>

                    </td>
                    <td>
                        <form id="resp_<?= $v['id_re'] ?>" method="POST">
                            <?=
                            formErp::hiddenToken('gt_retirada', 'delete')
                            . formErp::hidden([
                                '1[id_re]' => $v['id_re'],
                                'activeNav' => 6,
                                'id_pessoa' => $id_pessoa])
                            ?>
                        </form>
                        <div  onclick="if (confirm('Excluir?')) {
                                    resp_<?= $v['id_re'] ?>.submit()
                                }" >
                            <input checked id="switch-shadow-retirada<?= $v['id_pessoa'] ?>" class="switch switch--shadow" type="checkbox">
                            <label for="switch-shadow-retirada<?= $v['id_pessoa'] ?>"></label>
                        </div>
                    </td>
                </tr>
                <?php
            }
        }
    }
    ?>
</table>

<form action="<?= HOME_URI ?>/sed/def/fotoWebResp.php" target="frame" id="formFrame" method="POST">
    <input type="hidden" name="id_pessoa" value="<?= $id_pessoa ?>" />
    <input id="id_pessoa_resp" type="hidden" name="id_pessoa_resp" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 80vh; border: none; margin: 0 auto" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function lanc(id, campo, i) {
        if (i.checked) {
            sit = 1;
        } else {
            sit = 0;
        }
        dados = 'sit=' + sit
                + '&id_pessoa=<?= $id_pessoa ?>'
                + '&id_pessoa_resp=' + id
                + '&campo=' + campo;
        fetch('<?= HOME_URI ?>/sed/appRetirada', {
            method: "POST",
            body: dados,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
                .then(resp => resp.text())
                .then(resp => {
                    if (resp != 1) {
                        alert('Houve um erro na conexão. Por Favor, tente mais tarde')
                    }
                });
    }

    function verFoto() {
        $('#myModal').modal('show');
        $('.form-class').val('');
    }

    function webcam(id) {
        document.getElementById('id_pessoa_resp').value = id;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>