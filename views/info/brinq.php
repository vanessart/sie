<?php
ini_set('memory_limit', '500M');
?>
<div class="fieldBody">
    <div class="fieldTop">
        Lista de alunos para receber brinquedos
    </div>
    <br /><br />
    <?php
    $fields = " e.cie_escola as CIE, p.n_pessoa as NOME, cl.n_ciclo as CICLO, t.codigo as TURMA, p.dt_nasc as DT_NASC, p.sexo AS SEXO, p.mae AS MAE, p.pai AS PAI, ed.cep AS CEP, ed.logradouro LOGRADOURO, ed.num AS NUM, ed.complemento AS COMPL, p.certidao AS CERTIDAO, p.id_pessoa AS RSE";
    $sql = "select $fields from ge_turma_aluno ta "
            . "join ge_turmas t on t.id_turma = ta.fk_id_turma "
            . " join ge_ciclos cl on cl.id_ciclo = t.fk_id_ciclo "
            . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
            . " join ge_escolas e on e.fk_id_inst = t.fk_id_inst "
            . " join pessoa p on p.id_pessoa = ta.fk_id_pessoa "
            . " join endereco ed on ed.fk_id_pessoa = p.id_pessoa "
            . " where fk_id_tp = 1 "
            . " and  pl.at_pl = 1 "
            . " and p.dt_nasc >= '" . data::converteUS(@$_POST['ini']) . "' "
            . " and p.dt_nasc <= '" . data::converteUS(@$_POST['fim']) . "' "
            . " and ta.situacao like 'Frequente' ";

    echo $sql = "SELECT "
    . "t.fk_id_inst, p.n_pessoa, t.fk_id_ciclo, t.id_turma, p.dt_nasc, p.sexo, p.mae, p.pai, en.id_end, en.num, en.complemento, p.certidao, p.id_pessoa  "
    . " FROM pessoa p "
    . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa "
    . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
    . " left join endereco en on en.fk_id_pessoa = p.id_pessoa "
    . " WHERE ta.situacao = 'Frequente' "
    . " AND p.dt_nasc BETWEEN '2005-12-31' AND '2017-12-31'";
    $query = $model->db->query($sql);
    $form['array'] = $query->fetchAll();
    ?>
    <br /><br />
    <div class="row">
        <div class="col-sm-5"></div>
        <div class="col-sm-5">
            <?php
            formulario::exportarExcel($sql);
            ?>
        </div>
    </div>
    <br /><br />
    <?php
    /**
      $form['fields'] = [
      'CIE' => 'CIE',
      'NOME' => 'NOME',
      'CICLO' => 'CICLO',
      'TURMA' => 'TURMA',
      'DT_NASC' => 'DT_NASC',
      'SEXO' => 'SEXO',
      'MÃE' => 'MAE',
      'PAI' => 'PAI',
      'CEP' => 'CEP',
      'LOGRADOURO' => 'LOGRADOURO',
      'NUM' => 'NUM',
      'COMPLEMENTO' => 'COMPL',
      'CERTIDÃO' => 'CERTIDAO',
      'RSE' => 'RSE',
      ];
      tool::relatSimples($form);
     * 
     */
    ?>
</div>