<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cadamp
 *
 * @author mc
 */
class cadamp {

    public static function escolaFechaAbre($id_inst = null) {
        if (!empty($id_inst)) {
            $where = ['id_inst' => $id_inst];
            $fetch = 'fetch';
        } else {
            $fetch = 'fetchAll';
            $where = NULL;
        }

        return sql::get('cadam_fecha', '*', $where, $fetch);
    }

    public static function cadampesPorEscola($id_inst = NULL, $id_cargo = NULL, $diaSemana = NULL, $limit = NULL, $in = NULL, $notIn = NULL) {
        if (!empty($limit)) {
            $limit = "limit 0, $limit ";
        }
        if (!empty($notIn)) {
            $notIn = " and c.id_cad not in ($notIn) ";
        }
        if (!empty($in)) {
            $in = " and c.id_cad in ($in) ";
        }
        if (!empty($diaSemana)) {
            if (substr($diaSemana, -1) == 'G') {
                $di = substr($diaSemana, 0, 1);
                $diaSemana = " and("
                        . " c.dia like '%" . $di . "T%' "
                        . " or "
                        . " c.dia like '%" . $di . "M%' "
                        . ")";
            } else {
                $diaSemana = " and c.dia like '%$diaSemana%' ";
            }
        }
        if (empty($id_inst)) {
            $id_inst = tool::id_inst();
        } 

        if (!empty($id_cargo)) {
            $id_cargo = " and ce.fk_id_cargo in ($id_cargo) ";
        }
        $sql = "SELECT distinct "
                . " c.id_cad, c.dia, cl.class, s.n_sel, s.id_sel, p.n_pessoa, "
                . " p.tel1, p.tel2, p.tel3, p.email, ce.m, ce.t, ce.n, "
                . " c.cargos_e, c.fk_id_sel, c.fk_id_inscr, ce.fk_id_cargo, "
                . " c.tea, ce.fk_id_inst as ce, s.ordem, c.cad_pmb, c.contato, "
                . " ce.contato_esc,  ce.id_ce "
                . " FROM cadam_cadastro c "
                . " join pessoa p on p.id_pessoa = c.fk_id_pessoa "
                . " join cadam_escola ce on c.id_cad = ce.fk_id_cad left "
                . " join cadam_class cl on cl.fk_id_inscr = c.fk_id_inscr  and cl.fk_id_cargo = ce.fk_id_cargo  and cl.fk_id_sel = c.fk_id_sel "
                . " left join cadam_seletivas s on s.id_sel = c.fk_id_sel "
                . " WHERE ce.fk_id_inst = $id_inst "
                . " and c.ativo = 1 "
                . " $diaSemana "
                . $id_cargo
                . $in
                . $notIn
                . " order by ce.contato_esc, s.ordem, cl.class asc "
                . $limit;
        
        $query = pdoSis::getInstance()->query($sql);
        $dados = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($dados as $k => $v) {
            $dias = explode('|', $v['dia']);
            foreach ($dias as $d) {
                @$dados[$k]['dias'][substr($d, 0, 1)] .= substr($d, 1, 1) . ' ';
            }
        }

        return $dados;
    }

    public static function carregar_classificacao_cargo($id_cargo) {
            
        $sql = "SELECT c.id_cad, cl.fk_id_cargo, cl.class
        FROM cadam_cadastro c
        JOIN pessoa p ON p.id_pessoa = c.fk_id_pessoa
        LEFT JOIN cadam_class cl ON cl.fk_id_inscr = c.fk_id_inscr AND cl.fk_id_sel = c.fk_id_sel
        LEFT JOIN cadam_seletivas s ON s.id_sel = c.fk_id_sel
        WHERE c.ativo = 1 AND c.check_update = 1 AND cl.fk_id_cargo = $id_cargo
        ORDER BY cl.fk_id_cargo, s.ordem, cl.class ASC;";

        $query = pdoSis::getInstance()->query($sql);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $count = 0;
        foreach($result as $c=>$v){
            $count++;
            $id_cad = $v['id_cad'];
            $fk_id_cargo = $v['fk_id_cargo'];
            $class = $v['class'];
            //cadastra classificacao
            //$sql = "insert into cadam_classificacao_cargo_geral(fk_id_cad, fk_id_cargo, class, class_geral)values($id_cad,$fk_id_cargo,$class,$count)";
            //pdoSis::getInstance()->query($sql);
        }

    }

    public static function carrega_cargos() {
        
        $sql = "SELECT * from cadam_cargo ORDER BY id_cargo; ";

        $query = pdoSis::getInstance()->query($sql);
        $dados = $query->fetchAll(PDO::FETCH_ASSOC);

        // percorre cargo
        foreach($dados as $c=>$v) {
            echo $id_cargo = $v['id_cargo'];
            echo '<br>';
            cadamp::carregar_classificacao_cargo($v['id_cargo']);
        }

        return $dados;
    }

    public static function cadampesPorCargo($id_cargo = NULL) {
        
        $sql = "SELECT DISTINCT c.tea, c.id_cad, c.dia, ce.class, s.n_sel, s.id_sel, p.n_pessoa, 
        p.tel1, p.tel2, p.tel3, p.email, c.cargos_e, c.fk_id_sel, 
        c.fk_id_inscr, ce.fk_id_cargo, c.tea, s.ordem, c.cad_pmb, 
        c.contato, ce.id, ce.rodizio
       FROM cadam_cadastro c
       JOIN pessoa p ON p.id_pessoa = c.fk_id_pessoa
       JOIN cadam_classificacao_cargo_geral ce ON c.id_cad = ce.fk_id_cad
       LEFT JOIN cadam_seletivas s ON s.id_sel = c.fk_id_sel
       WHERE ce.fk_id_cargo = $id_cargo
       ORDER BY ce.rodizio, ce.class_geral ASC";

        $query = pdoSis::getInstance()->query($sql);
        $dados = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($dados as $k => $v) {
            $dias = explode('|', $v['dia']);
            foreach ($dias as $d) {
                @$dados[$k]['dias'][substr($d, 0, 1)] .= substr($d, 1, 1) . ' ';
            }
        }

        return $dados;
    }

    public static function jaContato($id_inst, $id_cargo, $diaSemana = NULL) {
        if (!empty($diaSemana)) {
            $diaSemana = " and ca.dia like '%$diaSemana%' ";
        }
        $data = date("Y-m-d");
        $sql = "SELECT "
                . " rm_reservado, cc.uniqid, email, n_pessoa, p.tel1, p.tel2, p.tel3, cc.id_con, ca.id_cad, ca.cad_pmb, cc.data_time, cc.contato "
                . " FROM `cadam_convoca` cc "
                . " join cadam_cadastro ca on ca.id_cad = cc.fk_id_cad "
                . " join pessoa p on p.id_pessoa = ca.fk_id_pessoa "
                . " WHERE `fk_id_inst` = $id_inst "
                . " AND `data_time` LIKE '$data%' "
                //  . " and ca.contato != '$data' "
                . $diaSemana
                . " and ca.cargos_e like '%|$id_cargo|%'  ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function get($id_cad) {
        $sql = "SELECT email, n_pessoa, p.tel1, p.tel2, p.tel3, ca.id_cad, ca.cad_pmb FROM cadam_cadastro ca"
                . " join pessoa p on p.id_pessoa = ca.fk_id_pessoa "
                . " WHERE `id_cad` = $id_cad ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function frequencia($mes, $cancelado = NULL, $id_inst = NULL, $ano = NULL) {
        if (empty($ano)) {
            $ano = date("Y");
        }

        if (empty($cancelado)) {
            $cancelado_ = " and cf.cancelado != 1 ";
        }
        $id_inst = tool::id_inst($id_inst);
        $sql = "SELECT "
                . " p.n_pessoa, p.id_pessoa, cc.cad_pmb, p.cpf, cf.horas, "
                . " cf.rm, cf.cancelado, cf.turmas, cf.periodo, "
                . " cg.id_cargo, cg.n_cargo, cf.dt_fr, cf.id_fr, "
                . " cf.fk_id_mot, cm.n_mot, cf.dia, cc.id_cad "
                . " FROM cadam_freq cf "
                . " JOIN cadam_cadastro cc on cc.id_cad = cf.fk_id_cad "
                . " join pessoa p on p.id_pessoa = cc.fk_id_pessoa "
                . " join cadam_cargo cg on cg.id_cargo = cf.fk_id_cargo "
                . " join cadam_motivo cm on cm.id_mot = cf.fk_id_mot "
                . " WHERE cf.fk_id_inst = $id_inst "
                . " AND ano = '$ano' "
                . " AND mes = '$mes' "
                . @$cancelado_
                . " order by cf.dia, n_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function tea($id_inst, $id_aluno = NULL, $id_cad = NULL, $ordem = NULL, $id_cargo = NULL) {
        if (!empty($id_cargo)) {
            $id_cargo = " and c.cargos_e like '%|$id_cargo|%'";
        }
        if (!empty($ordem)) {
            $ordem = "ORDER BY $ordem ASC";
        }
        if (!empty($id_cad)) {
            $id_cad = " and t.fk_id_cad = $id_cad";
        }
        if (!empty($id_aluno)) {
            $id_aluno = " and r.id_pessoa = $id_aluno";
        }
        $sql = "SELECT distinct c.cad_pmb, r.n_pessoa as aluno, "
                . " r.id_pessoa as rse, p.n_pessoa,t.fk_id_cad, "
                . " tu.n_turma, tu.id_turma  "
                . " FROM `cadam_tea` t "
                . " join pessoa r on r.id_pessoa = t.fk_id_pessoa "
                . " join cadam_cadastro c on c.id_cad = t.fk_id_cad "
                . " join pessoa p on p.id_pessoa = c.fk_id_pessoa "
                . ' join ge_turma_aluno ta on ta.fk_id_pessoa = r.id_pessoa '
                . " JOIN ge_turma_aluno_situacao tas on tas.id_tas = ta.fk_id_tas AND tas.id_tas = 0 "
                . ' join ge_turmas tu on tu.id_turma = ta.fk_id_turma '
                . ' join ge_periodo_letivo pl on pl.id_pl = tu.fk_id_pl '
                . " WHERE t.`fk_id_inst` = $id_inst "
                . ' and pl.at_pl = 1 '
                . " $id_aluno "
                . " $id_cad "
                . " $ordem ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function reservado($mes, $ano = NULL, $dia = NULL, $periodo = null, $id_cad) {
        if (empty($ano)) {
            $ano = date("Y");
        }

        if (!empty($dia)) {
            $dia = " and r.dia_ini <= $dia and r.dia_fim >= $dia ";
        }

        if (!empty($periodo)) {
            $periodo = " and r.periodo like '%$periodo%' ";
        }
        $sql = "SELECT "
                . " r.rm "
                . " FROM cadam_reserva r "
                . " WHERE r.ano = $ano "
                . " AND r.mes = $mes "
                . " and r.cancelado is NULL "
                . " and fk_id_cad = $id_cad "
                . $dia
                . $periodo
                . @$cancelado_;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array['rm'];
    }

    public static function reservaSet($mes, $ano = NULL, $dia = NULL, $periodo) {
        if (empty($ano)) {
            $ano = date("Y");
        }
        $res[0] = [0];
        $sql = "SELECT * FROM cadam_reserva r "
                . " WHERE r.ano = $ano "
                . " AND r.mes = $mes "
                . " and r.cancelado is NULL "
                . " and periodo like '%$periodo%' "
                . " and r.dia_ini <= $dia and r.dia_fim >= $dia "
                . " and cancelado is NULL ";
        ;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $res[$v['fk_id_cad']] = $v['rm'];
        }
        return $res;
    }

    public static function reserva($id_inst, $mes, $cancelado = NULL, $ano = NULL, $rm = NULL, $dia = NULL, $periodo = null, $id_cargo = NULL) {
        if (empty($ano)) {
            $ano = date("Y");
        }

        if (empty($cancelado)) {
            $cancelado_ = " and r.cancelado is NULL ";
        }

        if (!empty($rm)) {
            $rm = " and r.rm = $rm ";
        }

        if (!empty($dia)) {
            $dia = " and r.dia_ini <= $dia and r.dia_fim >= $dia ";
        }

        if (!empty($periodo)) {
            $periodo = " and r.periodo like '%$periodo%' ";
        }

        if (!empty($id_cargo)) {
            $id_cargo = " AND fk_id_cargo = $id_cargo ";
        }
        $sql = "SELECT "
                . " r.id_cr, r.periodo, r.cancelado, p2.n_pessoa as cad, "
                . " p.n_pessoa as prof, `dia_ini`, `dia_fim`, ca.n_cargo, c.cad_pmb, "
                . " c.cad_pmb,  p.tel1, p.tel2, p.tel3, p.email, c.id_cad "
                . " FROM cadam_reserva r "
                . " JOIN ge_funcionario f on f.rm = r.rm "
                . " JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " JOIN cadam_cadastro c on c.id_cad = r.fk_id_cad "
                . " JOIN pessoa p2 on p2.id_pessoa = c.fk_id_pessoa "
                . " JOIN cadam_cargo ca on ca.id_cargo = r.fk_id_cargo "
                . " WHERE r.fk_id_inst = $id_inst "
                . " AND r.ano = $ano "
                . " AND r.mes = $mes "
                . $id_cargo
                . $rm
                . $dia
                . $periodo
                . @$cancelado_;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function confereaLocado($id_cargo, $dia, $mes, $periodo, $id_inst = NULL, $rm = NULL, $id_cad = NULL, $aulas = NULL, $ano = NULL) {

        if (!empty($aulas)) {
            foreach (explode(',', $aulas) as $v) {
                $aulas_[] = " aulas like '%$v%' ";
            }
            $aulas = " AND (" . (implode(' or ', $aulas_)) . ') ';
        }

        if (!empty($id_inst)) {
            $id_inst = " AND `fk_id_inst` = $id_inst  ";
        }

        if (empty($ano)) {
            $ano = date("Y");
        }

        if (!empty($rm) && !empty($id_cad)) {
            $proc = "(`fk_id_cad` = '$id_cad' or `rm` = '$rm')";
        } elseif (!empty($rm)) {
            $proc = " `rm` = '$rm' ";
        } else {
            $proc = " `fk_id_cad` = '$id_cad' ";
        }


        $sql = " SELECT * FROM `cadam_freq` "
                . " WHERE $proc "
                . " AND `periodo` LIKE '$periodo' "
                // . " AND `fk_id_cargo` = $id_cargo "
                . " AND `dia` = $dia "
                . " AND `mes` = $mes "
                . " AND `ano` = $ano "
                . " AND cancelado is NULL "
                . $aulas;
        //. $id_inst;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function confereareservado($id_cargo, $dia_ini, $dia_fim, $mes, $periodo, $id_inst = NULL, $rm = NULL, $id_cad = NULL, $ano = NULL) {
        if ($id_cargo == 15) {
            $id_cargo = 3;
        }
        $periodo = str_split($periodo);

        foreach ($periodo as $v) {
            $per_[] = " periodo like '%$v%' ";
        }
        $per = " AND (" . (implode(' or ', $per_)) . ') ';

        if (!empty($id_inst)) {
            $id_inst = " AND `fk_id_inst` = $id_inst  ";
        }

        if (empty($ano)) {
            $ano = date("Y");
        }

        if (!empty($rm) && !empty($id_cad)) {
            $proc = "(`fk_id_cad` = '$id_cad' or `rm` = '$rm')";
        } elseif (!empty($rm)) {
            $proc = " `rm` = '$rm' ";
        } else {
            $proc = " `fk_id_cad` = '$id_cad' ";
        }


        $sql = " SELECT * FROM `cadam_reserva` "
                . " WHERE $proc "
                . " AND `fk_id_cargo` = $id_cargo "
                . " AND ("
                . "(`dia_ini` <= $dia_ini AND `dia_fim` >= $dia_ini )"
                . " OR (`dia_ini` <= $dia_fim AND `dia_fim` >= $dia_fim )"
                . ")"
                . " AND `mes` = $mes "
                . " AND `ano` = $ano "
                . " AND cancelado is NULL "
                . $id_inst
                . $per;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    /**
     * Histórico de atendimento 
     *
     * @param [int] $id_cad
     * @return array
     */
    public static function historico_atendimento($id_cad) {
        $sql = "SELECT t.obs_cs, t.justifica, t.dt_hora, t.atendente
                FROM cadam_convoca_sup AS t 
                WHERE t.fk_id_cad = $id_cad 
                ORDER BY t.dt_hora DESC ";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    /**
     * tentativas_atendiento function
     *
     * @param [int] $id_cad
     * @return int
     */
    public static function tentativas_atendimento($id_cad) {
        $sql = "select COUNT(*) AS tentativas
        FROM cadam_convoca_sup_tentativas AS t2
        WHERE t2.fk_id_cad = $id_cad AND t2.justifica = 2";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array['tentativas'];        
    }

    public static function registra_tentativas($justifica, $id_cad) {
        $sql = "insert into cadam_convoca_sup_tentativas(justifica,fk_id_cad)values('$justifica','$id_cad')";
        pdoSis::getInstance()->query($sql);   
    }
    
    public static function remove_tentativas($id_cad) {
        $sql = "delete FROM cadam_convoca_sup_tentativas WHERE fk_id_cad = $id_cad";
        pdoSis::getInstance()->query($sql);      
    }

    public static function verifica_tela_bloqueada($id_sup) {
        $sql = "SELECT tela_bloqueada, atendente FROM dttie_suporte_trab WHERE id_sup = $id_sup";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        return $array;  
    } 

    public static function verifica_tela_bloqueada_cadamp($id_cad) {
        $sql = "SELECT tela_bloqueada, atendente FROM cadam_cadastro WHERE id_cad = $id_cad";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        return $array;  
    }    

    public static function bloqueia_tela($id_sup, $atendente) {
        $sql = "UPDATE dttie_suporte_trab SET tela_bloqueada = 'S', atendente='$atendente' WHERE id_sup = $id_sup";
        $query = pdoSis::getInstance()->query($sql);
    } 

    public static function bloqueia_tela_cadamp($id_cad, $atendente) {
        $sql = "UPDATE cadam_cadastro SET tela_bloqueada = 'S', atendente='$atendente' WHERE id_cad = $id_cad";
        $query = pdoSis::getInstance()->query($sql);
    } 

    public static function desbloqueia_tela($id_sup) {
        $sql = "UPDATE dttie_suporte_trab SET tela_bloqueada = 'N', atendente=NULL WHERE id_sup = $id_sup";
        $query = pdoSis::getInstance()->query($sql);
    } 

    public static function desbloqueia_tela_cadamp($id_cad) {
        $sql = "UPDATE cadam_cadastro SET tela_bloqueada = 'N', atendente=NULL WHERE id_cad = $id_cad";
        $query = pdoSis::getInstance()->query($sql);
    } 


    public static function atualiza_classificacao($rodizio, $id_cad, $id_cargo ) {
        $sql = "UPDATE cadam_classificacao_cargo_geral SET rodizio = $rodizio WHERE fk_id_cad = $id_cad and fk_id_cargo = $id_cargo ";
        $query = pdoSis::getInstance()->query($sql);
    }

}
