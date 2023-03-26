<?php
if (!defined('ABSPATH'))
    exit;

//$ocorrencias = sql::get('profe_diario', 'id_profe_diario, ocorrencia, dt_data', " WHERE fk_id_turma = $id_turma AND fk_id_disc = $id_disc AND at_diario = 1 ORDER BY id_profe_diario DESC" );
$ocorrencias = $model->getOcorrencias(null,null,$id_turma,$id_disc,1);

?>
<div class="body">

    <form method="POST">
        <div class="row">
            <div class="col-md-">
               <?php formErp::textarea('1[ocorrencia]', null , 'Ocorrências', '300') ?>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col text-center">
                <?=
                formErp::hidden([
                    '1[fk_id_turma]' => $id_turma,
                    '1[fk_id_disc]' => $id_disc,
                    '1[dt_data]' => $data,
                    '1[fk_id_pessoa]' => toolErp::id_pessoa(),
                    'hlo' => 5,
                    "id_pl" => $id_pl,
                    "id_ciclo" => $id_ciclo,
                    "id_pessoa" => $id_pessoa,
                    "id_turma" => $id_turma,
                    "id_inst" => $id_inst,
                    'atual_letiva' => $dataFiltro['atual_letiva'],
                    'id_curso' => $id_curso,
                    'id_disc' => $id_disc,
                    "nome_disc" => $nome_disc,
                    "nome_turma" => $nome_turma,
                    "escola" => $escola,
                ])
                . formErp::hiddenToken('profe_diario', 'ireplace')
                . formErp::button('Salvar');
                ?>            
            </div>
        </div>
     
    </form>
    <?php
    if ($ocorrencias) {
            foreach ($ocorrencias as $k=> $v) {
                $sqlkey = formErp::token('profe_diario', 'ireplace');
                $hidden = [
                            '1[id_profe_diario]' => $v['id_profe_diario'],
                            '1[at_diario]' => 0,
                            'activeNav' => 5,
                        ];
                $ocorrencias[$k]['del'] = formErp::submit('&#x2718;', $sqlkey, $hidden ,null,null,'Deseja apagar esta Ocorrência ','btn btn-outline-danger');
            }
        $form['array'] = $ocorrencias;
        $form['fields'] = [
            'Dia' => 'dt_data',
            'Ocorrências' => 'ocorrencia',
            '||1' => 'del',
        ];
    }
    
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
