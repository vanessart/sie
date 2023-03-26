<?php
if (!defined('ABSPATH'))
    exit;
$tipoUp = sql::idNome('inscr_final_tipo_up');
$u = sql::get('inscr_final_up', '*', ['cpf' => $cpf]);
if ($u) {
    foreach ($u as $v) {
        $up[$v['fk_id_ftu']][] = $v;
    }
}
$tokenExcluir = formErp::token('excluirUpFinal')
?>
<style>
    /* Esconde o input */
    input[type='file'] {
        display: none
    }

    /* Aparência que terá o seletor de arquivo */
    .labelSet {
        background-color: #3498db;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        height: 37px;
        margin: 0 auto;
        padding: 9px;
        width: 300px;
        text-align: center
    }    
</style>
<div class="fieldTop">
    Todos os UPLOADS são OBRIGATÓRIOS
</div>
<?php
foreach ($tipoUp as $k => $v) {
    ?>
    <div class="border">
        <div class="row">
            <div class="col-6" style="font-size: 1.4em;">
                <?= $v ?>
            </div>
            <div class="col-3">
                <?php
                if (!in_array($dados['fk_id_vs'], [1, 2])) {
                    ?>
                    <form id="form<?= $k ?>" method="POST" enctype="multipart/form-data">
                        <?=
                        formErp::hidden([
                            'id_ftu' => $k,
                            'activeNav' => 2
                        ])
                        . formErp::hiddenToken('SalvaUpFinal')
                        ?>
                        <label class="btn btn-primary" id="label<?= $k ?>" for='selecao-arquivo<?= $k ?>'>Anexar Documento</label>
                        <input onchange="send('<?= $k ?>')" required="required" id='selecao-arquivo<?= $k ?>' type='file' name="arquivo">
                    </form>
                    <?php
                }
                ?>
            </div>
            <div class="col-3">
                <?php
                if (in_array($k, [1, 2, 3])) {
                    ?>
                    <form target="_blank" action="<?= HOME_URI ?>/pub/inscrOnline/<?= str_replace(' ', '_', $v) ?>.pdf">
                        <button class="btn btn-warning">
                            Baixa arquivo para preencher
                        </button>
                    </form>
                    <?php
                }
                ?>
            </div>
        </div>
        <br />
        <?php
        if (!empty($up[$k])) {
            ?>
            <table class="table table-bordered table-hover table-striped">
                <?php
                foreach ($up[$k] as $y) {
                    ?>
                    <tr>
                        <td>
                            <?= $y['nome_origin'] ?>
                        </td>
                        <td style="width: 100px">
                            <?php
                            if (file_exists(ABSPATH . '/pub/inscrOnline/' . $y['link'])) {
                                $link = HOME_URI . '/pub/inscrOnline/' . $y['link'];
                            }
                            if ($y['ativo'] == 1) {
                                $class = 'info';
                                $nomeBtn = "Visualizar";
                            } else {
                                $class = 'danger';
                                $nomeBtn = "Invalido";
                            }
                            ?>
                            <form target="_blank" action="<?= $link ?>">
                                <button class="btn btn-<?= $class ?>">
                                    <?= $nomeBtn ?>
                                </button>
                            </form>
                        </td>
                        <?php
                        if (!in_array($dados['fk_id_vs'], [1, 2, 3])) {
                            ?>
                            <td style="width: 100px">
                                <form id="excl<?= $y['id_fu'] ?>" method="POST">
                                    <?=
                                    formErp::hidden($tokenExcluir)
                                    . formErp::hidden([
                                        'activeNav' => 2,
                                        'id_fu' => $y['id_fu']
                                    ]);
                                    ?>
                                </form>
                                <button onclick="if (confirm('Excluir Documento?')) {
                                            excl<?= $y['id_fu'] ?>.submit();
                                        }" class="btn btn-danger">
                                    Excluir
                                </button>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
        }
        ?>
    </div>
    <br />
    <?php
}
?>
<script>
    function send(f) {
        input = document.getElementById('selecao-arquivo' + f);
        tamanho = input.files[0].size / 1024 / 1024;
        if (tamanho > 5) {
            alert("O tamanho máximo é 5 megabytes ");
        } else {
            document.getElementById('form' + f).submit();
        }
    }
</script>
