<?php

class globalModel extends MainModel {

    public $db;

    /**
     * Construtor para essa classe
     *
     * Configura o DB, o controlador, os parâmetros e dados do usuário.
     *
     * @since 0.1
     * @access public
     * @param object $db Objeto da nossa conexão PDO
     * @param object $controller Objeto do controlador
     */
    public function __construct($db = false, $controller = null) {
        // Configura o DB (PDO)
        $this->db = new DB();

        // Configura o controlador
        $this->controller = $controller;

        // Configura os parâmetros
        //$this->parametros = $this->controller->parametros;
        // Configura os dados do usuário
        $this->userdata = $this->controller->userdata;


        if ($this->db->sqlKeyVerif('habilidade')) {

            $insert['aval'] = $_POST['id_gl'];
            $insert['num'] = $_POST['quest'];
            $insert['questao'] = $_POST['quest'];
            $insert['titulo'] = $_POST['titulo'];
            $c = 1;
            foreach ($_POST['hab'] as $k => $v) {
                $insert['id_gd'] = @$_POST['id_gd'][$k];
                $insert['valor'] = $c++;
                $insert['valorNominal'] = $k;
                $insert['descricao'] = addslashes($v);
                $this->db->ireplace('global_descritivo', $insert, 1);
            }
            tool::alert('Lançado');
        }

        if ($this->db->sqlKeyVerif('adi')) {
            if (!empty($_POST['nota'])) {
                foreach ($_POST['nota'] as $k => $v) {
                    if (!empty($v) && @$v <= 10) {
                        $insert['id_rm'] = $k;
                        $insert['nota'] = str_replace(",", ".", $v);
                        $insert['fk_id_inst'] = @$_POST['id_inst'];
                        $insert['nome'] = @$_POST['nome'][$k];
                        $this->db->replace('global_nota_adi', $insert);
                    }
                }
            }
            tool::alert("Lançamento Efetuado");
        }
        if ($this->db->sqlKeyVerif('notaClasse')) {
            if (!empty($_POST['nota']) && is_array(@$_POST['nota'])) {

                foreach ($_POST['nota'] as $k => $v) {
                    if (!empty($v) && @$v <= 10) {

                        $insert['nota'] = str_replace([",", ",0", ".0"], [".", "", ""], $v);
                        $insert1['nota'] = str_replace([",", ",0", ".0"], [".", "", ""], $v);
                        $insert['fk_id_turma'] = $_POST['id_turma'][$k];
                        $insert1['fk_id_turma'] = $_POST['id_turma'][$k];
                        $insert['rm'] = $_POST['rm'][$k];
                        $insert1['rm'] = $_POST['rm'][$k];
                        @$insert['id_nt'] = $_POST['id_nt'][$k];
                        $insert['fk_id_inst'] = $_POST['id_inst'];
                        $insert1['fk_id_inst'] = $_POST['id_inst'];
                        $insert1['fk_id_pessoa'] = tool::id_pessoa();

                        $this->db->replace('global_nota_turma_pega', $insert1);
                        $this->db->replace('global_nota_turma', $insert);
                    }
                }
                tool::alert("Lançamento Efetuado");
            }
        }
        if (!empty($_REQUEST['calcular'])) {

            ini_set('memory_limit', '200000M');
            $aval = sql::get('global_aval', '*', ['id_gl' => @$_POST['id_gl']], 'fetch');
            $val = unserialize($aval['val']);
            $sql = "SELECT * FROM `global_respostas` "
                    . " WHERE `fk_id_gl` = " . @$_POST['id_gl'] . " "
                    . " ";
            $query = $this->db->query($sql);
            $lanc = $query->fetchAll();

            foreach ($lanc as $v) {
                $total = 0;
                for ($c = 1; $c <= $aval['quest']; $c++) {
                    @$total += $val[$v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)]];
                    @$insert['q' . str_pad($c, 2, "0", STR_PAD_LEFT)] = $val[$v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)]];
                }
                $insert['nota'] = $total;
                $insert['fk_id_gl'] = $v['fk_id_gl'];
                $insert['fk_id_pessoa'] = $v['fk_id_pessoa'];
                $insert['fk_id_turma'] = $v['fk_id_turma'];
                $insert['fk_id_inst'] = $v['fk_id_inst'];
                $this->db->replace('global_result', $insert);
                /**
                  $sql = "update global_respostas set nota = '$total' "
                  . " where `fk_id_gl` = " . @$_POST['id_gl'] . " ";
                  $query = $this->db->query($sql);
                 * 
                 */
            }
        }

        if (!empty($_POST['apagarNota'])) {

            $this->db->delete('global_respostas', 'id_resp', @$_POST['id_resp']);
        }

        if ($this->db->sqlKeyVerif('perc')) {
            $insert['perc'] = serialize($_POST[1]);
            $insert['titulo'] = @$_POST['titulo'];
            $insert['id_gl'] = @$_POST['id_gl'];
            $this->db->ireplace('global_aval', $insert);
        }

        if ($this->db->sqlKeyVerif('val')) {
            $insert['val'] = serialize($_POST[1]);
            $insert['id_gl'] = @$_POST['id_gl'];
            $this->db->ireplace('global_aval', $insert);
        }

        if (!empty($_POST['lacarNota'])) {
            $insert['id_resp'] = @$_POST['id_resp'];
            $insert['fk_id_gl'] = @$_POST['id_gl'];
            $insert['fk_id_pessoa'] = @$_POST['id_pessoa'];
            $insert['fk_id_turma'] = @$_POST['id_turma'];
            $insert['fk_id_inst'] = @$_POST['id_inst'];
            $insert['avaliador'] = tool::id_pessoa();
            $insert['data'] = date("Y-m-d H:i:s");
            $insert['escrita'] = @$_POST['escrita'];
            $relat['id_rt'] = @$_POST['id_rt'];
            $relat['fk_id_turma'] = @$_POST['id_turma'];
            $relat['fk_id_gl'] = @$_POST['id_gl'];
            $relat['porcent'] = @$_POST['porcent'];
            $relat['fk_id_pessoa'] = tool::id_pessoa();
            $insert['lapis'] = @$_POST['lapis'];

            if (!empty($_POST['nfez'])) {
                $insert['nfez'] = $_POST['nfez'];
                $this->db->ireplace('global_respostas', $insert);
                $this->db->ireplace('global_relat', $relat, 1);
            } elseif (!empty($_POST['resp'])) {
                $insert['nfez'] = NULL;
                foreach ($_POST['resp'] as $k => $v) {
                    $insert['q' . str_pad($k, 2, "0", STR_PAD_LEFT)] = $v;
                }

                $this->db->ireplace('global_respostas', $insert, 1);
                $this->db->ireplace('global_relat', $relat, 1);
            } else {
                tool::alert('Ou Lance as Notas ou Clique em \"Não Fez\" ');
            }
        }

        if (!empty($_POST['global_aval'])) {
            $insert = $_POST[1];
            if (!empty($_POST['esc'])) {
                $insert['escolas'] = '|';
                foreach ($_POST['esc'] as $v) {
                    $insert['escolas'] .= $v . '|';
                }
            }

            $this->db->ireplace('global_aval', $insert);
        }
    }

    public function resultadoAvalGlobal($id_gl, $ano = null) {

        
          if(empty($ano)){
              $ano = date('Y');
          }
        $sql = " select "
                . " g.`fk_id_gl`,g.`fk_id_pessoa`,g.`nfez`,"
                . " g.`q01`,g.`q02`,g.`q03`,g.`q04`,g.`q05`,g.`q06`,g.`q07`,g.`q08`,g.`q09`, g.`q10`, "
                . " g.`q11`,g.`q12`,g.`q13`,g.`q14`,g.`q15`,g.`q16`,g.`q17`,g.`q18`,g.`q19`, g.`q20`, "
                . " g.`q21`,g.`q22`,g.`q23`,g.`q24`,g.`q25`,g.`q26`,g.`q27`,g.`q28`,g.`q29`, g.`q30`, "
                . " g.`q31`,g.`q32`,g.`q33`,g.`q34`,g.`q35`,g.`q36`,g.`q37`,g.`q38`,g.`q39`, g.`q40`, "
                . " g.`q41`,g.`q42`,g.`q43`,g.`q44`,g.`q45`,g.`q46`,g.`q47`,g.`q48`,g.`q49`, g.`q50`, "
                . " g.escrita, ta.fk_id_inst "
                . " from ge2.global_respostas as g "
                . " left join ge2.global_aval as ga on ga.id_gl = g.fk_id_gl "
                . " left join ge2.pessoa as gp on gp.id_pessoa = g.fk_id_pessoa "
                . " left join ge2.pessoa as gp2 on gp2.id_pessoa = g.avaliador    "
                . " left join ge2.instancia as inst on inst.id_inst = g.fk_id_inst "
                . " left join ge2.ge_turmas as tu on tu.id_turma = g.fk_id_turma "
                . " left join ge2.ge_turma_aluno as ta on tu.id_turma = ta.fk_id_turma "
                . " and g.fk_id_pessoa = ta.fk_id_pessoa "
                . " where tu.periodo_letivo like '%$ano%' "
                . " and g.fk_id_gl = $id_gl  "
                . " and g.nfez is null  ";
        
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        
        return $array;
    }

// Crie seus próprios métodos daqui em diante
}
