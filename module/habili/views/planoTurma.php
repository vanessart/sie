<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="body">
    <div class="fieldTop">
        Plano de aula
    </div>
    <?php
    $id_instCoord = toolErp::id_inst();
    $plano = filter_input(INPUT_POST, 'plano', FILTER_SANITIZE_NUMBER_INT);
    $atualLetivaSet = filter_input(INPUT_POST, 'atualLetivaSet', FILTER_SANITIZE_NUMBER_INT);
    $id_pls = ng_main::periodosAtivos();
    $id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
    $id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_STRING);
    $ciclos = $model->getCiclos($id_instCoord, '1,5,9');
    $QtdLetiva = $model->getQtdLetiva('1,5,9');
    $id_curso = 0;
    $id_pessoa = 0;

    if (!empty($id_ciclo)) {
        $cicCurso = $model->getCurso($id_ciclo);
        $id_curso = $cicCurso[0]['id_curso'];
    }

    $hidden = [
        'id_ciclo' => $id_ciclo,       
        'id_disc' => $id_disc,       
        'atualLetivaSet' => $atualLetivaSet,       
    ];
    ?>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_ciclo', $ciclos, 'Ciclo', @$id_ciclo, 1, $hidden); ?>
        </div>
         <?php
        if ($id_ciclo) { 
            $discs = $model->getDiscs($id_instCoord, $id_ciclo);
            ?>
            <div class="col">
                <?= formErp::select('id_disc', $discs, 'Disciplina', @$id_disc, 1, $hidden);?>
            </div>
            <?php
        }
        foreach (range(1, (empty($QtdLetiva['qt_letiva']) ? 1 : $QtdLetiva['qt_letiva'])) as $v) {
            $al[$v] = $v . 'º ' . $QtdLetiva['un_letiva'];
        }
        $atualLetivaSet = (empty($atualLetivaSet) ? $QtdLetiva['atual_letiva'] : $atualLetivaSet);
        ?>
        <div class="col">
            <?php if (!empty($QtdLetiva)) { ?>
            <div>
                <?php
                if ($id_disc) {
                    echo formErp::select('atualLetivaSet', $al, 'Unidade Letiva', $atualLetivaSet, 1, $hidden);
                }
                ?>
            </div> 
            <?php } ?>
        </div>
    </div>
    <br>
        <?php 
    if (!empty($discs)) { ?>
        <div class="row">
            <div class="col-md-5" style="text-align:right; width: 100%; margin-bottom:5px;">
                <button  class="btn btn-info" style="width: 20px; height: 20px;"></button> Disponível para Alterações
                <button  class="btn btn-warning" style="width: 20px; height: 20px;"></button> Indisponível para Alterações
                <button  class="btn btn-outline-secondary" style="width: 20px; height: 20px;"></button> Não Enviado
            </div>
        </div>
        <?php 
    }   
    if ($id_disc) {
        $sql = " SELECT DISTINCT fk_id_pessoa, id_plano, dt_inicio, dt_fim, qt_aulas, fk_id_projeto_status, n_projeto_status, coord_vizualizar, atualLetiva, t.fk_id_pl  FROM coord_plano_aula pa "
                . " JOIN coord_plano_aula_turmas pt on pt.fk_id_plano = pa.id_plano "
                . " JOIN ge_turmas t ON pt.fk_id_turma = t.id_turma "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN profe_projeto_status ps on ps.id_projeto_status = pa.fk_id_projeto_status "
                . " WHERE pa.fk_id_ciclo = $id_ciclo"
                . " AND t.fk_id_inst = $id_instCoord "
                . " AND pa.iddisc LIKE '".$id_disc."' "
                . " AND pa.atualLetiva = $atualLetivaSet ";
        $query = pdoSis::getInstance()->query($sql);
        $plans = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($plans) {
            foreach ($plans as $k => $v) {
                $planos[$k]['id_plano'] = $v["id_plano"];
                $planos[$k]['dt_inicio'] = $v["dt_inicio"];
                $planos[$k]['dt_fim'] = $v["dt_fim"];
                $planos[$k]['qt_aulas'] = $v["qt_aulas"];
                $planos[$k]['n_projeto_status'] = $v["n_projeto_status"];
                if ($v['fk_id_projeto_status'] == 1) {
                    $btn_ver = "outline-secondary";
                    //$btn_ver = "info";
                }else{
                    $btn_ver = ($v['fk_id_projeto_status'] <> 2) ? 'warning' : 'info';
                }
                $planos[$k]['ac'] = '<button onclick="plan(' . $v['id_plano'] . ', ' . $v['fk_id_pl'] . ',' . $v['atualLetiva'] . ',' . $v['fk_id_pessoa'] . ')" class="btn btn-'. $btn_ver .'">Acessar</button>';
                $turmas = array_column($model->getTurmaPlano($v["id_plano"]), 'n_turma');
                
                $planos[$k]['turmas'] = toolErp::virgulaE($turmas);
            }

            $form['array'] = $planos;
            $form['fields'] = [
                'ID' => 'id_plano',
                'Início' => 'dt_inicio',
                'Término' => 'dt_fim',
                'Turmas' => 'turmas',
                'Aulas' => 'qt_aulas',
                'Situação' => 'n_projeto_status',
                '||1' => 'ac'
            ];
        }
    }
        
    if(!empty($form)){
        report::simple($form);
    }?>
    <form id="form"  action="<?= HOME_URI ?>/profe/planoCoord" method="POST">
        <input type="hidden" name="id_plano" id="id_plano" />
        <input type="hidden" name="id_pl" id="id_pl" />
        <input type="hidden" name="atualLetivaSet" id="atualLetivaSet" />
        <input type="hidden" name="id_pessoa" id="id_pessoa" />
        <?= formErp::hidden([
            'id_curso' => $id_curso,
            'id_disc' => $id_disc,
            'id_ciclo' => $id_ciclo,
            'id_inst' => $id_instCoord,
            'un_letiva' => $QtdLetiva['un_letiva'],
        ])
        ?>
    </form>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Calendário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $ano = str_pad(date("Y"), 2, "0", STR_PAD_LEFT);
                    $mes = str_pad(date("m"), 2, "0", STR_PAD_LEFT);
                    ?>
                    <div class="row">
                        <div class="col">
                            <?= formErp::select('mes', data::meses(), 'Mês', $mes, null, null, null, null, 1) ?>
                        </div>
                    </div>
                    <br /><br />
                    <div id="cal"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
</div>
<script>
    function cal(mes) {
        dados = 'id_pessoa=<?= $id_pessoa ?>&mes=' + mes;
        fetch('<?= HOME_URI ?>/habili/def/calendarioPlan.php', {
            method: "POST",
            body: dados,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
                .then(resp => resp.text())
                .then(resp => {
                    document.getElementById('cal').innerHTML = resp;
                });
    }
    function plan(id, id_pl, atualLetiva, id_pessoa) {
        document.getElementById("id_plano").value = id;
        document.getElementById("id_pessoa").value = id_pessoa;
        document.getElementById("id_pl").value = id_pl;
        document.getElementById("atualLetivaSet").value = atualLetiva;
        document.getElementById('form').submit();
        if (document.getElementById('cadModal')){
            var myModal = new bootstrap.Modal(document.getElementById('cadModal'), {
                keyboard: true
            });
            myModal.show();
        }
    }
    function mes(id) {
        cal(id);
    }
    function trocaCiclo(id) {
        document.getElementById(id).submit();
    }
</script>
