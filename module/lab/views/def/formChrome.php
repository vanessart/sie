<?php
if (!defined('ABSPATH'))
    exit;
$sit = sql::idNome('lab_chrome_status');
$id_ch = filter_input(INPUT_POST, 'id_ch', FILTER_SANITIZE_NUMBER_INT);
unset($sit[3]);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
if ($id_ch) {
    $chrome = sql::get('lab_chrome', '*', ['id_ch' => $id_ch], 'fetch');
    unset($sit[0]);
    unset($sit[4]);
}
?>
<div class="body">
    <div class="fieldTop">
        ChromeBook <?= @$chrome['serial'] ?>
    </div>
    <form action="<?= HOME_URI ?>/lab/chrome" target="_parent" method="POST">
        <div class="row">
            <div class="col">
                <?php
                if (@$chrome['fk_id_cs'] == 3) {
                    echo formErp::input(null, 'Situação', 'Emprestado para Aluno', ' readonly');
                    echo formErp::hidden(['1[fk_id_cs]' => 3]);
                } elseif (@$chrome['fk_id_cs'] == 4) {
                    $sql = " SELECT s.n_ms FROM lab_chrome_manutencao m "
                            . " JOIN lab_chrome_manutencao_status s on s.id_ms = m.fk_id_ms AND m.fk_id_ms != 5 "
                            . " WHERE `fk_id_ch` = $id_ch";
                    $query = pdoSis::getInstance()->query($sql);
                    @$n_ms = $query->fetch(PDO::FETCH_ASSOC)['n_ms'];
                    if (empty($n_ms)) {
                        $n_ms = 'Quebrado (enviado para manutenção)';
                    }
                    echo formErp::input(null, 'Situação', $n_ms, ' readonly');
                    echo formErp::hidden(['1[fk_id_cs]' => 4]);
                } else {
                    echo formErp::select('1[fk_id_cs]', $sit, 'Situação', @$chrome['fk_id_cs'], null, null, ' required ');
                }
                ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <div class="col">
                    <?= formErp::selectNum('1[carrinho]', [1, 50], 'Carrinho', @$chrome['carrinho'], null, null, ' required ') ?>
                </div>
            </div>
        </div>
        <br />
        <div id="aluno" class="border" style="display: none">
            <div class="row">
                <div class="col">
                    <?= formErp::input('1[fk_id_pessoa]', 'RA', @$chrome['fk_id_pessoa'], 'id="ra"') ?>
                </div>
                <div class="col">
                    <input type="hidden" id="fk_id_cd" name="1[fk_id_cd]" value="1" />
                    <button onclick="nome()" type="button" class="btn btn-warning">
                        buscar Aluno
                    </button>
                </div>
            </div>
            <br /><br />
            <div id="nomeAluno" style="font-weight: bold"></div>
        </div>
        <br /><br />
        <div style="text-align: center">
            <?=
            formErp::hidden([
                '1[id_ch]' => $id_ch,
                '1[fk_id_pessoa_lanc]' => toolErp::id_pessoa(),
                '1[fk_id_inst]' => @$id_inst,
                'id_inst' => @$id_inst
            ])
            . formErp::hiddenToken('chromeEdit')
            . formErp::button('Salvar')
            ?>

        </div>
    </form>
</div>
<script>
    function fk_id_cs(id) {
        let   ra = document.getElementById('ra')
        destino = document.getElementById('fk_id_cd');
        aluno = document.getElementById('aluno');
        ra.removeAttribute('required', 'required');
        aluno.style.display = 'none';
        if (id == 1 || id == 2) {
            destino.value = 1;
        } else if (id == 3) {
            destino.value = 3;
            aluno.style.display = '';
            ra.setAttribute('required', 'required')
        } else if (id == 4) {
            destino.value = 4;
        } else if (id == 5) {
            destino.value = 5;
        } else if (id == 8) {
            destino.value = 6;
        } else {
            destino.value = 0;
        }
    }
    function nome() {
        let ra = document.getElementById('ra').value;
        var dados = {
            ra: ra
        };
        $.post('<?php echo HOME_URI ?>/lab/nomeAluno', dados, function (retorna) {
            if (retorna) {
                document.getElementById('nomeAluno').innerHTML = 'Aluno: ' + retorna + '<br /><br />';
            }
        });
    }
</script>