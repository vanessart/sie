<?php
if (!defined('ABSPATH'))
    exit;

ob_start();
$pdf = new pdf();
?>
<style>
    .wpp {
        padding: 10px !important;
        padding-top: 15px !important;
        font-weight: bold;
        font-size: 16px;
    }
    .table{
        width: 100%;
        border: 1;
        font-size: 12px;
        cellspacing: 0;
        cellpadding: 2;
    }
    .table td{
        padding: 4px;
    }
</style>
<div class="body">
    <div class="row">
        <div class="col-3 col-form-label">
            <strong>Data da Pauta:</strong> <?= dataErp::converteBr($dt_pauta) ?>
        </div>
        <?php if ( empty($model::isProfessor()) ) { ?>
        <div class="col-4 col-form-label">
            <strong>Visível para o Professor?</strong> <?= !empty($visivel_professor)?'Sim':'Não' ?>
        </div>
        <?php } ?>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col col-form-label">
            <div><strong>PAUTA</strong></div> 
            <div style="white-space: pre-wrap;"><?= $n_pauta ?></div>
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
                    <th>DATA</th>
                    <th>AUTOR</th>
                    <th>TIPO DE DOC.</th>
                    <th>ARQUIVO</th>
                </tr>
                <?php foreach ($anexos as $key => $value) { ?>
                <tr>
                    <td><?= $value['dt_update'] ?></td>
                    <td><?= $value['n_pessoa'] ?></td>
                    <td><?= $value['tipo'] ?></td>
                    <td><?= $value['n_up'] ?></td>
                </tr>
                <?php } ?>
            </table>
            <?php } ?>
        </div>
    </div>
</div>

<?php
$pdf->exec();
