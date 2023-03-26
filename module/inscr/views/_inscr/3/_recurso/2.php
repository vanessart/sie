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
        width: 300px;
        text-align: center
    }    
</style>
<?php
if (!defined('ABSPATH'))
    exit;
$up = sql::get('inscr_recurso_up', '*', ['fk_id_ec' => $inscrito['id_ec']]);
if (empty($up)) {
    $up = [];
}
if ($rec) {
    ?>
    <div class="alert alert-dark">
        <p>
            <?= $rec['motivo'] ?>
        </p>
        <p>
            <?= $rec['recurso'] ?> 
        </p>

    </div>
    <?php
    if (empty($event['recurso_ver'])) {
        ?>
        <div class="alert alert-warning" style="text-align: center; font-weight: bold; font-size: 1.8">
            Seu RECURSO Está em análise
        </div>
        <?php
    } else {
        if (!empty($rec['deferido'])) {
            if ($rec['deferido'] == 1) {
                ?>
                <div class="alert alert-success" style="text-align: center; font-weight: bold; font-size: 1.8">
                    Seu RECURSO foi Deferido
                </div>
                <?php
            } elseif ($rec['deferido'] == 2) {
                ?>
                <div class="alert alert-danger" style="text-align: center; font-weight: bold; font-size: 1.8">
                    Seu RECURSO foi Indeferido
                </div>
                <?php
            }
        }
    }
    if (!empty($rec['resposta'])) {
        if (!empty($event['recurso_ver'])) {
            ?>
            <div class="fieldTop">
                Resposta ao Recurso
            </div>
            <div class="alert alert-primary">
                <?= $rec['resposta'] ?>
            </div>
            <?php
        }
    } elseif (count($up) < 20 && empty($rec['concluido'])) {
        ?>
        <div class="alert alert-warning">
            Anexar arquivos em PDF se for necessário
        </div>
        <br />
        <form id="formrc" method="POST" enctype="multipart/form-data">
            <label class="labelSet" id="label" for='selecao-arquivo'>Anexar Documento</label>
            <input onchange="send()" required="required" id='selecao-arquivo' type='file' name="arquivo">
            <?=
            formErp::hidden([
                'id_cate' => $id_cate,
                'id_ec' => $inscrito['id_ec'],
                'rec' => 2,
            ])
            . formErp::hiddenToken('recursoUp')
            ?>
        </form>
        <?php
    }
    $tokenExcluir = formErp::token('inscr_recurso_up', 'delete');
    if ($up) {
        ?>
        <br /><br />
        <table class="table table-bordered table-hover table-striped">
            <?php
            foreach ($up as $y) {
                if (file_exists(ABSPATH . '/pub/inscrOnline/' . $y['link'])) {
                    $link = HOME_URI . '/pub/inscrOnline/' . $y['link'];
                } else {
                    $link = HOME_URI . '/pub/inscrOnline2/' . $y['link'];
                }
                ?>
                <tr>
                    <td>
                        <?= $y['nome_origin'] ?>
                    </td>
                    <td style="width: 100px">
                        <form target="_blank" action="<?= $link ?>">
                            <button class="btn btn-primary">
                                Visualizar
                            </button>
                        </form>
                    </td>
                    <?php
                    if (empty($rec['concluido'])) {
                        ?>
                        <td style="width: 100px">
                            <form id="excl<?= $y['id_ru'] ?>" method="POST">
                                <?=
                                formErp::hidden($tokenExcluir)
                                . formErp::hidden([
                                    'id_cate' => $id_cate,
                                    'rec' => 2,
                                    '1[id_ru]' => $y['id_ru']
                                ]);
                                ?>
                            </form>
                            <button onclick="if (confirm('Excluir Documento?')) {
                                                        excl<?= $y['id_ru'] ?>.submit()
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
    if (empty($rec['concluido'])) {
        ?>
        <form id="concluir" method="POST" enctype="multipart/form-data">
            <?=
            formErp::hidden([
                'id_cate' => $id_cate,
                'rec' => 2,
                '1[id_rec]' => $rec['id_rec'],
                '1[concluido]' => 1
            ])
            . formErp::hiddenToken('inscr_recurso', 'ireplace')
            ?>
        </form>
        <div style="text-align: center; padding: 20px">
            <button class="btn btn-success" onclick="if (confirm('Após a conclusão, não será mais possível incluir novos arquivos. Concluir?')) {
                                concluir.submit()
                            }">
                Enviar e Gerar o Protocolo do Recurso
            </button>
        </div>
        <?php
    } else {
        ?>
        <form target="_blank" action="<?= HOME_URI ?>/inscr/pdf/recursoPdf" method="POST">
            <?=
            formErp::hidden([
                'id_rec' => $rec['id_rec'],
            ])
            ?>
            <div style="text-align: center; padding: 20px">
                <button class="btn btn-warning">
                    Gerar Protoco de Recurso
                </button>
            </div>
        </form>
        <?php
    }
} else {
    ?>
    <div class="fieldTop">
        À Comissão Especial da Secretaria de Educação.
    </div>
    <p>
        Número da inscrição: <?= str_pad($inscrito['id_ec'], 5, "0", STR_PAD_LEFT) . '/' . date("Y"); ?>
    </p>
    <p>
        Nome completo: <?= $dados['nome'] ?>
    </p>
    <p>
        Função: <?= $cate['n_cate'] ?>
    </p>
    <p style="font-weight: bold">
        Identificar o motivo do indeferimento da inscrição(resposta <span>OBRIGATÓRIA</span>): 
    </p>
    <form id="rec1" method="POST" enctype="multipart/form-data">
        <p>
            <label>
                <input name="1[motivo]" class="form-check-input" type="radio" value="Graduação obrigatória inválida."  required>
                Graduação obrigatória inválida.
            </label>
        </p>
        <p>
            <label>
                <input name="1[motivo]" class="form-check-input" type="radio" value="Documento ilegível."  required>
                Documento ilegível.
            </label>
        </p>
        <p>
            <label>
                <input name="1[motivo]" class="form-check-input" type="radio" value="Documento errado."  required>
                Documento errado.
            </label>
        </p>
        <p>
            <label>
                <input name="1[motivo]" class="form-check-input" type="radio" value="Documento corrompido."  required>
                Documento corrompido.
            </label>
        </p>
        <p>
            <label>
                <input name="1[motivo]" class="form-check-input" type="radio" value="Documento comprobatório não anexado."  required>
                Documento comprobatório não anexado.
            </label>
        </p>
        <p>
            <label>
                <input name="1[motivo]" class="form-check-input" type="radio" value="Documento comprobatório anexado inválido."  required>
                Documento comprobatório anexado inválido.
            </label>
        </p>
        <p>
            <label>
                <input name="1[motivo]" class="form-check-input" type="radio" value="Outros"  required>
                Outros (descrever abaixo)
            </label>
        </p>
        <?= formErp::textarea('1[recurso]', null, 'Recurso') ?>

        <?=
        formErp::hidden([
            'id_cate' => $id_cate,
            'rec' => 2,
            '1[fk_id_ec]' => $inscrito['id_ec']
        ])
        . formErp::hiddenToken('inscr_recurso', 'ireplace')
        ?>
    </form>
    <div style="text-align: center; padding: 20px">
        <button class="btn btn-primary" onclick="continua()">
            Continuar para Anexar os Documentos
        </button>
    </div>
    <?php
}
?>
<script>
    function continua() {
        if (confirm('Não será possível retornar a esta etapa. Continuar?')) {
            rec1.submit();
        }

    }
    function send() {
        input = document.getElementById('selecao-arquivo');
        tamanho = input.files[0].size / 1024 / 1024;
        if (tamanho > 5) {
            alert("O tamanho máximo é 5 megabytes ");
        } else {
            document.getElementById('formrc').submit();
        }
    }
</script>