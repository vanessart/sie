<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of aluno
 *
 * @author marco
 */
class aluno {

    public $_rse;
    public $_nome;
    public $_nomeSocial;
    public $_nasc;
    public $_email;
    public $_cpf;
    public $_sexo;
    public $_ra;
    public $_ra_dig;
    public $_ra_uf;
    public $_rg;
    public $_rgDig;
    public $_rgOe;
    public $_rgUf;
    public $_rgDt;
    public $_maeRg;
    public $_maeRgDig;
    public $_maeRgOe;
    public $_maeRgUf;
    public $_maeRgDt;
    public $_paiRg;
    public $_paiRgDig;
    public $_paiRgOe;
    public $_paiRgUf;
    public $_paiRgDt;
    public $_rgCompl;
    public $_certidao;
    public $_pai;
    public $_paiCpf;
    public $_mae;
    public $_maeCpf;
    public $_responsavel;
    public $_responsCpf;
    public $_responsRg;
    public $_responsEmail;
    public $_nascionalidade;
    public $_nascUf;
    public $_nascCidade;
    public $_deficiencia;
    public $_corPele;
    public $_ddd1;
    public $_ddd2;
    public $_ddd3;
    public $_tel1;
    public $_tel2;
    public $_tel3;
    public $_ativo;
    public $_codigo_classe;
    public $_nome_classe;
    public $_escola;
    public $_chamada;
    public $_situacao;
    public $_id_turma;
    public $_id_inst;
    public $_id_pl;
    public $_id_curso;
    public $_id_ciclo;
    public $_periodo_letivo;
    public $_dt_matricula;
    public $_dt_transferencia;
    public $_origem_escola;
    public $_id_turma_aluno;
    public $_destino_escola = NULL;
    public $_outrosRegistros;
    public $_situacaoAtual;
    public $_situacaoFinal;
    public $_cep;
    public $_logradouro;
    public $_logradouro_gdae;
    public $_num;
    public $_num_gdae;
    public $_complemento;
    public $_bairro;
    public $_cidade;
    public $_uf;
    public $_dt_barueri;
    public $_latitude;
    public $_longitude;
    public $_id_end;
    public $_novacert_cartorio;
    public $_novacert_acervo;
    public $_novacert_regcivil;
    public $_novacert_ano;
    public $_novacert_tipolivro;
    public $_novacert_numlivro;
    public $_novacert_folha;
    public $_novacert_termo;
    public $_novacert_controle;
    public $_sus;
    public $_nis;
    public $_bloco;
    public $_torre;
    public $_apart;
    public $_aluno_nsdp;
    public $_emailGoogle;

    public function __construct($id_pessoa) {

        //nome social
        $wsql = "SELECT * FROM ge_aluno_nsdp"
                . " WHERE fk_id_pessoa = $id_pessoa AND status_nsdp = 0";

        $query = pdoSis::getInstance()->query($wsql);
        $aluno = $query->fetch(PDO::FETCH_ASSOC);

        if (empty($aluno)) {
            $sql = "select * from pessoa "
                    . "where id_pessoa = $id_pessoa ";

            $query = pdoSis::getInstance()->query($sql);
            @$aluno = $query->fetch(PDO::FETCH_ASSOC);
        }
        if (empty($aluno['id_pessoa'])) {
            $this->_rse = @$aluno['fk_id_pessoa'];
            $this->_aluno_nsdp = @$aluno['id_nsdp'];
        } else {
            $this->_rse = @$aluno['id_pessoa'];
        }
        $this->_emailGoogle = @$aluno['emailgoogle'];
        $this->_nomeSocial = @$aluno['n_social'];
        $this->_nome = @$aluno['n_pessoa'];
        $this->_nomeSocial = @$aluno['n_social'];
        $this->_nasc = @$aluno['dt_nasc'];
        $this->_email = @$aluno['email'];
        $this->_cpf = @$aluno['cpf'];
        $this->_sexo = @$aluno['sexo'];
        $this->_ra = @$aluno['ra'];
        $this->_ra_dig = @$aluno['ra_dig'];
        $this->_ra_uf = @$aluno['ra_uf'];
        $this->_rm = @$aluno['rm'];
        $this->_rg = @$aluno['rg'];
        $this->_rgDig = @$aluno['rg_dig'];
        $this->_rgOe = @$aluno['rg_oe'];
        $this->_rgUf = @$aluno['rg_uf'];
        $this->_rgDt = @$aluno['dt_rg'];
        $this->_maeRg = @$aluno['mae_rg'];
        $this->_maeRgDig = @$aluno['mae_rg_dig'];
        $this->_maeRgOe = @$aluno['mae_rg_oe'];
        $this->_maeRgUf = @$aluno['mae_rg_uf'];
        $this->_maeRgDt = @$aluno['dt_mae_rg'];
        $this->_paiRg = @$aluno['pai_rg'];
        $this->_paiRgDig = @$aluno['pai_rg_dig'];
        $this->_paiRgOe = @$aluno['pai_rg_oe'];
        $this->_paiRgUf = @$aluno['pai_rg_uf'];
        $this->_paiRgDt = @$aluno['dt_pai_rg'];
        $this->_rgCompl = @$aluno['rg_oe'] . ';' . @$aluno['rg_uf'] . (@$aluno['dt_rg'] == '0000-00-00' ? '' : ';' . data::converteBr(@$aluno['dt_rg']));
        $this->_pai = @$aluno['pai'];
        $this->_paiCpf = @$aluno['cpf_pai'];
        $this->_mae = @$aluno['mae'];
        $this->_maeCpf = @$aluno['cpf_mae'];
        $this->_responsavel = @$aluno['responsavel'];
        $this->_responsCpf = @$aluno['cpf_respons'];
        $this->_responsRg = @$aluno['rg_respons'];
        $this->_responsEmail = @$aluno['email_respons'];
        $this->_nascionalidade = @$aluno['nacionalidade'];
        $this->_nascUf = @$aluno['uf_nasc'];
        $this->_nascCidade = @$aluno['cidade_nasc'];
        $this->_deficiencia = @$aluno['deficiencia'];
        $this->_corPele = @$aluno['cor_pele'];
        $this->_ddd1 = @$aluno['ddd1'];
        $this->_ddd2 = @$aluno['ddd2'];
        $this->_ddd3 = @$aluno['ddd3'];
        $this->_tel1 = @$aluno['tel1'];
        $this->_tel2 = @$aluno['tel2'];
        $this->_tel3 = @$aluno['tel3'];
        $this->_ativo = @$aluno['ativo'] == 1 ? "Sim" : "Não";
        $this->_novacert_acervo = @$aluno['novacert_acervo'];
        $this->_novacert_ano = @$aluno['novacert_ano'];
        $this->_novacert_cartorio = @$aluno['novacert_cartorio'];
        $this->_novacert_controle = @$aluno['novacert_controle'];
        $this->_novacert_folha = @$aluno['novacert_folha'];
        $this->_novacert_numlivro = @$aluno['novacert_numlivro'];
        $this->_novacert_regcivil = @$aluno['novacert_regcivil'];
        $this->_novacert_termo = @$aluno['novacert_termo'];
        $this->_novacert_tipolivro = @$aluno['novacert_tipolivro'];
        $this->_sus = @$aluno['sus'];
        $this->_nis = @$aluno['nis'];
        $this->_certidao = @$aluno['certidao'];
        if (!empty($this->_novacert_cartorio))
            $this->_certidao = @$aluno['novacert_cartorio'] . '-'
                    . @$aluno['novacert_acervo'] . '-'
                    . @$aluno['novacert_regcivil'] . '-'
                    . @$aluno['novacert_ano'] . '-'
                    . @$aluno['novacert_tipolivro'] . '-'
                    . @$aluno['novacert_numlivro'] . '-'
                    . @$aluno['novacert_folha'] . '-'
                    . @$aluno['novacert_termo'] . '-'
                    . @$aluno['novacert_controle']
            ;
    }

    public function vidaEscolar($situacao = "Frequente", $id_inst = NULL) {
        if (!empty($this->_rse)) {

            $sql = "select "
                    . " i.id_inst, t.codigo, t.n_turma, t.id_turma, i.n_inst, sf.n_sf, fk_id_pl, "
                    . " ta.chamada, ta.situacao, ta.situacao_final, t.periodo_letivo, "
                    . " dt_matricula, dt_transferencia, origem_escola, id_turma_aluno, destino_escola, "
                    . " c.id_curso, pl.at_pl, ci.id_ciclo "
                    . " from ge_turma_aluno ta "
                    . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
                    . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                    . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                    . " join ge_cursos c on c.id_curso = ci.fk_id_curso "
                    . " join instancia i on i.id_inst = t.fk_id_inst "
                    . " left join ge_situacao_final sf on sf.id_sf = ta.situacao_final"
                    . " where fk_id_pessoa = " . $this->_rse . " "
                    . " and situacao like '%$situacao%' "
                    . " and extra <> 1 "
                    . " order by t.periodo_letivo desc";
            $query = pdoSis::getInstance()->query($sql);
            $ca = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($ca as $k => $v) {
                if ($v['situacao'] == 'Frequente' && @$v['at_pl'] == 1) {
                    $this->_situacaoAtual = 'Frequente';
                    $classeFrequente = $v;
                    $classeAluno = $v;
                    unset($ca[$k]);
                }
                if (empty($classeFrequente)) {
                    $this->_situacaoAtual = $v['situacao'];
                    $classeFrequente = $v;
                    $classeAluno = $v;
                }
            }
            if (!empty($classeFrequente['codigo'])) {
                $this->_codigo_classe = $classeFrequente['codigo'];
                $this->_nome_classe = $classeFrequente['n_turma'];
                $this->_escola = $classeFrequente['n_inst'];
                $this->_chamada = $classeFrequente['chamada'];
                $this->_situacao = $classeFrequente['situacao'];
                $this->_situacaoFinal = $classeFrequente['n_sf'];
                $this->_id_pl = $classeFrequente['fk_id_pl'];
                $this->_id_curso = $classeFrequente['id_curso'];
                $this->_id_ciclo = $classeFrequente['id_ciclo'];
                $this->_id_turma = $classeFrequente['id_turma'];
                $this->_id_inst = $classeFrequente['id_inst'];
                $this->_periodo_letivo = $classeFrequente['periodo_letivo'];
                $this->_dt_matricula = data::converteBr($classeFrequente['dt_matricula']);
                $this->_dt_transferencia = $classeFrequente['dt_transferencia'] <> '0000-00-00' ? data::converteBr($classeFrequente['dt_transferencia']) : NULL;
                $this->_origem_escola = $classeFrequente['origem_escola'];
                $this->_destino_escola = $classeFrequente['destino_escola'];
                $this->_id_turma_aluno = $classeFrequente['id_turma_aluno'];
            } elseif (!empty($classeAluno['codigo'])) {
                $this->_codigo_classe = $classeAluno['codigo'];
                $this->_nome_classe = $classeAluno['n_turma'];
                $this->_escola = $classeAluno['n_inst'];
                $this->_chamada = $classeAluno['chamada'];
                $this->_situacao = $classeAluno['situacao'];
                $this->_situacaoFinal = $classeAluno['n_sf'];
                $this->_id_turma = $classeAluno['id_turma'];
                $this->_id_inst = $classeAluno['id_inst'];
                $this->_id_pl = $classeAluno['fk_id_pl'];
                $this->_id_curso = $classeAluno['id_curso'];
                $this->_id_ciclo = $classeAluno['id_ciclo'];
                $this->_periodo_letivo = $classeAluno['periodo_letivo'];
                $this->_dt_matricula = data::converteBr($classeAluno['dt_matricula']);
                $this->_dt_transferencia = $classeAluno['dt_transferencia'] <> '0000-00-00' ? data::converteBr($classeAluno['dt_transferencia']) : NULL;
                $this->_origem_escola = $classeAluno['origem_escola'];
                $this->_destino_escola = $classeAluno['destino_escola'];
                $this->_id_turma_aluno = $classeAluno['id_turma_aluno'];
            } else {
                $this->_codigo_classe = NULL;
                $this->_nome_classe = NULL;
                $this->_escola = NULL;
                $this->_chamada = NULL;
                $this->_situacao = NULL;
                $this->_situacaoFinal = NULL;
                $this->_id_turma = NULL;
                $this->_id_pl = NULL;
                $this->_id_curso = NULL;
                $this->_id_inst = NULL;
                $this->_periodo_letivo = NULL;
                $this->_dt_matricula = NULL;
                $this->_dt_transferencia = NULL;
                $this->_origem_escola = NULL;
                $this->_id_turma_aluno = NULL;
            }
            if (!empty($ca)) {
                $this->_outrosRegistros = $ca;
            }
        }
    }

    public function endereco() {
        $sql = "SELECT * FROM `endereco` WHERE `fk_id_pessoa` = " . $this->_rse . " AND `fk_id_tp` = 1 ";
        $query = pdoSis::getInstance()->query($sql);
        $end = $query->fetch(PDO::FETCH_ASSOC);
        $this->_cep = $end['cep'];
        $this->_logradouro = $end['logradouro'];
        $this->_logradouro_gdae = $end['logradouro_gdae'];
        $this->_num = $end['num'];
        $this->_num_gdae = $end['num_gdae'];
        $this->_complemento = $end['complemento'];
        $this->_bairro = $end['bairro'];
        $this->_cidade = $end['cidade'];
        $this->_uf = $end['uf'];
        $this->_dt_barueri = $end['dt_barueri'];
        $this->_latitude = trim($end['latitude']);
        $this->_longitude = trim($end['longitude']);
        $this->_id_end = $end['id_end'];
        $this->_bloco = $end['bloco'];
        $this->_torre = $end['torre'];
        $this->_apart = $end['apart'];
    }

    public function tempoEscola($tempo = NULL, $distancia = NULL, $id_inst = NULL) {
        if (empty($id_inst)) {
            $id_inst = tool::id_inst();
        }
        $t = sql::get('gt_aluno', ' distancia_esc, id_alu ', ['fk_id_pessoa' => $this->_rse], 'fetch');
        if (empty($tempo)) {
            return $t['distancia_esc'];
        } else {
            if (empty($t)) {
                $sql = "INSERT INTO `gt_aluno` (`id_alu`, `fk_id_pessoa`, fk_id_inst, `distancia_esc`, `tempo_esc`) VALUES (NULL, '" . $this->_rse . "', '$id_inst', '$distancia', '$tempo');";
            } else {
                $sql = "UPDATE `gt_aluno` SET `distancia_esc` = '$distancia', `tempo_esc` = '$tempo', fk_id_inst = '$id_inst' WHERE id_alu = " . $t['id_alu'];
            }
            $query = pdoSis::getInstance()->query($sql);

            return $tempo;
        }
    }

    /**
     * lista os responsáveis para retirada do aluno
     */
    public function responsaveis($desativados = NULL) {
        $resp = sql::get(['gt_retirada', 'tipo_doc'], '*', ['fk_id_pessoa' => $this->_rse, '>' => 'n_re']);
        if (empty($res)) {
            foreach ($resp as $v) {
                if ($v['parente'] == "Mãe") {
                    $mae = 1;
                } elseif ($v['parente'] == "Pai") {
                    $pai = 1;
                } elseif ($v['parente'] == "Responsável") {
                    $resp = 1;
                }
                if (empty($desativados)) {
                    if ($v['ativo'] == 1) {
                        $responsaveis[] = $v;
                    }
                } else {
                    $responsaveis[] = $v;
                }
            }
            $cpf = $cpf = $tipoDoc = NULL;
            if (empty($mae) && !empty($this->_mae)) {
                $sql = "INSERT INTO `gt_retirada` (`id_re`, `n_re`, `parente`, `doc`, `fk_id_doc`, `fk_id_pessoa`, `ativo`) VALUES ("
                        . "NULL, '" . addslashes($this->_mae) . "'   , 'Mãe', '" . $this->_maeCpf . "', 1, " . $this->_rse . ", 1);";
                $query = pdoSis::getInstance()->query($sql);
                $reQuery = 1;
            }
            $cpf = $cpf = $tipoDoc = NULL;
            if (empty($pai) && !empty($this->_pai)) {
                $sql = "INSERT INTO `gt_retirada` (`id_re`, `n_re`, `parente`, `doc`, `fk_id_doc`, `fk_id_pessoa`, `ativo`) VALUES ("
                        . "NULL, '" . addslashes($this->_pai) . "'   , 'Pai', '" . $this->_paiCpf . "', 1, " . $this->_rse . ", 1);";
                $query = pdoSis::getInstance()->query($sql);
                $reQuery = 1;
            }
            $cpf = $cpf = $tipoDoc = NULL;
            if (empty($resp) && !empty($this->_responsavel) && $this->_responsavel != $this->_mae && $this->_responsavel != $this->_pai) {
                $sql = "INSERT INTO `gt_retirada` (`id_re`, `n_re`, `parente`, `doc`, `fk_id_doc`, `fk_id_pessoa`, `ativo`) VALUES ("
                        . "NULL, '" . addslashes($this->_responsavel) . "'   , 'Responsável', '" . $this->_responsCpf . "', 1, " . $this->_rse . ", 1);";
                $query = pdoSis::getInstance()->query($sql);
                $reQuery = 1;
            }
        }

        if (!empty($reQuery)) {
            if (empty($desativados)) {
                $where = ['fk_id_pessoa' => $this->_rse, 'Ativo' => 1, '>' => 'n_re'];
            } else {
                $where = ['fk_id_pessoa' => $this->_rse, '>' => 'n_re'];
            }
            $responsaveis = sql::get(['gt_retirada', 'tipo_doc'], '*', $where);
        }

        return !empty($responsaveis) ? $responsaveis : NULL;
    }

}
