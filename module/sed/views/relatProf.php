<?php
if (!defined('ABSPATH'))
    exit;
?>
<style>
    .grd button{
        width: 100%;
    }
</style>
<div class="body">
    <div class="fieldTop">
        Relatórios - Professores
    </div>
    <div class="row grd">
        <div class="col">
            <a target="_blank" href="<?php echo HOME_URI ?>/prof/pdfprof">
                <button class="btn btn-info">
                    Lista de Professores
                </button>
            </a>
        </div>
        <div class="col">
            <a target="_blank" href="<?php echo HOME_URI ?>/prof/pdfprofno">
                <button class="btn btn-info">
                    Lista de Professores Não Alocados
                </button>
            </a>
        </div>
        <div class="col">
            <a target="_blank" href="<?php echo HOME_URI ?>/sed/pdf/pdfalocaprof.php">
                <button class="btn btn-info">
                    Alocação de Professores (por professor)
                </button>
            </a>
        </div>
    </div>
    <br />
    <div class="row grd">
        <div class="col">
            <a target="_blank" href="<?php echo HOME_URI ?>/prof/pdfalocadisc">
                <button class="btn btn-info">
                    Alocação de Professores (por disciplina)
                </button>
            </a>
        </div>
        <div class="col">
            <a target="_blank" href="<?php echo HOME_URI ?>/prof/pdfalocaclasse">
                <button class="btn btn-info">
                    Alocação de Professores (por classe)
                </button>
            </a>
        </div>
        <div class="col">
            <!--
            <form name ="disciplina" target="_blank" action="<?php echo HOME_URI ?>/prof/quadroprof" id="qprof" method="POST">
                <table style="width: 100%">
                    <tr>
                        <td>
                            <?= formErp::select('disc', $model->selecaodisciplina(), 'Quadro de Professores', null, null, null, ' required ') ?>
                        </td>
                        <td style="width: 150px">
                            <button class="btn btn-info">
                                Imprimir
                            </button>
                        </td>
                    </tr>
                </table>             
            </form>
            -->
        </div>
    </div>
    <br />
</div>
