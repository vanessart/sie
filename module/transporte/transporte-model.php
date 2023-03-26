<?php

class transporteModel extends MainModel {

    public $db;
    public $_setup;

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

        $idAluno = filter_input(INPUT_POST, 'idAluno', FILTER_SANITIZE_NUMBER_INT);
        if (!empty($idAluno)) {
            ob_end_clean();
            $dados = $this->dadosAluno($idAluno);
            echo json_encode($dados);
            exit();
        }

        $this->getSetup();

        if (!empty($_POST['apagarIntsLinha'])) {
            $sql = "DELETE FROM `transporte_inst_linha` "
                    . " WHERE `fk_id_li` = '" . @$_POST['id_li'] . "' "
                    . " and fk_id_inst = '" . @$_POST['fk_id_inst'] . "'";
            $query = pdoSis::getInstance()->query($sql);
        } elseif ($this->db->tokenCheck('cadAluno', true)) {
            $this->cadAluno();
        } elseif ($this->db->tokenCheck('exclAluno', true)) {
            $this->exclAluno();
        } elseif (!empty($_POST['mudaStatus'])) {
            $this->mudaStatus();
        } elseif (!empty($_POST['mapaReflech'])) {
            $this->mapaReflech();
        } elseif ($this->db->tokenCheck('trocaLinha', true)) {
            $this->trocaLinha();
        // } elseif ($this->db->tokenCheck('mudaStatusMult', true)) {
        //     $this->mudaStatusMult();
        } elseif ($this->db->tokenCheck('lancaFalta', true)) {
            $this->lancaFalta();
        } elseif (!empty($_REQUEST['encerrarTransf'])) {
            $this->encerrarTransf();
        } elseif (!empty($_POST['cadAdaptado'])) {
            $this->cadAdaptado();
        } elseif (!empty(@$_POST['table'] == 'cadAlunoAdaptado')) {
            $this->cadAlunoAdaptado();
        } elseif (!empty(@$_POST['table'] == 'exclAlunoAdaptado')) {
            $this->exclAlunoAdaptado();
        }

        if ($this->db->tokenCheck('gravalinhaescola', true)) {
            $this->gravalinhaesc();
        }

    }

    public function cadAdaptado() {

        $trans_alu_adaptado = $_POST[1];
        $destino = $_POST[2];
        $sql = " UPDATE `transporte_alu_adaptado_time` SET `Ativo` = NULL WHERE fk_id_pessoa = " . $trans_alu_adaptado['id_pessoa'];
        $query = pdoSis::getInstance()->query($sql);
        if (!empty($destino['destino'])) {
            foreach ($destino['destino'] as $v => $id_at) {
                $ins = $destino['desttime'][$v];
                $ins['fk_id_pessoa'] = $trans_alu_adaptado['id_pessoa'];
                $ins['Ativo'] = 1;
                $ins['fk_id_sa'] = intval(@$destino['status'][$v]);
                $ins['id_at'] = $id_at;
                $ins['destino'] = $v;
                $status = sqlErp::get('transporte_alu_adaptado_time', 'fk_id_sa', ['id_at' => $id_at], 'fetch')['fk_id_sa'];
                if ((@$status != intval(@$destino['status'][$v])) || (empty(@$status) && @$status != '0')) {
                    unset($insLog);
                    $insLog['fk_id_sa'] = intval(@$destino['status'][$v]);
                    $insLog['fk_id_pessoa'] = $trans_alu_adaptado['id_pessoa'];
                    $insLog['destino'] = $v;
                    $insLog['fk_id_pessoa_func'] = toolErp::id_pessoa();

                    $this->db->ireplace('transporte_adaptado_log', $insLog, 1);
                }
                $this->db->ireplace('transporte_alu_adaptado_time', $ins, 1);
            }
        }

        $this->db->ireplace('transporte_alu_adaptado', $trans_alu_adaptado);
    }

    public function lancaFalta() {

        $alu = @$_POST['alu'];
        $dias = @$_POST['dias'];
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $id_fj = filter_input(INPUT_POST, 'id_fj', FILTER_SANITIZE_NUMBER_INT);
        $justificativa = filter_input(INPUT_POST, 'justificativa', FILTER_SANITIZE_STRING);
        $id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa = toolErp::id_pessoa();

        if (empty($dias)) {
            toolErp::alert("Dados não enviados");
            return;
        }

        foreach ($dias as $key => $value) {
            $sql = "DELETE FROM `transporte_falta` "
                . " WHERE `dt_falta` = '$value' "
                . " AND `fk_id_alu` in ("
                    . "SELECT DISTINCT `id_alu` FROM `transporte_aluno` "
                    . " WHERE `fk_id_li` = $id_li "
                . ") ";
            $query = pdoSis::getInstance()->query($sql);
        }

        if (!empty($alu)) {
            foreach ($alu as $kk => $vv) {
                foreach ($vv as $k => $v) {
                    if (empty($v)) {
                        $v = NULL;
                    }

                    $ins = [
                        'id_falta' => $v,
                        'fk_id_alu' => $kk,
                        'fk_id_pessoa' => $id_pessoa,
                        'dt_falta' => $k,
                    ];
                    $this->db->ireplace('transporte_falta', $ins, 1);
                }
            }
        }
        if (empty($id_fj)) {
            $id_fj = NULL;
        }

        $insj['id_fj'] = $id_fj;
        $insj['justificativa'] = $justificativa;
        $insj['dt_just'] = date("Y-m-d");
        $insj['fk_id_inst'] = $id_inst;
        $insj['fk_id_pessoa'] = toolErp::id_pessoa();
        $insj['fk_id_li'] = $id_li;

        foreach ($dias as $key => $data) {
            $insj['dt_falta'] = $data;
            $this->db->ireplace('transporte_falta_justifica', $insj, 1);
        }

        toolErp::alert("Faltas Lançadas");
        log::logSet("Lançou faltas linha id $id_li");
    }

    public function mudaStatusMult() {

        ob_end_clean();
        $id_sa = @$_POST['id_sa'];
        $id_li = @$_POST['id_li'];
        $transpSec = @$_POST['transpSec'];
        $dt_inicio = @$_POST['dt_inicio'];
        $dt_fim = @$_POST['dt_fim'];
        $up = [];
        if (!empty($id_li)) {
            foreach ($id_li as $k => $v) {
                if ($v != 'X') {
                    $id_alu = $k;
                    $up['fk_id_li'] = "$v";
                    log::logSet("Mudou a id_alu $k para a linha $v");
                }
            }
        }

        if (!empty($transpSec)) {
            foreach ($transpSec as $kt => $t) {
                $id_alu = $kt;
                $up['transp_secundario'] = $t;
                log::logSet("Atribiu a id_alu $kt transporte secundário $t");
            }
        }

        if (!empty($dt_inicio)) {
            foreach ($dt_inicio as $kt => $t) {
                $id_alu = $kt;
                $up['dt_inicio'] = $t;
                log::logSet("Atribiu a id_alu $kt data de inicio $t");
            }
        }

        if (!empty($dt_fim)) {
            foreach ($dt_fim as $kt => $t) {
                $id_alu = $kt;
                $up['dt_fim'] = $t;
                log::logSet("Atribiu a id_alu $kt data final $t");
            }
        }

        if (!empty($id_sa)) {
            $data = date("Y-m-d");
            foreach ($id_sa as $k => $v) {
                $id_alu = $k;
                if ($v != 'X') {
                    $up['fk_id_sa'] = "$v";
                    if ($v == 1) {
                        $up['dt_inicio'] = $dt_inicio[$k];
                    } elseif ($v == 6) {
                        $up['dt_fim'] = $dt_fim[$k];
                    }
                    log::logSet("mudou o status do id_alu $k para $v");
                }
            }
        }

        if (!empty($up)) {
            $up['id_alu'] = $id_alu;
            $this->db->ireplace('transporte_aluno', $up, 1);
            echo json_encode(["status" => true]);
            return;
        }

        echo json_encode(["status" => false]);
        return;
    }

    public function trocaLinha() {
        $alunos = @$_POST['aluno'];
        $id_li_destino = filter_input(INPUT_POST, 'id_li_destino', FILTER_SANITIZE_NUMBER_INT);
        $capacidade = transporteErp::linhaGet($id_li_destino)['capacidade'];
        $ocupacao = transporteErp::ocupacao($id_li_destino);
        if ($capacidade >= ($ocupacao + (count($alunos)))) {
            foreach ($alunos as $k => $v) {
                $sql = "UPDATE `transporte_aluno` SET `fk_id_li` = '$id_li_destino' WHERE `id_alu` = $v;";
                $query = pdoSis::getInstance()->query($sql);
                log::logSet("Mudou a id_alu $v para a linha $id_li_destino");
            }
            toolErp::alert('Finalizado');
        } else {
            toolErp::alert('A capacidade do ônibus é ' . $capacidade . ' alunos');
        }
    }

    public function mapaReflech() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $e = new escola($id_inst);
        $sql = "SELECT latitude, longitude FROM `endereco` WHERE `fk_id_pessoa` = $id_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $v = $query->fetch(PDO::FETCH_ASSOC);
        $maps = toolErp::distancia($v['latitude'], $v['longitude'], $e->_latitude, $e->_longitude);
        $distancia = $maps[0];
        $tempo = $maps[1];
        $sql = "UPDATE `transporte_aluno` SET `distancia_esc` = '$distancia', `tempo_esc` = '$tempo', fk_id_inst = '$id_inst' WHERE fk_id_pessoa = " . $id_pessoa;
        $query = pdoSis::getInstance()->query($sql);
        toolErp::alert('Atualizado');
    }

    public function mudaStatus() {
        $data = date("Y-m-d");
        $fk_id_sa = filter_input(INPUT_POST, 'fk_id_sa', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $fk_id_sa = $fk_id_sa == 1 ? '0' : '1';
        if ($fk_id_sa == 1) {
            $sql = "UPDATE `transporte_aluno` SET `fk_id_sa` = '$fk_id_sa', dt_inicio = '$data' WHERE `fk_id_pessoa` = $id_pessoa AND IFNULL(fk_id_sa,0) <= 1;";
        } elseif ($fk_id_sa == 0) {
            $sql = "UPDATE `transporte_aluno` SET `fk_id_sa` = '$fk_id_sa', dt_inicio = NULL WHERE `fk_id_pessoa` = $id_pessoa AND IFNULL(fk_id_sa,0) <= 1;";
        }
        $query = pdoSis::getInstance()->query($sql);
        log::logSet("mudou o status do id_alu $id_pessoa para $fk_id_sa");
    }

    public function exclAluno() {
        $alunos = @$_POST['aluno'];
        if (is_array($alunos)) {
            foreach ($alunos as $k => $v) {
                $sql = "UPDATE `transporte_aluno` SET `fk_id_li` = NULL, fk_id_sa = 0, dt_solicita = NULL WHERE `id_alu` = $v ";
                $query = pdoSis::getInstance()->query($sql);
            }
        }
        log::logSet("Excluiu id_alu $v ");
        toolErp::alert('Concluído');
    }

    public function cadAluno() {
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);
        $alunos = @$_POST['aluno'];
        $capacidade = transporteErp::linhaGet($id_li)['capacidade'];
        $ocupacao = transporteErp::ocupacao($id_li);
        if (is_array($alunos)) {
            $data = date("Y-m-d");
            foreach ($alunos as $k => $v) {
                $dadosAluno = ng_aluno::get($k, $id_inst);
                unset($ins);
                $ins['fk_id_li'] = $id_li;
                $ins['id_alu'] = $v;
                $ins['fk_id_inst'] = $id_inst;
                $ins['dt_solicita'] = $data;
                $ins['fk_id_pessoa'] = $k;
                $ins['fk_id_sa'] = 0;
                $ins['fk_id_turma'] = !empty($dadosAluno) ? $dadosAluno['id_turma'] : null;
                $this->db->ireplace('transporte_aluno', $ins, 1);
                log::logSet("Incluiu fk_id_pessoa $k na linha $id_li");
            }
        }
        toolErp::alert('Concluído');
    }

    public function listaVeiculos($situacao = NULL) {
        if (empty($situacao)) {
            $situacao = 1;
        }
        $sql = "SELECT * FROM transporte_veiculo v "
                . " JOIN transporte_tipo_veiculo t on t.id_tiv=v.fk_id_tiv"
                . " JOIN transporte_status_veiculo s on s.id_sv = v.fk_id_sv"
                . " WHERE s.n_sv LIKE 'Ativo'"
                . " ORDER BY v.n_tv ";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        // $vei = sqlErp::get('transporte_veiculo', '*', ['situacao' => $situacao]);
        $token = formErp::token('transporte_veiculo', 'delete');
        foreach ($array as $k => $v) {
            $v['modal'] = 1;
            $array[$k]['del'] = formErp::submit('Apagar', $token, ['1[id_tv]' => $v['id_tv']]);
            // $array[$k]['edit'] = formErp::submit('Editar', NULL, $v);
            $array[$k]['edit'] = '<button class="btn btn-outline-info" onclick="editar(' . $v['id_tv'] . ')">Editar</button>';
            $array[$k]['cadeirante'] = toolErp::simnao($v['cadeirante']);
        }

        $form['array'] = $array;
        $form['fields'] = [
            'ID' => 'id_tv',
            'Veículo(registro)' => 'n_tv',
            'Placa' => 'placa',
            'Tipo' => 'n_tiv',
            'Capacidade' => 'capacidade',
            'Cadeirante' => 'cadeirante',
            'Situação' => 'n_sv',
            // '||1' => 'del',
            '||2' => 'edit'
        ];

        return $form;
    }

    public function listaLinhas($ativo = null)
    {
        $id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);
        $inativar = filter_input(INPUT_POST, 'inativar', FILTER_SANITIZE_NUMBER_INT);

        // inativando
        if (!empty($inativar))
        {
            $ins = [
                'id_li' => $id_li,
                'ativo' => '0',
            ];
            $this->db->ireplace('transporte_linha', $ins);
        }

        $sqlAux = "";
        if (is_null($ativo)) {
            $sqlAux .= " AND l.ativo = 1";
        } else {
            $sqlAux .= " AND l.ativo = $ativo";
        }

        $sql = "SELECT * FROM `transporte_linha` l "
                . "LEFT JOIN transporte_veiculo v ON v.id_tv = l.fk_id_tv "
                . "WHERE 1"
                . $sqlAux;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        // $token = formErp::token('transporte_linha', 'delete');
        foreach ($array as $k => $v) {
            $v['modal'] = 1;
            // $array[$k]['del'] = formErp::submit('Apagar', $token, ['1[id_li]' => $v['id_li']]);
            // $array[$k]['edit'] = formErp::submit('Editar', NULL, $v);
            if (!empty($array[$k]['ativo'])) {
                $array[$k]['edit'] = '<button class="btn btn-outline-info" onclick="editar(' . $v['id_li'] . ')">Editar</button>';
                $array[$k]['del'] = '<button class="btn btn-outline-danger" onclick="inativar(' . $v['id_li'] . ')">Inativar</button>';
            } else {
                $array[$k]['edit'] = '';
                $array[$k]['del'] = '';
            }
            $array[$k]['periodo'] = dataErp::periodoDoDia($v['periodo']);
            if ($v['at_nome'] != 1) {
                $array[$k]['n_li'] = $v['n_tv'] . '-V' . $v['viagem'] . '-' . (explode(' ', $v['motorista'])[0]) . '-' . $v['periodo'];
            }
        }

        $form['array'] = $array;
        $form['fields'] = [
            'ID' => 'id_li',
            'Linha' => 'n_li',
            'Viagem' => 'viagem',
            'Motorista' => 'motorista',
            'Monitor' => 'monitor',
            'Saída' => 'saida',
            'Capacidade' => 'capacidade',
            'Placa' => 'placa',
            'Período' => 'periodo',
            '||2' => 'edit',
            '||1' => 'del',
        ];

        return $form;
    }

    public function listaEmpresas() {
        $empresas = transporteErp::empresas();
        foreach ($empresas as $k => $v) {
            $v['novo'] = 1;
            $empresas[$k]['atv'] = toolErp::simnao($v['ativo']);
            // $empresas[$k]['ac'] = formErp::submit('Acessar', NULL, $v);
            $empresas[$k]['ac'] = '<button class="btn btn-outline-info" onclick="editar(' . $v['id_em'] . ')">Editar</button>';
        }

        $form['array'] = $empresas;
        $form['fields'] = [
            'ID' => 'id_em',
            'Empresa' => 'n_em',
            'Razão Social' => 'razao_social',
            'Nome Contato' => 'nome_contato',
            'Ativo' => 'atv',
            "||" => 'ac'
        ];

        return $form;
    }

    public function AlunoTurma($id_turma, $id_inst) {
        $e = new escola($id_inst);
        //alunos da c classe
        $alunoTurma = alunos::listar($id_turma);

        if (empty($alunoTurma)) {
            return [];
        }

        //coluna id_pessoa
        $idPessoas = array_column($alunoTurma, 'id_pessoa');

        //alunos inseridos na tabela transporte_aluno
        $sql = "SELECT fk_id_pessoa, distancia_esc FROM transporte_aluno "
                . " WHERE fk_id_pessoa IN(" . implode(',', $idPessoas) . ") "
                . " AND fk_id_sa NOT IN(6) "
                . " AND fk_id_inst = $id_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $alu = $query->fetchAll(PDO::FETCH_ASSOC);
        //coluna fk_id_pessoa
        $idPessoasAlu = array_column($alu, 'fk_id_pessoa');
        //Alunos que falta na tabela transporte_aluno
        $falta = array_diff($idPessoas, $idPessoasAlu);

        //add os alunos
        if (!empty($alu)) {
            foreach ($alu as $key => $value) {
                if (empty(preg_replace("/[^0-9]/", "", $value['distancia_esc']))) {
                    $falta[] = $value['fk_id_pessoa'];
                }   
            }
        }
        if (!empty($falta)) {
            //coordenadas dos alunos que faltam no transporte_aluno
            $sql = "SELECT "
                    . " `fk_id_pessoa`, `latitude`, `longitude` "
                    . " FROM `endereco` "
                    . " WHERE `fk_id_pessoa` in (" . implode(',', $falta) . ") "
                    . " AND `fk_id_tp` = 1";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($array as $v) {
                $sql = "SELECT * FROM `transporte_aluno` WHERE `fk_id_pessoa` = " . $v['fk_id_pessoa'] . " AND `fk_id_inst` = $id_inst AND IFNULL(`fk_id_sa`, 0) IN (0,1)";
                $query = pdoSis::getInstance()->query($sql);
                $existe = $query->fetch(PDO::FETCH_ASSOC);
                $maps = toolErp::distancia($v['latitude'], $v['longitude'], $e->_latitude, $e->_longitude);
                $distancia = $maps[0];
                $tempo = $maps[1];

                $ins = [
                    'id_alu' => @$existe['id_alu'],
                    'fk_id_pessoa' => $v['fk_id_pessoa'],
                    'fk_id_inst' => $id_inst,
                    'distancia_esc' => $distancia,
                    'tempo_esc' => "$tempo",
                ];
                if (empty($existe) || empty($existe['fk_id_turma']) )
                {
                    $dadosAluno = ng_aluno::get($v['fk_id_pessoa'], $id_inst);
                    $ins['fk_id_turma'] = !empty($dadosAluno) ? $dadosAluno['id_turma'] : null;
                }
                $this->db->ireplace('transporte_aluno', $ins, 1);
            }
        }

        $sql = "SELECT p.id_pessoa, p.n_pessoa, p.ra, a.id_alu, ta.chamada, a.distancia_esc, e.latitude, e.longitude, t.fk_id_ciclo ,a.fk_id_pessoa as teste, t.fk_id_inst"
                . " FROM `ge_turma_aluno` ta "
                . " JOIN ge_turma_aluno_situacao tas ON tas.id_tas = ta.fk_id_tas AND tas.id_tas = 0 "
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " JOIN endereco e on e.fk_id_pessoa = p.id_pessoa "
                . " JOIN transporte_aluno a on a.fk_id_pessoa = p.id_pessoa "
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " WHERE ci.fk_id_curso in (1,3) "
                . " AND ta.fk_id_turma = $id_turma "
                . " AND a.fk_id_li IS NULL "
                . " AND a.fk_id_sa NOT IN(6) "
                . " AND pl.at_pl = 1 "
                . " ORDER BY `chamada` ASC";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function alunoRelat($id_inst = NULL, $status = NULL) {

        if ($status == 'T' && empty($id_inst)) {
            toolErp::alert("Pesquisa muito abrangente");
        } else {
            $alunos = transporteErp::alunos($id_inst, $status);
            $linhaCount = $this->countLinha();

            foreach ($alunos as $k => $a) {

                $alunos[$k]['nec_esp'] = ng_aluno::necessidadesEspeciais($a['id_pessoa'], $a['ra']);
                $alunos[$k]['ocupacao'] = transporteErp::ocupacao($a['id_li'], 1);
                $alunos[$k]['vagas'] = $alunos[$k]['capacidade'] - $alunos[$k]['ocupacao'];
                $alunos[$k]['n_mot'] = $alunos[$k]['n_mot'] ?? '';

                if (!empty($a['distancia_esc'])) {
                    $medida = explode(' ', $a['distancia_esc'])[1];
                    if ($medida == 'm') {
                        $btn = "DarkOrange";
                    } else {
                        $setDist = str_replace(',', '.', explode(' ', $a['distancia_esc'])[0]);
                        if (in_array($a['fk_id_ciclo'], [1, 2, 3, 4, 19, 20])) {
                            $dist = 0.8;
                        } else {
                            $dist = 1.1;
                        }
                        if ($setDist <= $dist) {
                            $btn = "DarkOrange";
                        } elseif ($setDist > 10) {
                            $btn = "red";
                        } else {
                            $btn = "blue";
                        }
                    }
                } else {
                    $btn = "DarkOrange";
                }
                if (!empty($a['fk_id_tv_s'])) {
                    $alunos[$k]['transpSec'] = formErp::checkbox('transpSec[' . $a['id_alu'] . ']', 1, "", $a['transp_secundario'], ' onchange="update('.$a['id_alu'].')" class="alu-tsec-'.$a['id_alu'].'"');
                }
                $alunos[$k]['distancia_esc'] = '<div id="' . $a['id_pessoa'] . '" style="color: ' . $btn . ';cursor:pointer" onclick="acesso(\'' . $a['id_pessoa'] . '\',\'' . $a['id_inst'] . '\')"><img src="'.HOME_URI .'/includes/images/pointmap.png" />' . $a['distancia_esc'] . '</div>';
                $alunos[$k]['periodo'] = dataErp::periodoDoDia($a['periodo']);

                $clDi = ($a['id_sa'] != 1) ? 'visually-hidden' : '';
                $clDf = ($a['id_sa'] != 6) ? 'visually-hidden' : '';
                $alunos[$k]['n_sa'] = $this->selectStatus($a['id_alu'], $a['id_sa']) 
                    . formErp::input('dt_inicio[' . $a['id_alu'] . ']', '', $a['dt_inicio'], ' onblur="update('.$a['id_alu'].')"', NULL, 'date', 'alu-di-'.$a['id_alu'].' '.$clDi)
                    . formErp::input('dt_fim[' . $a['id_alu'] . ']', '', $a['dt_fim'], ' onblur="update('.$a['id_alu'].')"', NULL, 'date', 'alu-df-'.$a['id_alu'].' '.$clDf);

                if ($status == 1) {
                    $alunos[$k]['n_li'] = $this->selectLinhaInst($a['id_alu'], $linhaCount['esc'][$a['id_inst']], $a['id_li']);
                } else {
                    $alunos[$k]['n_li'] = $a['n_tv'] . '-v' . $a['viagem'] . '-' . (explode(' ', $a['motorista'])[0]) . '-' . $a['periodo'];
                }
                if ($status == 'ND') {
                    $alunos[$k]['n_mot'] = $alunos[$k]['justificativa'];
                }

                if (!empty($alunos[$k]['nec_esp'])) {
                    //procura pela especialidade 1-MULTIPLA
                    $id_nes = array_column($alunos[$k]['nec_esp'], 'id_ne');
                    if (in_array(1, $id_nes)) {
                        //remove para nao exibir
                        unset($alunos[$k]['nec_esp'][array_search(1, $id_nes)]);
                    }
                    $especialidades = array_column($alunos[$k]['nec_esp'], 'n_ne');
                    $alunos[$k]['n_mot'] .= "<br><br>Situação do Aluno:<br> ".implode(", ", $especialidades);
                }

                $alunos[$k]['ac'] = '<button class="btn btn-info btn-save-'.$a['id_alu'].'" type="button" onclick="salvar(\'' . $a['id_alu'] . '\')" style="font-weight: bold">&#x2713;</button>';
            }

            $fieldData = '<input type="date" name="date_generic" class="date-generic" onblur="setDate()" />';

            $form['array'] = $alunos;
            $form['fields'] = [
                'Escola' => 'n_inst',
                'Aluno' => 'n_pessoa',
                'RA' => 'ra',
                'Turma' => 'n_turma',
                'Período' => 'periodo',
                'Distâcia' => 'distancia_esc',
                'Linha' => 'n_li',
                'Capacidade' => 'capacidade',
                'Vagas' => 'vagas',
                'Motivo' => 'n_mot',
                'DT. Def.' => 'dt_inicio',
                'Solicitação' => 'dt_solicita',
                'Transp. Sec.' => 'transpSec',
                'Status '. $fieldData => 'n_sa',
                '||1' => 'ac'
            ];

            if (!empty($id_inst)) {
                unset($form['fields']['Escola']);
            }

            // if ($status != 'SE' && $status != 'ND') {
            //     unset($form['fields']['Motivo']);
            // }
            ?>
            <div class="fieldBorder2">
                <form id="ms" method="POST">
                    <!--div style="text-align: right">
                        <button class="btn btn-success" type="submit">
                            Concluir
                        </button>
                    </div>
                    <br /-->
                    <?php
                    // echo formErp::hiddenToken('mudaStatusMult');
                    echo formErp::hidden(['id_inst' => $id_inst, 'status' => $status, 'pesq' => 1]);
                    toolErp::relatSimples($form);
                    ?>
                </form>
            </div>
            <?php
        }
    }

    //ATENÇÃO!!!   Valor default está como 'X'  "$id_sa == $k ?NULL:$k"
    public function selectStatus($id_alu, $id_sa) {
        $n_sa = sqlErp::idNome('transporte_status_aluno');
        if ($id_sa == 0) {
            unset($n_sa[6]);
        } elseif ($id_sa == 1) {
            unset($n_sa[2]);
            unset($n_sa[0]);
        } elseif ($id_sa == 2) {
            unset($n_sa[1]);
            unset($n_sa[0]);
            unset($n_sa[6]);
        } elseif ($id_sa == 6) {
            unset($n_sa[1]);
            unset($n_sa[0]);
            unset($n_sa[2]);
        }

        $form = '<select name="id_sa[' . $id_alu . ']" onchange="update('.$id_alu.')" class="alu-sa-'.$id_alu.'">';
        foreach ($n_sa as $k => $v) {
            $form .= '<option ' . ($id_sa == $k ? 'selected' : '') . ' value="' . ($id_sa == $k ? 'X' : $k) . '" data-id="'.$k.'">' . $v . '</option>';
        }
        $form .= '</select>';

        return $form;
    }

    public function countLinha() {
        $sql = "SELECT "
                . " fk_id_li, count(fk_id_li) as ct "
                . " FROM `transporte_aluno`  a"
                . " GROUP BY fk_id_li";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $cont[$v['fk_id_li']] = $v['ct'];
        }

        $sql = "SELECT li.id_li, li.motorista, li.viagem, li.periodo, "
                . " tv.capacidade, tv.n_tv "
                . " FROM transporte_linha li"
                . " join transporte_veiculo tv on tv.id_tv = li.fk_id_tv ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $linha[$v['id_li']] = $v['n_tv'] . '-v' . $v['viagem'] . '-' . (explode(' ', $v['motorista'])[0]) . '-' . $v['periodo'] . ' (' . ($v['capacidade'] - intval(@$cont[$v['id_li']])) . '/' . $v['capacidade'] . ')';
        }

        $sql = "SELECT * FROM `transporte_inst_linha`";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $linhaEsc = [];
        if (!empty($linhaEsc)) {
            foreach ($array as $v) {
                $linhaEsc['esc'][$v['fk_id_inst']][$v['fk_id_li']] = $linha[$v['fk_id_li']];
            }
        }

        return $linhaEsc;
    }

    //ATENÇÃO!!!   Valor default está como 'X'  "$id_li == $k ?NULL:$k"
    public function selectLinhaInst($id_alu, $countLinha, $id_li) {

        $form = '<select name="id_li[' . $id_alu . ']" onchange="update('.$id_alu.')" class="alu-li-'.$id_alu.'">';
        foreach ($countLinha as $k => $v) {
            $form .= '<option ' . ($id_li == $k ? 'selected' : '') . ' value="' . ($id_li == $k ? 'X' : $k) . '">' . $v . '</option>';
        }
        $form .= '</select>';

        return $form;
    }

    public function listaBranca() {
        $sql = "SELECT "
                . " p.id_pessoa, p.n_pessoa, p.ra, i.n_inst, t.n_turma "
                . " FROM `transporte_lita_branca` b "
                . " JOIN pessoa p on p.id_pessoa = b.id_pessoa "
                . " LEFT JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa "
                . " LEFT JOIN ge_turma_aluno_situacao tas ON tas.id_tas = ta.fk_id_tas AND tas.id_tas = 0 "
                . " LEFT JOIN instancia i on i.id_inst = ta.fk_id_inst "
                . " LEFT JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " LEFT JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " WHERE ci.fk_id_curso in (1,3) "
                . " AND pl.at_pl = 1 "
                . " ORDER BY n_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $token = formErp::token('transporte_lita_branca', 'delete');
        foreach ($array as $k => $v) {
            $array[$k]['exc'] = formErp::submit('Excluir', $token, ['1[id_pessoa]' => $v['id_pessoa']]);
        }
        $form['array'] = $array;
        $form['fields'] = [
            'RA' => 'ra',
            'Aluno' => 'n_pessoa',
            'Escola' => 'n_inst',
            'Turma' => 'n_turma',
            '||' => 'exc'
        ];
        toolErp::relatSimples($form);
    }

    public function alunoPesq($id_inst = null, $nome = null, $fk_id_sa = NULL) {
        $alunos = [];
        if (empty($id_inst) && empty($nome)) {
            toolErp::alert('Preencha um dos Campos');
        } else {
            $alunos = transporteErp::alunoPesq($id_inst, $nome, $fk_id_sa);
        }

        return $alunos;
    }

###########################################################################

    /**
     * 
     * @param type $id_alus array com os "id_alu"
     */
    public static function faltaDia($id_alus = NULL, $data, $data_fim = null) {
        if (empty($id_alus)) {
            return [];
        }

        $sqlAux = '';
        if ( !empty($data_fim) ) {
            $sqlAux .= " AND dt_falta BETWEEN '$data' AND '$data_fim' ";
        } else {
            $sqlAux .= " AND dt_falta = '$data' ";
        }

        $sql = "SELECT id_falta, fk_id_alu, dt_falta FROM `transporte_falta` "
                . " WHERE `fk_id_alu` IN (" . (implode(",", $id_alus)) . ") "
                . $sqlAux;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if (empty($array)) {
            return [];
        }
        foreach ($array as $v) {
            $faltas[$v['fk_id_alu']][] = [
                'id_falta' => $v['id_falta'],
                'dt_falta' => $v['dt_falta'],
            ];
        }

        return $faltas;
    }

##################### Mario ###############

    public function pegalinharelVeiculo($idTv) {

        $sql = "SELECT DISTINCT tl.id_li, tl.n_li FROM transporte_linha tl"
                . " JOIN transporte_inst_linha il ON il.fk_id_li = tl.id_li"
                . " WHERE tl.fk_id_tv = '" . $idTv . "'";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function pegalinharel($idinst) {

        if (empty($idinst)){
            return [];
        }

        $sql = "SELECT DISTINCT tl.id_li, tl.n_li FROM transporte_linha tl"
                . " JOIN transporte_inst_linha il ON il.fk_id_li = tl.id_li"
                . " WHERE il.fk_id_inst = " . $idinst;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function pegacabecalhorel($idlinha = null, $id_inst = null, $id_em = null) {
        $sqlAux = '';

        if (!empty($idlinha)) {
            $sqlAux .= " AND l.id_li IN ($idlinha) ";
        }

        if (!empty($id_inst)) {
            $sqlAux .= " AND il.fk_id_inst = $id_inst ";
        }

        if (!empty($id_em)) {
            $sqlAux .= " AND e.id_em = $id_em";
        }

        $sql = "SELECT l.id_li, l.n_li, e.n_em, v.acessibilidade, v.capacidade, v.placa,"
                . " tv.n_tiv, i.n_inst, v.n_tv, l.periodo, l.motorista,"
                . " l.tel1, l.monitor, l.viagem FROM transporte_linha l"
                . " JOIN transporte_veiculo v ON v.id_tv = l.fk_id_tv"
                . " JOIN transporte_empresa e ON e.id_em = v.fk_id_em"
                . " JOIN transporte_tipo_veiculo tv ON tv.id_tiv = v.fk_id_tiv"
                . " JOIN transporte_inst_linha il ON il.fk_id_li = l.id_li"
                . " JOIN instancia i ON i.id_inst = il.fk_id_inst"
                . " WHERE 1 "
                . $sqlAux;

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function pegaalunos($idlinha) {

        $sql = "SELECT p.id_pessoa, p.n_pessoa, i.n_inst, p.ra, p.ra_dig,"
                . " t.codigo, p.dt_nasc, t.fk_id_ciclo, al.id_alu,"
                . " al.dt_inicio, al.dt_fim FROM transporte_aluno al"
                . " JOIN pessoa p ON p.id_pessoa = al.fk_id_pessoa"
                . " JOIN instancia i ON i.id_inst = al.fk_id_inst"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND t.fk_id_inst = ta.fk_id_inst "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " JOIN transporte_inst_linha il ON il.fk_id_inst = al.fk_id_inst AND il.fk_id_li = al.fk_id_li"
                . " JOIN transporte_linha tl on tl.id_li = il.fk_id_li AND tl.id_li = al.fk_id_li"
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " AND ci.fk_id_curso in (1,3) "
                . " WHERE il.fk_id_li = $idlinha "
                . " AND pl.at_pl = 1 "
                . " AND al.fk_id_sa = 1 "
                . " ORDER BY p.n_pessoa";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function movimentacaogeral($mes) {
        //Iniciando as variaveis
        $dados['totali'] = 0;
        $dados['totale'] = 0;
        $dados['totals'] = 0;

        $sql = "SELECT DISTINCT fk_id_inst FROM transporte_aluno";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $dados['inclusao'][$v['fk_id_inst']] = 0;
            $dados['exclusao'][$v['fk_id_inst']] = 0;
            $dados['espera'][$v['fk_id_inst']] = 0;
        }

        $m = date('Y') . '-' . $mes . '%';
        // Inicio
        $sql = "SELECT i.id_inst, COUNT(gt.dt_inicio) AS inicio FROM transporte_aluno gt"
                . " JOIN instancia i ON i.id_inst = gt.fk_id_inst"
                . " GROUP BY i.id_inst, gt.dt_inicio"
                . " HAVING dt_inicio LIKE '" . $m . "'";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $k => $v) {
            $dados['inclusao'][$v['id_inst']] = $v['inicio'];
            $dados['totali'] = $dados['totali'] + $v['inicio'];
        }
        //fim
        $sql = "SELECT i.id_inst, COUNT(gt.dt_fim) AS fim FROM transporte_aluno gt"
                . " JOIN instancia i ON i.id_inst = gt.fk_id_inst"
                . " GROUP BY i.id_inst, gt.dt_fim"
                . " HAVING dt_fim LIKE '" . $m . "'";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $k => $v) {
            $dados['exclusao'][$v['id_inst']] = $v['fim'];
            $dados['totale'] = $dados['totale'] + $v['fim'];
        }

        //Aguardando
        $sql = "SELECT i.id_inst, COUNT(gt.dt_solicita) AS solicita FROM transporte_aluno gt"
                . " JOIN instancia i ON i.id_inst = gt.fk_id_inst"
                . " GROUP BY i.id_inst, gt.dt_solicita, gt.fk_id_sa"
                . " HAVING dt_solicita LIKE '" . $m . "' AND gt.fk_id_sa = 0";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $k => $v) {
            $dados['espera'][$v['id_inst']] = $v['solicita'];
            $dados['totals'] = $dados['totals'] + $v['solicita'];
        }

        return $dados;
    }

    public function pegaescola() {
        //escola em ordem alfabetica

        $sql = "SELECT DISTINCT i.id_inst, i.n_inst FROM transporte_aluno gt"
                . " JOIN instancia i ON i.id_inst = gt.fk_id_inst"
                . " ORDER BY i.n_inst";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $esc[$v['id_inst']] = $v['n_inst'];
        }

        return $esc;
    }

    public function alunostransportados($mes) {

        $sql = "SELECT DISTINCT fk_id_inst FROM transporte_aluno";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $dados['Manhã'][$v['fk_id_inst']] = 0;
            $dados['Tarde'][$v['fk_id_inst']] = 0;
            $dados[$v['fk_id_inst']] = 0;
            $dados['Manhãt'] = 0;
            $dados['Tardet'] = 0;
            $dados['Vagas'][$v['fk_id_inst']] = 0;
            $dados['Vagast'] = 0;
        }

        $sql = "SELECT gt.fk_id_inst, tl.periodo,"
                . " COUNT(gt.fk_id_inst) as total FROM transporte_aluno gt"
                . " JOIN transporte_inst_linha il ON il.fk_id_inst = gt.fk_id_inst"
                . " JOIN transporte_linha tl ON tl.id_li = il.fk_id_li AND tl.id_li = gt.fk_id_li"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = gt.fk_id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl"
                . " WHERE pl.at_pl = 1 AND gt.fk_id_sa = 1"
                . " GROUP BY gt.fk_id_inst, tl.periodo, t.fk_id_pl, gt.fk_id_sa, pl.at_pl";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $dados[$v['periodo']][$v['fk_id_inst']] = $v['total'];
            @$dados[$v['periodo'] . 't'] = $dados[$v['periodo'] . 't'] + $v['total'];
            $dados[$v['fk_id_inst']] = $dados[$v['fk_id_inst']] + $v['total'];
        }

        $sql = "SELECT il.fk_id_inst, SUM(tv.capacidade) as total FROM transporte_inst_linha il"
                . " JOIN transporte_linha tl ON tl.id_li = il.fk_id_li"
                . " JOIN transporte_veiculo tv ON tv.id_tv = tl.fk_id_tv"
                . " WHERE tv.fk_id_sv = 1 "
                . " GROUP BY il.fk_id_inst, tv.fk_id_sv"
                . " ORDER BY il.fk_id_inst";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $dados['Vagas'][$v['fk_id_inst']] = $v['total'];
            $dados['Vagast'] = $dados['Vagast'] + $v['total'];
        }

        return $dados;
    }

    public function movimentacaoaluno($mes, $idinst)
    {
        $dados['Inclusão'] = $this->movimentacaoalunoinclusao($mes, $idinst);
        $dados['Exclusão'] = $this->movimentacaoalunoexclusao($mes, $idinst);
        return $dados;
    }

    public function movimentacaoalunoinclusao($mes, $idinst) {
        $m = date('Y') . '-' . $mes . '%';

        $sql = "SELECT p.n_pessoa, p.ra, p.ra_dig, gt.fk_id_sa, t.codigo,"
                . " e.logradouro, e.num, e.bairro, gt.distancia_esc,"
                . " e.logradouro_gdae, e.num_gdae,"
                . " tr.n_li, tr.periodo, gt.dt_inicio AS Data FROM pessoa p"
                . " JOIN transporte_aluno gt ON gt.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = gt.fk_id_turma "
                . " JOIN transporte_inst_linha il ON il.fk_id_inst = gt.fk_id_inst AND il.fk_id_li = gt.fk_id_li"
                . " JOIN transporte_linha tr ON tr.id_li = il.fk_id_li"
                . " LEFT JOIN endereco e ON e.fk_id_pessoa = p.id_pessoa"
                . " WHERE 1 "
                . " AND gt.fk_id_inst = " . $idinst
                . " AND gt.dt_inicio LIKE '" . $m . "'"
                . " ORDER BY p.n_pessoa";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $dados = [];
        if (!empty($array)){
            foreach ($array as $k => $v) {
                $dados[$k] = $v;
            }
        }

        return $dados;
    }

    public function movimentacaoalunoexclusao($mes, $idinst) {
        $m = date('Y') . '-' . $mes . '%';

        $sql = "SELECT p.n_pessoa, p.ra, p.ra_dig, gt.fk_id_sa, t.codigo,"
                . " e.logradouro, e.num, e.bairro, gt.distancia_esc,"
                . " e.logradouro_gdae, e.num_gdae,"
                . " tr.n_li, tr.periodo, gt.dt_fim AS Data FROM pessoa p"
                . " JOIN transporte_aluno gt ON gt.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = gt.fk_id_turma "
                . " JOIN transporte_inst_linha il ON il.fk_id_inst = gt.fk_id_inst AND il.fk_id_li = gt.fk_id_li"
                . " JOIN transporte_linha tr ON tr.id_li = il.fk_id_li"
                . " LEFT JOIN endereco e ON e.fk_id_pessoa = p.id_pessoa"
                . " WHERE 1 "
                . " AND gt.fk_id_inst = " . $idinst 
                . " AND gt.fk_id_sa = 6"
                . " AND gt.dt_fim LIKE '" . $m . "'"
                . " ORDER BY p.n_pessoa";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $dados = [];
        if (!empty($array)){
            foreach ($array as $k => $v) {
                $dados[$k] = $v;
            }
        }

        return $dados;
    }

    public function wpegaferiadosfalta($mes, $dia, $tipoesc, $idpessoa, $linha) {

        $tipo = substr($tipoesc, 0, 2);
        $ano = date('Y');
        $d = $ano . '-' . $mes . '-' . $dia;

        $sql = "SELECT tipo_feriado FROM ge_feriados"
                . " WHERE dia = '" . $dia . "' AND mes = '" . $mes . "'"
                . " AND ano = '" . $ano . "' AND tipo_escola = '" . $tipo . "'";

        $query = $this->db->query($sql);
        $array = $query->fetch();

        if (!empty($array)) {
            $sit = $array['tipo_feriado'];
        } else {
            $sql = "SELECT al.id_alu FROM transporte_aluno al"
                    . " JOIN transporte_falta tf ON tf.fk_id_alu = al.id_alu"
                    . " WHERE al.fk_id_pessoa = '" . $idpessoa . "'"
                    . " AND al.fk_id_li = '" . $linha . "' AND tf.dt_falta = '" . $d . "'";
            $query = $this->db->query($sql);
            $array = $query->fetch();

            if (!empty($array)) {
                $sit = 'F';
            } else {
                $sit = 'P';
            }
        }

        return $sit;
    }

    public function falatsLinha($mes, $linha) {
        $sql = "SELECT * FROM transporte_aluno al"
                . " JOIN transporte_falta tf ON tf.fk_id_alu = al.id_alu"
                . " WHERE  al.fk_id_li = '" . $linha . "' AND tf.dt_falta like '%-" . $mes . "-%'";
        $query = $this->db->query($sql);
        $array = $query->fetchALL();

        foreach ($array as $v) {
            $dias[substr($v['dt_falta'], 8)][$v['fk_id_alu']] = $v['fk_id_alu'];
        }

        return $dias;
    }

    public function pegaferiadosfalta($mes, $linha) {
        $ano = date('Y');
        $sql = "SELECT * FROM ge_feriados"
                . " WHERE mes = '" . $mes . "'"
                . " AND ano = '" . $ano . "' ";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        foreach ($array as $v) {
            $sit[$v['tipo_escola']][$v['dia']] = $v['tipo_feriado'];
        }

        return $sit;
    }

    public function faltaJutifica($mes, $linha) {
        $sql = "SELECT * FROM `transporte_falta_justifica` WHERE `dt_just` LIKE '%-$mes-%' AND `fk_id_li` = $linha";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $dias[] = substr($v['dt_falta'], 8);
        }

        return $dias;
    }

    public function pegafalta($mes, $linha) {

        $d = date('Y') . '-' . $mes . '%';

        $sql = "SELECT * FROM transporte_aluno WHERE fk_id_li = '" . $linha . "'";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $falta[$v['fk_id_pessoa']] = 0;
        }

        for ($x = 1; $x <= 31; $x++) {
            if ($x < 10) {
                $falta['dia']['0' . $x] = 0;
            } else {
                $falta['dia'][$x] = 0;
            }
        }
        $falta['total'] = 0;

        $sql = "SELECT al.fk_id_pessoa, f.dt_falta FROM transporte_aluno al"
                . "  JOIN transporte_falta f ON f.fk_id_alu = al.id_alu"
                . " WHERE dt_falta LIKE '" . $d . "' AND al.fk_id_li = '" . $linha . "'";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($array)) {
            foreach ($array as $v) {
                $dia = date('d', strtotime($v['dt_falta']));
                @$falta[$v['fk_id_pessoa']]++;
                @$falta['dia'][$dia]++;
                $falta['total']++;
            }
        }

        return $falta;
    }

    public function encerrarTransf() {
        $data = date("Y-m-d");
        $sql = "SELECT a.fk_id_pessoa, a.fk_id_inst FROM transporte_aluno a "
                . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = a.fk_id_pessoa "
                . " JOIN ge_turma_aluno_situacao tas ON tas.id_tas = ta.fk_id_tas AND tas.id_tas = 0 "
                . " AND ta.fk_id_inst = a.fk_id_inst "
                . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " WHERE a.fk_id_sa = 1 "
                . " and pl.at_pl = 1";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $sql = "SELECT a.fk_id_pessoa, a.fk_id_inst FROM transporte_aluno a "
                    . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = a.fk_id_pessoa "
                    . " JOIN ge_turma_aluno_situacao tas ON tas.id_tas = ta.fk_id_tas AND tas.id_tas = 0 "
                    . " AND ta.fk_id_inst = a.fk_id_inst "
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
                    . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                    . " WHERE a.fk_id_sa = 1 "
                    . " AND a.fk_id_pessoa = " . $v['fk_id_pessoa']
                    . " AND a.fk_id_inst = " . $v['fk_id_inst']
                    . " AND pl.at_pl = 1";
            $query = pdoSis::getInstance()->query($sql);
            $frequente = $query->fetch(PDO::FETCH_ASSOC);
            if (empty($frequente['fk_id_pessoa'])) {
                $sql = "UPDATE `transporte_aluno` "
                . " SET `fk_id_sa` = '6', "
                . " `dt_solicita_fim` = '$data', "
                . " `fk_id_mot` = '1' "
                . " WHERE `fk_id_pessoa` = " . $v['fk_id_pessoa']
                . " and fk_id_sa = 1 "
                . " and fk_id_inst = " . $v['fk_id_inst'];
                $query = pdoSis::getInstance()->query($sql);
                log::logSet("transp" . str_replace("'", '', $sql));
            }
        }
    }

    public function relatAluAdaptado($id_inst) {
        $destino_ = unserialize(base64_decode($this->_setup['destino']));
        foreach ($destino_ as $k => $v) {
            if ($v['ativo'] == 1) {
                $destino[$k] = $v;
            }
        }
        $sql = "SELECT p.id_pessoa, p.ra, p.n_pessoa, p.responsavel, at.fk_id_sa, at.destino, at.id_at "
                . " FROM `transporte_alu_adaptado` a "
                . " JOIN pessoa p on p.id_pessoa = a.id_pessoa "
                . " JOIN transporte_alu_adaptado_time at on at.fk_id_pessoa = p.id_pessoa "
                . " WHERE `fk_id_inst` = $id_inst "
                . " AND at.ativo = 1 "
                . "ORDER BY p.n_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $k => $v) {
            $old = @$al[$v['id_pessoa']]['pdf'];
            $al[$v['id_pessoa']] = $v;
            @$al[$v['id_pessoa']]['pdf'] = $old . '<div style="float: left; padding-left: 20px">' . formErp::submit($destino[$v['destino']]['nome'], NULL, $v, HOME_URI . '/transporte/protocolo', 1) . '</div>';
            $al[$v['id_pessoa']]['ac'] = '<input class="btn btn-success" onclick="acessar(\'' . $v['id_pessoa'] . '\')" type="button" value="Acessar" />';
        }
        if (!empty($al)) {
            $form['array'] = $al;
            $form['fields'] = [
                'RA' => 'ra',
                'RSE' => 'id_pessoa',
                'Aluno' => 'n_pessoa',
                'Responsável' => 'responsavel',
                'Protocolo por Destino' => 'pdf',
                '||1' => 'ac'
            ];

            toolErp::relatSimples1($form);
        } else {
            ?>
            <div class="alert alert-warning text-center f-5">
                Não há Registros
            </div>
            <?php
        }
    }

    public function alunoDestino($destino, $id_inst = NULL, $statusMostar = '0,1') {
        if ($id_inst) {
            $id_inst = " and i.id_inst = $id_inst ";
        }

        if ($statusMostar == 'T') {
            $statusMostar = NULL;
            $idLiNull = NULL;
        } else {
            $statusMostar = " and d.fk_id_sa in ($statusMostar) ";
            $idLiNull = " and d.fk_id_li is NULL ";
        }
        $sql = "SELECT p.n_pessoa, p.id_pessoa, p.ra, p.ra_dig, i.n_inst, s.n_sa, s.id_sa "
                . " FROM transporte_alu_adaptado a "
                . " JOIN pessoa p on p.id_pessoa = a.id_pessoa "
                . " JOIN instancia i on i.id_inst = a.fk_id_inst "
                . " JOIN transporte_alu_adaptado_time d on d.fk_id_pessoa = a.id_pessoa "
                . " JOIN transporte_status_aluno s on s.id_sa = d.fk_id_sa "
                . " WHERE d.destino = $destino "
                . $statusMostar
                . $idLiNull
                . $id_inst
                . " order by n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function dadosAluno($idAluno, $time = NULL) {
        if ($time) {
            $time = " and destino = $time";
        }
        $sql = "select "
                . "  p.n_pessoa, p.id_pessoa, p.dt_nasc, p.responsavel, p.cpf_respons"
                . " , a.tp_def, a.logradouro, a.num, a.cep, a.bairro, a.cadeirante "
                . " ,a.rg_respons, a.tel1, a.tel2 "
                . " from pessoa p "
                . " left join transporte_alu_adaptado a on a.id_pessoa = p.id_pessoa "
                . " where p.id_pessoa = '$idAluno' ";
        $query = pdoSis::getInstance()->query($sql);
        $aluno = $query->fetch(PDO::FETCH_ASSOC);
        if ($aluno) {
            $aluno['idade'] = dataErp::calculoIdade($aluno['dt_nasc']) . ' Anos';
        } else {
            $aluno = ['erro' => 1];
        }

        $sql = "SELECT * FROM `transporte_alu_adaptado_time` "
                . " WHERE `fk_id_pessoa` = $idAluno"
                . $time;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $destino[$v['destino']] = $v;
        }
        $dados['aluno'] = $aluno;
        if (!empty($destino)) {
            $dados['destino'] = $destino;
        }

        return $dados;
    }

    public function cadAlunoAdaptado() {
        $alunos = @$_POST['aluno'];
        $destino = filter_input(INPUT_POST, 'destino', FILTER_SANITIZE_NUMBER_INT);
        $id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);
        foreach ($alunos as $v) {
            $sql = "UPDATE `transporte_alu_adaptado_time`
                 SET `fk_id_li` = '$id_li' ,
                     fk_id_sa = 1 
                 WHERE `destino` = $destino "
                    . " and fk_id_pessoa = $v";
            $query = pdoSis::getInstance()->query($sql);
        }
    }

    public function exclAlunoAdaptado() {
        $alunos = @$_POST['aluno'];
        $destino = filter_input(INPUT_POST, 'destino', FILTER_SANITIZE_NUMBER_INT);
        $id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);
        foreach ($alunos as $k => $v) {
            $sql = "UPDATE `transporte_alu_adaptado_time`
                 SET `fk_id_li` = NULL ,
                     fk_id_sa = 6 
                 WHERE `destino` = $v "
                    . " and fk_id_pessoa = $k";
            $query = pdoSis::getInstance()->query($sql);
        }
    }

    public function protocolo($id_at) {
        $sql = "SELECT "
                . " p.id_pessoa, p.ra, p.n_pessoa, p.responsavel "
                . " , p.dt_nasc, p.responsavel, p.cpf_respons "
                . " ,at.*"
                . " ,a.* "
                . " ,i.n_inst "
                . " ,sa.n_sa"
                . " FROM `transporte_alu_adaptado` a "
                . " join pessoa p on p.id_pessoa = a.id_pessoa "
                . " join transporte_alu_adaptado_time at on at.fk_id_pessoa = p.id_pessoa "
                . " join instancia i on i.id_inst = a.fk_id_inst "
                . " join transporte_status_aluno sa on sa.id_sa = at.fk_id_sa "
                . "WHERE `id_at` = $id_at ";
        $query = pdoSis::getInstance()->query($sql);
        $aluno = $query->fetch(PDO::FETCH_ASSOC);

        $log = sqlErp::get(['transporte_adaptado_log', 'transporte_status_aluno'], '*', ['fk_id_pessoa' => $aluno['id_pessoa'], 'destino' => $aluno['destino']]);
        $aluno['log'] = $log;

        return $aluno;
    }

    public function consultaaluno($id_pl = null) {

        $sqlAux = "";

        if (!empty($id_pl)) {
            $sqlAux .= " AND pl.at_pl IN($id_pl) ";
        } else {
            $sqlAux .= " AND pl.at_pl = 1";
        }

        $sql = "SELECT p.n_pessoa, p.ra, p.ra_dig, gt.distancia_esc,"
                . " gt.dt_solicita, t.n_turma, n_sa FROM pessoa p"
                . " JOIN transporte_aluno gt ON gt.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turma_aluno_situacao tas ON tas.id_tas = ta.fk_id_tas AND tas.id_tas = 0 "
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " JOIN transporte_status_aluno st ON st.id_sa = gt.fk_id_sa"
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " WHERE gt.fk_id_inst = " . toolErp::id_inst() . " AND gt.fk_id_sa = 0"
                . " AND dt_solicita IS NOT NULL"
                . $sqlAux
                . " ORDER BY p.n_pessoa";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function cabecalhorelatorio($linha) {

        $sql = "SELECT tl.id_li, tl.n_li, tl.motorista, tl.tel1, tl.periodo, tv.capacidade,"
                . " tv.acessibilidade, t.n_em, tv.n_tv, tl.monitor FROM transporte_linha tl"
                . " JOIN transporte_veiculo tv ON tv.id_tv = tl.fk_id_tv"
                . " JOIN transporte_empresa t ON t.id_em = tv.fk_id_em"
                . " WHERE tl.id_li = '" . $linha . "'";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function gravalinhaesc() {
        $idinst = $_POST[1]['fk_id_inst'];
        $idlinha = $_POST['id_li'];

        $sql = "INSERT INTO transporte_inst_linha (fk_id_inst, fk_id_li) VALUES ($idinst, $idlinha)";
        $query = pdoSis::getInstance()->query($sql);
    }

    public function fotoEnd($id_pessoa) {
        if (file_exists(ABSPATH . "/pub/fotos/" . $id_pessoa . ".jpg")) {
            return HOME_URI . '/pub/fotos/' . $id_pessoa . '.jpg?' . uniqid();
        } elseif (file_exists(ABSPATH . "/pub/fotos/" . $id_pessoa . ".png")) {
            return HOME_URI . '/pub/fotos/' . $id_pessoa . '.png?' . uniqid();
        } else {
            return HOME_URI . '/includes/images/anonimo.jpg';
        }
    }

    public function pegaalunoscarteirinha($aluno, $id_pl = null) {
        $alunos = implode(",", $aluno);

        $sql = "SELECT p.id_pessoa, p.n_pessoa, p.dt_nasc, p.ra, p.ra_dig, p.ra_uf,"
                . " i.n_inst, t.n_turma, tl.n_li, tl.motorista, tl.monitor,"
                . " tl.tel1,tl.tel2 FROM transporte_aluno gt"
                . " JOIN pessoa p ON p.id_pessoa = gt.fk_id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = gt.fk_id_turma"
                . " JOIN instancia i ON i.id_inst = gt.fk_id_inst"
                . " JOIN endereco e ON e.fk_id_pessoa = p.id_pessoa"
                . " JOIN transporte_linha tl ON tl.id_li = gt.fk_id_li"
                . " WHERE p.id_pessoa IN($alunos)";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function nomeultimo($nome) {

        $temp = explode(" ", $nome);
        $nomeNovo = $temp[0] . " " . $temp[count($temp) - 1];

        return$nomeNovo;
    }

    public function getSetup() {
        $this->_setup = sqlErp::get('transporte_setup', '*', NULL, 'fetch');
        if (empty($this->_setup)) {
            $this->_setup = [
                'troca' => 0,
                'aberto' => 0,
                'distancia' => '',
                'distancia_min' => '',
                'relatorio' => 0,
                'destino' => '',
                'abrir_falta' => 0,
                'aberto_dia_semana' => '',
            ];
        }
        return $this->_setup;
    }

    public function getStatusAluno()
    {
        $status_a = sqlErp::get('transporte_status_aluno', '*', ['at_sa' => 1]);
        if (empty($status_a)) {
            return [];
        }

        $st = [];
        foreach ($status_a as $v){
            $st[$v['id_sa']]=$v['n_sa'];
        }
        asort($st);
        return $st;
    }

    public function sistemaAberto()
    {
        if (user::session('id_nivel') == 10) {
            return '';
        }

        if (empty($this->_setup['aberto'])) {
            return "Esta operação está fechada";
        }

        if (empty($this->_setup['aberto_dia_semana'])) {
            return "Esta operação está fechada";
        }

        $e = explode(',', $this->_setup['aberto_dia_semana']);
        if (!in_array(date('w')+1, $e)){
            return "Esta operação está fechada";
        }
        return '';
    }

    public function abrirFalta()
    {
        if (empty($this->_setup['abrir_falta']) && user::session('id_nivel') != 10) {
            return "Lançamento Fechado";
        }
        return '';
    }

    public function dashGet($id_inst) {

        $dt_inicio = $dt_fim = $dt_solicita = dataErp::dataPorSemana(date('W'))['data_inicio'];

        // inclusos
        $arrayI = transporteErp::alunos($id_inst, 'D', $dt_inicio);
        $tInc = count($arrayI);

        // exclusos
        $arrayE = transporteErp::alunos($id_inst, 'E', null, $dt_fim);
        $tExc = count($arrayE);

        // agd deferimto
        $arrayA = transporteErp::alunos($id_inst, 'A', null, null, $dt_solicita);
        $tADef = count($arrayA);

        $atendimentos = array();
        $atendimentos['total'] = $tInc + $tExc + $tADef;
        $atendimentos['i'] = $tInc;
        $atendimentos['e'] = $tExc;
        $atendimentos['a'] = $tADef;

        return $atendimentos;
    }

    public function dashGetFaltas($id_turma=null) {
        if (!empty($id_turma)) {
           $WHERE = " WHERE fk_id_turma_AEE IN ($id_turma) AND presenca = 0  "; 
        }else{
            $WHERE = "WHERE YEAR(dt_atend) = YEAR(NOW()) AND presenca = 0  ";
        }
        $sql = "SELECT count(id_atend) as numAtend, month(dt_atend) as mes"
                . " FROM apd_pdi pdi  "
                . " JOIN apd_pdi_atend ate ON ate.fk_id_pdi = pdi.id_pdi "
                . " WHERE YEAR(dt_atend) = YEAR(NOW()) AND presenca = 0 "
                . " GROUP BY month(dt_atend) "
                . " ORDER BY month(dt_atend) ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $faltas = 0;
        $meses = "";
        foreach ($array as $v) {
            $mes = "'".data::mes($v['mes'])."'";
            $meses = (!empty($meses) ? $meses.", ".$mes : $mes);
            $faltas = (!empty($faltas) ? $faltas.", ".$v['numAtend'] : $v['numAtend']);
        }
        $total['faltas'] = $faltas;
        $total['meses'] = $meses;
        return $total;
    }
}
