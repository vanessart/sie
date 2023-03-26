<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of alunos
 *
 * @author marco
 */
class alunos {

    /**
     * busca pelo nome aceitando alguns erros de digitação
     * @param type $nome_
     * @param type $mae
     * @return type
     */
    public static function busca($nome_) {
        $nome_ = trim($nome_);
        $fields = "n_pessoa, pai, mae, dt_nasc, certidao, id_pessoa, n_social, sexo, ra ";

        if (is_numeric($nome_)) {
            $sql = "select * from pessoa p "
                    . "left join ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
                    . " where id_pessoa = " . $nome_
                    . " and f.rm is NULL ";
        } else {
            $nome = explode(' ', str_replace("'", "''", $nome_));

            if (count($nome) < 2) {
                ?>
                <div class="alert alert-danger text-center" style="font-size: 18px">
                    Insira nome e sobrenome
                </div>
                <?php
                return;
            } else {
                $sql = "select $fields from pessoa p "
                        . " left join ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
                        . " where "
                        . " n_pessoa like '". addslashes($nome_)."%' "
                        . " and f.rm is NULL "
                        . " order by n_pessoa ";
            }
        }
        if (!empty($sql)) {
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($array[0]['id_pessoa']) && !is_numeric($nome_)) {

                $array = ['a', 'o', 'i', 'y', 'w', 'e', 'A', 'O', 'I', 'Y', 'W', 'E', 'h', 'H'];
                unset($nomes);
                
                foreach ($nome as $v) {
                  
                    if (strlen($v) > 2) {
                        $nomeTmp = str_replace($array, '%', $v);
                        $nomes[] = " n_pessoa like '" .@$pc. $nomeTmp . "%' ";
                    }
                   $pc = "%";
                }
                $nomeBusca = implode(" and ", $nomes);
                 $sql = "select $fields from pessoa p "
                . " left join ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
                . " where "
                . "((n_pessoa like '%". addslashes($nome_)."%') "
                . " or "
                . "($nomeBusca) )"
                . " and f.rm is NULL "
                . " order by n_pessoa "
                . " limit 0, 25 ";

                $query = pdoSis::getInstance()->query($sql);
                $array = $query->fetchAll(PDO::FETCH_ASSOC);
            } elseif (is_numeric($nome_) && empty($array)) {
                ?>
                <div class="alert alert-danger text-center" style="font-size: 18px">
                    RSE Não Encontrado
                </div>
                <?php
            }
            foreach ($array as $k => $v) {

                $sql = "select"
                        . " i.n_inst, i.id_inst, t.codigo, ta.situacao, ta.situacao_final, c.n_curso, c.id_curso "
                        . " from ge_turma_aluno ta "
                        . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
                        . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                        . " join ge_cursos c on c.id_curso = ci.fk_id_curso "
                        . " left join instancia i "
                        . " on i.id_inst = ta.fk_id_inst "
                        . " where ta.fk_id_pessoa = " . $v['id_pessoa']
                        . " order by dt_matricula asc ";
                $query = pdoSis::getInstance()->query($sql);
                $aluTurma = $query->fetchALL(PDO::FETCH_ASSOC);
                @$contaAtivo = 0;
                @$contaInativo=0;
                foreach ($aluTurma as $alu) {

                    if ($alu['situacao'] == "Frequente" && empty($alu['situacao_final'])) {
                        $array[$k]['frequente'][$alu['id_curso']] = $alu['id_curso'];
                        $array[$k]['cursoAtivo'][@$contaAtivo]['id_curso'] = $alu['id_curso'];
                        $array[$k]['cursoAtivo'][@$contaAtivo]['n_curso'] = $alu['n_curso'];
                        $array[$k]['cursoAtivo'][@$contaAtivo]['id_inst'] = $alu['id_inst'];
                        $array[$k]['cursoAtivo'][@$contaAtivo]['n_inst'] = $alu['n_inst'];
                        $array[$k]['cursoAtivo'][@$contaAtivo]['situacao'] = $alu['situacao'];
                        $array[$k]['cursoAtivo'][@$contaAtivo]['codigo'] = $alu['codigo'];
                         @$contaAtivo ++;
                   } else {
                        $array[$k]['cursoInativo'][@$contaInativo]['id_curso'] = $alu['id_curso'];
                        $array[$k]['cursoInativo'][@$contaInativo]['n_curso'] = $alu['n_curso'];
                        $array[$k]['cursoInativo'][@$contaInativo]['id_inst'] = $alu['id_inst'];
                        $array[$k]['cursoInativo'][@$contaInativo]['n_inst'] = $alu['n_inst'];
                        $array[$k]['cursoInativo'][@$contaInativo]['situacao'] = $alu['situacao'];
                        $array[$k]['cursoInativo'][@$contaInativo]['codigo'] = $alu['codigo'];
                          @$contaInativo ++;
                  }
                }
            }

            return $array;
        } else {
            return 1;
        }
    }

    /**
     * busca aluno por nome ou idessoa que também é o RSE é devolve um relatorio
     * @param type $search
     */
    public static function relatAluno($search) {
        $where = " (n_pessoa LIKE '%$search%' OR id_pessoa = '$search') ";
        $sql = "SELECT * FROM pessoa "
                . "where "
                . $where
                . " ORDER BY n_pessoa limit 0, 100";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $sqlkey = DB::sqlKey('pessoa', 'delete');
        foreach ($array as $k => $v) {
            $array[$k]['edit'] = formOld::submit('Editar', NULL, $v);
            $array[$k]['acesso'] = formOld::submit('Acessar Usuário', NULL, ['user' => $v['id_pessoa']], HOME_URI . '/adm/user');
        }
        $form['array'] = $array;
        $form['fields'] = [
            'RSE' => 'id_pessoa',
            'Nome' => 'n_pessoa',
            'Dt. Nasc.' => 'dt_nasc',
            'CPF' => 'cpf',
            'E-mail' => 'email',
            'Nome Social' => 'n_social',
            '||2' => 'edit',
            '||3' => 'acesso'
        ];

        tool::relatSimples($form);
    }

    public static function listar($id_turma = NULL, $fields = NULL) {

        if (!empty($id_turma)) {
            $id_turma = " and t.id_turma = $id_turma";
        }
        if (empty($fields)) {
            $fields = "p.id_pessoa, p.sexo, ta.chamada, situacao, origem_escola, id_turma_aluno, "
                    . "destino_escola, dt_transferencia, n_pessoa, dt_nasc, ra, "
                    . "id_turma, n_turma, t.fk_id_ciclo, codigo, t.periodo_letivo, letra ";
        }
        $sql = "SELECT $fields FROM pessoa p "
                . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa "
                . " JOIN ge_turma_aluno_situacao tas on tas.id_tas = ta.fk_id_tas AND tas.id_tas = 0 "
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " WHERE 1 "
                . " $id_turma "
                . " ORDER BY n_turma, chamada";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function alunosGet($id_inst = NULL, $fields = " p.id_pessoa, p.n_pessoa, t.codigo, ta.chamada, id_ciclo") {
        if (empty($id_inst)) {
            $id_inst = tool::id_inst();
        }
        $sql = "select  $fields from ge_turma_aluno ta "
                . " JOIN ge_turma_aluno_situacao tas on tas.id_tas = ta.fk_id_tas AND tas.id_tas = 0 "
                . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " join pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " where t.fk_id_inst = $id_inst "
                . " and pl.at_pl = 1 "
                . " order by n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    /**
     * incluir id="busca" onkeypress="complete()" no input
     */
    public static function AlunosAutocomplete($id_inst = NULL, $fields = "p.id_pessoa, p.n_pessoa, t.codigo, ta.chamada") {
        if (empty($id_inst)) {
            $id_inst = tool::id_inst();
        }

        $array = alunos::alunosGet($id_inst, $fields)
        ?>


        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="/resources/demos/style.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="<?php echo HOME_URI; ?>/views/_js/jquery-ui.js"></script>
        <script>
            function complete() {

                var buscar = [
        <?php
        foreach ($array AS $value) {
            ?>
                        "<?php
            echo strtoupper(str_replace('"', "'", @$value['n_pessoa']))
            . '|' . @$value['id_pessoa']
            . '|' . @$value['chamada']
            . '|' . @$value['codigo']
            ?>",
            <?php
        }
        ?>
                ];
                $("#busca").autocomplete({
                    source: buscar,
                    campo_adicional: ['#rse', '#chamada', '#codigo']
                });
            }
        </script>
        <?php
    }

}
