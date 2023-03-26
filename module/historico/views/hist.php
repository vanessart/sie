<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
if ($id_pessoa) {
    $dados = new ng_pessoa($id_pessoa);

    if (empty($dados->dadosPessoais)) {
        ?>
        <div class="alert alert-danger text-center">
            Aluno não encontrado

        </div>
        <?php
        exit();
    } else {
        $dp = $dados->dadosPessoais;
    }
}
?>
<div class="body">
    <div class="row">
        <div class="col-10">

            <div class="fieldTop">
                Histórico Escolar
                <?php
                if (!empty($dp)) {
                    echo '<br>' . $dp['n_pessoa'] . ' RSE: ' . $dp['id_pessoa'];
                }
                ?>
            </div>
        </div>
        <div class="col-2">
            <div style="padding: 10px; width: 100%; text-align: right; padding-right: 50px">
                <a href="#" onclick="ajuda()">
                    <img src="<?= HOME_URI ?>/includes/images/video-help.png">
                </a>
            </div>
        </div>
    </div>
    <?php
    if (empty($id_pessoa)) {
        ?>
        <form method="POST">
            <div class="row">
                <div class="col">
                    <?= formErp::input('id_pessoa', 'RSE', $id_pessoa) ?>
                </div>
                <div class="col">
                    <button class="btn btn-warning">
                        Buscar
                    </button>
                </div>
            </div>
            <br />
        </form>
        <?php
    } else {
        $abas[1] = ['nome' => "Dados Gerais", 'ativo' => 1, 'hidden' => ['id_pessoa' => $id_pessoa]];
        $abas[2] = ['nome' => "Componentes Currículares", 'ativo' => 1, 'hidden' => ['id_pessoa' => $id_pessoa]];
        $abas[3] = ['nome' => "Carga Horária", 'ativo' => 1, 'hidden' => ['id_pessoa' => $id_pessoa]];
        $abas[4] = ['nome' => "Anos Anteriores", 'ativo' => 1, 'hidden' => ['id_pessoa' => $id_pessoa]];
        $abas[5] = ['nome' => "Observações e Certificado", 'ativo' => 1, 'hidden' => ['id_pessoa' => $id_pessoa]];
        $abas[6] = ['nome' => "Imprimir", 'ativo' => 1, 'hidden' => ['id_pessoa' => $id_pessoa]];
        $aba = report::abas($abas);
        include ABSPATH . "/module/historico/views/_hist/$aba.php";
    }
    ?>
</div>
<div class="modal fade" id="ajudaVidio" tabindex="-1" aria-labelledby="ajudaVidioLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ajudaVidioLabel">Ajuda  </h5>
                <button onclick="fechaModal877()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <video id="ajud" style="width: 100%" controls>
                    <source src="<?= HOME_URI ?>/pub/vd/historico.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>
</div>
<script>
    function ajuda() {
        $('#ajudaVidio').modal('show');
        $('.form-class').val('');
    }
    function fechaModal877() {
        ajud.pause();
    }
</script>