<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of hab
 *
 * @author vanessa
 */
class hab {

    /**
     * busca Grupos de habilidades dentro de uma disciplina
     * @param type $id_disc_grupo
     * @return array
     */
    public static function habilidades($id_competencia_opcoes) {
        $fields = "hab.peso1, hab.peso2, hab.peso3,  hab.peso4, hab.peso5, hab.peso6, hab.peso7, hab.peso8, hab.peso9, hab.peso10, hab.hab1, hab.hab2, hab.hab3, hab.hab4, hab.hab5, hab.hab6, hab.hab7, hab.hab8, hab.hab9, hab.hab10, hab.id_competencia_opcoes, hab.n_competencia_opcoes, hab_disc_grupo.n_disc_grupo, hab.fk_id_disc_grupo, hab_disc_grupo.id_disc_grupo, ge_cursos.n_curso, ge_cursos.id_curso, ge_ciclos.n_ciclo, ge_ciclos.id_ciclo, ge_disciplinas.n_disc, ge_disciplinas.id_disc ";
        $sql = "SELECT $fields FROM hab_disc_grupo hab_disc_grupo "
                . "INNER JOIN ge_cursos ON ge_cursos.id_curso = hab_disc_grupo.fk_id_curso "
                . "INNER JOIN ge_ciclos ON ge_ciclos.id_ciclo = hab_disc_grupo.fk_id_ciclo "
                . "LEFT OUTER JOIN ge_disciplinas ON hab_disc_grupo.fk_id_disc = ge_disciplinas.id_disc "
                . "LEFT OUTER join hab_competencia_opcoes hab ON hab.fk_id_disc_grupo = hab_disc_grupo.id_disc_grupo "
                //. "WHERE hab.id_competencia_opcoes = " . $id_competencia_opcoes
                . " ORDER BY hab_disc_grupo.n_disc_grupo";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function grupos($id_ciclo, $id_curso, $id_disc) {

        if (!empty($id_disc)) {
            @$where = " AND hab.fk_id_disc = " . $id_disc;
        }

        $sql = "SELECT id_disc_grupo, n_disc_grupo FROM hab_disc_grupo hab"
                . " WHERE hab.fk_id_ciclo = " . $id_ciclo
                . " AND hab.fk_id_curso = " . $id_curso
                . @$where
                . " ORDER BY n_disc_grupo ";
        $query = pdoSis::getInstance()->query($sql);
        $grupos = $query->fetchAll(PDO::FETCH_ASSOC);
        return $grupos;
    }

    public static function competencias($id_disc_grupo) {

        $sql = "SELECT n_competencia_opcoes, id_competencia_opcoes FROM hab_competencia_opcoes compt"
                . " WHERE "
                . " compt.fk_id_disc_grupo = " . $id_disc_grupo
                . " ORDER BY n_competencia_opcoes ";
        $query = pdoSis::getInstance()->query($sql);
        $competencias = $query->fetchAll(PDO::FETCH_ASSOC);

        return $competencias;
    }

    public static function turmas($id_inst) {
        $sql = "SELECT n_turma, id_turma FROM ge_turmas tur"
                . " WHERE tur.fk_id_inst = " . $id_inst
                . " ORDER BY n_turma ";

        $query = pdoSis::getInstance()->query($sql);
        $turmas = $query->fetchAll(PDO::FETCH_ASSOC);

        return $turmas;
    }

    public static function idturmas($id_inst) {
        $sql = "SELECT id_turma FROM ge_turmas tur"
                . " WHERE tur.fk_id_inst = " . $id_inst
                . " ORDER BY n_turma ";

        $query = pdoSis::getInstance()->query($sql);
        $turmas = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($turmas as $i) {
            $turma = $i['id_turma'] . "," . @$turma;
        }
        return $turma;
    }

    /**
     * Caalcula a nota do aluno calculando peso
     * @param type $id_pessoa
     * @return array
     */
    public static function habaluno($id_pessoa) {
        $sql = "SELECT c.fk_id_disc_grupo, c.id_competencia_opcoes, a.hab1*c.peso1 AS 'nota1', a.hab2*c.peso2 AS 'nota2', a.hab3*c.peso3 AS 'nota3', a.hab4*c.peso4 AS 'nota4', a.hab5*c.peso5 AS 'nota5', a.hab6*c.peso6 AS 'nota6', a.hab7*c.peso7 AS 'nota7', a.hab8*c.peso8 AS 'nota8', a.hab9*c.peso9 AS 'nota9', a.hab10*c.peso10 AS 'nota10'"
                . " FROM `hab_aluno` a"
                . " JOIN `hab_competencia_opcoes` c ON c.`id_competencia_opcoes` = a.`fk_id_competencia_opcoes`"
                . " WHERE a.fk_id_pessoa = " . $id_pessoa;

        $query = pdoSis::getInstance()->query($sql);
        $habaluno = $query->fetchAll(PDO::FETCH_ASSOC);

        return $habaluno;
    }

    public static function listescolas() {
        $sql = "select id_inst, n_inst from ge_escolas "
                . "join instancia on  instancia.id_inst = ge_escolas.fk_id_inst "
                . "order by n_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $escolas = $query->fetchAll(PDO::FETCH_ASSOC);
        /**
          $ciclos = sql::get(['ge_ciclos', 'ge_cursos'], '*', ['id_curso' => $id_curso, '>' => 'n_ciclo']);
         * 
         */
        return $escolas;
    }

    public static function disc($id_turma) {
        $fields = "hab.peso1, hab.peso2, hab.peso3,  hab.peso4, hab.peso5, hab.peso6, hab.peso7, hab.peso8, hab.peso9, hab.peso10, hab.hab1, hab.hab2, hab.hab3, hab.hab4, hab.hab5, hab.hab6, hab.hab7, hab.hab8, hab.hab9, hab.hab10, hab.id_competencia_opcoes, hab.n_competencia_opcoes, hab_disc_grupo.n_disc_grupo, hab.fk_id_disc_grupo, hab_disc_grupo.id_disc_grupo, ge_cursos.n_curso, ge_cursos.id_curso, ge_ciclos.n_ciclo, ge_ciclos.id_ciclo, disc.n_disc, disc.id_disc ";
        $sql = "SELECT $fields FROM hab_disc_grupo hab_disc_grupo "
                . "INNER JOIN ge_cursos ON ge_cursos.id_curso = hab_disc_grupo.fk_id_curso "
                . "INNER JOIN ge_ciclos ON ge_ciclos.id_ciclo = hab_disc_grupo.fk_id_ciclo "
                . "INNER JOIN ge_turmas ON ge_turmas.fk_id_ciclo = ge_ciclos.id_ciclo  "
                . "INNER JOIN ge_disciplinas disc ON hab_disc_grupo.fk_id_disc = disc.id_disc "
                . "INNER JOIN hab_competencia_opcoes hab ON hab.fk_id_disc_grupo = hab_disc_grupo.id_disc_grupo "
                . "WHERE ge_turmas.id_turma = " . $id_turma
                . " ORDER BY disc.n_disc";
        ?>
        <pre>
            <?php
            print_r($sql)
            ?>
        </pre>
        <?php
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function ciclos($id_inst) {
        $sql = "select * from ge_ciclos "
                . "join ge_turmas on  ge_turmas.fk_id_ciclo = ge_ciclos.id_ciclo "
                . "where ge_turmas.fk_id_inst = $id_inst "
                . "group by id_ciclo order by n_ciclo ";
        $query = pdoSis::getInstance()->query($sql);
        $ciclos = $query->fetchAll(PDO::FETCH_ASSOC);
        /**
          $ciclos = sql::get(['ge_ciclos', 'ge_cursos'], '*', ['id_curso' => $id_curso, '>' => 'n_ciclo']);
         * 
         */
        return $ciclos;
    }

    public static function disciplinas($id_inst) {
        $sql = "SELECT id_disc, n_disc, cg.fk_id_ciclo AS id_ciclo FROM ge_curso_grade cg "
                . " LEFT OUTER join ge_aloca_disc ad on ad.fk_id_grade = cg.fk_id_grade "
                . " LEFT OUTER join ge_disciplinas d on d.id_disc = ad.fk_id_disc "
                . " where cg.fk_id_ciclo in (select id_ciclo from ge_ciclos join ge_turmas on ge_turmas.fk_id_ciclo = ge_ciclos.id_ciclo where ge_turmas.fk_id_inst = " . $id_inst . " group by id_ciclo order by n_ciclo) "
                . "group by id_disc  order by n_disc";
        $query = pdoSis::getInstance()->query($sql);
        $disciplinas = $query->fetchAll(PDO::FETCH_ASSOC);

        return $disciplinas;
    }

    public static function professores($id_inst) {
        $sql = "SELECT "
                . "`n_pe`, p.rm, `disciplinas`, `hac_dia`, `hac_periodo`, n_inst, f.fk_id_pessoa  "
                . "FROM ge_prof_esc p "
                . " join instancia i on i.id_inst = p.fk_id_inst "
                . " join ge_funcionario f on f.rm = p.rm "
                . "WHERE p.fk_id_inst  = " . $id_inst;
        $query = pdoSis::getInstance()->query($sql);
        $professores = $query->fetchAll(PDO::FETCH_ASSOC);

        return $professores;
    }
/**
 * Só para o curso fundamental
 */
    public static function habilidadesList($id_ciclo = null, $id_disc = null) {
        if ($id_ciclo) {
            $id_ciclo = " AND ci.fk_id_ciclo = $id_ciclo ";
        }
        if ($id_disc) {
            $id_disc = " AND h.fk_id_disc = $id_disc ";
        }
        $sql = "SELECT "
                . " h.id_hab, h.codigo, h.descricao, h.fk_id_disc, ci.fk_id_ciclo, ci.atual_letiva "
                . " FROM coord_hab h "
                . " JOIN coord_hab_ciclo ci on ci.fk_id_hab = h.id_hab and h.fk_id_gh = (SELECT `fk_id_gh` FROM `coord_set_grupo_curso` WHERE `fk_id_cur` = 1 ) "
                . $id_ciclo
                . $id_disc
                . ' order by ci.atual_letiva, h.codigo';
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $hab[$v['fk_id_ciclo']][$v['fk_id_disc']][$v['id_hab']] = $v;
        }
        if (!empty($hab)) {
            ksort($hab);
            return $hab;
        }
    }

    public static function turmasCiclo($id_pl, $id_inst = null, $id_ciclo = null) {
        if ($id_inst) {
            $instWhere = " and t.fk_id_inst = $id_inst ";
        } else {
            $instWhere = null;
        }
        if ($id_ciclo) {
            $cicloWhere = " and ci.id_ciclo = $id_ciclo ";
        } else {
            $cicloWhere = null;
        }
        $sql = "select t.id_turma, t.n_turma, t.fk_id_inst id_inst, ci.id_ciclo from ge_turmas t "
                . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " and ci.fk_id_curso = 1 "
                . " and t.fk_id_pl = $id_pl and ci.id_ciclo != 31 $instWhere $cicloWhere "
                . " join instancia i on i.id_inst = t.fk_id_inst "
                . " order by i.n_inst, ci.n_ciclo, t.n_turma ";
        $query = pdoSis::getInstance()->query($sql);
        $turmasEsc = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($turmasEsc as $v) {
            $turmasCiclo[$v['id_ciclo']][$v['id_turma']] = ['n_turma' => $v['n_turma'], 'id_inst' => $v['id_inst']];
            $turmasEscCiclo[$v['id_inst']][$v['id_ciclo']][$v['id_turma']] = ['n_turma' => $v['n_turma'], 'id_inst' => $v['id_inst']];
            $turmasIds[] = $v['id_turma'];
        }
        if (!empty($turmasCiclo)) {
            ksort($turmasCiclo);
            return [$turmasCiclo, $turmasEscCiclo, $turmasIds];
        }
    }

    public static function habAplic($id_pl, $turmas) {
        $mongo = new mongoCrude('Diario');
        $option = [
            'projection' => [
                'id_hab' => 1,
                'id_turma' => 1,
            ],
        ];
        $hab = $mongo->query('habilidades.' . $id_pl, ['id_turma' => ['$in' => $turmas]], $option);
        foreach ($hab as $v) {
            $habTurma[$v->id_hab][$v->id_turma] = $v->id_turma;
        }
        if (!empty($habTurma)) {
            return $habTurma;
        }
    }

    public static function sondagem($id_curso, $ano, $id_pessoa, $id_disc = null, $num_sondagem = null) {
        $filter = [];
        if ($id_pessoa) {
            $filter['id_pessoa'] = $id_pessoa;
        }
        if ($num_sondagem) {
            $filter['num_sondagem'] = $num_sondagem;
        }
        if ($id_disc) {
            $filter['id_disc'] = $id_disc;
        }
        $mongo = new mongoCrude('Diario');
        $sondagem = $mongo->query('sondagem.' . $id_curso . '.' . $ano, $filter);
        if (!empty($sondagem[0]->hab)) {
            foreach ($sondagem[0]->hab as $k => $v) {
                if ($v) {
                    $hab[$k] = $k;
                }
            }
        }
        if (!empty($hab)) {
            return $hab;
        }
    }

    public static function sondagemAluno($id_curso, $ano, $id_disc, $id_turma, $num_sondagem) {
        $filter = [];
        if ($id_turma) {
            $filter['id_turma'] = $id_turma;
        }
        if ($num_sondagem) {
            $filter['num_sondagem'] = $num_sondagem;
        }
        if ($id_disc) {
            $filter['id_disc'] = $id_disc;
        }
        $mongo = new mongoCrude('Diario');
        $sondagem = $mongo->query('sondagem.' . $id_curso . '.' . $ano, $filter);

        if (!empty($sondagem)) {
            foreach ($sondagem as $v) {
                foreach ($v->hab as $k => $y) {
                    if ($y) {
                        $hab[$v->id_pessoa][$k] = $k;
                    }
                }
            }
        }

        if (!empty($hab)) {
            return $hab;
        }
    }

    public static function habQtPorGrupo() {
        $sql = "SELECT h.fk_id_gh, ci.fk_id_ciclo, COUNT(h.id_hab) ct FROM `coord_hab` h "
                . " join coord_hab_ciclo ci on ci.fk_id_hab = h.id_hab "
                . " GROUP BY ci.fk_id_ciclo, h.fk_id_gh;";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $groupCiclo[$v['fk_id_gh']][$v['fk_id_ciclo']] = $v['ct'];
        }
        if (!empty($groupCiclo)) {
            return $groupCiclo;
        }
    }

}
