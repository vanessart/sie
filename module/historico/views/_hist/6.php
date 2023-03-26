<?php
if (!defined('ABSPATH'))
    exit;
$anoCompleto = @$_SESSION['TMP']['anoCompleto'][$id_pessoa];
$id_ciclo = @$_SESSION['TMP']['id_ciclo'][$id_pessoa];
$h = sqlErp::get(['historico_dados_gerais', 'historico_tipo_ensino'], '*', ['fk_id_pessoa' => $id_pessoa], 'fetch');
$pdf = 1;
$notas = $model->notasSoParciais($id_pessoa);
$cp_ = sql::get('historico_carga', '*', ['fk_id_pessoa' => $id_pessoa], 'fetch');
$ant = $model->anosAnteriores($dados->dadosPessoais);
if ($ant) {
    $ultimoIdCiclo = end($ant)['fk_id_ciclo'];
}

if ($cp_) {
    $ciclosArr = array_keys($notas);
    $id_carga = $cp_['id_carga'];
    foreach ($cp_ as $k => $v) {
        $ar = explode('_', $k);
        if (in_array($ar[1], $ciclosArr)) {
            $carga[$ar[1]][$ar[0]] = $v;
        }
    }
}
if (!empty($carga)) {
    foreach ($carga as $v) {
        if (!empty($v['total'])) {
            $cargaTem = true;
        }
    }
}
if ($notas) {
    foreach ($notas as $v) {
        foreach ($v as $k => $y) {
            if (substr($k, 0, 2) == 'n_' && is_numeric(substr($k, 2)) && $y) {
                @$contNota++;
            }
        }
        if (@$contNota > 5) {
            $temNota = true;
        }
    }
}
if (@$ultimoIdCiclo == 1) {
    $temNota = true;
}
if ($ant) {
    foreach ($ant as $v) {
        if (!empty($v['ano']) && !empty($v['cidade']) && !empty($v['escola']) && !empty($v['uf'])) {
            $antTem = 1;
        } elseif (!empty($v['ano']) || !empty($v['cidade']) || !empty($v['escola']) || !empty($v['uf'])) {
            $antTem = null;
        }
    }
}
if (@$_SESSION['TMP']['anoCompleto'][$id_pessoa] == 'x' && @$_SESSION['TMP']['id_ciclo'][$id_pessoa] == 1) {
    $temNota = true;
    $cargaTem = true;
    $antTem = true;
}
?>
<br /><br />
<div class="row">
    <div class="col-4">
        <table class="table table-bordered table-hover table-striped border">
            <tr>
                <td colspan="2" style="text-align: center">
                    Dados Gerais
                </td>
            </tr>
            <tr>
                <td>
                    Nome da Escola   
                </td>
                <td>
                    <?php
                    if (!empty($h['escola'])) {
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/s.png">
                        <?php
                    } else {
                        $pdf = null;
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/n.png">
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    Nome do Aluno  
                </td>
                <td>
                    <?php
                    if (!empty($h['nome'])) {
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/s.png">
                        <?php
                    } else {
                        $pdf = null;
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/n.png">
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    RG (número e Dígito)
                </td>
                <td>
                    <?php
                    if (!empty($h['rg']) && !empty($h['rg_dig'])) {
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/s.png">
                        <?php
                    } else {
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/t.png">
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    CPF
                </td>
                <td>
                    <?php
                    if (!empty($h['cpf'])) {
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/s.png">
                        <?php
                    } else {
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/t.png">
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    Data de Nascimento  
                </td>
                <td>
                    <?php
                    if (!empty($h['dt_nasc'])) {
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/s.png">
                        <?php
                    } else {
                        $pdf = null;
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/n.png">
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    Certidão de Nascimento  
                </td>
                <td>
                    <?php
                    if (!empty($h['certidao'])) {
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/s.png">
                        <?php
                    } else {
                        $pdf = null;
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/n.png">
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    Cidade e Estado que Nasceu  
                </td>
                <td>
                    <?php
                    if (!empty($h['cidade_uf_nasc'])) {
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/s.png">
                        <?php
                    } else {
                        $pdf = null;
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/n.png">
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    Nacionalidade  
                </td>
                <td>
                    <?php
                    if (!empty($h['nacionalidade'])) {
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/s.png">
                        <?php
                    } else {
                        $pdf = null;
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/n.png">
                        <?php
                    }
                    ?>
                </td>
            </tr>

        </table>
    </div>
    <div class="col">
        <table class="table table-bordered table-hover table-striped border">
            <tr>
                <td>
                    Componentes Currículares
                </td>
                <td>
                    <?php
                    if (!empty($temNota)) {
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/s.png">
                        <?php
                    } else {
                        $pdf = null;
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/n.png">
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    Carga Horária
                </td>
                <td>
                    <?php
                    if (!empty($cargaTem)) {
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/s.png">
                        <?php
                    } else {
                        $pdf = null;
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/n.png">
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    Anos Anteriores
                </td>
                <td>
                    <?php
                    if (!empty($antTem)) {
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/s.png">
                        <?php
                    } else {
                        $pdf = null;
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/n.png">
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    Observações e Certificado  
                </td>
                <td>
                    <?php
                    if (!empty($h['certificado'])) {
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/s.png">
                        <?php
                    } else {
                        $pdf = null;
                        ?>
                        <img style="width: 20px" src="<?= HOME_URI ?>/includes/images/n.png">
                        <?php
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="col">
        <?php
        if (empty($pdf)) {
            ?>
            <div class="alert alert-danger" style="font-weight: bold; font-size: 1.4">
                Solucione as pendências ao lado para que a impressão seja liberada
            </div>
            <?php
        } else {
            ?>
            <form target="_blank" action="<?= HOME_URI ?>/historico/pdf/historico" method="POST">
                <div style="text-align: center; padding: 80px">
                    <?=
                    formErp::hidden([
                        'id_pessoa' => $id_pessoa,
                        'anoCompleto' => $anoCompleto,
                        'id_ciclo' => $id_ciclo,
                        'FrequentaCiclo1' => (@$ultimoIdCiclo == 1 ? 1 : null)
                    ])
                    . formErp::button('Gerar PDF')
                    ?>
            </form>
        </div>
    <?php }
    ?>
</div>
</div>
<br />

