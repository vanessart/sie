<?php
if (!defined('ABSPATH'))
    exit;
?>
<style>
    .wpp {
        padding: 10px !important;
        padding-top: 15px !important;
        font-weight: bold;
        font-size: 16px;
    }
</style>
<div class="body">
    <div class="row">
        <div class="col-4 col-form-label">
            <strong>Data da ATA:</strong> <?= dataErp::converteBr($dt_ata) ?>
        </div>
        <div class="col-2 col-form-label">
            <strong>Período:</strong> <?= $n_periodo ?>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col col-form-label">
            <strong>ATA</strong> 
            <div style="white-space: pre-wrap;text-align: justify;"><?= $n_ata ?>
                
            <?php if (!empty($pdfView) && !empty($listaPresenca)) { ?>
                <br><br>Nada mais havendo a tratar, deu-se por encerrada a reunião. Segue a lista de professores presentes e ausentes:
            <?php } ?>

            </div>
        </div>
    </div>
    <?php if (!empty($pdfView)) { ?>
    <br>
    <div class="row">
        <div class="col col-form-label">
            <div><strong>LISTA DE PRESENÇA</strong></div> 
            <?php if (empty($listaPresenca)) { ?>
                Não há listaPresenca
            <?php } else { ?>
            <table class="table">
                <tr>
                    <th align="left">Professores</th>
                </tr>
                <?php
                $stat = ''; 
                $presentes = array_column($listaPresenca, 'presente');
                array_multisort($presentes, SORT_DESC, $listaPresenca);
                foreach ($listaPresenca as $key => $value)
                {
                    if ($stat != $value['presente']) { ?>
                    <tr>
                        <th align="center"><?= ($value['presente'] == 1) ? 'Presentes' : 'Ausentes' ?></th>
                    </tr>
                    <?php 
                        $stat = $value['presente'];
                    } ?>
                <tr>
                    <td><?= $value['n_pessoa'] ?></td>
                </tr>
                <?php } ?>
            </table>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
    <br>
    <div class="row">
        <div class="col col-form-label">
            <div><strong>EMENTAS</strong></div> 
            <?php if (empty($ementas)) { ?>
                Não há ementas
            <?php } else { ?>
            <table class="table">
                <tr>
                    <th align="center" style="width:8%">Item</th>
                    <th align="center">Data</th>
                    <th>Responsável</th>
                    <th>Ementa</th>
                </tr>
                <?php foreach ($ementas as $key => $value) { ?>
                <tr>
                    <td align="center"><?= $key+1 ?></td>
                    <td align="center"><?= $value['dt_criacao'] ?></td>
                    <td><?= $value['n_pessoa'] ?></td>
                    <td style="white-space:pre-wrap"><?= $value['descricao'] ?></td>
                </tr>
                <?php } ?>
            </table>
            <?php } ?>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col col-form-label">
            <div><strong>ANEXOS</strong></div> 
            <?php if (empty($anexos)) { ?>
                Não há anexos
            <?php } else { ?>
            <table class="table">
                <tr>
                    <th align="center" style="width:8%">Item</th>
                    <th align="center">Data</th>
                    <th>Tipo</th>
                    <?php if (empty($pdfView)) { ?><th>Anexo</th><?php } ?>
                </tr>
                <?php foreach ($anexos as $key => $value) { ?>
                <tr>
                    <td align="center"><?= $key+1 ?></td>
                    <td align="center"><?= dataErp::converteBr($value['dt_update']) ?></td>
                    <td><?= $value['tipo'] ?></td>
                    <?php if (empty($pdfView)) { ?><td><?= $value['docx'] ?></td><?php } ?>
                </tr>
                <?php } ?>
            </table>
            <?php } ?>
        </div>
    </div>
</div>