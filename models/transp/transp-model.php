<?php

class transpModel extends MainModel {

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
        $this->db = new DB();
        // Configura o controlador
        $this->controller = $controller;

        // Configura os parâmetros
        // $this->parametros = $this->controller->parametros;
        // Configura os dados do usuário

        $this->userdata = $this->controller->userdata;

        $idAluno = filter_input(INPUT_POST, 'idAluno', FILTER_SANITIZE_NUMBER_INT);
        if (!empty($idAluno)) {
            ob_end_clean();
            $dados = $this->dadosAluno($idAluno);
            echo json_encode($dados);
            exit();
        }
        $this->_setup = sql::get('transp_setup', '*', NULL, 'fetch');
        if (!empty($_POST['apagarIntsLinha'])) {
            $sql = "DELETE FROM `transp_inst_linha` "
                    . " WHERE `fk_id_li` = '" . @$_POST['id_li'] . "' "
                    . " and fk_id_inst = '" . @$_POST['fk_id_inst'] . "'";
            $query = pdoSis::getInstance()->query($sql);
        } elseif (DB::sqlKeyVerif('cadAluno')) {
            $this->cadAluno();
        } elseif (DB::sqlKeyVerif('exclAluno')) {
            $this->exclAluno();
        } elseif (!empty($_POST['mudaStatus'])) {
            $this->mudaStatus();
        } elseif (!empty($_POST['mapaReflech'])) {
            $this->mapaReflech();
        } elseif (DB::sqlKeyVerif('trocaLinha')) {
            $this->trocaLinha();
        } elseif (DB::sqlKeyVerif('mudaStatusMult')) {
            $this->mudaStatusMult();
        } elseif (DB::sqlKeyVerif('lancaFalta')) {
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

        if (!empty($_POST['gravalinhaescola'])) {
            $this->gravalinhaesc();
        }
        if (!empty($_POST['cadastraveiculo'])) {
            $this->gravaveiculos();
        }
    }

    public function cadAdaptado() {

        $trans_alu_adaptado = $_POST[1];
        $destino = $_POST[2];
        $sql = " UPDATE `transp_alu_adaptado_time` SET `Ativo` = NULL WHERE fk_id_pessoa = " . $trans_alu_adaptado['id_pessoa'];
        $query = pdoSis::getInstance()->query($sql);
        if (!empty($destino['destino'])) {
            foreach ($destino['destino'] as $v => $id_at) {
                $ins = $destino['desttime'][$v];
                $ins['fk_id_pessoa'] = $trans_alu_adaptado['id_pessoa'];
                $ins['Ativo'] = 1;
                $ins['fk_id_sa'] = intval(@$destino['status'][$v]);
                $ins['id_at'] = $id_at;
                $ins['destino'] = $v;
                $status = sql::get('transp_alu_adaptado_time', 'fk_id_sa', ['id_at' => $id_at], 'fetch')['fk_id_sa'];
                if ((@$status != intval(@$destino['status'][$v])) || (empty(@$status) && @$status != '0')) {
                    unset($insLog);
                    $insLog['fk_id_sa'] = intval(@$destino['status'][$v]);
                    $insLog['fk_id_pessoa'] = $trans_alu_adaptado['id_pessoa'];
                    $insLog['destino'] = $v;
                    $insLog['fk_id_pessoa_func'] = tool::id_pessoa();

                    $this->db->ireplace('transp_adaptado_log', $insLog, 1);
                }
                $this->db->ireplace('transp_alu_adaptado_time', $ins, 1);
            }
        }

        $this->db->ireplace('transp_alu_adaptado', $trans_alu_adaptado);
    }

    public function lancaFalta() {

        $alu = @$_POST['alu'];
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $id_fj = filter_input(INPUT_POST, 'id_fj', FILTER_SANITIZE_NUMBER_INT);
        $justificativa = filter_input(INPUT_POST, 'justificativa', FILTER_UNSAFE_RAW);
        $id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_NUMBER_INT);
        $dia = filter_input(INPUT_POST, 'dia', FILTER_SANITIZE_NUMBER_INT);
        $data = date("Y") . '-' . $mes . '-' . $dia;
        $id_pessoa = tool::id_pessoa();
        $sql = "DELETE FROM `transp_falta` "
                . " WHERE `dt_falta` = '$data' "
                . " AND `fk_id_alu` in ("
                . "SELECT DISTINCT `id_alu` FROM `gt_aluno` "
                . " WHERE `fk_id_li` = $id_li "
                . ") ";
        $query = pdoSis::getInstance()->query($sql);
        if (!empty($alu)) {
            foreach ($alu as $k => $v) {
                if (empty($v)) {
                    $v = 'NULL';
                }
                $sql = "replace INTO `transp_falta` "
                        . "(`id_falta`, `fk_id_alu`, `dt_falta`, `fk_id_pessoa`) VALUES ("
                        . "$v, '$k', '$data', $id_pessoa); ";
                $query = pdoSis::getInstance()->query($sql);
            }
        }
        if (empty($id_fj)) {
            $id_fj = 'NULL';
        }
        $sql = "REPLACE INTO `transp_falta_justifica` "
                . " (`id_fj`, `justificativa`, `dt_falta`, `dt_just`, `fk_id_inst`, `fk_id_pessoa`, fk_id_li) "
                . " VALUES ($id_fj, '$justificativa', '$data', '" . date("Y-m-d") . "', $id_inst, " . tool::id_pessoa() . ", $id_li);";
        $query = pdoSis::getInstance()->query($sql);
        tool::alert("Lançado");
        log::logSet("Lançou faltas linha id  $id_li");
    }

    public function mudaStatusMult() {

        $id_sa = @$_POST['id_sa'];
        $id_li = @$_POST['id_li'];
        $transpSec = @$_POST['transpSec'];
        if (!empty($id_li)) {
            foreach ($id_li as $k => $v) {
                if ($v != 'X') {
                    $sql = "UPDATE `gt_aluno` SET `fk_id_li` = '$v' WHERE `gt_aluno`.`id_alu` = $k;";
                    $query = pdoSis::getInstance()->query($sql);
                    log::logSet("Mudou a id_alu $k para a linha $v");
                }
            }
        }

        if (!empty($transpSec)) {
            foreach ($transpSec as $kt => $t) {
                $sql = "UPDATE `gt_aluno` SET `transp_secundario` = '$t' WHERE `gt_aluno`.`id_alu` = $kt;";
                $query = pdoSis::getInstance()->query($sql);
                log::logSet("Atribiu a id_alu $kt transporte secundário $t");
            }
        }
        if (!empty($id_sa)) {
            $data = date("Y-m-d");
            foreach ($id_sa as $k => $v) {
                if ($v != 'X') {
                    if ($v == 1) {
                        $sql = "UPDATE `gt_aluno` SET `fk_id_sa` = '$v', dt_inicio = '$data' WHERE `gt_aluno`.`id_alu` = $k;";
                    } elseif ($v == 6) {
                        $sql = "UPDATE `gt_aluno` SET `fk_id_sa` = '$v', dt_fim = '$data' WHERE `gt_aluno`.`id_alu` = $k;";
                    } else {
                        $sql = "UPDATE `gt_aluno` SET `fk_id_sa` = '$v' WHERE `gt_aluno`.`id_alu` = $k;";
                    }
                    $query = pdoSis::getInstance()->query($sql);
                    log::logSet("mudou o status do id_alu $k para $v");
                }
            }
            tool::alert('Concluido');
        }
    }

    public function trocaLinha() {
        $alunos = @$_POST['aluno'];
        $id_li_destino = filter_input(INPUT_POST, 'id_li_destino', FILTER_SANITIZE_NUMBER_INT);
        $capacidade = transporte::linhaGet($id_li_destino)['capacidade'];
        $ocupacao = transporte::ocupacao($id_li_destino);
        if ($capacidade >= ($ocupacao + (count($alunos)))) {
            foreach ($alunos as $k => $v) {
                $sql = "UPDATE `gt_aluno` SET `fk_id_li` = '$id_li_destino' WHERE `gt_aluno`.`id_alu` = $v;";
                $query = pdoSis::getInstance()->query($sql);
                log::logSet("Mudou a id_alu $v para a linha $id_li_destino");
            }
            tool::alert('Finalizado');
        } else {
            tool::alert('A capacidade do ônibus é ' . $capacidade . ' alunos');
        }
    }

    public function mapaReflech() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $e = new escola($id_inst);
        $sql = "SELECT latitude, longitude FROM `endereco` WHERE `fk_id_pessoa` = $id_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $v = $query->fetch(PDO::FETCH_ASSOC);
        $maps = tool::distancia((trim($v['latitude']) . ',' . trim($v['longitude'])), (trim($e->_latitude) . ',' . trim($e->_longitude)));
        $distancia = $maps[0];
        $tempo = $maps[1];
        $sql = "UPDATE `gt_aluno` SET `distancia_esc` = '$distancia', `tempo_esc` = '$tempo', fk_id_inst = '$id_inst' WHERE fk_id_pessoa = " . $id_pessoa;
        $query = pdoSis::getInstance()->query($sql);
        tool::alert('Atualizado');
    }

    public function mudaStatus() {
        $data = date("Y-m-d");
        $fk_id_sa = filter_input(INPUT_POST, 'fk_id_sa', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $fk_id_sa = $fk_id_sa == 1 ? '0' : '1';
        if ($fk_id_sa == 1) {
            $sql = "UPDATE `gt_aluno` SET `fk_id_sa` = '$fk_id_sa', dt_inicio = '$data' WHERE `gt_aluno`.`fk_id_pessoa` = $id_pessoa and fk_id_sa <= 1;";
        } elseif ($fk_id_sa == 0) {
            $sql = "UPDATE `gt_aluno` SET `fk_id_sa` = '$fk_id_sa', dt_inicio = NULL WHERE `gt_aluno`.`fk_id_pessoa` = $id_pessoa and fk_id_sa <= 1;";
        }
        $query = pdoSis::getInstance()->query($sql);
        log::logSet("mudou o status do id_alu $id_pessoa para $fk_id_sa");
    }

    public function exclAluno() {
        $alunos = @$_POST['aluno'];
        if (is_array($alunos)) {
            foreach ($alunos as $k => $v) {
                $sql = "UPDATE `gt_aluno` SET `fk_id_li` = NULL, fk_id_sa = 0, dt_solicita = NULL WHERE `gt_aluno`.`id_alu` = $v ";
                $query = pdoSis::getInstance()->query($sql);
            }
        }
        log::logSet("Excluiu id_alu $v ");
        tool::alert('Concluído');
    }

    public function cadAluno() {
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);
        $alunos = @$_POST['aluno'];
        $capacidade = transporte::linhaGet($id_li)['capacidade'];
        $ocupacao = transporte::ocupacao($id_li);
        if (is_array($alunos)) {
            $data = date("Y-m-d");
            foreach ($alunos as $k => $v) {
                unset($ins);
                $ins['fk_id_li'] = $id_li;
                $ins['id_alu'] = $v;
                $ins['fk_id_inst'] = $id_inst;
                $ins['dt_solicita'] = $data;
                $ins['fk_id_pessoa'] = $k;
                $ins['fk_id_sa'] = 0;
                $this->db->ireplace('gt_aluno', $ins, 1);
                log::logSet("Incluiu fk_id_pessoa $k na linha $id_li");
            }
        }
        tool::alert('Concluído');
    }

    public function listaVeiculos($situacao = NULL) {
        if (empty($situacao)) {
            $situacao = 1;
        }
        $sql = "SELECT * FROM transp_veiculo v "
                . " JOIN transp_tipo_veiculo t on t.id_tiv=v.fk_id_tiv"
                . " JOIN transp_status_veiculo s on s.id_sv = v.fk_id_sv"
                . " WHERE s.n_sv LIKE 'Ativo'";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

// $vei = sql::get('transp_veiculo', '*', ['situacao' => $situacao]);
        $token = DB::sqlKey('transp_veiculo', 'delete');
        foreach ($array as $k => $v) {
            $v['modal'] = 1;
            $array[$k]['del'] = form::submit('Apagar', $token, ['1[id_tv]' => $v['id_tv']]);
            $array[$k]['edit'] = form::submit('Editar', NULL, $v);
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

    public function listaLinhas() {
        $sql = "SELECT * FROM `transp_linha` l "
                . "left join transp_veiculo v on v.id_tv = l.fk_id_tv ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $token = DB::sqlKey('transp_linha', 'delete');
        foreach ($array as $k => $v) {
            $v['modal'] = 1;
            $array[$k]['del'] = form::submit('Apagar', $token, ['1[id_li]' => $v['id_li']]);
            $array[$k]['edit'] = form::submit('Editar', NULL, $v);
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
            //'||1' => 'del',
            '||2' => 'edit'
        ];

        return $form;
    }

    public function AlunoTurma($id_turma, $id_inst) {
        $e = new escola($id_inst);
        //alunos da c classe
        $alunoTurma = alunos::listar($id_turma);

        //coluna id_pessoa
        $idPessoas = array_column($alunoTurma, 'id_pessoa');
        //alunos inseridos na tabela gt_aluno
        $sql = "select * from gt_aluno "
                . " where fk_id_pessoa in (" . implode(',', $idPessoas) . ") "
                . " and fk_id_sa not in (2, 6) "
                . " and fk_id_inst = $id_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $alu = $query->fetchAll(PDO::FETCH_ASSOC);
        //coluna fk_id_pessoa
        $idPessoasAlu = array_column($alu, 'fk_id_pessoa');
        //Alunos que falta na tabela gt_aluno
        $falta = array_diff($idPessoas, $idPessoasAlu);

        if (!empty($falta)) {
            //coordenadas dos alunos que faltam no gt_)aluno
            $sql = "SELECT "
                    . " `fk_id_pessoa`, `latitude`, `longitude` "
                    . " FROM `endereco` "
                    . " WHERE `fk_id_pessoa` in (" . implode(',', $falta) . ") "
                    . " AND `fk_id_tp` = 1";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($array as $v) {
                $sql = "SELECT * FROM `gt_aluno` WHERE `fk_id_pessoa` = " . $v['fk_id_pessoa'] . " AND `fk_id_inst` = $id_inst AND `fk_id_sa` IN (0,1)";
                $query = pdoSis::getInstance()->query($sql);
                $existe = $query->fetch(PDO::FETCH_ASSOC);
                $maps = tool::distancia((trim($v['latitude']) . ',' . trim($v['longitude'])), (trim($e->_latitude) . ',' . trim($e->_longitude)));
                $distancia = $maps[0];
                $tempo = $maps[1];
                if (empty($existe['id_alu'])) {
                    $sql = "INSERT INTO `gt_aluno` (`id_alu`, `fk_id_pessoa`, fk_id_inst, `distancia_esc`, `tempo_esc`) VALUES (NULL, '" . $v['fk_id_pessoa'] . "', '$id_inst',  '$distancia', '$tempo');";
                } else {
                    $sql = "REPLACE INTO `gt_aluno` (`id_alu`, `fk_id_pessoa`, fk_id_inst, `distancia_esc`, `tempo_esc`) VALUES (" . $existe['id_alu'] . ", '" . $v['fk_id_pessoa'] . "', '$id_inst',  '$distancia', '$tempo');";
                }
                $query = pdoSis::getInstance()->query($sql);
            }
        }

        $sql = "SELECT p.id_pessoa, p.n_pessoa, p.ra, a.id_alu, ta.chamada, a.distancia_esc, e.latitude, e.longitude, t.fk_id_ciclo ,a.fk_id_pessoa as teste, t.fk_id_inst"
                . " FROM `ge_turma_aluno` ta "
                . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " Join endereco e on e.fk_id_pessoa = p.id_pessoa "
                . " left join gt_aluno a on a.fk_id_pessoa = p.id_pessoa "
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " WHERE ci.fk_id_curso in (1,3) "
                . " AND ta.fk_id_turma = $id_turma "
                . " AND a.fk_id_li is NULL "
                . " AND a.fk_id_sa not in (2, 6) "
                . " and ta.situacao = 'Frequente' "
                . " ORDER BY `chamada` ASC";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function alunoRelat($id_inst = NULL, $status = NULL) {

        if ($status == 'T' && empty($id_inst)) {
            tool::alert("Pesquisa muito abrangente");
        } else {
            $alunos = transporte::alunos($id_inst, $status);
            $linhaCount = $this->countLinha();

            foreach ($alunos as $k => $a) {

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
                    $alunos[$k]['transpSec'] = '
                        <select name="transpSec[' . $a['id_alu'] . ']">
                             <option value="">Não</option>
                             <option ' . (!empty($a['transp_secundario']) ? 'selected' : '') . ' value="1">Sim</option>
                        </select>
                     ';
                }
                $alunos[$k]['distancia_esc'] = '<div id="' . $a['id_pessoa'] . '" style="color: ' . $btn . '">' . $a['distancia_esc'] . '</div>';
                $alunos[$k]['periodo'] = $a['periodo'] == 'T' ? 'Tarde' : ($a['periodo'] == 'M' ? 'Manhã' : ($a['periodo'] == 'I' || $a['periodo'] == 'G' ? 'Integral' : 'Noite'));
                $alunos[$k]['n_sa'] = $this->selectStatus($a['id_alu'], $a['id_sa']);
                if ($status == 1) {
                    $alunos[$k]['n_li'] = $this->selectLinhaInst($a['id_alu'], $linhaCount['esc'][$a['id_inst']], $a['id_li']);
                } else {
                    $alunos[$k]['n_li'] = $a['n_tv'] . '-v' . $a['viagem'] . '-' . (explode(' ', $a['motorista'])[0]) . '-' . $a['periodo'];
                }
                $alunos[$k]['ac'] = '<button class="btn btn-info" type="button" onclick=" $(\'#myModal\').modal(\'show\');acesso(\'' . $a['id_pessoa'] . '\',\'' . $a['id_inst'] . '\')"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span></button>';
            }
            $form['array'] = $alunos;

            if (empty($id_inst)) {
                if ($status == 'SE' || $status == 'SE') {
                    $form['fields'] = [
                        'Escola' => 'n_inst',
                        'Aluno' => 'n_pessoa',
                        'RA' => 'ra',
                        'Turma' => 'n_turma',
                        'Período' => 'periodo',
                        'Distâcia' => 'distancia_esc',
                        'Linha' => 'n_li',
                        'Motivo' => 'n_mot',
                        'DT. Def.' => 'dt_inicio',
                        'Solicitação' => 'dt_solicita',
                        'Transp. Sec.' => 'transpSec',
                        'Status' => 'n_sa',
                        '||1' => 'ac'
                    ];
                } else {
                    $form['fields'] = [
                        'Escola' => 'n_inst',
                        'Aluno' => 'n_pessoa',
                        'RA' => 'ra',
                        'Turma' => 'n_turma',
                        'Período' => 'periodo',
                        'Distâcia' => 'distancia_esc',
                        'Linha' => 'n_li',
                        'DT. Def.' => 'dt_inicio',
                        'Solicitação' => 'dt_solicita',
                        'Transp. Sec.' => 'transpSec',
                        'Status' => 'n_sa',
                        '||1' => 'ac'
                    ];
                }
            } else {
                if ($status == 'SE' || $status == 'SE') {
                    $form['fields'] = [
                        'Aluno' => 'n_pessoa',
                        'RA' => 'ra',
                        'Turma' => 'n_turma',
                        'Período' => 'periodo',
                        'Distâcia' => 'distancia_esc',
                        'Linha' => 'n_li',
                        'Motivo' => 'n_mot',
                        'DT. Def.' => 'dt_inicio',
                        'Solicitação' => 'dt_solicita',
                        'Transp. Sec.' => 'transpSec',
                        'Status' => 'n_sa',
                        '||1' => 'ac'
                    ];
                } else {
                    $form['fields'] = [
                        'Aluno' => 'n_pessoa',
                        'RA' => 'ra',
                        'Turma' => 'n_turma',
                        'Período' => 'periodo',
                        'Distâcia' => 'distancia_esc',
                        'Linha' => 'n_li',
                        'DT. Def.' => 'dt_inicio',
                        'Solicitação' => 'dt_solicita',
                        'Transp. Sec.' => 'transpSec',
                        'Status' => 'n_sa',
                        '||1' => 'ac'
                    ];
                }
            }
            ?>
            <div class="fieldBorder2">
                <form  method="POST">
                    <div style="text-align: right">
                        <button class="btn btn-success" type="submit">
                            Concluir
                        </button>
                    </div>
                    <br />
                    <?php
                    echo DB::hiddenKey('mudaStatusMult');
                    echo form::hidden(['id_inst' => $id_inst, 'status' => $status, 'pesq' => 1]);
                    tool::relatSimples($form);
                    ?>
                </form>
            </div>
            <?php
        }
    }

    //ATENÇÃO!!!   Valor default está como 'X'  "$id_sa == $k ?NULL:$k"
    public function selectStatus($id_alu, $id_sa) {
        $n_sa = sql::idNome('transp_status_aluno');
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

        $form = '<select name="id_sa[' . $id_alu . ']" ">';
        foreach ($n_sa as $k => $v) {
            $form .= '<option ' . ($id_sa == $k ? 'selected' : '') . ' value="' . ($id_sa == $k ? 'X' : $k) . '">' . $v . '</option>';
        }
        $form .= '</select>';

        return $form;
    }

    public function countLinha() {
        $sql = "SELECT "
                . " fk_id_li, count(fk_id_li) as ct "
                . " FROM `gt_aluno`  a"
                . " GROUP BY fk_id_li";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $cont[$v['fk_id_li']] = $v['ct'];
        }


        $sql = "SELECT li.id_li, li.motorista, li.viagem, li.periodo, "
                . " tv.capacidade, tv.n_tv "
                . " FROM transp_linha li"
                . " join transp_veiculo tv on tv.id_tv = li.fk_id_tv ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $linha[$v['id_li']] = $v['n_tv'] . '-v' . $v['viagem'] . '-' . (explode(' ', $v['motorista'])[0]) . '-' . $v['periodo'] . ' (' . ($v['capacidade'] - intval(@$cont[$v['id_li']])) . '/' . $v['capacidade'] . ')';
        }

        $sql = "SELECT * FROM `transp_inst_linha`";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $linha['esc'][$v['fk_id_inst']][$v['fk_id_li']] = $linha[$v['fk_id_li']];
        }

        return $linha;
    }

    //ATENÇÃO!!!   Valor default está como 'X'  "$id_li == $k ?NULL:$k"
    public function selectLinhaInst($id_alu, $countLinha, $id_li) {

        $form = '<select name="id_li[' . $id_alu . ']" ">';
        foreach ($countLinha as $k => $v) {
            $form .= '<option ' . ($id_li == $k ? 'selected' : '') . ' value="' . ($id_li == $k ? 'X' : $k) . '">' . $v . '</option>';
        }
        $form .= '</select>';

        return $form;
    }

    public function listaBranca() {
        $sql = "SELECT "
                . " p.id_pessoa, p.n_pessoa, p.ra, i.n_inst, t.n_turma "
                . " FROM `transp_lita_branca` b "
                . " join pessoa p on p.id_pessoa = b.id_pessoa "
                . " left join ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa "
                . " left join instancia i on i.id_inst = ta.fk_id_inst "
                . " left join ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " left join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " WHERE ci.fk_id_curso in (1,3) "
                . " AND "
                . " ( "
                . "pl.at_pl = 1 "
                . " or "
                . " pl.at_pl is NULL "
                . " ) "
                . " and ta.situacao like 'Frequente'"
                . " order by n_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $token = DB::sqlKey('transp_lita_branca', 'delete');
        foreach ($array as $k => $v) {
            $array[$k]['exc'] = form::submit('Excluir', $token, ['1[id_pessoa]' => $v['id_pessoa']]);
        }
        $form['array'] = $array;
        $form['fields'] = [
            'RA' => 'ra',
            'Aluno' => 'n_pessoa',
            'Escola' => 'n_inst',
            'Turma' => 'n_turma',
            '||' => 'exc'
        ];
        tool::relatSimples($form);
    }

    public function alunoPesq($id_inst = null, $nome = null, $aDeferimento = NULL) {
        if (empty($id_inst) && empty($nome)) {
            tool::alert('Preencha um dos Campos');
        } else {
            $alunos = transporte::alunoPesq($id_inst, $nome, $aDeferimento);
        }

        return $alunos;
    }

###########################################################################

    /**
     * 
     * @param type $id_alus array com os "id_alu"
     */
    public static function faltaDia($id_alus = NULL, $data) {
        if (!empty($id_alus)) {
            $sql = "SELECT * FROM `transp_falta` "
                    . " WHERE `fk_id_alu` IN (" . (implode(",", $id_alus)) . ") "
                    . " and dt_falta = '$data' ";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($array)) {
                foreach ($array as $v) {
                    $faltas[$v['fk_id_alu']] = $v['id_falta'];
                }

                return $faltas;
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

##################### Mario ###############

    public function pegalinharelVeiculo($idTv) {

        $sql = "SELECT tl.id_li, tl.n_li FROM transp_linha tl"
                . " JOIN transp_inst_linha il ON il.fk_id_li = tl.id_li"
                . " WHERE tl.fk_id_tv = '" . $idTv . "'";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function pegalinharel($idinst) {

        $sql = "SELECT tl.id_li, tl.n_li FROM transp_linha tl"
                . " JOIN transp_inst_linha il ON il.fk_id_li = tl.id_li"
                . " WHERE il.fk_id_inst = '" . $idinst . "'";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function pegacabecalhorel($idlinha) {

        $sql = "SELECT l.id_li, l.n_li, e.n_em, v.acessibilidade, v.capacidade, v.placa,"
                . " tv.n_tiv, i.n_inst, v.n_tv, l.periodo, l.motorista,"
                . " l.tel1, l.monitor FROM transp_linha l"
                . " JOIN transp_veiculo v ON v.id_tv = l.fk_id_tv"
                . " JOIN transp_empresa e ON e.id_em = v.fk_id_em"
                . " JOIN transp_tipo_veiculo tv ON tv.id_tiv = v.fk_id_tiv"
                . " JOIN transp_inst_linha il ON il.fk_id_li = l.id_li"
                . " JOIN instancia i ON i.id_inst = il.fk_id_inst"
                . " WHERE l.id_li IN ($idlinha)";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function pegaalunos($idlinha) {

        $sql = "SELECT p.id_pessoa, p.n_pessoa, i.n_inst, p.ra, p.ra_dig,"
                . " t.codigo, p.dt_nasc, t.fk_id_ciclo, al.id_alu,"
                . " al.dt_inicio, al.dt_fim FROM gt_aluno al"
                . " JOIN pessoa p ON p.id_pessoa = al.fk_id_pessoa"
                . " JOIN instancia i ON i.id_inst = al.fk_id_inst"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND t.fk_id_inst = ta.fk_id_inst "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " JOIN transp_inst_linha il ON il.fk_id_inst = al.fk_id_inst AND il.fk_id_li = al.fk_id_li"
                . " JOIN transp_linha tl on tl.id_li = il.fk_id_li AND tl.id_li = al.fk_id_li"
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

        $sql = "SELECT DISTINCT fk_id_inst FROM gt_aluno";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $dados['inclusao'][$v['fk_id_inst']] = 0;
            $dados['exclusao'][$v['fk_id_inst']] = 0;
            $dados['espera'][$v['fk_id_inst']] = 0;
        }

        $m = date('Y') . '-' . $mes . '%';
        // Inicio
        $sql = "SELECT i.id_inst, COUNT(gt.dt_inicio) AS inicio FROM gt_aluno gt"
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
        $sql = "SELECT i.id_inst, COUNT(gt.dt_fim) AS fim FROM gt_aluno gt"
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
        $sql = "SELECT i.id_inst, COUNT(gt.dt_solicita) AS solicita FROM gt_aluno gt"
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

        $sql = "SELECT DISTINCT i.id_inst, i.n_inst FROM gt_aluno gt"
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

        $sql = "SELECT DISTINCT fk_id_inst FROM gt_aluno";

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
                . " COUNT(gt.fk_id_inst) as total FROM gt_aluno gt"
                . " JOIN transp_inst_linha il ON il.fk_id_inst = gt.fk_id_inst"
                . " JOIN transp_linha tl ON tl.id_li = il.fk_id_li AND tl.id_li = gt.fk_id_li"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = gt.fk_id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl"
                . " GROUP BY gt.fk_id_inst, tl.periodo, t.fk_id_pl, gt.fk_id_sa, pl.at_pl"
                . " HAVING pl.at_pl = 1  AND gt.fk_id_sa = 1";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $dados[$v['periodo']][$v['fk_id_inst']] = $v['total'];
            @$dados[$v['periodo'] . 't'] = $dados[$v['periodo'] . 't'] + $v['total'];
            $dados[$v['fk_id_inst']] = $dados[$v['fk_id_inst']] + $v['total'];
        }

        $sql = "SELECT il.fk_id_inst, SUM(tv.capacidade) as total FROM transp_inst_linha il"
                . " JOIN transp_linha tl ON tl.id_li = il.fk_id_li"
                . " JOIN transp_veiculo tv ON tv.id_tv = tl.fk_id_tv"
                . " GROUP BY il.fk_id_inst, tv.fk_id_sv"
                . " HAVING tv.fk_id_sv = 1 ORDER BY il.fk_id_inst";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $dados['Vagas'][$v['fk_id_inst']] = $v['total'];
            $dados['Vagast'] = $dados['Vagast'] + $v['total'];
        }

        return $dados;
    }

    public function movimentacaoaluno($mes, $idinst) {
        $m = date('Y') . '-' . $mes . '%';

        $sql = "SELECT p.n_pessoa, p.ra, p.ra_dig, gt.fk_id_sa, t.codigo,"
                . " e.logradouro, e.num, e.bairro, gt.distancia_esc,"
                . " e.logradouro_gdae, e.num_gdae,"
                . " tr.n_li, tr.periodo, gt.dt_inicio AS Data FROM pessoa p"
                . " JOIN gt_aluno gt ON gt.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa AND ta.fk_id_pessoa = gt.fk_id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND t.fk_id_inst = gt.fk_id_inst"
                . " JOIN transp_inst_linha il ON il.fk_id_inst = gt.fk_id_inst AND il.fk_id_li = gt.fk_id_li"
                . " JOIN transp_linha tr ON tr.id_li = il.fk_id_li"
                . " LEFT JOIN endereco e ON e.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " WHERE ci.fk_id_curso in (1,3) "
                . " AND gt.fk_id_inst = '" . $idinst . "' AND t.fk_id_pl IN (27,28)"
                . " AND gt.dt_inicio LIKE '" . $m . "'"
                . " ORDER BY p.n_pessoa";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $k => $v) {
            $dados['Inclusão'][$k] = $v;
            //  $dados[$k]['situacao'] = 'Inclusão';
        }

        $sql = "SELECT p.n_pessoa, p.ra, p.ra_dig, gt.fk_id_sa, t.codigo,"
                . " e.logradouro, e.num, e.bairro, gt.distancia_esc,"
                . " e.logradouro_gdae, e.num_gdae,"
                . " tr.n_li, tr.periodo, gt.dt_fim AS Data FROM pessoa p"
                . " JOIN gt_aluno gt ON gt.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa AND ta.fk_id_pessoa = gt.fk_id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND t.fk_id_inst = gt.fk_id_inst"
                . " JOIN transp_inst_linha il ON il.fk_id_inst = gt.fk_id_inst AND il.fk_id_li = gt.fk_id_li"
                . " JOIN transp_linha tr ON tr.id_li = il.fk_id_li"
                . " LEFT JOIN endereco e ON e.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " WHERE ci.fk_id_curso in(1,3) "
                . " AND gt.fk_id_inst = '" . $idinst . "' AND t.fk_id_pl IN (87,89)"
                . " AND gt.fk_id_sa = 6"
                . " AND gt.dt_fim LIKE '" . $m . "'"
                . " ORDER BY p.n_pessoa";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $kk => $vv) {
            $dados['Exclusão'][$kk] = $vv;
            // $dados[$kk]['situacao'] = 'Exclusão';
        }

        return @$dados;
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
            $sql = "SELECT al.id_alu FROM  gt_aluno al"
                    . " JOIN transp_falta tf ON tf.fk_id_alu = al.id_alu"
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
        $sql = "SELECT * FROM  gt_aluno al"
                . " JOIN transp_falta tf ON tf.fk_id_alu = al.id_alu"
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
        $sql = "SELECT * FROM `transp_falta_justifica` WHERE `dt_just` LIKE '%-$mes-%' AND `fk_id_li` = $linha";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $dias[] = substr($v['dt_falta'], 8);
        }

        return $dias;
    }

    public function pegafalta($mes, $linha) {

        $d = date('Y') . '-' . $mes . '%';

        $sql = "SELECT * FROM gt_aluno WHERE fk_id_li = '" . $linha . "'";

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

        $sql = "SELECT al.fk_id_pessoa, f.dt_falta  FROM gt_aluno al"
                . "  JOIN transp_falta f ON f.fk_id_alu = al.id_alu"
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
        $sql = "SELECT a.fk_id_pessoa, a.fk_id_inst FROM gt_aluno a "
                . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = a.fk_id_pessoa "
                . " AND ta.fk_id_inst = a.fk_id_inst "
                . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " WHERE a.fk_id_sa = 1 "
                . " and ta.situacao NOT LIKE 'Frequente' "
                . " and pl.at_pl = 1";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $sql = "SELECT a.fk_id_pessoa, a.fk_id_inst FROM gt_aluno a "
                    . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = a.fk_id_pessoa "
                    . " AND ta.fk_id_inst = a.fk_id_inst "
                    . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
                    . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                    . " WHERE a.fk_id_sa = 1 "
                    . " and ta.situacao LIKE 'Frequente' "
                    . " AND a.fk_id_pessoa = " . $v['fk_id_pessoa']
                    . " and a.fk_id_inst = " . $v['fk_id_inst']
                    . " and pl.at_pl = 1";
            $query = pdoSis::getInstance()->query($sql);
            $frequente = $query->fetch(PDO::FETCH_ASSOC);
            if (empty($frequente['fk_id_pessoa'])) {
                echo '<br />' . $sql = "UPDATE `gt_aluno` "
                . " SET `fk_id_sa` = '6', "
                . " `dt_solicita_fim` = '$data', "
                . " `fk_id_mot` = '1' "
                . " WHERE `gt_aluno`.`fk_id_pessoa` = " . $v['fk_id_pessoa']
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
                . " FROM `transp_alu_adaptado` a "
                . " join pessoa p on p.id_pessoa = a.id_pessoa "
                . " join transp_alu_adaptado_time at on at.fk_id_pessoa = p.id_pessoa "
                . "WHERE `fk_id_inst` = $id_inst "
                . " and at.ativo = 1 "
                . "order by p.n_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $k => $v) {
            $old = @$al[$v['id_pessoa']]['pdf'];
            $al[$v['id_pessoa']] = $v;
            @$al[$v['id_pessoa']]['pdf'] = $old . '<div style="float: left; padding-left: 20px">' . form::submit($destino[$v['destino']]['nome'], NULL, $v, HOME_URI . '/transp/protocolo', 1) . '</div>';
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

            tool::relatSimples1($form);
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
                . " FROM transp_alu_adaptado a "
                . " JOIN pessoa p on p.id_pessoa = a.id_pessoa "
                . " JOIN instancia i on i.id_inst = a.fk_id_inst "
                . " JOIN transp_alu_adaptado_time d on d.fk_id_pessoa = a.id_pessoa "
                . " JOIN transp_status_aluno s on s.id_sa = d.fk_id_sa "
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
                . " left join transp_alu_adaptado a on a.id_pessoa = p.id_pessoa "
                . " where p.id_pessoa = '$idAluno' ";
        $query = pdoSis::getInstance()->query($sql);
        $aluno = $query->fetch(PDO::FETCH_ASSOC);
        if ($aluno) {
            $aluno['idade'] = data::calculoIdade($aluno['dt_nasc']) . ' Anos';
        } else {
            $aluno = ['erro' => 1];
        }

        $sql = "SELECT * FROM `transp_alu_adaptado_time` "
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
            $sql = "UPDATE `transp_alu_adaptado_time`
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
            $sql = "UPDATE `transp_alu_adaptado_time`
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
                . " FROM `transp_alu_adaptado` a "
                . " join pessoa p on p.id_pessoa = a.id_pessoa "
                . " join transp_alu_adaptado_time at on at.fk_id_pessoa = p.id_pessoa "
                . " join instancia i on i.id_inst = a.fk_id_inst "
                . " join transp_status_aluno sa on sa.id_sa = at.fk_id_sa "
                . "WHERE `id_at` = $id_at ";
        $query = pdoSis::getInstance()->query($sql);
        $aluno = $query->fetch(PDO::FETCH_ASSOC);

        $log = sql::get(['transp_adaptado_log', 'transp_status_aluno'], '*', ['fk_id_pessoa' => $aluno['id_pessoa'], 'destino' => $aluno['destino']]);
        $aluno['log'] = $log;

        return $aluno;
    }

    public function consultaaluno() {

        $sql = "SELECT p.n_pessoa, p.ra, p.ra_dig, gt.distancia_esc,"
                . " gt.dt_solicita, t.n_turma, n_sa FROM pessoa p"
                . " JOIN gt_aluno gt ON gt.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " JOIN transp_status_aluno st ON st.id_sa = gt.fk_id_sa"
                . " WHERE gt.fk_id_inst = '" . tool::id_inst() . "' AND gt.fk_id_sa = '0'"
                . " AND t.fk_id_pl = '87' AND ta.situacao = 'Frequente'"
                . " AND dt_solicita IS NOT NULL"
                . " ORDER BY p.n_pessoa";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function cabecalhorelatorio($linha) {

        $sql = "SELECT tl.id_li, tl.n_li, tl.motorista, tl.tel1, tl.periodo, tv.capacidade,"
                . " tv.acessibilidade, t.n_em, tv.n_tv, tl.monitor FROM transp_linha tl"
                . " JOIN transp_veiculo tv ON tv.id_tv = tl.fk_id_tv"
                . " JOIN transp_empresa t ON t.id_em = tv.fk_id_em"
                . " WHERE tl.id_li = '" . $linha . "'";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function gravalinhaesc() {
        $idinst = $_POST[1]['fk_id_inst'];
        $idlinha = $_POST['id_li'];

        $sql = "INSERT INTO transp_inst_linha (fk_id_inst, fk_id_li) VALUES ($idinst, $idlinha)";
        $query = pdoSis::getInstance()->query($sql);
    }

    public function gravaveiculos() {

        $ntv = $_POST[1]['n_tv'];
        $wplaca = $_POST[1]['placa'];
        $wcapacidade = $_POST[1]['capacidade'];
        $wacessibilidade = $_POST[1]['acessibilidade'];
        $idsv = $_POST[1]['fk_id_sv'];
        $idtiv = $_POST[1]['fk_id_tiv'];
        $idem = $_POST[1]['fk_id_em'];
        $wcadeirante = $_POST[1]['cadeirante'];

        $sql = "INSERT INTO transp_veiculo (n_tv, placa, capacidade, acessibilidade, fk_id_sv, fk_id_tiv, fk_id_em, cadeirante)"
                . " VALUES ('" . $ntv . "', '" . $wplaca . "',  $wcapacidade, $wacessibilidade, $idsv, $idtiv, $idem, $wcadeirante)";

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

    public function pegaalunoscarteirinha($aluno) {
        $alunos = implode(",", $aluno);
        $sql = "SELECT p.id_pessoa, p.n_pessoa, p.dt_nasc, p.ra, p.ra_dig, p.ra_uf,"
                . " i.n_inst, t.n_turma, tl.n_li, tl.motorista, tl.monitor,"
                . " tl.tel1,tl.tel2 FROM gt_aluno gt"
                . " JOIN pessoa p ON p.id_pessoa = gt.fk_id_pessoa"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = gt.fk_id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " JOIN instancia i ON i.id_inst = gt.fk_id_inst"
                . " JOIN endereco e ON e.fk_id_pessoa = p.id_pessoa"
                . " JOIN transp_linha tl ON tl.id_li = gt.fk_id_li"
                . " WHERE t.fk_id_pl = 87 AND ta.fk_id_tas = 0"
                . " AND gt.fk_id_sa = 1 AND p.id_pessoa IN($alunos)";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function nomeultimo($nome) {

        $temp = explode(" ", $nome);
        $nomeNovo = $temp[0] . " " . $temp[count($temp) - 1];

        return $nomeNovo;
    }

    public function pegaferiados($mes, $ano) {

        $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);

        $sql = "SELECT * FROM ge_feriados"
                . " WHERE mes = '" . $mes . "'"
                . " AND ano = '" . $ano . "'"
                . " AND tipo_escola = 'EF'";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        if ($array) {
            foreach ($array as $v) {

                $sit[$v['dia']] = $v['tipo_feriado'];
            }
            return $sit;
        }
    }

###################  Mario ########################

    public function gerarelalinha() {

        $sql = "SELECT i.n_inst AS 'Nome Escola', te.n_em AS Empresa, tl.n_li AS Linha,"
                . " tl.viagem AS Viagem, tl.motorista AS Motorista,"
                . " tl.monitor AS Monitor FROM transp_linha tl"
                . " JOIN transp_veiculo tv ON tv.id_tv = tl.fk_id_tv"
                . " JOIN transp_empresa te ON te.id_em = tv.fk_id_em"
                . " JOIN transp_inst_linha ti ON ti.fk_id_li = tl.id_li"
                . " JOIN instancia i ON i.id_inst = ti.fk_id_inst"
                . " WHERE tl.ativo = 1";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        return $array;
    }

##############################
}
