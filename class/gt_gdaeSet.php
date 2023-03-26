<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of gdaeSet
 *
 * @author mc
 */
class gt_gdaeSet {

    /**
     * 
     * @param type $periodoDia M, N ou T
     * @return string periodo do dia
     */
    public static function turno($periodoDia = NULL, $field = NULL) {
        $sql = "SELECT * FROM gdae.`turno` ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $turno = tool::idName($array, 'id_turno', 'sg_turno');
        if (empty($periodoDia)) {
            return $turno;
        } else {
            return $turno[$periodoDia];
        }
    }

    /**
     * 
     * @return type id da turma baseado no ciclo e na letra
     */
    public static function turmasCicloLetra($id_inst, $id_pl) {
        $sql = " SELECT * FROM ge_turmas "
                . " WHERE fk_id_inst = " . $id_inst
                . " and fk_id_pl in ( " . $id_pl . ")";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $id_turmas[$v['fk_id_ciclo']][$v['letra']] = $v['id_turma'];
        }
        if (!empty($id_turmas)) {
            return $id_turmas;
        } else {
            return;
        }
    }

    /**
     * 
     * @return type cursos baseado no tipo de ensino do gdae
     */
    public static function curcoTipoEnsino() {
        $sql = "SELECT sg_curso, TipoEnsino, id_curso  FROM `ge_cursos` ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $curso[$v['TipoEnsino']] = $v;
        }

        return $curso;
    }

    public static function turma($classeProdesp, $id_inst = NULL) {
        $id_inst = tool::id_inst($id_inst);
        $escola = new escola($id_inst);
        $gdae = new gdae();
        $c = $gdae->ConsultaFormacaoClasse($classeProdesp);
        if (is_string($c)) {
            tool::alert($c);
            return;
        } else {
            @$classe = $c['Mensagens']->ConsultaClasse;

            return $classe;
        }
    }

    /**
     * 
     * @param type $classeProdesp  array com alunos do gdae
     * @return type alunos do gdae que tb estam na base de dados do sistema
     */
    public static function alunosComIdPessoa($classeProdesp) {
        foreach ($classeProdesp as $v) {
            $v = (array) $v;
            $ras[] = gt_gdaeSet::raNormatiza($v['RA']);
            $ras1[] = $v['RA'];
            $rasId[] = trim($v['digitoRA']);
        }
        //alunos com RA e IDRA
        $sql = "select * from pessoa "
                . " where "
                . "("
                . "ra in ('" . implode("','", $ras) . "')  "
                . "OR ra in ('" . implode("','", $ras1) . "')  "
                . ")"
                . " and ra_dig in ('" . implode("','", $rasId) . "')  ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $siebComRaId[gt_gdaeSet::raNormatiza($v['ra'])] = $v;
        }

        return @$siebComRaId;
    }

    public static function situacaoAluno($situacao = NULL) {
        $sit = [
            0 => 'Frequente',
            1 => 'Transferido Escola',
            2 => 'Abandonou',
            3 => 'Reclassificado',
            4 => 'Falecido',
            5 => 'Não Compareceu',
            6 => 'Cessão Por Objetivos Atingidos',
            7 => 'Cessão Por Não Frequência',
            8 => 'Cessão Por Transferência/Remanejamento',
            9 => 'Cessão Por Desistência',
            10 => 'Remanejamento',
            11 => 'Cessão Por Exame',
            12 => 'Cessão Por Número Reduzido De Alunos',
            13 => 'Cessão Por Falta De Docente',
            14 => 'Cessão Por Dispensa',
            15 => 'Cessão Por Conclusão Do Curso',
            16 => 'Transferido (Conversão Do Abandono)',
            17 => 'Remanejado (Conversão Do Abandono)',
            18 => 'Não Compareceu / Fora Do Prazo',
            19 => 'Transferido - CEEJA',
            20 => 'Não Comparecimento - CEEJA',
            21 => 'Concluinte - CEEJA',
        ];
        return $sit[$situacao];
    }

    public static function situacaoAlunoMigracao($situacao = NULL) {

        $sit = [
            '' => 'Frequente',
            'ATIVO' => 'Frequente',
            'TRANSFERIDO' => 'Transferido Escola',
            'BAIXA - TRANSFERÊNCIA' => 'Transferido Escola',
            'BAIXA - TR' => 'Transferido Escola',
            'ABANDONOU' => 'Abandonou',
            'RECLASSIFICADO' => 'Reclassificado',
            'RECLASSIFI' => 'Reclassificado',
            'FALECIDO' => 'Falecido',
            'NÃO COMPARECEU' => 'Não Compareceu',
            'CESSÃO POR OBJETIVOS ATINGIDOS' => 'Cessão Por Objetivos Atingidos',
            'CESSÃO POR NÃO FREQUÊNCIA' => 'Cessão Por Não Frequência',
            'CESSÃO POR TRANSFERÊNCIA/REMANEJAMENTO' => 'Cessão Por Transferência/Remanejamento',
            'CESSÃO POR DESISTÊNCIA' => 'Cessão Por Desistência',
            'REMANEJAMENTO' => 'Remanejamento',
            'CESSÃO POR EXAME' => 'Cessão Por Exame',
            'CESSÃO POR NÚMERO REDUZIDO DE ALUNOS' => 'Cessão Por Número Reduzido De Alunos',
            'CESSÃO POR FALTA DE DOCENTE' => 'Cessão Por Falta De Docente',
            'CESSÃO POR DISPENSA' => 'Cessão Por Dispensa',
            'CESSÃO POR' => 'Cessão',
            'CESSÃO POR CONCLUSÃO DO CURSO' => 'Cessão Por Conclusão Do Curso',
            'TRANSFERIDO (CONVERSÃO DO ABANDONO)' => 'Transferido Escola',
            'TRANSFERID' => 'Transferido (Conversão Do Abandono)',
            'REMANEJADO (CONVERSÃO DO ABANDONO)' => 'Remanejado (Conversão Do Abandono)',
            'REMANEJAME' => 'Remanejado (Conversão Do Abandono)',
            'NÃO COMPARECEU / FORA DO PRAZO' => 'Não Compareceu / Fora Do Prazo',
            'TRANSFERIDO - CEEJA' => 'Transferido - CEEJA',
            'NÃO COMPARECIMENTO - CEEJA' => 'Não Comparecimento - CEEJA',
            'CONCLUINTE - CEEJA' => 'Concluinte - CEEJA',
            'NÃO COMPARECIMENTO' => 'Não Compareceu',
            'NÃO COMPAR' => 'Não Compareceu'
        ];

        if (!empty($sit[$situacao])) {
            return $sit[$situacao];
        } else {
            tool::alert("Situação Não Definida. Procure o Depto de Informática (" . $situacao . ")");
            return 'Indefinida';
        }
    }

    public static function cicloAno($tipoEnsino = NULL, $ano = NULL) {
        $sql = "SELECT * FROM gdae.ciclo_ano ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $ciclo[$v['TipoEnsino']][$v['SerieAno']] = $v['id_ciclo'];
        }

        if (empty($ano) && empty($tipoEnsino)) {
            return $ciclo;
        } else {
            return $ciclo[$tipoEnsino][$ano];
        }
    }

    public static function raNormatiza($ra) {
        if (is_numeric($ra)) {
            $ra = intval($ra);
        }

        return $ra;
    }

}
