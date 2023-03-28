<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of escola
 *
 * @author marco
 */
class escola {

    public $_nome;
    public $_email;
    public $_ativo;
    public $_cie;
    public $_fk_id_tp_ens;
    public $_id_inst;
    public $_classeAF;
    public $_ato_cria;
    public $_ato_municipa;
    public $_latitude;
    public $_longitude;
    public $_maps;

    public function __construct($id = null, $search = 'id_inst') {
        if (empty($id)) {
            $id = tool::id_inst();
        }
        $sql = "select "
                . "id_inst, n_inst, email, cie_escola, ativo, fk_id_tp_ens,classe, "
                . "ato_cria, ato_municipa, latitude, longitude, maps, "
                . " esc_site, esc_contato, terceirizada, id_escola "
                . "from instancia "
                . "left join ge_escolas on ge_escolas.fk_id_inst = instancia.id_inst "
                . "where $search = '$id' ";
        $query = pdoSis::getInstance()->query($sql);
        $esc = $query->fetch(PDO::FETCH_ASSOC);

        $this->_id_inst = $esc['id_inst'];
        $this->_nome = $esc['n_inst'];
        $this->_email = $esc['email'];
        $this->_cie = $esc['cie_escola'];
        $this->_classeAF = $esc['classe'];
        $this->_ativo = $esc['ativo'] == 1 ? 'Sim' : 'Nao';
        $this->_fk_id_tp_ens = str_replace('|', ',', substr($esc['fk_id_tp_ens'], 1, -1));
        $this->_ato_cria = @$esc['ato_cria'];
        $this->_ato_municipa = @$esc['ato_municipa'];
        $this->_latitude = trim(@$esc['latitude']);
        $this->_longitude = trim(@$esc['longitude']);
        $this->_maps = @$esc['maps'];
        $this->_site = @$esc['esc_site'];
        $this->_contato = @$esc['esc_contato'];
        $this->_terceirizada = @$esc['terceirizada'];
        $this->_id_escola = @$esc['id_escola'];
    }

    /**
     * 
     * @return type todos os predio da instancia. Se houver mais de um predio, o primeiro (0) e sempre a sede
     */
    public function endereco($sede = NUll) {
        if ($sede == 1) {
            $sede_ = "and sede = 1";
        } else {
            $sede_ = NULL;
        }
        $sql = "SELECT "
                . "n_predio as nome, "
                . "descricao, ativo, cep, logradouro, num, complemento, bairro, cidade, uf, id_predio FROM `instancia_predio` "
                . "join predio on predio.id_predio = instancia_predio.fk_id_predio "
                . "WHERE `fk_id_inst` = " . $this->_id_inst . " $sede_ order by sede desc ";
        $query = pdoSis::getInstance()->query($sql);
        if ($sede == 1) {
            $p = $query->fetch(PDO::FETCH_ASSOC);
            $tel = sql::get('telefones', 'num', ['fk_id_pessoa' => $p['id_predio']]);
            foreach ($tel as $t) {
                $tel_[] = $t['num'];
            }
            @$p['telefones'] = $tel_;
        } else {
            $p = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($p as $k => $pp) {
                $tel = sql::get('telefones', 'num', ['fk_id_pessoa' => $pp['id_predio']]);
                unset($tel_);
                foreach ($tel as $t) {
                    $tel_[] = $t['num'];
                }
                @$p[$k]['telefones'] = $tel_;
            }
        }

        return $p;
    }

    /**
     * 
     * @param type $sede 1 para retornar só a sede
     * @return type todos os predio da instancia. Se houver mais de um predio, o primeiro (0) e sempre a sede
     */
    public function enderecoEstruturado($sede = NUll) {
        if ($sede == 1) {
            $sede_ = "and sede = 1";
        }
        $sql = "SELECT "
                . "n_predio as nome, "
                . "descricao, ativo, cep, logradouro, num, complemento, bairro, cidade, uf, id_predio, tel1, tel2, tel3 FROM `instancia_predio` "
                . "join predio on predio.id_predio = instancia_predio.fk_id_predio "
                . "WHERE `fk_id_inst` = " . $this->_id_inst . " $sede_ order by sede desc ";
        $query = pdoSis::getInstance()->query($sql);
        if ($sede == 1) {
            $p = $query->fetch(PDO::FETCH_ASSOC);
            @$pe = $p['logradouro'] . ', ' . $p['num'] . ' - ' . $p['bairro'] . ' - ' . $p['cep'] . '<br />Telefone(s): ' . $p['tel1'] . ';' . $p['tel2'] . ';' . $p['tel3'];
        } else {
            $p = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($p as $k => $pp) {
                $pe[] = $pp['logradouro'] . ', ' . $pp['num'] . ' - ' . $pp['bairro'] . ' - ' . $pp['cep'] . '<br />Telefone(s): ' . $pp['tel1'] . ';' . $pp['tel2'] . ';' . $pp['tel3'] . ';';
            }
        }

        return $pe;
    }

    /**
     * 
     * @param string $sede 1 retorna soh as salas da sede
     * @param type $fields colunas: null = resumido, 1 = completas ou insira as colunas manualmente
     * @return type array com as salas
     */
    public function salas($sede = NUll, $fields = null) {
        if ($sede == 1) {
            $sede = "and sede = 1";
        }
        if (empty($fields)) {
            $fields = "id_predio, n_predio, id_sala, n_sala, n_ts, alunos_sala";
        } elseif ($fields == 1) {
            $fields = "id_predio, n_predio, sede, id_sala, n_sala, largura,  comprimento, piso, cadeirante, tomadas, computadores, ar, datashow, cortinas, wifi, cabo_rede, alunos_sala, n_ts";
        }
        $sql = "SELECT "
                . $fields
                . " FROM `instancia_predio` "
                . "join predio on predio.id_predio = instancia_predio.fk_id_predio "
                . "join salas on salas.fk_id_predio = predio.id_predio "
                . "join tipo_sala on tipo_sala.id_ts = salas.fk_id_ts "
                . "WHERE `fk_id_inst` = " . $this->_id_inst . " $sede order by sede desc ";
        $query = pdoSis::getInstance()->query($sql);
        $s = $query->fetchAll(PDO::FETCH_ASSOC);

        return $s;
    }

    /**
     * 
     * @return type tipos de ensinos nesta escola
     */
    public function ensinos() {
        $sql = "SELECT "
                . "n_tp_ens as nome, sigla, metro_alu as aluno_metro "
                . " FROM `ge_tp_ensino` WHERE `id_tp_ens` IN (" . $this->_fk_id_tp_ens . ")";
        $query = pdoSis::getInstance()->query($sql);
        $e = $query->fetchAll(PDO::FETCH_ASSOC);
        return $e;
    }

    public function cursos() {
        $sql = "SELECT "
                . " id_curso, n_curso, sg_curso, un_letiva, qt_letiva, sg_letiva, n_tp_ens, id_tp_ens "
                . " FROM `ge_tp_ensino` "
                . " join ge_cursos on ge_cursos.fk_id_tp_ens = ge_tp_ensino.id_tp_ens "
                . " WHERE `id_tp_ens` IN (" . $this->_fk_id_tp_ens . ") "
                . " order by n_curso, n_tp_ens ";
        $query = pdoSis::getInstance()->query($sql);
        $e = $query->fetchAll(PDO::FETCH_ASSOC);
        asort($e);
        return $e;
    }

    public function turmas($ciclo = NUll, $id_curso = NULL, $id_ensino = NULL, $ano = NULL) {
        if (empty($ano)) {
            $ano = "and ge_periodo_letivo.at_pl = 1";
        }
        if (!empty($ciclo)) {
            $ciclo = "and sg_ciclo = '$ciclo'";
        }
        if (!empty($id_curso)) {
            $id_curso = "and id_curso in ($id_curso)";
        }
        if (!empty($id_ensino)) {
            $id_ensino = "and fk_id_tp_ens = '$id_ensino'";
        }

        $sql = "SELECT "
                . " fk_id_pl id_pl, id_curso, qt_letiva, atual_letiva, un_letiva, id_turma, n_turma, codigo, periodo, prodesp, status, n_ciclo, id_ciclo, aprova_automatico, n_curso, n_grade, id_grade, n_sala, cadeirante "
                . " FROM `ge_turmas` "
                . " join ge_periodo_letivo on ge_periodo_letivo.id_pl = ge_turmas.fk_id_pl "
                . " join ge_ciclos on ge_ciclos.id_ciclo =  ge_turmas.fk_id_ciclo "
                . " join ge_cursos on ge_cursos.id_curso = ge_ciclos.fk_id_curso "
                . " left join ge_grades on ge_grades.id_grade = ge_turmas.fk_id_grade "
                . " left join salas on salas.id_sala = ge_turmas.fk_id_sala "
                . " WHERE `fk_id_inst` =  " . $this->_id_inst
                . " and ge_periodo_letivo.at_pl = 1 "
                . " $ano "
                . $ciclo
                . $id_curso
                . $id_ensino
                . "order by n_curso, n_turma, sg_ciclo";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function professores($disciplina = NULL) {
        if (!empty($disciplina)) {
            $disciplina = "and disciplinas like '%$disciplina%'";
        }
        $where = "where a.fk_id_inst = " . $this->_id_inst . " $disciplina order by n_pessoa ";
        $sql = "SELECT a.`fk_id_inst`, a.`rm`, a.`disciplinas`, a.`hac_dia`, a.`hac_periodo`, a.`email`, a.`nao_hac`, p.n_pessoa "
                . " FROM `ge_prof_esc` a "
                . " join ge_funcionario f on f.rm = a.rm "
                . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . $where;
        $query = pdoSis::getInstance()->query($sql);
        $pf = $query->fetchAll(PDO::FETCH_ASSOC);

        return $pf;
    }

    public function cabecalho($ato = NULL) {
        if (!empty($this->_ato_cria) && !empty($this->_ato_municipa) && !empty($ato)) {
            $ato = '<table style="width: 100%"><tr><td>Ato de Criação: ' . $this->_ato_cria . '</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>Ato de Municipalização: ' . $this->_ato_municipa . '</td></tr></table>';
        }

        return '<table style="width: 100%">'
                . '<tr><td rowspan="3"><img style="width: 80px" src="' . HOME_URI . '/views/_images/brasao.jpg"/></td><td style = "text-align: center"><div style="font-size: 15px; font-weight: bold">'. ucfirst(CLI_NOME) .'<br>SE - Secretaria de Educação</div><div style="font-size: 11px; text-align: center">' . $this->_nome . '<br>' . $this->_email . '</div></td><td rowspan="3" width: 200px><img style="width: 200px" src="' . HOME_URI . '/views/_images/logo_relatorio.jpg"/></td></tr>'
                . '<tr><td style="font-size: 8px;text-align: center">' . $this->enderecoEstruturado(1) . '</td></tr>'
                . '</table>' . @$ato;
    }

    public function contaAluno($id_turma = NULL, $dt_inicio = NULL, $dt_fim = NULL) {
        if (empty($dt_inicio)) {
            $dt_inicio = date("Y") . '0101';
            $dt_fim = date("Ym") . '28';
        }
        $sql = "select t.codigo as codigo_classe, a.situacao, a.dt_matricula, a.dt_transferencia  "
                . "from  ge_turma_aluno a "
                . "join ge_turmas t on t.id_turma = a.fk_id_turma "
                . "where t.fk_id_inst = " . $this->_id_inst . " "
                . "and t.periodo_letivo = '" . date("Y") . "'";
        $query = pdoSis::getInstance()->query($sql);
        $alunos = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($alunos as $v) {
            @$result[$v['codigo_classe']]['total']++;
            @$result[$v['codigo_classe']][$v['situacao']]++;
            if (str_replace('-', '', $v['dt_matricula']) > $dt_inicio && str_replace('-', '', $v['dt_matricula']) < $dt_fim) {
                @$result[$v['codigo_classe']]['suplementar']++;
            }
            if (str_replace('-', '', $v['dt_transferencia']) > $dt_inicio && str_replace('-', '', $v['dt_transferencia']) < $dt_fim) {
                @$result[$v['codigo_classe']]['p_transferencia']++;
            }
        }
        return $result;
    }

    public function alunos($search = null, $id_pl = null, $field = 'id_turma') {
        if ($id_pl) {
            $id_pl = " AND t.fk_id_pl = $id_pl ";
        } else {
            $id = ng_main::periodosAtivos();
            $id_pl = " AND t.fk_id_pl in (" . implode(',', $id) . ") ";
        }
        if ($search) {
            $search = " AND t.$field = $search ";
        }
        $sql = "SELECT "
                . " ta.chamada, p.n_pessoa, p.id_pessoa, t.n_turma, ta.fk_id_tas, t.periodo, t.fk_id_ciclo "
                . " FROM ge_turmas t JOIN ge_turma_aluno ta on ta.fk_id_turma = t.id_turma "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " WHERE t.fk_id_inst = " . $this->_id_inst
                . $id_pl
                . $search
                . " order by t.n_turma, ta.chamada";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

}
