<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ng_aluno
 *
 * @author marco
 */
class ng_aluno extends ng_pessoa {

    public $responsaveis;
    public $vidaEscolar;

    public function vidaEscolar($id_pl = null) {
        if ($id_pl) {
            $id_pl = " AND id_pl in ($id_pl) ";
        }
        $sql = "select "
                . " i.id_inst, t.codigo, t.n_turma, t.id_turma, i.n_inst, sf.n_sf, t.fk_id_pl, pl.id_pl, pl.n_pl, "
                . " ta.chamada, tas.n_tas, tas.id_tas, ta.situacao_final, ta.fk_id_ciclo_aluno, t.periodo_letivo, "
                . " dt_matricula, dt_transferencia, origem_escola, id_turma_aluno, destino_escola, "
                . " c.id_curso, pl.at_pl, ci.id_ciclo "
                . " from ge_turma_aluno ta "
                . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl $id_pl "
                . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " join ge_cursos c on c.id_curso = ci.fk_id_curso "
                . " join instancia i on i.id_inst = t.fk_id_inst "
                . " join ge_turma_aluno_situacao tas on tas.id_tas = ta.fk_id_tas "
                . " join ge_situacao_final sf on sf.id_sf = ta.fk_id_sf "
                . " "
                . " where fk_id_pessoa = " . $this->id_pessoa . " "
                . " order by t.periodo_letivo desc, tas.id_tas asc";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($array)) {
            $this->vidaEscolar = $array;
            return $array;
        }
    }

    public function responsaveis() {

        $sql = " SELECT "
                . " r.responsavel, p.id_pessoa, p.n_pessoa, p.cpf, p.email, p.emailgoogle, rt.n_rt, rt.id_rt, r.retirada, r.app, r.trabalho, r.trabalho_endereco FROM ge_aluno_responsavel r "
                . " left JOIN ge_responsavel_tipo rt on rt.id_rt = r.fk_id_rt"
                . " JOIN pessoa p on p.id_pessoa = r.fk_id_pessoa_resp "
                . " WHERE r.fk_id_pessoa_aluno = " . $this->id_pessoa;

        /**
          $sql = " SELECT "
          . " r.responsavel, p.id_pessoa, p.n_pessoa, p.cpf, p.email, p.emailgoogle, rt.n_rt, r.retirada, r.app FROM ge_aluno_responsavel r "
          . " left JOIN ge_responsavel_tipo rt on rt.id_rt = r.fk_id_rt"
          . " JOIN pessoa p on p.id_pessoa = r.fk_id_pessoa_resp "
          . " WHERE r. retirada = 1 AND r.fk_id_pessoa_aluno = " . $this->id_pessoa;
         * 
         */
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $tel = [];
        if ($array) {
            foreach ($array as $k => $v) {
                $resp[$v['id_pessoa']] = $v;
                $resp[$v['id_pessoa']]['tel'] = ng_pessoa::telefone($v['id_pessoa']);
            }
        }
        if (!empty($resp)) {
            $this->responsaveis = $resp;
            return $resp;
        }
    }

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
            $contaAtivo = 0;
            $contaInativo=0;
            foreach ($array as $k => $v) {

                $sql = "select"
                        . " i.n_inst, i.id_inst, t.codigo, ta.situacao, ta.situacao_final, c.n_curso, c.id_curso, ta.fk_id_tas "
                        . " from ge_turma_aluno ta "
                        . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
                        . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                        . " join ge_cursos c on c.id_curso = ci.fk_id_curso "
                        . " left join instancia i on i.id_inst = ta.fk_id_inst "
                        . " where ta.fk_id_pessoa = " . $v['id_pessoa']
                        . " ORDER BY dt_matricula ASC ";
                $query = pdoSis::getInstance()->query($sql);
                $aluTurma = $query->fetchALL(PDO::FETCH_ASSOC);

                if (empty($aluTurma)) {
                    unset($array[$k]);
                    continue;
                }

                foreach ($aluTurma as $alu) {
                    if ($alu['fk_id_tas'] == "0" && empty($alu['situacao_final'])) {
                        $array[$k]['frequente'][$alu['id_curso']] = $alu['id_curso'];
                        $array[$k]['cursoAtivo'][$contaAtivo]['id_curso'] = $alu['id_curso'];
                        $array[$k]['cursoAtivo'][$contaAtivo]['n_curso'] = $alu['n_curso'];
                        $array[$k]['cursoAtivo'][$contaAtivo]['id_inst'] = $alu['id_inst'];
                        $array[$k]['cursoAtivo'][$contaAtivo]['n_inst'] = $alu['n_inst'];
                        $array[$k]['cursoAtivo'][$contaAtivo]['situacao'] = $alu['situacao'];
                        $array[$k]['cursoAtivo'][$contaAtivo]['codigo'] = $alu['codigo'];
                        $contaAtivo++;
                   } else {
                        $array[$k]['cursoInativo'][$contaInativo]['id_curso'] = $alu['id_curso'];
                        $array[$k]['cursoInativo'][$contaInativo]['n_curso'] = $alu['n_curso'];
                        $array[$k]['cursoInativo'][$contaInativo]['id_inst'] = $alu['id_inst'];
                        $array[$k]['cursoInativo'][$contaInativo]['n_inst'] = $alu['n_inst'];
                        $array[$k]['cursoInativo'][$contaInativo]['situacao'] = $alu['situacao'];
                        $array[$k]['cursoInativo'][$contaInativo]['codigo'] = $alu['codigo'];
                        $contaInativo++;
                  }
                }
            }

            return $array;
        } else {
            return 1;
        }
    }

    /**
     * Busca as necessidades especiais do aluno
     * @param type int $id_pessoa
     * @param type string $ra
     * @return type array com as necessidades especiais 
     */
    public static function necessidadesEspeciais($id_pessoa, $ra) {
        $sql = "SELECT ne.id_ne, ne.n_ne FROM ge_aluno_necessidades_especiais ane "
            . "LEFT JOIN ge_necessidades_especiais ne ON ane.fk_id_ne = ne.id_ne "
            . " WHERE ane.fk_id_pessoa = $id_pessoa "
            . " AND ane.ra = '$ra' ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array ?? [];
    }

    public static function get($id_pessoa, $id_inst = null) {
        if (empty($id_inst)) {
            $id_inst = tool::id_inst();
        }
        $sql = "SELECT p.id_pessoa, p.n_pessoa, t.codigo, ta.chamada, ci.id_ciclo, t.id_turma "
                . " FROM ge_turma_aluno ta "
                . " JOIN ge_turma_aluno_situacao tas on tas.id_tas = ta.fk_id_tas AND tas.id_tas = 0 "
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " WHERE ta.fk_id_pessoa = $id_pessoa "
                . " AND t.fk_id_inst = $id_inst "
                . " AND pl.at_pl = 1 ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }
}
