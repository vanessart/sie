<?php
$entr_proj = filter_input(INPUT_POST, 'entr_proj', FILTER_SANITIZE_NUMBER_INT);
$entr_port = filter_input(INPUT_POST, 'entr_port', FILTER_SANITIZE_NUMBER_INT);
$entr_midia = filter_input(INPUT_POST, 'entr_midia', FILTER_SANITIZE_NUMBER_INT);
$entr_gest = filter_input(INPUT_POST, 'entr_gest', FILTER_SANITIZE_NUMBER_INT);
$entr_hist = filter_input(INPUT_POST, 'entr_hist', FILTER_SANITIZE_NUMBER_INT);
$entr_prot = filter_input(INPUT_POST, 'entr_prot', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);

echo $sql = "UPDATE `giz_prof` SET "
        . " `entr_gest` = '$entr_gest', "
        . " `entr_hist` = '$entr_hist', "
        . " `entr_prot` = '$entr_prot', "
        . " `entr_proj` = '$entr_proj', "
        . " `entr_port` = '$entr_port', "
        . " `entr_midia` = '$entr_midia' "
        . " WHERE fk_id_pessoa = '$id_pessoa' ";
$query = pdoSis::getInstance()->query($sql);
$prof = funcionarios::Get($id_pessoa, 'id_pessoa', 'n_pessoa, rm, sexo')[0];
$inscr = $model->inscricoes($id_pessoa, 'p.id_pessoa', NULL, 'n_inst, n_cate, titulo')[0];

ob_start();
?>
<div style="text-align: center; font-weight: bold; font-size: 20px">
    Giz de Ouro <?php echo date("Y") ?>
    <br /><br />
    Protocolo de Recebimento
</div>
<br /><br /><br /><br /><br />
<div>
    Recebemos d<?php echo tool::sexoArt($prof['sexo']) ?> Professor<?php echo tool::sexoArt($prof['sexo']) ?>
    <span style="font-weight: bold"><?php echo ucwords(strtolower(stripslashes($prof['n_pessoa']))) ?></span>, 
    matrícula <span style="font-weight: bold"><?php echo $prof['rm'] ?></span> 
    , inscrit<?php echo tool::sexoArt($prof['sexo']) ?> na categoria <span style="font-weight: bold"><?php echo $inscr['n_cate'] ?></span> 
    com o projeto <span style="font-weight: bold"><?php echo $inscr['titulo'] ?></span> 
    aplicado na <span style="font-weight: bold"><?php echo $inscr['n_inst'] ?></span> 
    os materiais abaixo relacionados:

    <br /><br />
    <?php
    if (!empty($entr_hist)) {
        ?>
        <div style="margin-left: 50px; font-weight: bold">
            - Histórico de Ausência.
        </div>
        <?php
    } else {
        $falta[] = '- Histórico de Ausência.';
    }

    if (!empty($entr_proj)) {
        ?>
        <div style="margin-left: 50px; font-weight: bold">
            - Escopo do projeto editado no ato da inscrição.
        </div>
        <?php
    } else {
        $falta[] = '- Escopo do projeto editado no ato da inscrição.';
    }
    if (!empty($entr_gest)) {
        ?>
        <div style="margin-left: 50px; font-weight: bold">
            - Relatório de Acompanhamento da Equipe de Gestão - preenchido e assinado.
        </div>
        <?php
    } else {
        $falta[] = '- Relatório de Acompanhamento da Equipe de Gestão - preenchido e assinado.';
    }

    if (!empty($entr_prot)) {
        ?>
        <div style="margin-left: 50px; font-weight: bold">
            - Protocolo gerado na inscrição
        </div>
        <?php
    } else {
        $falta[] = '- Protocolo gerado na inscrição';
    }

    if (!empty($entr_port)) {
        ?>
        <div style="margin-left: 50px; font-weight: bold">
            - Portfolio Impresso
        </div>
        <?php
    } else {
        $falta[] = '- Portfolio Impresso';
    }
    if (!empty($entr_midia)) {
        ?>
        <div style="margin-left: 50px; font-weight: bold">
            - Mídia
        </div>
        <?php
    } else {
        $falta[] = '- Mídia';
    }
    if (!empty($falta)) {
        ?>
        <br /><br />
        <?php
        if (count($falta) == 1) {
            ?>
            Não recebemos o seguinte material:
            <?php
        } else {
            ?>
            Não recebemos os seguintes materiais:
            <?php
        }
        ?>
        <br /><br />
        <?php
        foreach ($falta as $f) {
            ?>
            <div style="margin-left: 50px; font-weight: bold">
                <?php echo $f ?>
            </div>
            <?php
        }
    }
    ?>
    <br /><br />
    <div style="text-align: right; margin-left: 100px">
        Barueri, <?php echo data::porExtenso(date("Y-m-d")) ?>
    </div>
    <br /><br /><br />
    <br /><br /><br />
    <table style="width: 100%">
        <tr>
            <td style="text-align: center">
                ______________________________
                <br />
                <?php echo tool::abrevia(ucwords(strtolower(stripslashes($prof['n_pessoa'])))) ?>  
                <br />
                ou seu representante (nome legível)
            </td>
            <td style="text-align: center">
                ______________________________
                <br />
                <?php echo tool::abrevia(ucwords(strtolower(stripslashes(user::session('n_pessoa'))))) ?>  
            </td>
        </tr>
    </table>


</div>
<?php
tool::pdfSecretaria();
