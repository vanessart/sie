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
        margin: 0 auto;
        padding: 6px;
        width: 500px;
        text-align: center
    }    
</style>
<?php
$token = formErp::token('upDoc');
$tokenExcluir = formErp::token('upDocExcluir');
if (!defined('ABSPATH'))
    exit;
?>
<div class="alert alert-warning">
    O sistema só aceita anexar documentos no formato (extensão) PDF e de tamanho máximo de 5 megabytes.
</div>
<?php
if ($ups) {
    foreach ($ups as $k => $v) {
        ?>
        <div class="border" style="width: 98%; margin:  auto">
            <?php
            if (!empty($v['n_up'])) {
                ?>
                <div style="text-align: justify">
                    <?= $v['n_up'] ?>
                </div>
                <?php
            }
            if (!empty($v['descr_up'])) {
                ?>
                <div style="text-align: justify">
                    <?= $v['descr_up'] ?>
                </div>
                <?php
            }
            if (!empty($upload[$v['id_up']])) {
                $maxUp = count($upload[$v['id_up']]) < 5 ? true : false;
            } else {
                $maxUp = true;
            }
            if ($_SESSION['TMP']['SIT'] == 1 && $maxUp) {
                ?>
                <br />
                <form id="form<?= $k ?>" method="POST" enctype="multipart/form-data">
                    <?=
                    formErp::hidden([
                        'id_cate' => $id_cate,
                        'activeNav' => 2,
                        'id_up' => $v['id_up']
                    ])
                    . formErp::hidden($token)
                    ?>
                    <div class="row">
                        <div class="col">
                            <label class="labelSet" id="label<?= $k ?>" for='selecao-arquivo<?= $k ?>'>Anexar frente e verso do Documento <?= $v['obrigatorio'] == 1 ? ' (obrigatório)' : '' ?></label>
                            <input onchange="send('<?= $k ?>')" required="required" id='selecao-arquivo<?= $k ?>' type='file' name="arquivo">
                        </div>
                        <div class="col">
                            <div class="alert alert-warning text-center">
                                No máximo 5 arquivos
                            </div>
                        </div>
                    </div>

                </form>
                <?php
            }
            if (!empty($upload[$v['id_up']])) {
                ?>
                <br />
                <table class="table table-bordered table-hover table-striped">
                    <?php
                    foreach ($upload[$v['id_up']] as $y) {
                        ?>
                        <tr>
                            <td>
                                <?= $y['nome_origin'] ?>
                            </td>
                            <td style="width: 100px">
                                <?php
                                if (file_exists(ABSPATH . '/pub/inscrOnline/' . $y['link'])) {
                                    $link = HOME_URI . '/pub/inscrOnline/' . $y['link'];
                                } else {
                                    $link = HOME_URI . '/pub/inscrOnline2/' . $y['link'];
                                }
                                ?>
                                <form target="_blank" action="<?= $link ?>">
                                    <button class="btn btn-primary">
                                        Visualizar
                                    </button>
                                </form>
                            </td>
                            <?php
                            if ($_SESSION['TMP']['SIT'] == 1) {
                                ?>
                                <td style="width: 100px">
                                    <form id="excl<?= $y['id_iu'] ?>" method="POST">
                                        <?=
                                        formErp::hidden($tokenExcluir)
                                        . formErp::hidden([
                                            'id_cate' => $id_cate,
                                            'activeNav' => 2,
                                            'id_iu' => $y['id_iu']
                                        ]);
                                        ?>
                                    </form>
                                    <button onclick="if (confirm('Excluir Documento?')) {
                                                                    excl<?= $y['id_iu'] ?>.submit()
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
            } else {
                ?>
                <br />
                <p>
                    Sem Arquivos Anexados
                </p>
                <?php
            }
            ?>
        </div>
        <br />
        <?php
    }
}
/**
if ($dados['filhos'] > 0) {
    $fu = sql::get('inscr_upload_filhos', '*', ['cpf' => $_SESSION['TMP']['CPF'], 'fk_id_evento' => $_SESSION['TMP']['FORM']]);
    if ($fu) {
        foreach ($fu as $v) {
            $filho[$v['filho']] = $v;
        }
    }
    foreach (range(1, $dados['filhos']) as $v) {
        $token = formErp::token('upFilhos');
        ?>
        <div class="border" style="width: 98%; margin:  auto">
            <div style="text-align: justify">
                Certidão de nascimento - <?= $v ?>
            </div>
            <br />
            <div class="row">
                <?php
                if (empty($filho[$v])) {
                    ?>
                    <div class="col">
                        <form id="form<?= $k ?>C" method="POST" enctype="multipart/form-data">
                            <?=
                            formErp::hidden([
                                'id_cate' => $id_cate,
                                'activeNav' => 2,
                                'filho' => $v
                            ])
                            . formErp::hidden($token)
                            ?>

                            <label class="labelSet" id="label<?= $k ?>C" for='selecao-arquivo<?= $k ?>C'>Anexar Documento (obrigatório)</label>
                            <input onchange="send('<?= $k ?>C')" required="required" id='selecao-arquivo<?= $k ?>C' type='file' name="arquivo">
                        </form>
                    </div>
                    <div class="col">
                        <div class="alert alert-warning text-center">
                            Apenas um arquivo
                        </div>
                    </div>
                    <?php
                } else {
                    $y = $filho[$v];
                    $tokenExcluirFilho = formErp::token('upFilhoExcluir');
                    ?>
                    <div class="col">
                        <table class="table table-bordered table-hover table-striped">
                            <tr>
                                <td>
                                    <?= $y['nome_origin'] ?>
                                </td>
                                <td style="width: 100px">
                                    <?php
                                    if (file_exists(ABSPATH . '/pub/inscrOnline/' . $y['link'])) {
                                        $link = HOME_URI . '/pub/inscrOnline/' . $y['link'];
                                    } else {
                                        $link = HOME_URI . '/pub/inscrOnline2/' . $y['link'];
                                    }
                                    ?>
                                    <form target="_blank" action="<?= $link ?>">
                                        <button class="btn btn-primary">
                                            Visualizar
                                        </button>
                                    </form>
                                </td>
                                <?php
                                if ($_SESSION['TMP']['SIT'] == 1) {
                                    ?>
                                    <td style="width: 100px">
                                        <form id="exclF<?= $y['id_iuf'] ?>" method="POST">
                                            <?=
                                            formErp::hidden($tokenExcluirFilho)
                                            . formErp::hidden([
                                                'id_cate' => $id_cate,
                                                'activeNav' => 2,
                                                'id_iuf' => $y['id_iuf']
                                            ]);
                                            ?>
                                        </form>
                                        <button onclick="if (confirm('Excluir Documento?')) {
                                                                    exclF<?= $y['id_iuf'] ?>.submit()
                                                                }" class="btn btn-danger">
                                            Excluir
                                        </button>
                                    </td>
                                    <?php
                                }
                                ?>
                        </table>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <br />
        <?php
    }
}
 * 
 */
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
