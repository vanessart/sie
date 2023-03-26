<?php

class pseModel extends MainModel {

    public $db;

    /**
      if($this->db->tokenCheck('table')){

      }
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
        $this->db = new crud();
        // Configura o controlador
        $this->controller = $controller;

        // Configura os parâmetros
        $this->parametros = $this->controller->parametros->variavel;

        // Configura os dados do usuário
        $this->userdata = $this->controller->userdata;

        //seta o select dinamico
        if ($opt = formErp::jqOption()) {
            $metodo = $opt[0];
            if (in_array($metodo, get_class_methods($this))) {
                $this->$metodo($opt[1]);
            }
        }

        if ($this->db->tokenCheck('salvarForm', true)) {
            $this->salvarForm();
        } elseif ($this->db->tokenCheck('salvarFormProf', true)) {
            $this->salvarFormProf();
        } elseif ($this->db->tokenCheck('ativar_campanha', true)) {
            $this->ativar_campanha();
        } elseif ($this->db->tokenCheck('cadOdonto', true)) {
            $this->cadOdonto();
        }elseif ($this->db->tokenCheck('formDel', true)) {
            $this->formDel();
        }
    }

    public function getForm($id_form, $fk_id_pai = null) {

        if (empty($_perguntas)) {
            $_perguntas = [];
        }

        $P1 = $this->getPerguntas($id_form, $fk_id_pai);

        if (empty($P1)) {
            return null;
        }

        foreach ($P1 as $k => $v) {
            $_perguntas[$v['id_pergunta']] = $v;
            $_perguntas[$v['id_pergunta']]['peguntas'] = [];
            $_perguntas[$v['id_pergunta']]['opcoes'] = [];

            $pergs = $this->getForm($id_form, $v['id_pergunta']);
            if (!empty($pergs)) {
                $_perguntas[$v['id_pergunta']]['peguntas'] = $pergs;
            }

            $opcoes = $this->getOpcoes($v['id_pergunta']);
            if (!empty($opcoes)) {
                $_perguntas[$v['id_pergunta']]['opcoes'] = $opcoes;
            }
        }

        return $_perguntas;
    }

    public function getPerguntas($id_form, $fk_id_pai = null) {
        $sqlPai = '';
        if (empty($fk_id_pai)) {
            $sqlPai .= " AND p1.fk_id_pai IS NULL ";
        } else {
            $sqlPai .= " AND p1.fk_id_pai = $fk_id_pai ";
        }

        $sql = "SELECT "
                . " id_pergunta, tem_resposta, n_pergunta, ordem, fk_id_pai "
                . " FROM form_perguntas AS p1 "
                . " WHERE fk_id_form = $id_form"
                . $sqlPai
                . " ORDER BY p1.ordem ";

        $query = pdoSis::getInstance()->query($sql);
        $P1 = $query->fetchAll(PDO::FETCH_ASSOC);
        return $P1;
    }

    public function getOpcoes($fk_id_pergunta, $fk_id_pai = null) {
        /* $sqlPai = '';
          if (empty($fk_id_pai)){
          $sqlPai .= " AND p1.fk_id_pai IS NULL ";
          } else {
          $sqlPai .= " AND p1.fk_id_pai = $fk_id_pai ";
          } */

        $sql = "SELECT "
                . " fk_id_pergunta, id_opcao, n_opcao, tipo, acao "
                . " FROM form_opcoes"
                . " WHERE fk_id_pergunta = $fk_id_pergunta"
                . " ORDER BY ordem ";
        $query = pdoSis::getInstance()->query($sql);
        $P1 = $query->fetchAll(PDO::FETCH_ASSOC);
        return $P1;
    }

    public function getView($form, $id_pessoa, $id_form) {
        $respostas = $this->getRespostas($id_form, $id_pessoa);
        $dadosForm = $this->getDadosForm($id_form, $id_pessoa);
        if (empty($form))
            return '';
        $oa = $this->oa($id_pessoa);
        $seu = $this->seu($id_pessoa);
        foreach ($form as $k => $v) {

            if (!empty($v)) {
                ?>

                <?php
            }
            ?>
            <div class="row pergunta_<?= $v['id_pergunta'] ?>">
                <div class="col col-sm per" style="<?= !empty($v['fk_id_pai']) ? 'padding-left: 30px' : 'font-weight:bold' ?>">
                    <?php
                    $data_template = array('oa' => $oa, 'seu' => $seu);
                    $n_pergunta = parser::parse_string($v['n_pergunta'], $data_template, true);
                    echo $n_pergunta . '<br>';
                    ?>
                </div>
            </div>
            <br>
            <?php if (!empty($v['opcoes'])) { ?>
                <div class="row" style="padding-left: 30px;">
                    <?php
                    foreach ($v['opcoes'] as $i => $j) {
                        if (strpos($j['acao'], 'required') !== false) {
                            $classe = 'class="opcoes"';
                        } else {
                            $classe = '';
                        }
                        $id_opcao = 'null';
                        if ($j['tipo'] == 2) {

                            if (!empty($respostas)) {
                                if (array_key_exists($j['id_opcao'], $respostas)) {
                                    $id_opcao = $j['id_opcao'];
                                } else {
                                    $id_opcao = 'null';
                                }
                            }
                            ?>
                            <div class="col col-sm" id="c_<?= $j['id_opcao'] ?>">
                                <?php echo formErp::radio('1[fk_id_pergunta][' . $v['id_pergunta'] . ']', $j['id_opcao'], $j['n_opcao'], $id_opcao, $classe . ' data-id-pergunta="' . $v['id_pergunta'] . '" id=' . $j['id_opcao'] . ' ' . $j['acao']); ?>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <div class="row"  style="padding-left: 30px;" data-id-pergunta="87" class="opcoes">
                    <div class="col">
                        <div class="row">
                            <?php
                            foreach ($v['opcoes'] as $i => $j) {
                                if (strpos($j['acao'], 'required') !== false) {
                                    $classe = 'class="opcoes"';
                                } else {
                                    $classe = '';
                                }
                                if ($j['tipo'] == 1) {
                                    $id_opcao = 'null';
                                    if (!empty($respostas)) {
                                        if (array_key_exists($j['id_opcao'], $respostas)) {
                                            $id_opcao = $j['id_opcao'];
                                        } else {
                                            $id_opcao = 'null';
                                        }
                                    }
                                    if (substr_count($j['n_opcao'], ' ') > 2) {
                                        ?>
                                    </div>
                                    <div class="row">
                                        <?php
                                    }
                                    ?>
                                    <div class="col col-sm" style="padding-top: 10px;" id="c_<?= $j['id_opcao'] ?>">
                                        <?php echo formErp::checkbox('1[fk_id_pergunta][checkbox][' . $v['id_pergunta'] . '][]', $j['id_opcao'], $j['n_opcao'], $id_opcao, $classe . ' data-id-pergunta="' . $v['id_pergunta'] . '" id=' . $j['id_opcao'] . ' ' . $j['acao']); ?>
                                    </div>
                                    <?php if (substr_count($j['n_opcao'], ' ') > 2) { ?>
                                    </div>
                                    <div class="row">
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row"  style="padding-left: 30px;">
                    <?php
                    foreach ($v['opcoes'] as $i => $j) {
                        if (strpos($j['acao'], 'required') !== false) {
                            $classe = 'class="opcoes"';
                        } else {
                            $classe = '';
                        }
                        if ($j['tipo'] == 0) {
                            $n_resposta = null;
                            if (!empty($respostas)) {
                                if (array_key_exists($j['id_opcao'], $respostas)) {
                                    $n_resposta = $respostas[$j['id_opcao']];
                                } else {
                                    $n_resposta = null;
                                }
                            }
                            ?>
                            <div class="col col-sm"  style="padding-top: 10px;"  id="c_<?= $j['id_opcao'] ?>">
                                <?php echo formErp::input('1[fk_id_pergunta][text][' . $j['id_opcao'] . ']', $j['n_opcao'], $n_resposta, $classe . '  data-id-pergunta="' . $v['id_pergunta'] . '" id=' . $j['id_opcao'] . ' ' . $j['acao']); ?>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <?php
            }
            if (!empty($v['peguntas'])) {
                $this->getView($v['peguntas'], $id_pessoa, $id_form);
            }
            ?>
            <br>
            <div style="border-bottom: 3px solid #e7e1e1"></div>
            <br>
            <?php
        }
        return $dadosForm;
    }

    public function getTurmas($id_pessoa, $id_inst = null) {

        if (empty($id_inst)) {
            $id_inst = toolErp::id_inst();
        }

        if (toolErp::id_nilvel() == 24) {
            $sql = "SELECT DISTINCT t.id_turma, t.n_turma "
                    . " FROM ge_aloca_prof prof "
                    . " JOIN ge_turmas t on t.id_turma = prof.fk_id_turma"
                    . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                    . " JOIN ge_funcionario f on f.rm = prof.rm "
                    . " WHERE f.fk_id_pessoa = $id_pessoa"
                    // . " AND t.AND t.fk_id_pl = $id_pl"
                    . " ORDER BY n_turma";
        } else {
            $sql = "SELECT DISTINCT t.id_turma, t.n_turma "
                    . " FROM ge_turmas t "
                    . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 AND fk_id_ciclo != 32"
                    . " WHERE t.fk_id_inst = $id_inst"
                    // . " AND t.AND t.fk_id_pl = $id_pl"
                    . " ORDER BY n_turma";
        }

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (empty($array)) {
            toolErp::divAlert('warning', 'Verifique na Secretaria se há turmas alocadas');
            die();
        } else {
            foreach ($array as $k => $v) {
                $turmas[$v['id_turma']] = $v['n_turma'];
            }
            return $turmas;
        }
    }

    public function autorizacaoPSE($id_turma) {
        if (!empty($id_turma)) {
            $alunos = $this->getAlunos($id_turma);
            foreach ($alunos as $k => $v) {
                if ($v['Paisresp'] == 1) {
                    $btnPais = 'success';
                } else {
                    $btnPais = 'outline-info';
                }
                $token = formErp::token('formDel');
                $alunos[$k]['reset'] = formErp::submit('Resetar Formulário', $token, ['id_form' => 1, 'id_pessoa' => $v['id_pessoa'],'id_assinatura' => $v['fk_id_assinatura'], 'id_turma' => $id_turma], HOME_URI.'/pse/questionario', null, 'ATENÇÃO!!! Esta ação irá apagar todas as respostas do formulário de ' . $v['n_pessoa'] . '. Deseja Continuar', 'btn btn-outline-danger', 'white-space: normal;');
                $alunos[$k]['pais'] = '<button class="btn btn-' . $btnPais . '" onclick="acesso(\'autorizacaoPSE\',\' - Autorização PSE\',' . $v['id_pessoa'] . ',\'' . $v['n_pessoa'] . '\',' . $v['Paisresp'] . ' )">Autorização PSE</button>';
                $alunos[$k]['QRpais'] = '<button class="btn btn-info" style="color:white;" onclick="pdf(\'autorizacaoPSEPDF\',' . $v['id_pessoa'] . ',null,1,' . $v['Paisresp'] . ')">Imprimir QrCode</button>';
                $alunos[$k]['pdf'] = '<button class="btn btn-info" style="color:white;" onclick="pdf(\'autorizacaoPSEPDF\',' . $v['id_pessoa'] . ',null,null,' . $v['Paisresp'] . ' )">Imprimir Autorização</button>';
            }
            $form['array'] = $alunos;
            $form['fields'] = [
                'RSE' => 'id_pessoa',
                'Aluno' => 'n_pessoa',
                '||1' => 'reset',
                '||5' => 'QRpais',
                '||3' => 'pdf',
                '||2' => 'pais',
            ];
        }
        if (!empty($alunos)) {
            $lista_alunos = report::simple($form);
        } else {
            $lista_alunos = toolErp::divAlert('warning', 'Verifique se há alunos alocados nesta turma');
        }

        return $lista_alunos;
    }

    public function getAlunos($id_turma, $resp = null, $id_form = null) {
        $sqlAux = '';
        $id_pl = $this->campanha('id_pl');
        if (!empty($resp) && !empty($id_form)) {
            if ($resp == 2) {
                $sIn = ' NOT ';
            } else {
                $sIn = '';
            }
            $sqlAux .= " AND p.id_pessoa $sIn IN("
                    . "SELECT fk_id_pessoa "
                    . " FROM form_resposta "
                    . " WHERE fk_id_form = $id_form "
                    . " AND fk_id_pessoa = p.id_pessoa "
                    . " AND fk_id_pl = $id_pl "
                    . " )";
        }

        $sql = "SELECT DISTINCT p.id_pessoa, p.n_pessoa, fp.respondido AS Paisresp, fprof.respondido AS Profresp, fp.fk_id_assinatura "
                . " FROM ge_turma_aluno ta "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " LEFT JOIN form_pessoa fp on fp.fk_id_pessoa = ta.fk_id_pessoa AND fp.fk_id_form = 1 AND fp.fk_id_pl = $id_pl"
                . " LEFT JOIN form_pessoa fprof on fprof.fk_id_pessoa = ta.fk_id_pessoa AND fprof.fk_id_form = 2 AND fprof.fk_id_pl = $id_pl"
                . " WHERE ta.fk_id_turma = $id_turma"
                . $sqlAux
                . " AND (ta.fk_id_tas = 0 or ta.fk_id_tas is null)"
                . " ORDER BY p.n_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $alunos = $query->fetchAll(PDO::FETCH_ASSOC);
        return $alunos;
    }

    public function getId_turma($turmas, $id_turma) {
        if (!empty($id_turma)) {
            return $id_turma;
        } else {
            return array_key_first($turmas);
        }
    }

    public function getRespostas($id_form, $id_pessoa) {
        $id_pl = $this->campanha('id_pl');
        if (!empty($id_pessoa) && !empty($id_form)) {
            $sql = "SELECT "
                    . " fk_id_opcao, n_resposta"
                    . " FROM form_resposta "
                    . " WHERE fk_id_form = $id_form"
                    . " AND fk_id_pessoa = $id_pessoa"
                    . " AND fk_id_pl = $id_pl";
            $query = pdoSis::getInstance()->query($sql);
            $P1 = $query->fetchAll(PDO::FETCH_ASSOC);


            foreach ($P1 as $k => $v) {
                $resp[$v["fk_id_opcao"]] = $v["n_resposta"];
            }

            if (!empty($resp)) {
                return $resp;
            }
        } else {
            $lista_alunos = toolErp::divAlert('warning', 'Escolha um aluno e um questionário');
            die();
        }
    }

    public function salvarForm2() {
        $respostas = @$_POST[1];
        $id_form = filter_input(INPUT_POST, 'id_form', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        formDB::salvarForm($respostas, $id_form, $id_pessoa, $id_pl,);
    }

    public function salvarForm() {
        $respostas = @$_POST[1];
        $id_form = filter_input(INPUT_POST, 'id_form', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);

        $sql = "DELETE FROM form_resposta WHERE  fk_id_form =  $id_form AND fk_id_pessoa = $id_pessoa AND fk_id_pl = $id_pl";
        $query = $this->db->query($sql);

        $id = assinaturaDigital::salvarAssinatura();
        if (empty($id)) {
            toolErp::alertModal('Falha ao salvar a assinatura digital');
            return;
        }

        if (!empty(toolErp::id_pessoa())) {
            $id_pessoa_responde = toolErp::id_pessoa();
        } else {
            $id_pessoa_responde = -1; //respondido pelos pais no celular
        }

        foreach ($respostas['fk_id_pergunta']['checkbox'] as $k => $v) { //checkbox
            foreach ($v as $i => $j) {
                if (!empty($j)) {
                    $ins = [
                        'fk_id_form' => $id_form,
                        'fk_id_pessoa' => $id_pessoa,
                        'fk_id_opcao' => $j,
                        'fk_id_pl' => $id_pl,
                        'fk_id_pessoa_responde' => $id_pessoa_responde
                    ];
                    $this->db->ireplace('form_resposta', $ins, 1);
                }
            }
        }

        //foreach ($respostas['fk_id_pergunta'] as $k => $v) {   
        if (!empty($respostas['fk_id_pergunta'])) {
            foreach ($respostas['fk_id_pergunta'] as $k => $v) {
                if ($k == 'text') { //input
                    foreach ($v as $kk => $vv) {
                        if (!empty($vv)) {
                            $ins = [
                                'fk_id_form' => $id_form,
                                'fk_id_pessoa' => $id_pessoa,
                                'fk_id_opcao' => $kk,
                                'n_resposta' => $vv,
                                'fk_id_pl' => $id_pl,
                                'fk_id_pessoa_responde' => $id_pessoa_responde
                            ];
                            $this->db->ireplace('form_resposta', $ins, 1);
                            $respondido = 1;
                        }
                    }
                } else { //radio
                    if (!empty($v)) {
                        $ins = [
                            'fk_id_form' => $id_form,
                            'fk_id_pessoa' => $id_pessoa,
                            'fk_id_opcao' => $v,
                            'fk_id_pl' => $id_pl,
                            'n_resposta' => null,
                            'fk_id_pessoa_responde' => $id_pessoa_responde
                        ];
                        $this->db->ireplace('form_resposta', $ins, 1);
                        $respondido = 1;
                    }
                }
            }
        }
        //}
        if (!empty($respondido)) {
            $ins = [
                'fk_id_form' => $id_form,
                'fk_id_pessoa' => $id_pessoa,
                'fk_id_assinatura' => $id,
                'fk_id_pessoa_responde' => $id_pessoa_responde,
                'fk_id_pl' => $id_pl,
                'respondido' => 1
            ];
            $this->db->ireplace('form_pessoa', $ins);
        }
    }

    public function getPessoa($id_pessoa) {
        $sql = "SELECT "
                . " p.n_pessoa, p.dt_nasc, p.mae, e.logradouro, e.num, e.complemento, e.bairro, GROUP_CONCAT(concat(t.ddd, ' ', t.num)) tel,sus"
                . " FROM pessoa p "
                . " LEFT JOIN endereco e ON e.fk_id_pessoa = p.id_pessoa "
                . " left join telefones t on t.fk_id_pessoa = p.id_pessoa "
                . " WHERE p.id_pessoa = $id_pessoa "
                . " group by t.fk_id_pessoa ";

        $query = pdoSis::getInstance()->query($sql);
        $dados = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($dados)) {
            if (empty($dados['tel'])) {
                $dados['tel'] = "Sem telefone cadastrado, atualize os dados na Secretaria da Escola.";
            }
            if (!empty($dados['logradouro']) && !empty($dados['num'])) {
                $dados['endereco'] = $dados['logradouro'] . ', ' . $dados['num'] . ' ' . $dados['complemento'] . ' - ' . $dados['bairro'];
            } else {
                $dados['endereco'] = "Sem endereço cadastrado, atualize os dados na Secretaria da Escola.";
            }
        }
        return $dados;
    }

    // public function salvarFormProf() {
    //     $respostas = @$_POST[1];
    //     $id_form = filter_input(INPUT_POST, 'id_form', FILTER_SANITIZE_NUMBER_INT);
    //     $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
    //     $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
    //     $id_pessoa_responde = filter_input(INPUT_POST, 'id_pessoa_responde', FILTER_SANITIZE_NUMBER_INT);
    //     $sql = "DELETE FROM form_resposta WHERE  fk_id_form =  $id_form AND fk_id_pessoa = $id_pessoa AND fk_id_pl = $id_pl";
    //     $query = $this->db->query($sql);
    //     foreach ($respostas['fk_id_pergunta']['checkbox'] as $k => $v) { //checkbox
    //         foreach ($v as $i => $j) { 
    //             if (!empty($j)) {
    //                 $ins = [
    //                     'fk_id_form'=> $id_form,
    //                     'fk_id_pessoa'=> $id_pessoa,
    //                     'fk_id_pessoa_responde'=> $id_pessoa_responde,
    //                     'fk_id_pl' => $id_pl,
    //                     'fk_id_opcao'=> $j,
    //                 ];
    //                 $this->db->ireplace('form_resposta', $ins,1);
    //             }
    //         }
    //     }
    //     foreach ($respostas['fk_id_pergunta'] as $k => $v) {   
    //         if (!empty($v)) {
    //             if ($k == 55) {//input
    //                 $ins = [
    //                     'fk_id_form'=> $id_form,
    //                     'fk_id_pessoa'=> $id_pessoa,
    //                     'fk_id_pessoa_responde'=> $id_pessoa_responde,
    //                     'fk_id_opcao'=> $k,
    //                      'fk_id_pl' => $id_pl,
    //                     'n_resposta'=> $v,
    //                 ];
    //             }else{//radio
    //                  $ins = [
    //                     'fk_id_form'=> $id_form,
    //                     'fk_id_pessoa'=> $id_pessoa,
    //                     'fk_id_pessoa_responde'=> $id_pessoa_responde,
    //                      'fk_id_pl' => $id_pl,
    //                     'fk_id_opcao'=> $v,
    //                 ];   
    //             }
    //             $this->db->ireplace('form_resposta', $ins,1); 
    //             $respondido = 1;
    //         }      
    //     }
    //     if (!empty($respondido)) {
    //         $ins = [
    //             'fk_id_form'=> $id_form,
    //             'fk_id_pessoa'=> $id_pessoa,
    //              'fk_id_pl' => $id_pl,
    //             'respondido'=> 1
    //         ];   
    //         $this->db->ireplace('form_pessoa', $ins);  
    //     }
    // }

    public function getViewPDF($form, $id_pessoa, $id_form, $respostas = null) {

        $respostas = $this->getRespostas($id_form, $id_pessoa);
        $dadosForm = $this->getDadosForm($id_form, $id_pessoa);

        if (empty($form))
            return '';

        foreach ($form as $k => $v) {
            ?>
            <table style="font-size: 12px; border: 0px;padding:0; margin-top:<?= empty($v['fk_id_pai']) ? '20px' : "0" ?>; border-collapse: collapse;">
                <tr>
                    <td width="78%" <?= empty($v['fk_id_pai']) ? 'style="font-weight:bold"' : "" ?>>
                        <?php echo $v['n_pergunta']; ?>
                    </td>
                    <td>
                        <?php
                        if (!empty($v['opcoes'])) {
                            foreach ($v['opcoes'] as $i => $j) {
                                $respX = ' ';
                                if ($j['tipo'] == 2) {

                                    if (!empty($respostas)) {
                                        if (array_key_exists($j['id_opcao'], $respostas)) {
                                            $respX = '<b>X</b>';
                                        } else {
                                            $respX = ' ';
                                        }
                                    }
                                    echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $j['n_opcao'] . ' (' . $respX . ')';
                                }
                            }
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <?php if (!empty($v['opcoes'])) { ?>
                <div class="row"  style="padding-left: 30px; font-size: 12px;">
                    <div class="col">
                        <?php
                        foreach ($v['opcoes'] as $i => $j) {
                            if ($j['tipo'] == 1) {
                                $respX = ' ';
                                if (!empty($respostas)) {
                                    if (array_key_exists($j['id_opcao'], $respostas)) {
                                        $respX = '<b>X</b>';
                                    } else {
                                        $respX = ' ';
                                    }
                                }
                                echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $j['n_opcao'] . ' (' . $respX . ')';
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="row"  style="padding-left: 30px; font-size: 12px;">
                    <div class="col">
                        <?php
                        foreach ($v['opcoes'] as $i => $j) {
                            if ($j['tipo'] == 0) {
                                $n_resposta = '<br>&nbsp;&nbsp;&nbsp;&nbsp;_____________________________________________________________';
                                if (!empty($respostas)) {
                                    if (array_key_exists($j['id_opcao'], $respostas)) {
                                        $n_resposta = '<b>' . $respostas[$j['id_opcao']] . '</b>';
                                    }
                                }
                                echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $j['n_opcao'] . ' ' . $n_resposta;
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
            if (!empty($v['peguntas'])) {
                ?>
                <div class="row"  style="padding-left: 30px;">
                    <?php $this->getViewPDF($v['peguntas'], $id_pessoa, $id_form); ?>
                </div>

                <?php
            }
            ?>

            <?php
        }
    }

    public function alunoSala($id_pessoa) {
        $sql = "SELECT DISTINCT prof.rm, p.n_pessoa, t.n_turma, t.codigo, t.periodo "
                . " FROM ge_turma_aluno ta "
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN ge_aloca_prof prof on prof.fk_id_turma = t.id_turma "
                . " join ge_funcionario f on f.rm = prof.rm "
                . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " WHERE ta.fk_id_pessoa = $id_pessoa AND (ta.fk_id_tas = 0 or ta.fk_id_tas = null)";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($array)) {
            $vir = "";
            $prof = "";
            foreach ($array as $k => $v) {
                $prof = $prof . " " . $vir . $v['n_pessoa'] . ' (' . $v['rm'] . ')';
                $vir = ",";
            }
            foreach ($array as $k => $v) {
                if ($v['periodo'] == 'M') {
                    $alunoSala['periodo'] = 'Manhã';
                } elseif ($v['periodo'] == 'T') {
                    $alunoSala['periodo'] = 'Tarde';
                } else {
                    $alunoSala['periodo'] = 'Integral';
                }
                $alunoSala['rm'] = $v['rm'];
                $alunoSala['n_pessoa'] = $v['n_pessoa'];
                $alunoSala['n_turma'] = $v['n_turma'];
                $alunoSala['codigo'] = $v['codigo'];
            }
        }
        if (!empty($prof)) {
            $alunoSala['prof'] = $prof;
        } else {
            $alunoSala['prof'] = '';
        }
        return $alunoSala;
    }

    public function getProf($id_pessoa) {
        $sql = " SELECT f.rm, p.n_pessoa "
                . " FROM pessoa p "
                . " JOIN ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
                . " WHERE p.id_pessoa = $id_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $arrProf = $query->fetch(PDO::FETCH_ASSOC);

        return $arrProf;
    }

    public function getFormRespondido($id_pessoa, $id_form, $id_pl) {
        $form = sql::get('form_pessoa', 'respondido', ['fk_id_pessoa' => $id_pessoa, 'fk_id_form' => $id_form, 'fk_id_pl' => $id_pl], 'fetch');
        if (!empty($form)) {
            if ($form['respondido'] == 1) {
                $respondido = 1;
            } else {
                $respondido = 0;
            }
        } else {
            $respondido = 0;
        }
        return $respondido;
    }

    public function getEscolaAluno($id_pessoa) {
        $sql = "SELECT DISTINCT i.n_inst "
                . " FROM ge_turma_aluno ta "
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN instancia i on ta.fk_id_inst = i.id_inst "
                . " WHERE ta.fk_id_pessoa = $id_pessoa AND ta.fk_id_tas = 0";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        $escola = '';
        if (!empty($array)) {
            $escola = $array['n_inst'];
        }
        return $escola;
    }

    public function dashGet($id_inst = null, $id_pl) {

        $totais['autorizacaoPSE'] = $this->respTotalGet(1, null, $id_pl);
        $totais['autorizacaoPSEQr'] = $this->respTotalGet(1, -1, $id_pl);
        $totais['formProf'] = $this->respTotalGet(2, null, $id_pl);
        $totais['porDiaPais'] = $this->respDiaGet(1, null, $id_pl);
        $totais['porDiaProf'] = $this->respDiaGet(2, null, $id_pl);
        $totais['porDiaTotal'] = $totais['porDiaPais'] + $totais['porDiaProf'];
        $totais['totalALunos'] = $this->totalAlunosGet(null, null, $id_pl);
        $totais['totalRespPais'] = $totais['autorizacaoPSE'];
        $totais['respPais100'] = intval(($totais['totalRespPais'] / $totais['totalALunos']) * 100);
        $totais['respProf100'] = intval(($totais['formProf'] / $totais['totalALunos']) * 100);
        $totais['totalResp'] = $totais['autorizacaoPSE'] + $totais['formProf'];
        $totais['totalRespf100'] = intval(($totais['totalResp'] / $totais['totalALunos']) * 100); //1 formulario por aluno
        if ($totais['totalRespf100'] > 100) {
            $totais['totalRespf100'] = 100;
        }
        $totais['totSemana'] = $this->respGetSemana('1,2', null, $id_pl);
        $totais['porInst'] = $this->respInstGet(null, null, $id_pl);
        $totais['porInst100'] = $this->respInstGet(null, null, $id_pl, 1);
        $totais['totalRespPaisSemqr'] = $totais['autorizacaoPSE'] - $totais['autorizacaoPSEQr'];

        foreach ($totais['porDiaPais'] as $k => $v) {
            $datas = (!empty($datas) ? $datas . "," . $v : $v);
            $qtdResp = (!empty($qtdResp) ? $qtdResp . "," . $k : $k);
        }
        foreach ($totais['porDiaProf'] as $k => $v) {
            $datas = (!empty($datas) ? $datas . "," . $v : $v);
            $qtdResp = (!empty($qtdResp) ? $qtdResp . "," . $k : $k);
        }
        foreach ($totais['totSemana'] as $k => $v) {
            $dataSemana = (!empty($dataSemana) ? $dataSemana . "," . $k : $k);
            $qtdRespSemana = (isset($qtdRespSemana) ? $qtdRespSemana . "," . $v : $v);
        }
        $escola = '';
        foreach ($totais['porInst'] as $k => $v) {
            $escola = (!empty($escola) ? $escola . "," . $k : $k);
            $qtdEsc = (isset($qtdEsc) ? $qtdEsc . "," . $v : $v);
        }

        if (!empty($datas)) {
            $dataArray = explode(',', $datas);
            $datasStr = '"' . implode('","', $dataArray) . '"';
        }
        $totais['datas'] = '';
        $totais['qtdResp'] = '';
        if (!empty($datas)) {
            $totais['datas'] = $datasStr;
            $totais['qtdResp'] = $qtdResp;
        }

        $dataSemanaArray = explode(',', $dataSemana);
        $dataSemanaStr = '"' . implode('","', $dataSemanaArray) . '"';
        $totais['dataSemana'] = $dataSemanaStr;
        $totais['qtdRespSemana'] = $qtdRespSemana;

        $escolaArray = explode(',', $escola);
        $escolaStr = '"' . implode('","', $escolaArray) . '"';

        $totais['escola'] = $escolaStr;

        if (!empty($qtdEsc)) {
            $totais['qtdEsc'] = $qtdEsc;
        } else {
            $totais['qtdEsc'] = 0;
        }

        return $totais;
    }

    public function respGetSemana($id_form = null, $id_pessoa_responde = null, $id_pl) {
        $gerente = $this->gerente();
        if ($gerente <> 1) {
            $id_inst = " AND t.fk_id_inst = " . toolErp::id_inst();
        }

        if ($id_pessoa_responde == -1) {
            $sql = " SELECT COUNT(id_form_pessoa) AS respQrCode, date_format(dt_update,'%w') as dia_semana     "
                    . " FROM form_pessoa afp "
                    . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = afp.fk_id_pessoa AND (ta.fk_id_tas = 0 or ta.fk_id_tas = null) "
                    . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND t.fk_id_pl = $id_pl "
                    . $id_inst
                    . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                    . " WHERE fk_id_pessoa_responde = -1 AND fk_id_form IN ($id_form) AND t.fk_id_pl = $id_pl"
                    . " GROUP BY date_format(dt_update,'%w') ";
        } else {
            $sql = " SELECT COUNT(id_form_pessoa) AS respQrCode, date_format(dt_update,'%w') as dia_semana     "
                    . " FROM form_pessoa afp "
                    . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = afp.fk_id_pessoa AND (ta.fk_id_tas = 0 or ta.fk_id_tas = null) "
                    . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND t.fk_id_pl = $id_pl "
                    . @$id_inst
                    . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                    . " WHERE fk_id_form IN ($id_form) AND t.fk_id_pl = $id_pl"
                    . " GROUP BY date_format(dt_update,'%w') "
                    . " ORDER BY date_format(dt_update,'%w') ";
        }

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $resp = [];
        $resp2 = [];
        $dia_semana = $this->diaSemanaGet();

        foreach ($array as $v) {
            $resp[$dia_semana[$v['dia_semana']]] = $v['respQrCode'];
        }
        for ($i = 0; $i < 7; $i++) {
            if (!isset($resp[$dia_semana[$i]])) {
                $resp2[$dia_semana[$i]] = 0;
            } else {
                $resp2[$dia_semana[$i]] = $resp[$dia_semana[$i]];
            }
        }

        return $resp2;
    }

    public function respTotalGet($id_form, $id_pessoa_responde = null, $id_pl) {
        $gerente = $this->gerente();

        if (!empty($id_pessoa_responde)) {
            $id_pessoa_responde = ' AND fk_id_pessoa_responde = -1 ';
        }
        if ($gerente <> 1) {
            $id_inst = "  AND t.fk_id_inst = " . toolErp::id_inst();
        }

        $sql = " SELECT COUNT(id_form_pessoa) AS respQrCode"
                . " FROM form_pessoa afp "
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = afp.fk_id_pessoa AND (ta.fk_id_tas = 0 or ta.fk_id_tas = null) "
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND t.fk_id_pl = $id_pl"
                . @$id_inst
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1"
                . " WHERE fk_id_form IN ($id_form) AND t.fk_id_pl = $id_pl"
                . $id_pessoa_responde;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($array)) {
            $resp = $array['respQrCode'];
        } else {
            $resp = 0;
        }
        return $resp;
    }

    public function respDiaGet($id_form, $id_pessoa_responde = null, $id_pl) {
        $gerente = $this->gerente();

        if (!empty($id_pessoa_responde)) {//qr_code
            $id_pessoa_responde = ' AND fk_id_pessoa_responde = -1 ';
        }
        if ($gerente <> 1) {
            $id_inst = "  AND t.fk_id_inst = " . toolErp::id_inst();
        }

        $sql = " SELECT COUNT(id_form_pessoa) AS respQrCode, date_format(dt_update,'%d/%m/%Y') as dia"
                . " FROM form_pessoa afp "
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = afp.fk_id_pessoa AND (ta.fk_id_tas = 0 or ta.fk_id_tas = null) "
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND t.fk_id_pl = $id_pl"
                . @$id_inst
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1"
                . " WHERE fk_id_form = $id_form "
                . $id_pessoa_responde
                . " GROUP BY  date_format(dt_update,'%d/%m/%Y')";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $resp = [];
        foreach ($array as $k => $v) {
            $resp[$v['respQrCode']] = $v['dia'];
        }
        return $resp;
    }

    public static function diaSemanaGet() {
        $dias = [
            '0' => 'Domingo',
            '1' => 'Segunda-Feira',
            '2' => 'Terça-Feira',
            '3' => 'Quarta-Feira',
            '4' => 'Quinta-Feira',
            '5' => 'Sexta-Feira',
            '6' => 'Sábado'
        ];

        return $dias;
    }

    public function totalAlunosGet($id_inst = null, $ids = null, $id_pl = null) {

        $gerente = $this->gerente();
        if ($gerente <> 1) {
            $id_inst = " AND ta.fk_id_inst = " . toolErp::id_inst();
        } elseif ($gerente == 1 && !empty($id_inst)) {
            $id_inst = " AND ta.fk_id_inst = " . $id_inst;
        } else {
            $id_inst = '';
        }
        if (!empty($ids)) {
            $id_pessoa = 'p.id_pessoa';
        } else {
            $id_pessoa = 'count(fk_id_pessoa) AS total';
        }

        $sql = "SELECT DISTINCT $id_pessoa"
                . " FROM ge_turma_aluno ta  "
                . " WHERE ta.fk_id_turma IN  "
                . " (SELECT DISTINCT t.id_turma "
                . " FROM ge_turmas t "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " ) "
                . $id_inst
                . " AND (ta.fk_id_tas = 0 or ta.fk_id_tas is null)";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($array)) {
            $resp = $array['total'];
        } else {
            $resp = 0;
        }
        return $resp;
    }

    public static function professor() {
        if (in_array(user::session('id_nivel'), [24])) {
            $professor = 1;
        } else {
            $professor = 0;
        }

        return $professor;
    }

    public static function gerente() {
        if (in_array(user::session('id_nivel'), [10])) {
            $gerente = 1;
        } else {
            $gerente = 0;
        }

        return $gerente;
    }

    public function ativar_campanha() {
        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa = toolErp::id_pessoa();
        $n_pessoa = toolErp::n_pessoa();
        if (empty($id_pl)) {
            toolErp::alert("Escolha um Período Letivo");
            return;
        }
        $sql = "UPDATE pse_campanha SET at_campanha = 0";
        $query = $this->db->query($sql);
        $ins = [
            'at_campanha' => 1,
            'fk_id_pl' => $id_pl,
            'fk_id_pessoa' => $id_pessoa,
        ];
        $periodo = sql::get('ge_periodo_letivo', 'n_pl', ['id_pl' => $id_pl]);
        $id = $this->db->ireplace('pse_campanha', $ins);
        if ($id) {
            log::logSet("Ativou o período " . @$periodo['n_pl']);
        } else {
            toolErp::alert("Ops... algo deu errado");
            return;
        }
    }

    public static function campanha($id_pl = null) {
        $campanha = sql::get(['pse_campanha', 'ge_periodo_letivo'], 'fk_id_pl,n_pl', ['at_campanha' => 1], 'fetch');
        if (!empty($campanha)) {
            $n_pl = $campanha['n_pl'];
            $_SESSION['userdata']['n_pl'] = $n_pl;
        }

        if (empty($id_pl)) {
            return $n_pl;
        } else {
            return $campanha['fk_id_pl'];
        }
    }

    public function respInstGet($id_form, $id_pessoa_responde = null, $id_pl, $total100 = null) {
        $gerente = $this->gerente();

        if (!empty($id_pessoa_responde)) {//qrcode
            $id_pessoa_responde = ' AND fk_id_pessoa_responde = -1 ';
        }
        if ($gerente <> 1) {
            $id_inst = " AND t.fk_id_inst = " . toolErp::id_inst();
        }

        $sql = " SELECT COUNT(id_form_pessoa) as qtd, n_inst, id_inst FROM ge2.form_pessoa afp"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = afp.fk_id_pessoa AND (ta.fk_id_tas = 0 or ta.fk_id_tas = null) "
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND t.fk_id_pl = $id_pl "
                . @$id_inst
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN instancia i ON t.fk_id_inst = i.id_inst "
                . " WHERE  t.fk_id_pl = $id_pl "
                . $id_pessoa_responde
                . " GROUP BY n_inst ORDER BY n_inst";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $resp = [];
        if (!empty($total100)) {
            $id_inst_S = '';
            foreach ($array as $k => $v) {
                if (!empty($v['n_inst'])) {
                    $totalAlunos = $this->totalAlunosGet($v['id_inst'], null, $id_pl);
                    $id_inst_S = !empty($id_inst_S) ? $id_inst_S . ',' . $v['id_inst'] : $v['id_inst'];
                    $result = intval(($v['qtd'] / ($totalAlunos * 2)) * 100); //dois formularios
                    if ($result > 100) {
                        $result = 100;
                    }
                    $resp[$v['n_inst']] = [
                        'total100' => $result
                    ];
                }
            }
            $AND = '';
            if (!empty($id_inst_S)) {
                $AND = " AND id_inst NOT IN ($id_inst_S) ";
            }
            $sql = "SELECT n_inst FROM instancia i "
                    . " JOIN ge_escolas ON ge_escolas.fk_id_inst = i.id_inst "
                    . " JOIN ge_turmas t ON t.fk_id_inst = i.id_inst AND t.fk_id_pl = $id_pl"
                    . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                    . " WHERE i.ativo = 1" . $AND
                    . " GROUP BY n_inst ORDER BY n_inst";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchALL(PDO::FETCH_ASSOC);
            foreach ($array as $k => $v) {
                $resp[$v['n_inst']] = [
                    'total100' => 0
                ];
            }
        } else {
            foreach ($array as $k => $v) {
                $resp[$v['n_inst']] = $v['qtd'];
            }
        }

        return $resp;
    }

    public function oa($id_pessoa) {
        $FM = toolErp::sexo_pessoa($id_pessoa);
        $oa = toolErp::sexoArt($FM);
        return $oa;
    }

    public function seu($id_pessoa) {
        $FM = toolErp::sexo_pessoa($id_pessoa);
        if ($FM == 'F') {
            return 'sua';
        } elseif ($FM == 'M') {
            return 'seu';
        } else {
            return 'seu/sua';
        }
    }

    public function getDadosForm($id_form, $id_pessoa) {
        $id_pl = $this->campanha('id_pl');
        if (!empty($id_pessoa) && !empty($id_form)) {
            $sql = "SELECT "
                    . " fo.tipo, fo.id_opcao, fr.n_resposta"
                    . " FROM form_resposta fr"
                    . " JOIN form_opcoes fo  ON fo.id_opcao = fr.fk_id_opcao"
                    . " WHERE fr.fk_id_form = $id_form"
                    . " AND fr.fk_id_pessoa = $id_pessoa"
                    . " AND fr.fk_id_pl = $id_pl";
            $query = pdoSis::getInstance()->query($sql);
            $P1 = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($P1 as $k => $v) {
                if ($v["tipo"] == -1) {
                    $resp[$v["id_opcao"]] = $v["n_resposta"];
                }
            }
            if (!empty($resp)) {
                return $resp;
            }
        }
    }

    public function getAlunosOdonto($id_turma, $id_pl, $lanc = null) {
        $sql = "SELECT pao.avalia_odonto, pao.necssita_tratamento, pao.realizou_tratamento, pao.id_atend_odonto, pao.orientacao_odonto,p.id_pessoa, p.n_pessoa, dt_nasc,dt_avaliacao, timestampdiff(year,dt_nasc,CURDATE()) AS anos, TIMESTAMPDIFF(MONTH, dt_nasc, CURDATE()) % 12 AS meses, sexo, sus"
                . " FROM ge_turma_aluno ta "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " LEFT JOIN pse_atend_odonto pao on p.id_pessoa = pao.fk_id_pessoa AND pao.fk_id_pl = $id_pl"
                . " WHERE ta.fk_id_turma = $id_turma "
                . " AND (ta.fk_id_tas = 0 or ta.fk_id_tas is null) "
                . " ORDER BY p.n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $form = [];
        if (!empty($array)) {
            foreach ($array as $k => $v) {
                $tel = 0;
                $telArray = ng_pessoa::telefone($v['id_pessoa']);
                foreach ($telArray as $j) {
                    $tel = !empty($tel) ? $tel . ' / ' . $j['ddd'] . '-' . $j['num'] : $j['ddd'] . '-' . $j['num'];
                }
                $array[$k]['orient_odonto'] = ($v['orientacao_odonto'] ? 'Sim' : 'Nao');
                $array[$k]['tel'] = ($tel ? $tel : 'Sem telefone');
                $array[$k]['nec_tratamento'] = ($v['necssita_tratamento'] ? 'Sim' : 'Nao');
                $array[$k]['real_tratamento'] = ($v['realizou_tratamento'] ? 'Sim' : 'Nao');
                $array[$k]['aval_odonto'] = ($v['avalia_odonto'] ? 'Sim' : 'Não');
                $array[$k]['cadAluno'] = '<button class="btn btn-outline-info" onclick="cadAluno(' . $v['id_pessoa'] . ',\'' . $v['n_pessoa'] . '\',\'' . $v['sus'] . '\')">Atualizar</button>';
            }
            $form['array'] = $array;
            $form['fields'] = [
                'Nome do Aluno' => 'n_pessoa',
                'Telefone' => 'tel',
                'Nasc' => 'dt_nasc',
                'Data Aval' => 'dt_avaliacao',
                'Idade (anos)' => 'anos',
                'Idade (meses)' => 'meses',
                'Sexo' => 'sexo',
                'Cartão SUS' => 'sus',
                'Palestra' => 'orient_odonto',
                'Avaliação Odontológica' => 'aval_odonto',
                'Necessita Tratamento Dentário' => 'nec_tratamento',
                'Tratamento Dentário realizado' => 'real_tratamento',
                '||1' => 'cadAluno',
            ];
        }

        if (!empty($lanc)) {
            return $array;
        } else {
            return $form;
        }
    }

    public function cadOdonto() {
        $array = $_POST;
        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        foreach ($array as $k => $v) {
            if (!empty($v['orientacao_odonto'])) {
                $orientacao_odonto = 1;
                $insere = 1;
            } else {
                $orientacao_odonto = 0;
            }
            if (!empty($v['avalia_odonto'])) {
                $avalia_odonto = 1;
                $insere = 1;
            } else {
                $avalia_odonto = 0;
            }
            if (!empty($v['necssita_tratamento'])) {
                $necssita_tratamento = 1;
                $insere = 1;
            } else {
                $necssita_tratamento = 0;
            }
            if (!empty($v['realizou_tratamento'])) {
                $realizou_tratamento = 1;
                $insere = 1;
            } else {
                $realizou_tratamento = 0;
            }
            if (!empty($v['id_atend_odonto'])) {
                $insere = 1;
            }
            if (!empty($insere)) {
                $ins = [
                    'id_atend_odonto' => @$v['id_atend_odonto'],
                    'fk_id_pl' => $id_pl,
                    'fk_id_pessoa' => $k,
                    'fk_id_pessoa_cadastra' => toolErp::id_pessoa(),
                    'realizou_tratamento' => $realizou_tratamento,
                    'necssita_tratamento' => $necssita_tratamento,
                    'avalia_odonto' => $avalia_odonto,
                    'orientacao_odonto' => $orientacao_odonto,
                    'dt_avaliacao' => $v['dt_avaliacao'],
                ];
                $id = $this->db->ireplace('pse_atend_odonto', $ins, 1);
            }
            $insere = 0;
        }
        if (!empty($id)) {
            echo toolErp::alert("Salvo Com sucesso");
            return;
        }
    }

    public function formDel() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $id_form = filter_input(INPUT_POST, 'id_form', FILTER_SANITIZE_NUMBER_INT);
        $id_assinatura = filter_input(INPUT_POST, 'id_assinatura', FILTER_SANITIZE_NUMBER_INT);
        $id_pl = $this->campanha('id_pl');

        if (!empty($id_pessoa)&&!empty($id_form)&&!empty($id_pl)&&!empty($id_assinatura)) {
           $sql = "DELETE FROM asd_assinatura "
            . " WHERE id_assinatura = $id_assinatura";
            $query = pdoSis::getInstance()->query($sql);
            $sql = "DELETE FROM form_pessoa "
                . " WHERE fk_id_pessoa = $id_pessoa AND fk_id_form = $id_form AND fk_id_pl = $id_pl";
            $query = pdoSis::getInstance()->query($sql);
            $sql = "DELETE FROM form_resposta "
                . " WHERE fk_id_pessoa = $id_pessoa AND fk_id_form = $id_form AND fk_id_pl = $id_pl";
            $query = pdoSis::getInstance()->query($sql);
            toolErp::alert("Formulário Resetado");
            log::logSet("Resetou o formulário id $id_pessoa"); 
        }else{
            toolErp::alert("Nao há formulário preenchido para este aluno");
        }
        
        
    }
}
