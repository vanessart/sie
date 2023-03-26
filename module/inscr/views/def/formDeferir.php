<?php
if (!defined('ABSPATH'))
    exit;

$id_ec = filter_input(INPUT_POST, 'id_ec', FILTER_SANITIZE_STRING);
$evento = $model->evento;
$id_cate = filter_input(INPUT_POST, 'id_cate', FILTER_SANITIZE_NUMBER_INT);
$id_sit = filter_input(INPUT_POST, 'id_sit', FILTER_SANITIZE_NUMBER_INT);
$nomeCpf = filter_input(INPUT_POST, 'nomeCpf', FILTER_SANITIZE_STRING);
if ($id_ec) {
    $dados = $model->inscrito($id_ec);
    if ($dados['deferido'] == 1) {
        $deferido = ' (Deferido)';
    } else {
        $deferido = ' (Não Deferido)';
    }
    ?>
    <div class="body">
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    Nº Inscrição
                </td>
                <td>
                    Nome
                </td>
                <td>
                    categoria
                </td>
                <td>
                    Situação
                </td>
            </tr>
            <tr>
                <td>
                    <?= $dados['id_ec'] ?>
                </td>
                <td>
                    <?= $dados['nome'] ?>
                </td>
                <td>
                    <?= $dados['n_cate'] ?>
                </td>
                <td>
                    <?= $dados['n_sit'] ?>
                </td>
            </tr>
        </table>
        <!--
        <form target="_parent" action="<?= HOME_URI ?>/inscr/deferi" method="POST">
            <div>
                <div class="row">
                    <div class="col-8">
        <?= formErp::textarea('1[obs_ec]', $dados['obs_ec'], 'Obs') ?>
                    </div>
                    <div class="col-4">
                        <table style="margin: auto">
                            <tr>
                                <td>
        <?= formErp::radio('1[fk_id_sit]', 3, 'Deferido', $dados['fk_id_sit']) ?>
                                </td>
                                <td>
        <?= formErp::radio('1[fk_id_sit]', 4, 'Indeferido', $dados['fk_id_sit']) ?>
                                </td>
                            </tr>
                        </table>
                        <br /><br />
                        <div style="text-align: center">
        <?=
        formErp::hidden([
            'id_ec' => $id_ec,
            '1[id_ec]' => $id_ec,
            'dt_deferido_ec' => date("Y-m-d"),
            'fk_id_pessoa_defere' => toolErp::id_pessoa(),
            'activeNav' => @$_POST['activeNav'],
            'id_cate' => $id_cate,
            'nomeCpf' => $nomeCpf,
            'id_sit' => $id_sit
        ])
        . formErp::hiddenToken('inscr_evento_categoria', 'ireplace')
        . formErp::button('Salvar')
        ?>
                        </div>
                    </div>
                </div>
                <br />
            </div>
        </form>
        -->
        <?php
    }
    $abas[1] = ['nome' => "Dados Pessoais", 'ativo' => 1, 'hidden' => ['id_ec' => $id_ec]];
    $abas[2] = ['nome' => "Certificados", 'ativo' => 1, 'hidden' => ['id_ec' => $id_ec]];
//    $abas[4] = ['nome' => "Validar Documentos", 'ativo' => 1, 'hidden' => ['id_ec' => $id_ec]];
    $aba = report::abas($abas);
    include ABSPATH . "/module/inscr/views/def/_formDeferir/$aba.php";
    ?>
</div>