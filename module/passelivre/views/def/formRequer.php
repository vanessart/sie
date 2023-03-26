<?php
if (!defined('ABSPATH'))
    exit;
$cie = filter_input(INPUT_POST, 'cie', FILTER_SANITIZE_STRING);
@$id_inst = sql::get('ge_escolas', 'fk_id_inst', ['cie_escola' => $cie], 'fetch')['fk_id_inst'];
if (!empty($id_inst)) {
    $ras = sql::get([
                'ge_turma_aluno', 'ge_turmas',
                'ge_periodo_letivo', 'pessoa'],
                    ' concat(\' RA: \', ra, \' - \', n_pessoa) as n_ra, ra as id_ra, ra_uf',
                    ['ge_turmas.fk_id_inst' => $id_inst, 'at_pl' => 1, '>' => 'n_pessoa']);
    $raSel = toolErp::idName($ras);
    $raJson = json_encode(toolErp::idName($ras, 'id_ra', 'ra_uf'));
}
?>
<div class="body">
    <div class="row">
        <div class="col">
            <?php
            if (empty($id_inst)) {
                echo formErp::input('ra', 'RA (SED)', null, 'id="ra_"');
            } else {
                echo formErp::select('ra', $raSel, 'RA (SED)', null, null, null, null, null, 1);
            }
            ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('uf', 'UF (SED)', null, 'id="uf"') ?>
        </div>
        <div class="col">
            <button onclick="sed()" class="btn btn-success" type="button">
                Pesquisar 
            </button>
        </div>
    </div>
    <br />
    <div id="alert" class="alert alert-warning" style="display: none"></div>
    <div class="row">
        <div class="col text-center">
            <form action="<?= HOME_URI ?>/passelivre/requer" method="POST" target="_parent">
                <input type="hidden" name="cie" value="<?= $cie ?>" />
                <?= formErp::button('Cadastrar Manualmente') ?>
            </form>
        </div>
    </div>
    <br />
    <form  id="continue" action="<?= HOME_URI ?>/passelivre/requer" method="POST" target="_parent">
        <input type="hidden" name="cie" value="<?= $cie ?>" />
        <input type="hidden" name="id_passelivre" id="id_passelivre" value="" />
    </form>
</div>
<script>
    function sed() {
        document.getElementById('alert').style.display = '';
        document.getElementById('alert').classList.remove('alert-danger');
        document.getElementById('alert').classList.add('alert-warning');
        document.getElementById('alert').innerHTML = 'Se demorar muito ou der algum erro, você poderá usar a opção "Cadastrar Manualmente"<br /><img id="gif" src="<?= HOME_URI ?>/includes/images/aguarde.gif" alt="alt"/>';
        ra = document.getElementById('ra_').value;
        uf = document.getElementById('uf').value;
        const data = "ra=" + ra + '&uf=' + uf + '&cie=<?= $cie ?>';
        fetch('<?= HOME_URI ?>/passelivre/requerSED', {
            method: 'POST',
            body: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
                .then(resp => resp.text())
                .then(resp => {
                    if (isNaN(resp)) {
                        document.getElementById('alert').classList.remove('alert-warning');
                        document.getElementById('alert').classList.add('alert-danger');
                        document.getElementById('alert').innerHTML = resp;
                        //alert('Não foi possível baixar as informações. Por favor, continue manualmente');
                    } else {
                        document.getElementById('id_passelivre').value = resp;
                        console.log(resp);
                        document.getElementById('continue').submit();
                    }
                });
    }
<?php
if (!empty($id_inst)) {
    ?>
        function ra(id) {
            uf = <?= $raJson ?>;
            document.getElementById('uf').value = uf[id];
        }
    <?php
}
?>
</script>
