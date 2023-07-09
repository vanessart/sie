<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ng_escola
 *
 * @author marco
 */
class ng_escola {

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
    public $_site;
    public $_contato;
    public $_terceirizada;
    public $_id_escola;
    public $_visualizar;
    public $_manutencao;

    public function __construct($id = null, $search = 'id_inst') {
        if (empty($id)) {
            $id = toolErp::id_inst();
        }
        $sql = "select "
                . "id_inst, n_inst, email, cie_escola, ativo, fk_id_tp_ens,classe, "
                . "ato_cria, ato_municipa, latitude, longitude, maps, "
                . " esc_site, esc_contato, terceirizada, id_escola, visualizar, manutencao "
                . "from instancia "
                . "left join ge_escolas on ge_escolas.fk_id_inst = instancia.id_inst "
                . "where $search = '$id'";
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
        $this->_latitude = @$esc['latitude'];
        $this->_longitude = @$esc['longitude'];
        $this->_maps = @$esc['maps'];
        $this->_site = @$esc['esc_site'];
        $this->_contato = @$esc['esc_contato'];
        $this->_terceirizada = @$esc['terceirizada'];
        $this->_id_escola = @$esc['id_escola'];
        $this->_visualizar = @$esc['visualizar'];
        $this->_manutencao = @$esc['manutencao'];
    }

    public static function longLat($id_inst) {
        $sql = "SELECT `latitude`, `longitude`, maps FROM `ge_escolas` WHERE `fk_id_inst` = $id_inst";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function alunoPorTurma($id_turma) {
        $sql = "SELECT "
                . " ta.chamada, p.n_pessoa, tas.n_tas, tas.n_tas as situacao, ta.id_turma_aluno, p.id_pessoa, p.sexo, p.mae, p.dt_nasc, p.dt_gdae, t.dt_gdae as tgdae, p.ra, p.ra_dig, p.ra_uf, ta.fk_id_sit_sed, ta.fk_id_tas  "
                . " FROM ge_turma_aluno ta "
                . " join ge_turma_aluno_situacao tas on tas.id_tas = ta.fk_id_tas"
                . " join ge_turmas t on t.id_turma= ta.fk_id_turma "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " WHERE fk_id_turma = " . $id_turma
                . " order by chamada ";
        $query = pdoSis::getInstance()->query($sql);
        $alunos = $query->fetchAll(PDO::FETCH_ASSOC);

        return $alunos;
    }

    /**
     * 
     * @param type $id_turma
     * @param type $disciplina mostra o nome da disciplina no lugar do id_disc
     * @return type
     */
    public static function horario($id_turma, $disciplina = null) {
        if ($disciplina) {
            $disc = ng_main::disciplinas($id_turma, 'n_disc');
            $disc['nc']['n_disc'] = 'Núcleo Comum';
        }
        $h = sql::get('ge_horario', '*', ['fk_id_turma' => $id_turma]);
        foreach ($h as $v) {
            if ($disciplina) {
                if (!empty($disc[$v['iddisc']])) {
                    $n = $disc[$v['iddisc']]['n_disc'];
                } else {
                    $n = null;
                }
            } else {
                $n = $v['iddisc'];
            }
            $horario[$v['dia_semana']][$v['aula']] = $n;
        }

        return @$horario;
    }

    public function cabecalho($ato = NULL) {
        if (!empty($this->_ato_cria) && !empty($this->_ato_municipa) && !empty($ato)) {
            $ato = '<table style="width: 100%"><tr><td>Ato de Criação: ' . $this->_ato_cria . '</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>Ato de Municipalização: ' . $this->_ato_municipa . '</td></tr></table>';
        }

        return '<table style="width: 100%">'
                . '<tr><td rowspan="3"><img style="width: 80px" src="' . HOME_URI . '/'. INCLUDE_FOLDER .'/images/brasao.jpg"/></td><td style = "text-align: center"><div style="font-size: 15px; font-weight: bold">Prefeitura Municipal de '. ucfirst(CLI_CIDADE) .'<br>SE - Secretaria de Educação</div><div style="font-size: 11px; text-align: center">' . $this->_nome . '<br>' . $this->_email . '</div></td><td rowspan="3" width: 200px><img style="width: 200px" src="' . HOME_URI . '/'. INCLUDE_FOLDER .'/images/logo_relatorio.jpg"/></td></tr>'
                . '<tr><td style="font-size: 8px;text-align: center">' . $this->enderecoEstruturado(1) . '</td></tr>'
                . '</table>' . @$ato;
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
                . "descricao, ativo, cep, logradouro, num, complemento, bairro, cidade, uf, id_predio FROM `instancia_predio` "
                . "join predio on predio.id_predio = instancia_predio.fk_id_predio "
                . "WHERE `fk_id_inst` = " . $this->_id_inst . " $sede_ order by sede desc ";
        $query = pdoSis::getInstance()->query($sql);
        if ($sede == 1) {
            $p = $query->fetch(PDO::FETCH_ASSOC);
            @$pe = $p['logradouro'] . ', ' . $p['num'] . ' - ' . $p['bairro'] . ' - ' . $p['cep'] . (!empty($p['tel1']) ? '<br />Telefone(s): ' . $p['tel1'] : '') . (!empty($p['tel2']) ? ' ' . $p['tel2'] : '') . (!empty($p['tel3']) ? ' ' . $p['tel3'] : '');
        } else {
            $p = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($p as $k => $pp) {
                $tel = sql::get('telefones', 'num', ['fk_id_pessoa' => $pp['id_predio']]);
                unset($tel_);
                foreach ($tel as $t) {
                    @$tel_ .= $t['num'];
                }
                $pe[] = $p['logradouro'] . ', ' . $p['num'] . ' - ' . $p['bairro'] . ' - ' . $p['cep'] . '<br />Telefone(s):' . $tel_;
            }
        }

        return $pe;
    }

    public function Cursos() {
        $sql = "SELECT "
                . " c.id_curso, c.n_curso  "
                . "FROM ge_cursos c JOIN ge_tp_ensino s on s.id_tp_ens = c.fk_id_tp_ens "
                . " WHERE c.id_curso in ( "
                . "SELECT fk_id_curso FROM `sed_inst_curso` WHERE `fk_id_inst` = " . $this->_id_inst . " "
                . ")";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return toolErp::idName($array);
    }

    /**
     * 
     * @return type todos os predio da instancia. Se houver mais de um predio, o primeiro (0) e sempre a sede
     */
    public function endereco() {

        $sql = "SELECT "
                . "n_predio as nome, "
                . "descricao, ativo, cep, logradouro, num, complemento, bairro, cidade, uf, id_predio, tel1, tel2, tel3 FROM `instancia_predio` "
                . "join predio on predio.id_predio = instancia_predio.fk_id_predio "
                . "WHERE `fk_id_inst` = " . $this->_id_inst . " and sede = 1 order by sede desc ";
        $query = pdoSis::getInstance()->query($sql);
        $p = $query->fetch(PDO::FETCH_ASSOC);

        return $p;
    }

    public static function turmasAtiva($id_inst = null) {
        $id_inst = toolErp::id_inst($id_inst);
        $sql = "SELECT "
                . "  t.id_turma, t.n_turma "
                . " FROM ge_turmas t "
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " WHERE t.fk_id_inst = $id_inst "
                . " AND pl.at_pl = 1 "
                . " order by t.n_turma ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($array) {
            return toolErp::idName($array);
        }
    }

    public static function turmasSegAtiva($id_inst = null) {
        $id_inst = toolErp::id_inst($id_inst);
        $sql = "SELECT "
                . "  t.id_turma, t.n_turma, c.id_curso, c.n_curso "
                . " FROM ge_turmas t "
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " JOIN ge_cursos c on c.id_curso = ci.fk_id_curso "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " WHERE t.fk_id_inst = $id_inst "
                . " AND pl.at_pl = 1 "
                . " order by c.n_curso, t.n_turma ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $t[$v['n_curso']][$v['id_turma']] = $v['n_turma'];
        }
        if (!empty($t)) {
            return $t;
        }
    }

    public function professores($disciplina = NULL) {
        if (!empty($disciplina)) {
            $disciplina = "and disciplinas like '%$disciplina%'";
        }
        $where = "where a.fk_id_inst = " . $this->_id_inst . " $disciplina order by n_pessoa ";
        $sql = "SELECT a.`fk_id_inst`, a.`rm`, a.`disciplinas`, a.`hac_dia`, a.`hac_periodo`, a.`email`, a.`nao_hac`, p.n_pessoa "
                . " FROM `ge_prof_esc` a "
                . " join ge_funcionario f on f.rm = a.rm and f.at_func = 1 "
                . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . $where;
        $query = pdoSis::getInstance()->query($sql);
        $pf = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($pf as $v) {
            $pr[$v['rm']] = $v;
        }
        if (!empty($pr)) {
            return $pr;
        }
    }

    public static function alocaProf($id_turma) {
        $sql = "Select a.fk_id_turma, a.iddisc, a.fk_id_inst, a.rm, a.prof2, a.suplementar, a.cit, p.n_pessoa from ge_aloca_prof a "
                . " join ge_funcionario f on f.rm = a.rm and f.at_func = 1"
                . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " where a.fk_id_turma = $id_turma ";
        $query = pdoSis::getInstance()->query($sql);
        $alocado = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($alocado) {
            foreach ($alocado as $v) {
                $result['aloca'][$v['iddisc']] = $v['rm'];
                $result['prof2'][$v['iddisc']][$v['rm']] = $v['prof2'];
                $result['suplementar'][$v['prof2']][$v['iddisc']] = $v['suplementar'];
                $result['cit'][$v['prof2']][$v['iddisc']] = $v['cit'];
                if ($v['cit'] == 1) {
                    $result['n_pessoa'][$v['rm']] = $v['n_pessoa'];
                }
            }
        }

        if (!empty($result)) {
            return $result;
        }
    }

    public function alocaProfInst($ciclos = null) {
        if ($ciclos) {
            $ciclos = " and t.fk_id_ciclo in (" . implode(', ', $ciclos) . ")";
        }
        $sql = "Select a.iddisc, a.fk_id_inst, a.rm, a.prof2, a.suplementar, a.cit, p.n_pessoa, p.id_pessoa, "
                . " t.id_turma, t.n_turma, t.fk_id_ciclo "
                . " from ge_aloca_prof a "
                . " join ge_funcionario f on f.rm = a.rm and f.at_func = 1"
                . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " join ge_turmas t on t.id_turma = a.fk_id_turma $ciclos "
                . " where a.fk_id_inst = $this->_id_inst "
                . " and a.cit = 1 "
                . " ORDER BY n_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $alocado = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($alocado) {
            foreach ($alocado as $v) {
                $idt[$v['rm']][$v['id_turma']] = $v['id_turma'];
                $t[$v['rm']][$v['id_turma']] = $v['n_turma'];
                $c[$v['rm']][$v['fk_id_ciclo']] = $v['fk_id_ciclo'];
            }
            foreach ($alocado as $v) {
                $result[$v['rm']] = [
                    'nome' => $v['n_pessoa'],
                    'rm' => $v['rm'],
                    'id_pessoa' => $v['id_pessoa'],
                    'turmas' => toolErp::virgulaE($t[$v['rm']]),
                    'id_turma' => $idt[$v['rm']],
                    'ciclos' => $c[$v['rm']]
                ];
            }
        }

        if (!empty($result)) {
            return $result;
        }
    }

}
