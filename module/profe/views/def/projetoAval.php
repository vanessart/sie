<?php
if (!defined('ABSPATH'))
    exit;

$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_UNSAFE_RAW);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'fk_id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_projeto = filter_input(INPUT_POST, 'id_projeto_aval', FILTER_SANITIZE_NUMBER_INT);
$id_ciclo = filter_input(INPUT_POST, 'fk_id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'fk_id_disc', FILTER_SANITIZE_NUMBER_INT);
$msg_coord = filter_input(INPUT_POST, 'msg_coord', FILTER_UNSAFE_RAW);
$fk_id_pessoa_prof = toolErp::id_pessoa();

if (!empty($id_turma)) {

    $turma = sql::get(['ge_turmas', 'ge_ciclos'], 'n_turma, fk_id_ciclo, fk_id_curso, periodo, fk_id_pl, ge_turmas.fk_id_grade', ['id_turma' => $id_turma], 'fetch');
    //$grade = gtMain::discGrade($turma['fk_id_grade']);
}


$projeto = sql::get('profe_projeto', 'id_projeto, n_projeto, dt_inicio, dt_fim, justifica, recurso, resultado, situacao, fonte, avaliacao', 'WHERE id_projeto =' . $id_projeto, 'fetch', 'left');

$registros = sql::get(['profe_projeto_reg', 'coord_hab'], 'situacao, codigo, n_hab ', 'WHERE fk_id_projeto =' . $id_projeto, NULL, 'left');

?>
<style>
    td{
        font-size: 13px;

        font-style: italic;
    }
    html, body {
        max-width: 100%;
        overflow-x: hidden;
    }

</style>

<div class="fieldBody">
    <div class="row">
        <div class="fieldTop" style="font-size: 18px">
            Título: <?php echo $projeto['n_projeto'] ?>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <table class="table table-bordered table-striped">
                    <tr >
                        <th  colspan="2" class="table-active">
                            Escola: <?= toolErp::n_inst() ?>
                        </th>
                    </tr>
                    <tr>
                        <th>
                            Período: de 
                            <?php echo data::converteBr($projeto['dt_inicio']) ?> 
                            a 
                            <?php echo data::converteBr($projeto['dt_fim']) ?> 
                        </th>
                        <th>
                            Fase: <?php echo $turma['n_turma'] ?> 
                        </th>       
                    </tr>
                </table>
            </div>
        </div>


        <div class="row">
            <div class="col">
                <table class="table table-bordered table-hover sortable" style="padding:4px">
                    <tr>
                        <th width="130px">
                            Justificativa:
                        </th>
                        <td>
                            <?php echo $projeto['justifica'] ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Habilidades:
                        </th>
                        <td>
                            <table class="table table-bordered table-condensed table-responsive table-striped">
                                <tr class="text-center">
                                    <th >
                                        Habilidade
                                    </th>
                                    <th>
                                        Situação de Aprendizagem
                                    </th>
                                </tr>
                                <?php foreach ($registros as $k => $v) { ?>
                                    <tr>
                                        <td>
                                            <?= $registros[$k]['codigo'] ?> - <?= $registros[$k]['n_hab'] ?>
                                        </td>
                                        <td>
                                            <?= $registros[$k]['situacao'] ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Recursos:
                        </th>
                        <td>
                            <?php echo $projeto['recurso'] ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Resultado do Projeto:
                        </th>
                        <td>
                            <?php echo $projeto['resultado'] ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Fontes de Pesquisa:
                        </th>
                        <td>
                            <?php echo $projeto['fonte'] ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
    <form action="<?= HOME_URI ?>/profe/projeto" method="POST" target='_parent'>
        <div class="row">
            <div class="fieldTop" style="font-size: 18px">
                Avaliação do Projeto
            </div>
            <div class="row">
                <div class="col">
                    Descreva o percurso das crianças ao longo das experiências propostas durante o projeto e se as habilidades foram contempladas.
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?= formErp::textarea('1[avaliacao]', @$projeto['avaliacao'], 'Avaliação') ?>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col text-center">

                <?=
                formErp::hidden([
                    'activeNav' => 1,
                    'fk_id_turma' => $id_turma,
                    '1[id_projeto]' => $id_projeto,
                    'fk_id_disc' => @$id_disc,
                    'fk_id_ciclo' => @$id_ciclo,
                    'n_turma' => $n_turma,
                    'msg_coord' => $msg_coord,
                    'id_inst' => $id_inst
                ])
                . formErp::hiddenToken('profe_projeto', 'ireplace')
                . formErp::button('Salvar');
                ?>            
            </div>
        </div>     
    </form>
</div>
