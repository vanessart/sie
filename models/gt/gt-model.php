<?php

class gtModel extends MainModel {

    public $_gt_gdae;
    public $_gdaeSet;
    public $gdae;
    public $_turma;
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
        $this->_gt_gdae = new gt_gdae;
        $this->gdae = new gdae();
        // Configura o controlador
        $this->controller = $controller;

        // Configura os parâmetros
        //$this->parametros = $this->controller->parametros;
        // Configura os dados do usuário
        $this->userdata = $this->controller->userdata;

        $setup = sql::get('ge_setup', '*', ['id_set' => 1], 'fetch');
        $this->_gdaeSet = $setup['gdae'];

        if (!empty($_POST['importarClasses'])) {
            //$this->importarClasse(tool::id_inst(), $_POST['id_pl']);
        } elseif (!empty($_POST['sincronizarTodosAlunos'])) {
            $raId = gt_gdaeSet::raNormatiza($_POST['ra']);
            $this->sincronizarTodosAlunos($raId, @$_POST['id_turma']);
        } elseif (!empty($_POST['sincronizarAlunos'])) {
            $this->sincronizarAlunos($_POST['id_turma']);
        } elseif (!empty($_POST['sincronizarPessoa'])) {
            $this->sincronizarPessoa($_POST['id_pessoa']);
        } elseif (!empty($_POST['atualizarClasseGdae'])) {
            $this->atualizarClasseGdae(@$_POST['id_turma']);
        } elseif (DB::sqlKeyVerif('cancelOnibus')) {
            $id_alu = filter_input(INPUT_POST, 'id_alu', FILTER_SANITIZE_NUMBER_INT);
            $sql = "UPDATE `gt_aluno` SET `fk_id_li` = NULL WHERE `gt_aluno`.`id_alu` = $id_alu ";
            $query = pdoSis::getInstance()->query($sql);
        } elseif (!empty($_POST['upFoto'])) {
            $this->upload();
        } elseif (!empty($_POST['rotateImg'])) {
            $this->rotateImg();
        } elseif (!empty($_POST['cropImg'])) {
            $this->cropImg();
        }

        if (DB::sqlKey('novaPessoa') && !empty($_POST['salvarPessoa'])) {

            pessoa::replace($_POST[1]);
            @$id_pessoa = sql::get('pessoa', 'id_pessoa', ['cpf' => $_POST[1]['cpf']], 'fetch')['id_pessoa'];
            @$funExist = sql::get('ge_funcionario', 'fk_id_pessoa', ['fk_id_pessoa' => $id_pessoa, 'fk_id_inst' => tool::id_inst(@$_POST['id_inst'])], 'fetch')['fk_id_pessoa'];
            if (!empty($id_pessoa) && empty($funExist)) {
                $insert['fk_id_pessoa'] = $id_pessoa;
                $insert['rm'] = uniqid();
                $insert['funcao'] = empty($_POST['prof']) ? 'outro' : 'Professor';
                $insert['situacao'] = 'Ativo';
                $insert['fk_id_inst'] = tool::id_inst(@$_POST['id_inst']);
                $insert['email'] = @$_POST[1]['email'];
                $insert['tel'] = @$_POST['tel1'];
                $insert['cel'] = @$_POST['tel2'];
                $this->db->ireplace('ge_funcionario', $insert, 1);
            } else {
                tool::alert("Erro! Verifique se o funcionário já existe ou o CPF pertença a outra pessoa");
            }
            /**
              $user['fk_id_pessoa'] = $id_pessoa;
              $user['user_password'] = '123';
              $user['user_session_id'] = '123';
              $user['ativo'] = '1';
              $this->db->ireplace('users', $user);
             * 
             */
        }

        if (!empty($_POST['grupoInsert'])) {
            $insert['fk_id_pessoa'] = @$_POST['id_pessoa'];
            $insert['fk_id_inst'] = @$_POST['id_inst'];
            $insert['fk_id_gr'] = @$_POST['fk_id_gr'];
            $this->db->ireplace('acesso_pessoa', $insert);
        }

        if (!empty($_POST['excluirFunc'])) {
            $sql = "DELETE FROM `ge_funcionario` WHERE fk_id_pessoa = " . @$_POST['id_pessoa'];
            $query = pdoSis::getInstance()->query($sql);
            $sql = "DELETE FROM `acesso_pessoa` WHERE fk_id_pessoa = " . @$_POST['id_pessoa']
                    . " AND fk_id_inst = " . @$_POST['fk_id_inst'];
            $query = pdoSis::getInstance()->query($sql);
        }

        if (empty($_REQUEST['periodoLetivo'])) {
            $_POST['periodoLetivo'] = @$_POST[1]['fk_id_pl'];
        }
        if (DB::sqlKeyVerif('clonarClasse')) {
            $id_turma = $this->db->ireplace('ge_turmas', $_POST[1], 1);
            $sql = "SELECT ta.fk_id_pessoa, ta.origem_escola FROM ge_turma_aluno ta "
                    . " join pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                    . " WHERE `fk_id_turma` = " . @$_POST['id_turma']
                    . " AND `situacao` in ('Frequente','Finalizado') "
                    . " order by p.n_pessoa ";
            $query = $this->db->query($sql);
            $array = $query->fetchAll();
            $periodo_letivo = sql::get('ge_periodo_letivo', 'n_pl', ['id_pl' => $_POST[1]['fk_id_pl']], 'fetch')['n_pl'];
            $c = 1;
            $sg_ciclo_sg_curso = sql::get(['ge_ciclos', 'ge_cursos'], 'sg_curso,sg_ciclo, n_ciclo', ['id_ciclo' => $_POST[1]['fk_id_ciclo']], 'fetch');
            $codigo = $sg_ciclo_sg_curso['sg_curso'] . $_POST[1]['periodo'] . $sg_ciclo_sg_curso['sg_ciclo'] . $_POST[1]['letra'];

            foreach ($array as $vv) {

                $v['fk_id_pessoa'] = $vv['fk_id_pessoa'];
                $v['origem_escola'] = $vv['origem_escola'];
                $v['id_turma_aluno'] = NULL;
                $v['situacao'] = 'Frequente';
                $v['codigo_classe'] = $codigo;
                $v['fk_id_turma'] = $id_turma;
                $v['periodo_letivo'] = $periodo_letivo;
                $v['fk_id_inst'] = tool::id_inst();
                $v['chamada'] = $c++;
                $v['dt_matricula'] = date("Y-m-d");
                $this->db->ireplace('ge_turma_aluno', $v, 1);
            }
        }

        if (!empty($_POST['escluirUser'])) {
            $sql = "DELETE FROM `acesso_pessoa` WHERE `fk_id_pessoa` = " . @$_POST['id_pessoa'];
            $query = $this->db->query($sql);
            $array = $query->fetchAll();
        }
    }

    public function cropImg() {

        $x = filter_input(INPUT_POST, 'x', FILTER_SANITIZE_NUMBER_INT);
        $y = filter_input(INPUT_POST, 'y', FILTER_SANITIZE_NUMBER_INT);
        $w = filter_input(INPUT_POST, 'w', FILTER_SANITIZE_NUMBER_INT);
        $h = filter_input(INPUT_POST, 'h', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);

        $im = imagecreatefromjpeg(ABSPATH . '/pub/fotos/' . $id_pessoa . '.jpg');
        $im2 = imagecrop($im, ['x' => $x, 'y' => $y, 'width' => $w, 'height' => $h]);
        if ($im2 !== FALSE) {
            imagejpeg($im2, ABSPATH . '/pub/fotos/' . $id_pessoa . '.jpg');
            imagedestroy($im2);
        }
        imagedestroy($im);
    }

    public function rotateImg() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $filename = ABSPATH . '/pub/fotos/' . $id_pessoa . '.jpg';
        $rotang = 90; // Rotation angle
        $source = imagecreatefromjpeg($filename) or die('Erro ao Abrir o Arquivo ' . $filename);

        $rotation = imagerotate($source, $rotang, 0);
        imagejpeg($rotation, ABSPATH . '/pub/fotos/' . $id_pessoa . '.jpg', 100);
        imagedestroy($rotation);
    }

    public function upload() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $file = ABSPATH . '/pub/fotos/';
        $up = new upload($file, $id_pessoa, 21000000000000000000000, ['jpg']);
        $up->_resize = false;
        $end = $up->up();
        if ($end) {
            //diminuir a imagem
            $im = @imagecreatefromjpeg($file . $id_pessoa . '.jpg');
            $x = imagesx($im);
            $y = imagesy($im);
            $y = $y / ($x / 320);

            $resizeObj = new resizeImg($file . $id_pessoa . '.jpg');
            $resizeObj->resizeImage(320, $y, 'auto');
            $resizeObj->saveImage($file . $id_pessoa . '.jpg', 100);
        }
    }

    public function importarClasse($id_inst, $id_pl) {

        $sql = "update ge_turmas set prodesp = 0 "
                . " where fk_id_inst = " . $id_inst
                . " and fk_id_pl = $id_pl";
        $query = pdoSis::getInstance()->query($sql);

        $periodo = sql::get('ge_periodo_letivo', '*', ['id_pl' => $id_pl], 'fetch');

        $turno = gt_gdaeSet::turno(NULL, 'sg_turno');

        $idTurma = gt_gdaeSet::turmasCicloLetra($id_inst, $id_pl);

        $escola = new escola();

        $c = $this->gdae->ConsultaClasseAlunoPorEscola($periodo['ano'], $escola->_cie, $periodo['semestre'], NULL, NULL, NULL, NULL);

        if (is_string($c)) {
            tool::alert($c);
        } else {
            if (!empty($c['Mensagens'])) {
                $cc = (array) $c['Mensagens']->MsgConsultaClasseAlunoPorEscola;
                if (empty($cc[0])) {
                    $cc1[] = $cc;
                } else {
                    $cc1 = $cc;
                }
                foreach ($cc1 as $v) {
                    $v = (array) $v;
                    $sql = " select * from ge_ciclos ci "
                            . " join ge_cursos c on c.id_curso = ci.fk_id_curso "
                            . " where c.TipoEnsino = " . $v['outTipoEnsino']
                            . " and SerieAno = " . $v['outSerieAno'];
                    $query = pdoSis::getInstance()->query($sql);
                    $ciclo = $query->fetch(PDO::FETCH_ASSOC);

                    $id_turma = @$idTurma[$ciclo['id_ciclo']][strtoupper(trim($v['outTurma']))];
                    if (!empty($id_turma)) {
                        $insert['id_turma'] = $id_turma;
                    } else {
                        $insert['id_turma'] = NULL;
                    }

                    $insert['prodesp'] = $v['outNrClasse'];
                    $insert['n_turma'] = $ciclo['n_ciclo'] . ' ' . strtoupper(trim($v['outTurma']));
                    $insert['fk_id_inst'] = $id_inst;
                    $insert['fk_id_ciclo'] = $ciclo['id_ciclo'];
                    $insert['fk_id_grade'] = $ciclo['fk_id_grade'];
                    $insert['codigo'] = $ciclo['sg_curso'] . $turno[trim($v['outTurno'])] . $ciclo['sg_ciclo'] . strtoupper(trim($v['outTurma']));
                    $insert['periodo'] = $turno[trim($v['outTurno'])];
                    $insert['periodo_letivo'] = date("Y");
                    $insert['fk_id_pl'] = $id_pl;
                    $insert['letra'] = strtoupper(trim($v['outTurma']));

                    $this->db->ireplace('ge_turmas', $insert, 1);
                }
                $sql = "SET foreign_key_checks = 0; ";
                $query = pdoSis::getInstance()->query($sql);
                $sql = "DELETE FROM `ge_turmas` "
                        . " WHERE `fk_id_inst` = " . $id_inst
                        . " and fk_id_pl = " . $id_pl
                        . " and prodesp = 0 ";
                //$query = pdoSis::getInstance()->query($sql);
                tool::alert("Feito, verifique se a importação foi completa");
            } else {
                tool::alert("Erro no Prodesp");
            }
        }
    }

    public function listTurma($id_inst, $periodo = NULL) {


        $t = gtTurmas::turmas($id_inst, $periodo);

        $sqlkey = null;
//        $sqlkey = DB::sqlKey('ge_turmas', 'delete');
        foreach ($t as $k => $v) {
            $vazia = sql::get('ge_turma_aluno', 'id_turma_aluno', ['fk_id_turma' => $v['id_turma']], 'fetch');
            if (empty($vazia)) {
                $t[$k]['del'] = formulario::submit('Excluir', $sqlkey, ['1[id_turma]' => $v['id_turma'], 'periodoLetivo' => @$_REQUEST['periodoLetivo']]);
            } else {
                $t[$k]['del'] = '<img src="' . HOME_URI . '/views/_images/aluno.png"/>';
            }
            $t[$k]['periodoLetivo'] = $periodo;
            $t[$k]['edit'] = formulario::submit('Editar', NULL, ['id_turma' => $v['id_turma'], 'modal' => 1, 'periodoLetivo' => $periodo]);
            $t[$k]['ac'] = formulario::submit('Acessar', NULL, ['turma' => $v['id_turma'], 'periodoLetivo' => $periodo], HOME_URI . '/gestao/manutencaoclasse');
            $v['clonar'] = 1;
            $t[$k]['clone'] = formulario::submit('Clonar', NULL, $v);
        }
        if (!empty($t)) {
            $form['array'] = $t;
            $form['fields'] = [
                'ID' => 'id_turma',
                'Curso' => 'n_curso',
                'Código' => 'codigo',
                'Turma' => 'n_turma',
                'Prédio' => 'n_predio',
                'Sala' => 'n_sala',
                // 'Status' => 'n_st',
                '||1' => 'del',
                //'||4' => 'clone',
                '||2' => 'edit',
                '||3' => 'ac'
            ];

            tool::relatSimples($form);
        } else {
            ?>
            <div class="alert alert-warning" style="text-align: center; font-size: 18px">
                Não há Classes neste período
            </div>
            <?php
        }
    }

    public function listTurmaGdae($id_inst, $id_pl = NULL) {


        $t = gtTurmas::turmas($id_inst, $id_pl);

        foreach ($t as $k => $v) {
            $vazia = sql::get('ge_turma_aluno', 'id_turma_aluno', ['fk_id_turma' => $v['id_turma']], 'fetch');
            if (!empty($vazia)) {
                $t[$k]['vazio'] = '<img src="' . HOME_URI . '/views/_images/aluno.png"/>';
            }
            $t[$k]['id_pl'] = $id_pl;
            $t[$k]['ac'] = formulario::submit('Acessar', NULL, ['id_turma' => $v['id_turma'], 'id_pl' => $id_pl], HOME_URI . '/gt/gerirclasse');
            $t[$k]['edit'] = formulario::submit('Editar', NULL, ['id_turma' => $v['id_turma'], 'modal' => 1, 'id_pl' => $id_pl]);
        }
        if (!empty($t)) {
            $form['array'] = $t;
            $form['fields'] = [
                'ID' => 'id_turma',
                'Prodesp' => 'prodesp',
                'Curso' => 'n_curso',
                'Código' => 'codigo',
                'Turma' => 'n_turma',
                '||1' => 'vazio',
                '||2' => 'edit',
                '||3' => 'ac'
            ];

            tool::relatSimples($form);
        } else {
            ?>
            <div class="alert alert-warning" style="text-align: center; font-size: 18px">
                Não há Classes neste período
            </div>
            <?php
        }
    }

    public function turma($id_turma) {
        $this->_turma = $turma = sql::get('ge_turmas', '*', ['id_turma' => $id_turma], 'fetch');

        return $this->_turma;
    }

    public function relatAlunos($id_pl) {
        $sql = "SELECT "
                . " ta.chamada, p.n_pessoa,ta.situacao, ta.id_turma_aluno, p.id_pessoa, p.sexo, p.mae, p.dt_nasc, p.dt_gdae, t.dt_gdae as tgdae, p.ra, p.ra_dig, p.ra_uf "
                . " FROM ge_turma_aluno ta "
                . " join ge_turmas t on t.id_turma= ta.fk_id_turma "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " WHERE fk_id_turma = " . $this->_turma['id_turma']
                . " order by chamada ";
        $query = pdoSis::getInstance()->query($sql);
        $alunos = $query->fetchAll(PDO::FETCH_ASSOC);

        if (empty($alunos)) {
            $sql = "select ta.chamada, p.n_pessoa, ta.id_turma_aluno, p.id_pessoa, p.sexo, p.mae, p.dt_nasc, p.dt_gdae, t.dt_gdae as tgdae, ta.situacao,"
                    . " ra, ra_dig, ra_uf "
                    . " from ge_turma_aluno ta "
                    . " join instancia i on i.id_inst = ta.fk_id_inst "
                    . " join pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                    . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
                    . " WHERE t.fk_id_ciclo = '" . $this->_turma['fk_id_ciclo'] . "' "
                    . " and t.fk_id_inst = " . $this->_turma['fk_id_inst']
                    . " and t.fk_id_pl  = '" . $this->_turma['fk_id_pl'] . "' "
                    . " and t.letra = '" . $this->_turma['letra'] . "' "
                    . "  order by chamada ";
            $query = pdoSis::getInstance()->query($sql);
            $alunos = $query->fetchAll(PDO::FETCH_ASSOC);
        }

        foreach ($alunos as $k => $v) {
            if (empty($v['ra']) || !isset($v['ra_dig'])) {
                $erro = 1;
            }
            $v['gdae'] = 1;
            $v['id_turma'] = $this->_turma['id_turma'];
            $v['periodoLetivo'] = $id_pl;
            if (empty($v['dt_gdae'])) {
                $alunos[$k]['dt_gdae'] = 'Nunca';
            } else {
                $alunos[$k]['dt_gdae'] = $v['dt_gdae'];
            }
            //$v['sincronizarPessoa'] = 1;
            if ($v['tgdae'] != NULL && $v['tgdae'] != '0000-00-00') {
                $alunos[$k]['at'] = formulario::submit('Atualizar', NULL, $v, NULL, NULL, NULL, 'btn btn-primary');
            } else {
                $erro = 1;
            }
            $alunos[$k]['ac'] = formulario::submit('Acessar', NULL, $v, HOME_URI . '/gt/aluno');
        }


        $form['array'] = $alunos;
        $form['fields'] = [
            'RSE' => 'id_pessoa',
            'Num' => 'chamada',
            'Nome' => 'n_pessoa',
            'D. Nasc.' => 'dt_nasc',
            'RA' => 'ra',
            'RA (dig)' => 'ra_dig',
            'Situação' => 'situacao',
            'Atualização' => 'dt_gdae',
            //'||2' => 'at',
            '||1' => 'ac'
        ];

        tool::relatSimples($form);

        return @$erro;
    }

    public function sincronizarTodosAlunos($raId, $id_turma) {
        $sql = "select dt_matricula, fk_id_pessoa, chamada from ge_turma_aluno "
                . " where fk_id_turma = " . $id_turma;
        $query = pdoSis::getInstance()->query($sql);
        $da = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($da as $a) {
            if (!empty($a['fk_id_pessoa']) && !empty($a['chamada'])) {
                $datMatr[$a['fk_id_pessoa']][$a['chamada']] = $a['dt_matricula'];
            }
        }
        $unicoId = uniqid(date("sh"));
        $turma = sql::get(['ge_turmas', 'ge_ciclos'], '*', ['id_turma' => $id_turma], 'fetch');

        $codigo = $turma['codigo'];
        $fk_id_pl = $turma['fk_id_pl'];
        $prodesp = gt_gdaeSet::turma($turma['prodesp']);
        $idPessoa[0] = 0;
        foreach ($prodesp as $v) {
            $ins['id_turma_aluno'] = NULL;
            $v = (array) $v;

            $id_pessoa = @$raId[$v['numero']];
            $insert['id_pessoa'] = @$id_pessoa;
            $ins['id_turma_aluno'] = NULL;
            $insert['ra'] = gt_gdaeSet::raNormatiza($v['RA']);
            $insert['ra_dig'] = trim($v['digitoRA']);
            $insert['ra_uf'] = $v['UF'];
            $insert['n_pessoa'] = $v['nomeAluno'];
            $id_pessoa = $this->db->ireplace('pessoa', $insert, 1);
            $ins['codigo_classe'] = $codigo;
            $ins['fk_id_turma'] = $id_turma;
            $ins['periodo_letivo'] = $v['outAno'];
            $ins['fk_id_pessoa'] = $id_pessoa;
            $ins['fk_id_inst'] = tool::id_inst();
            $ins['chamada'] = $v['numero'];
            $ins['situacao'] = gt_gdaeSet::situacaoAlunoMigracao($v['status']);
            $ins['gdae'] = $unicoId;
            if (!empty($datMatr[@$id_pessoa][$v['numero']])) {
                $ins['dt_matricula'] = $datMatr[@$id_pessoa][$v['numero']];
            }

            $this->db->ireplace('ge_turma_aluno', $ins, 1);
            if ($ins['situacao'] == 'Frequente') {
                $idPessoa[] = $id_pessoa;
            }
        }
        $sql = "SET foreign_key_checks = 0; ";
        $query = pdoSis::getInstance()->query($sql);
        //mudar status caso o aluno estava matriculado em outra sala
        $sql = " update ge_turma_aluno ta "
                . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " join ge_cursos c on c.id_curso = ci.fk_id_curso "
                . " set situacao = 'Transferido Escola' "
                . " Where fk_id_pessoa in (" . implode(",", $idPessoa) . ") "
                . " and situacao = '" . 'Frequente' . "' "
                . " and c.id_curso = " . $turma['fk_id_curso']
                . " and t.fk_id_pl = " . $turma['fk_id_pl']
                . " and t.id_turma != $id_turma ";
        $query = pdoSis::getInstance()->query($sql);

        $sql = "SET foreign_key_checks = 0; ";
        $query = pdoSis::getInstance()->query($sql);
        $sql = "delete from ge_turma_aluno "
                . " where fk_id_turma = " . $id_turma
                . " and ( gdae not like '$unicoId' "
                . " or gdae is null "
                . ")";
        $query = pdoSis::getInstance()->query($sql);

        $insTurma['dt_gdae'] = date("Y-m-d");
        $insTurma['id_turma'] = $id_turma;
        $this->db->ireplace('ge_turmas', $insTurma, 1);

        //inserir data de matrícula
        $sql = "select dt_matricula, fk_id_pessoa, id_turma_aluno, dt_matricula from ge_turma_aluno "
                . " where id_turma_aluno in ("
                . " select id_turma_aluno from ge_turma_aluno "
                . " where fk_id_turma = " . $id_turma
                . " and gdae = '$unicoId' "
                . ")";
        $query = pdoSis::getInstance()->query($sql);
        $alunosTurma = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($alunosTurma as $v) {
            $this->sincronizarDataMatricula(gt_gdaeSet::raNormatiza($insert['ra']), trim($insert['ra_dig']), $insert['ra_uf'], $turma['prodesp'], $v['id_turma_aluno'], $v['dt_matricula']);
        }
    }

    public function sincronizarDataMatricula($ra, $ra_dig, $ra_uf, $prodesp, $id_turma_aluno, $dt_matricula = null) {
        $a = $this->gdae->ConsultarMatriculaClasseRA(intval($ra), $prodesp, trim($ra_dig), $ra_uf, NULL);
        if (is_string($a)) {
            tool::alert($a);
        } else {
            if (!empty($a['Mensagens'])) {
                $aluno = (array) $a['Mensagens']->ConsultarMatrRAClasse;
            }
        }

        if (!empty($aluno)) {
            $ta['dt_matricula'] = data::converteUS($aluno['outDataInicio']);
            $ta['dt_matricula_fim'] = data::converteUS($aluno['outDataFim']);
            $ta['dt_transferencia'] = data::converteUS($aluno['outDataFim']);
            $ta['id_turma_aluno'] = $id_turma_aluno;

            $query = $this->db->ireplace('ge_turma_aluno', $ta, 1);

            return;
        } else {

            return 1;
        }
    }

    public function sincronizarAlunos($id_turma) {
        $alunos = sql::get('ge_turma_aluno', ' fk_id_pessoa ', ['fk_id_turma' => $id_turma]);
        foreach ($alunos as $v) {
            $this->sincronizarPessoa($v['fk_id_pessoa']);
        }
    }

    public function sincronizarPessoa($id_pessoa = NULL) {

        if (!empty($_POST['veri'])) {
            if (substr(@$_POST['verifica'], 1) == "u sei o que estou fazendo") {
                $continua = 1;
            } else {
                tool::alert("Você não escreveu as palavras mágicas");
            }
        } else {
            $continua = 1;
        }
        if (@$continua == 1) {
            if (empty($id_pessoa)) {
                $id_pessoa = $this->db->ireplace('pessoa', ['nis' => '1']);
                $_POST['id_pessoa'] = $id_pessoa;
            }
            if (!empty($_POST['ra']) && isset($_POST['ra_dig']) && !empty($_POST['ra_uf'])) {
                $pessoa['ra'] = gt_gdaeSet::raNormatiza($_POST['ra']);
                $pessoa['ra_dig'] = $_POST['ra_dig'];
                $pessoa['ra_uf'] = $_POST['ra_uf'];
                if (empty($pessoa['ra_dig']) && $pessoa['ra_dig'] != 0) {
                    $pessoa['ra_dig'] = '';
                }
            } else {
                $sql = "SELECT ra, ra_dig, ra_uf FROM `pessoa` WHERE `id_pessoa` = " . $id_pessoa;
                $query = pdoSis::getInstance()->query($sql);
                $pessoa = $query->fetch(PDO::FETCH_ASSOC);
            }
            if (!empty($pessoa['ra']) && $pessoa['ra_uf']) {
                $endereco = sql::get('endereco', 'logradouro', ['fk_id_pessoa' => $id_pessoa, 'fk_id_tp' => 1], 'fetch');

                $p = $this->gdae->ConsultarFichaAlunoRa($pessoa['ra'], $pessoa['ra_dig'], $pessoa['ra_uf']);
                if (is_string($p)) {
                    tool::alert($p);
                } else {

                    $pg = (array) $p['FichasAluno']->FichaAluno;
                    $ins['ra'] = gt_gdaeSet::raNormatiza($pessoa['ra']);
                    $ins['ra_dig'] = $pessoa['ra_dig'];
                    $ins['ra_uf'] = $pessoa['ra_uf'];
                    $pg['outCertidaoResp'] = (array) $pg['outCertidaoResp'];
                    $ins['cor_pele'] = @$pg['outCorRaca'];

                    if (!empty($pg['outFoneRecado'])) {
                        $ins['tel2'] = $pg['outFoneRecado'];
                        $ins['ddd2'] = @$pg['outDDD'];
                    }
                    if (!empty($pg['outFoneResidencial'])) {
                        $ins['tel3'] = @$pg['outFoneResidencial'];
                        $ins['ddd3'] = @$pg['outDDD'];
                    }
                    if (!empty($pg['outFoneCel'])) {
                        $ins['tel1'] = @$pg['outFoneCel'];
                        $ins['ddd1'] = @$pg['outDDDCel'];
                    }
                    $ins['certidao'] = @$pg['outNumRegNasc'] . ' - ' . @$pg['outCertidaoResp']['outNumLivroReg'] . ' - ' . @$pg['outCertidaoResp']['outFolhaRegNum'];
                    $ins['novacert_cartorio'] = @$pg['outCertidaoResp']['outCertMatr01'];
                    $ins['novacert_acervo'] = @$pg['outCertidaoResp']['outCertMatr02'];
                    $ins['novacert_regcivil'] = @$pg['outCertidaoResp']['outCertMatr03'];
                    $ins['novacert_ano'] = @$pg['outCertidaoResp']['outCertMatr04'];
                    $ins['novacert_tipolivro'] = @$pg['outCertidaoResp']['outCertMatr05'];
                    $ins['novacert_numlivro'] = @$pg['outCertidaoResp']['outCertMatr06'];
                    $ins['novacert_folha'] = @$pg['outCertidaoResp']['outCertMatr07'];
                    $ins['novacert_termo'] = @$pg['outCertidaoResp']['outCertMatr08'];
                    $ins['novacert_controle'] = @$pg['outCertidaoResp']['outCertMatr09'];
                    $ins['dt_nasc'] = data::converteUS(@$pg['outDataNascimento']);
                    $ins['cidade_nasc'] = @$pg['outDescMunNasc'];
                    $ins['nacionalidade'] = @$pg['outNacionalidade'];
                    $ins['n_pessoa'] = @$pg['outNomeAluno'];
                    $ins['mae'] = @$pg['outNomeMae'];
                    $ins['pai'] = @$pg['outNomePai'];
                    $ins['sexo'] = substr($pg['outSexo'], 0, 1);
                    $ins['uf_nasc'] = @$pg['outUFNascimento'];
                    $ins['id_pessoa'] = $id_pessoa;
                    $ins['dt_gdae'] = date("Y-m-d");
                    $ins['cpf'] = @$pg['outCPF'];

                    if (!empty($pg['outNumRG'])) {
                        $ins['rg'] = @$pg['outNumRG'];
                        $ins['rg_dig'] = @$pg['outDigitoRG'];
                        $ins['rg_uf'] = @$pg['outUFRG'];
                        $ins['dt_rg'] = data::converteUS(@$pg['outDataEmissaoRG']);
                    }
                    $this->db->ireplace('pessoa', $ins, 1);

//endereço
                    if (empty($endereco)) {
                        $end['logradouro'] = @$pg['outLogradouro'];
                        $end['num'] = @$pg['outNumero'];
                    }

                    $end['bairro'] = @$pg['outBairro'];
                    $end['cep'] = @$pg['outCEP'];
                    $end['cidade'] = @$pg['outCidade'];
                    $end['logradouro_gdae'] = @$pg['outLogradouro'];
                    $end['uf'] = @$pg['outUF'];
                    $end['latitude'] = @$pg['outLatitude'];
                    $end['longitude'] = @$pg['outLongitude'];
                    $end['num_gdae'] = @$pg['outNumero'];
                    $end['complemento'] = @$pg['outComplemento'];
                    $end['id_end'] = sql::get('endereco', 'id_end', ['fk_id_tp' => 1, 'fk_id_pessoa' => $id_pessoa], 'fetch')['id_end'];
                    $end['fk_id_tp'] = 1;
                    $end['fk_id_pessoa'] = $id_pessoa;
                    $this->db->ireplace('endereco', $end, 1);
                }
            } else {
                tool::alert("Falta dados para a consulta.");
            }
        }
    }

    public static function pessoaGedae($ra, $raDig, $raUF) {


        if (!empty($ra) && isset($raDig) && !empty($raUF)) {
            $gdae = new gdae();
            $p = $gdae->ConsultarFichaAlunoRa($ra, $raDig, $raUF);
            if (is_string($p)) {
                tool::alert($p);
            } else {

                @$pg = (array) $p['FichasAluno']->FichaAluno;

                $ins['certidao'] = @$pg['outNumRegNasc'] . ' - ' . @$pg['outCertidaoResp']->outNumLivroReg . ' - ' . @$pg['outCertidaoResp']->outFolhaRegNum;
                $ins['novacert_cartorio'] = @$pg['outCertidaoResp']->outCertMatr01;
                $ins['novacert_acervo'] = @$pg['outCertidaoResp']->outCertMatr02;
                $ins['novacert_regcivil'] = @$pg['outCertidaoResp']->outCertMatr03;
                $ins['novacert_ano'] = @$pg['outCertidaoResp']->outCertMatr04;
                $ins['novacert_tipolivro'] = @$pg['outCertidaoResp']->outCertMatr05;
                $ins['novacert_numlivro'] = @$pg['outCertidaoResp']->outCertMatr06;
                $ins['novacert_folha'] = @$pg['outCertidaoResp']->outCertMatr07;
                $ins['novacert_termo'] = @$pg['outCertidaoResp']->outCertMatr08;
                $ins['novacert_controle'] = @$pg['outCertidaoResp']->outCertMatr09;
                $ins['n_pessoa'] = @$pg['outNomeAluno'];
                $ins['cor_pele'] = @$pg['outCorRaca'];

                $ins['ra'] = gt_gdaeSet::raNormatiza($ra);
                $ins['ra_dig'] = strtoupper($raDig);
                $ins['ra_uf'] = strtoupper($raUF);
                @$ins['sexo'] = substr($pg['outSexo'], 0, 1);
                $ins['dt_nasc'] = data::converteUS(@$pg['outDataNascimento']);
                if (!empty($pg['outNumRG'])) {
                    $ins['rg'] = @$pg['outNumRG'];
                    $ins['rg_dig'] = @$pg['outDigitoRG'];
                    $ins['rg_uf'] = @$pg['outUFRG'];
                    $ins['dt_rg'] = data::converteUS(@$pg['outDataEmissaoRG']);
                }
                $ins['cpf'] = @$pg['outCPF'];
                $ins['nascionalidade'] = @$pg['outNacionalidade'];
                $ins['uf_nasc'] = @$pg['outUFNascimento'];
                $ins['cidade_nasc'] = @$pg['outDescMunNasc'];
                $ins['mae'] = @$pg['outNomeMae'];
                $ins['pai'] = @$pg['outNomePai'];
                $ins['tel2'] = @$pg['outFoneRecado'];
                $ins['tel3'] = @$pg['outFoneResidencial'];
                $ins['tel1'] = @$pg['outFoneCel'];
                $ins['ddd1'] = @$pg['outDDDCel'];
//endereço
                $ins['cep'] = @$pg['outCEP'];
                $ins['logradouro'] = @$pg['outLogradouro'];
                $end['num_gdae'] = @$pg['outNumero'];
                $ins['num'] = @$pg['outNumero'];
                $ins['bairro'] = @$pg['outBairro'];
                $ins['cidade'] = @$pg['outCidade'];
                $ins['logradouro_gdae'] = @$pg['outLogradouro'];
                $ins['uf'] = @$pg['outUF'];
                $ins['complemento'] = @$pg['outComplemento'];

                tool::alert("Finalize para efetivar as alterações.");

                return $ins;
            }
        } else {
            tool::alert("preencha todos os campos");
        }
    }

    public function atualizarClasseGdae($id_turma) {

        $turmaDados = sql::get('ge_turmas', '*', ['id_turma' => $id_turma], 'fetch');
        $prodesp = $turmaDados['prodesp'];

        $teste = $this->povoarGdae_aluno($prodesp);
        if (!empty($teste)) {
            tool::alert($teste);
            return;
        }
        if (!empty($turmaDados)) {
            $where1['outNumClasse'] = $prodesp;
            $where1['>'] = 'numero';
            $fields = 'RA, digitoRA, UF, outNumClasse, '
                    . 'numero, nomeAluno, outAno, outCodEscola, '
                    . 'outErro, outSerie, outTipoEnsino, '
                    . 'outTurma, outTurno, status, '
                    . 'seriemulti, tipoensinomulti, data';
            $classeProdesp = sql::get('gdae_aluno', $fields, $where1);

            if (is_array($classeProdesp)) {
                //confiro os alunos que já estão na turma
                $siebComRaId = $this->alunosComIdPessoa($classeProdesp);
                unset($numero);
                foreach ($classeProdesp as $v) {
                    $alunoSieb = @$siebComRaId[gt_gdaeSet::raNormatiza($v['RA']) . '-' . trim($v['digitoRA']) . '-' . $v['UF']];

                    $v = (array) $v;
                    //se a pessoa não exiete

                    if (empty($alunoSieb)) {
                        $id_pessoa = $this->incluirAlerarAlunoRA($v);
                    } else {
                        $id_pessoa = $alunoSieb['id_pessoa'];
                    }
                    if (!empty($alunoSieb) or!empty($id_pessoa)) {
                        $this->alunoClasse($v, $turmaDados, $id_pessoa);
                    }

                    $numero[$v['numero']] = $v['numero'];
                }
                if (is_array(@$numero) && !empty($turmaDados['id_turma'])) {
                    $sql = "DELETE FROM `ge_turma_aluno` "
                            . " WHERE `fk_id_turma` =  " . $turmaDados['id_turma']
                            . " AND `chamada` NOT IN (" . implode(',', $numero) . ") ";
                    $query = pdoSis::getInstance()->query($sql);
                }
            }
        }
        $sql = "update ge_turmas set dt_gdae = '" . date("Y-m-d H:i:s") . "' "
                . " where prodesp = " . $turmaDados['prodesp'];
        $query = pdoSis::getInstance()->query($sql);
        tool::alert('Concluído');
    }

    /**
     * 
     * @param type $classeProdesp  array com alunos do gdae
     * @return type alunos do gdae que tb estam na base de dados do sistema
     */
    public function alunosComIdPessoa($classeProdesp) {
        foreach ($classeProdesp as $v) {
            $v = (array) $v;
            $ras[] = gt_gdaeSet::raNormatiza($v['RA']);
            $ras1[] = $v['RA'];
            $rasId[] = trim($v['digitoRA']);
            $rasUF[] = trim($v['UF']);
        }
        //alunos com RA e IDRA
        $sql = "select id_pessoa, n_pessoa, dt_nasc, sexo, ra, ra_dig, ra_uf, pai, mae from pessoa "
                . " where "
                . "("
                . " ra in ('" . implode("','", $ras) . "') "
                . " or ra in ('" . implode("','", $ras1) . "') "
                . ") "
                . " and "
                . "(ra_dig in ('" . implode("','", $rasId) . "') "
                . " or ra_dig is NULL "
                . ") "
                . " and ra_uf in ('" . implode("','", $rasUF) . "')  ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            if (empty($v['ra_dig']) && $v['ra_dig'] != 0) {
                $v['ra_dig'] = 'NULL';
            }
            $siebComRaId[gt_gdaeSet::raNormatiza($v['ra']) . '-' . trim($v['ra_dig']) . '-' . $v['ra_uf']] = $v;
        }

        return @$siebComRaId;
    }

    /**
     * se o RA não for encontrado, busca um aluno e faz update, se não achar o aluno, faz um insert
     */
    public function incluirAlerarAlunoRA($v) {

        $where2 = [
            'outRA' => gt_gdaeSet::raNormatiza($v['RA']),
            'outdigitoRA' => trim($v['digitoRA']),
            'outUF' => $v['UF'],
        ];
//buscar dados na matrícula
        $pessoaGdae = sql::get('gdae_pessoa', ' * ', $where2, 'fetch');

        if (empty($pessoaGdae)) {
//buscar dados no Gdae
            $this->incluirPessoa($v['RA'], $v['digitoRA'], $v['UF'], @$v['outAno']);

            $pessoaGdae = sql::get('gdae_pessoa', ' * ', $where2, 'fetch');
        }

        if (!empty($pessoaGdae)) {
            $sql = "select id_pessoa from pessoa "
                    . "where n_pessoa like '" . str_replace("'", '', $v['nomeAluno']) . "'"
                    . " and dt_nasc = '" . data::converteUS($pessoaGdae['outDataNascimento']) . "' "
                    . " and mae like '" . str_replace("'", '', explode(' ', $pessoaGdae['outNomeMae'])[0]) . "%' "
                    . " and (ra is NULL or ra = '0')";
            $query = pdoSis::getInstance()->query($sql);
            $pessoaSearch = $query->fetch(PDO::FETCH_ASSOC);
            if (!empty($pessoaSearch)) {
//confirmo se não há este ra cadastrano no sistema
                $sql = "SELECT * FROM `pessoa` WHERE `ra` like '" . gt_gdaeSet::raNormatiza($v['RA']) . "'";
                $query = pdoSis::getInstance()->query($sql);
                $array = $query->fetch(PDO::FETCH_ASSOC);
                if (empty($array)) {
                    $sql = "UPDATE `pessoa` "
                            . " SET ra = '" . gt_gdaeSet::raNormatiza($v['RA']) . "', ra_dig = '" . trim($v['digitoRA']) . "', ra_uf = '" . $v['UF'] . "' "
                            . " where id_pessoa = " . $pessoaSearch['id_pessoa'];
                    $query = pdoSis::getInstance()->query($sql);

                    return $pessoaSearch['id_pessoa'];
                } else {
//completa digito e uf na tabela

                    $sql = "UPDATE `pessoa` "
                            . " SET  ra_dig = '" . trim($v['digitoRA']) . "', ra_uf = '" . $v['UF'] . "' "
                            . " where id_pessoa = " . $array['id_pessoa'];
                    $query = pdoSis::getInstance()->query($sql);

                    return $pessoaSearch['id_pessoa'];
                }
            } else {
                $id_pessoa = $this->pessoaInsertTbGdaePessoa($pessoaGdae);

                return $id_pessoa;
            }
        } else {
            $id_pessoa = $this->pessoaInsertTbGdaeAluno($v);

            return $id_pessoa;
        }
    }

    function incluirPessoa($ra, $raDigito, $uf, $AnoLetivo = NULL) {
        $p = $this->_gt_gdae->ConsultarFichaAlunoRa($ra, $raDigito, $uf);

        if (is_array($p) && !empty($p)) {
            $pg = (array) $p['FichasAluno']->FichaAluno;

            @$pg['outDigitoRA'] = trim(@$pg['outDigitoRA']);
            $pg['outRA'] = gt_gdaeSet::raNormatiza($pg['outRA']);
            /**
              if (!empty($pg['outDeficiencias'])) {
              $pg['outDeficiencias'] = serialize($pg['outDeficiencias']);
              }
             * 
             */
            if (!empty($pg['outEndIndicativo'])) {
                $pg['outEndIndicativo'] = serialize($pg['outEndIndicativo']);
            }
            if (!empty($pg['outCertidaoResp'])) {
                $pg['outCertidaoResp'] = serialize($pg['outCertidaoResp']);
            }
            if (!empty($pg['outDeficiencias'])) {
                $pg['outDeficiencias'] = serialize($pg['outDeficiencias']);
            }
            @$pg['outDigitoRA'] = @trim($pg['outDigitoRA']);
            @$pg['ano'] = $AnoLetivo;
            $this->db->replace('gdae_pessoa', $pg);
        }
    }

    public function alunoClasse($aluno, $turmaDados, $id_pessoa) {

        $periodo_letivo = sql::get('ge_periodo_letivo', 'n_pl', ['id_pl' => $turmaDados['fk_id_pl']], 'fetch')['n_pl'];
        $turmaAluno = sql::get('ge_turma_aluno', '*', ['fk_id_turma' => $turmaDados['id_turma'], 'chamada' => @$aluno['numero'], 'fk_id_pessoa' => @$id_pessoa], 'fetch');
        if (@$aluno['status'] != '' && @$turmaAluno['situacao'] == 'Frequente') {
            $dados = [
                'RA' => gt_gdaeSet::raNormatiza(@$aluno['RA']),
                'digitoRA' => trim(@$aluno['digitoRA']),
                'UF' => @$aluno['UF'],
                'outNumClasse' => $turmaDados['prodesp']
            ];

            $this->insertMatricula($dados);
        }
        $where2 = [
            'outRA' => gt_gdaeSet::raNormatiza(@$aluno['RA']),
            'outdigitoRA' => trim(@$aluno['digitoRA']),
            'outUF' => @$aluno['UF'],
        ];

        $matricula = sql::get('gdae_matricula', '*', $where2, 'fetch');

        if (empty($matricula)) {
            $dados = [
                'RA' => gt_gdaeSet::raNormatiza(@$aluno['RA']),
                'digitoRA' => trim(@$aluno['digitoRA']),
                'UF' => @$aluno['UF'],
                'outNumClasse' => $turmaDados['prodesp']
            ];

            $this->insertMatricula($dados);
            $matricula = sql::get('gdae_matricula', '*', $where2, 'fetch');
        }


        if (empty($turmaAluno)) {
            $sql = "SET FOREIGN_KEY_CHECKS = 0;";
            $query = pdoSis::getInstance()->query($sql);
            $sql = "DELETE FROM `ge_turma_aluno` WHERE `fk_id_turma` = '" . $turmaDados['id_turma'] . "' AND `chamada` = " . @$aluno['numero'];
            $query = pdoSis::getInstance()->query($sql);

            $ta['codigo_classe'] = $turmaDados['codigo'];
            $ta['fk_id_turma'] = $turmaDados['id_turma'];
            $ta['periodo_letivo'] = $periodo_letivo;
            $ta['fk_id_pessoa'] = $id_pessoa;
            $ta['fk_id_inst'] = $turmaDados['fk_id_inst'];
            $ta['chamada'] = @$aluno['numero'];
            $ta['situacao'] = gt_gdaeSet::situacaoAlunoMigracao(@$aluno['status']);
            if (empty($matricula['outDataInicio'])) {
                $ta['dt_matricula'] = date("Y-m-d");
            } else {
                $ta['dt_matricula'] = data::converteUS(@$matricula['outDataInicio']);
            }
            if (!empty($matricula['outDataFim'])) {
                $ta['dt_matricula_fim'] = data::converteUS(@$matricula['outDataFim']);
                $ta['dt_transferencia'] = data::converteUS(@$matricula['outDataFim']);
            }
            $ta['turma_status'] = '1';

            $this->db->insert('ge_turma_aluno', $ta);
        } else {
            $ta['id_turma_aluno'] = $turmaAluno['id_turma_aluno'];
            $ta['codigo_classe'] = $turmaDados['codigo'];
            $ta['fk_id_turma'] = $turmaDados['id_turma'];
            $ta['periodo_letivo'] = $periodo_letivo;
            $ta['fk_id_pessoa'] = $id_pessoa;
            $ta['fk_id_inst'] = $turmaDados['fk_id_inst'];
            $ta['chamada'] = @$aluno['numero'];
            $ta['situacao'] = gt_gdaeSet::situacaoAlunoMigracao(@$aluno['status']);
            if (empty($turmaAluno['dt_matricula'])) {
                $ta['dt_matricula'] = data::converteUS(@$matricula['outDataInicio']);
            }
            if (!empty($matricula['outDataFim'])) {
                $ta['dt_matricula_fim'] = data::converteUS(@$matricula['outDataFim']);
                $ta['dt_transferencia'] = data::converteUS(@$matricula['outDataFim']);
            } elseif (@$aluno['status'] != '' && $turmaAluno['situacao'] == 'Frequente') {
                $ta['dt_matricula_fim'] = date("Y-m-d");
                $ta['dt_transferencia'] = date("Y-m-d");
            }
            $ta['turma_status'] = '1';

            $this->db->update('ge_turma_aluno', 'id_turma_aluno', $turmaAluno['id_turma_aluno'], $ta);
        }
    }

    public function insertMatricula($v) {
        @$a = $this->_gt_gdae->ConsultarMatriculaClasseRA($v['RA'], $v['outNumClasse'], $v['digitoRA'], $v['UF'], 'TR');

        if (is_string(@$a) || empty($a)) {
            if (is_array(@$a)) {
                $a = NULL;
            }
            $sql = "REPLACE INTO gdae_matricula_erro (`ra`, `ra_dig`, `ra_uf`, `classe`, `msg`, `data`) VALUES ("
                    . "'" . $v['RA'] . "', "
                    . "'" . $v['digitoRA'] . "', "
                    . "'" . $v['UF'] . "', "
                    . "'" . $v['outNumClasse'] . "',"
                    . " '" . @$a . "',"
                    . " CURRENT_TIMESTAMP);";
            $query = pdoSis::getInstance()->query($sql);
        } else {
            @$aluno = (array) @$a['Mensagens']->ConsultarMatrRAClasse;

            unset($ins);
            $ins['outAnoLetivo'] = @$aluno['outAnoLetivo'];
            $ins['outData'] = data::converteUS(@$aluno['outData']);
            $ins['outDataFim'] = data::converteUS(@$aluno['outDataFim']);
            $ins['outDataInicio'] = data::converte(@$aluno['outDataInicio']);
            $ins['outDataNascimento'] = data::converte(@$aluno['outDataNascimento']);
            $ins['outDescTipoEnsino'] = @$aluno['outDescTipoEnsino'];
            $ins['outDescricaoTurno'] = @$aluno['outDescricaoTurno'];
            $ins['outDigitoRA'] = @@$aluno['outDigitoRA'];
            $ins['outEscola'] = @$aluno['outEscola'];
            $ins['outHoraFinal'] = @$aluno['outHoraFinal'];
            $ins['outHoraInicial'] = @$aluno['outHoraInicial'];
            $ins['outLitTransp'] = @$aluno['outLitTransp'];
            $ins['outNomeAluno'] = @$aluno['outNomeAluno'];
            $ins['outNomeMae'] = @$aluno['outNomeMae'];
            $ins['outNomePai'] = @$aluno['outNomePai'];
            $ins['outNumAluno'] = @$aluno['outNumAluno'];
            $ins['outNumClasse'] = @$aluno['outNumClasse'];
            $ins['outRA'] = @$aluno['outRA'];
            $ins['outSerie'] = @$aluno['outSerie'];
            $ins['outTipoEnsino'] = @$aluno['outTipoEnsino'];
            $ins['outTurma'] = @$aluno['outTurma'];
            $ins['outTurno'] = @$aluno['outTurno'];
            $ins['outUF'] = @$aluno['outUF'];

            $this->db->replace('gdae_matricula', $ins);
        }
    }

    public function pessoaInsertTbGdaeAluno($dados) {
        $ins['n_pessoa'] = $dados['nomeAluno'];
        $ins['ra'] = gt_gdaeSet::raNormatiza($dados['RA']);
        $ins['ra_dig'] = $dados['digitoRA'];
        $ins['ra_uf'] = $dados['UF'];

        $id_pessoa = $this->db->insert('pessoa', $ins);

        return $id_pessoa;
    }

    public function povoarGdae_aluno($prodesp) {

        @$alunos = $this->_gt_gdae->ConsultaFormacaoClasse($prodesp);
        if (is_array(@$alunos)) {

            if (!empty($alunos['Mensagens'])) {
                unset($numero);
                @$alunos = @$alunos['Mensagens']->ConsultaClasse;
                foreach (@$alunos as $a) {
                    $a = (array) $a;
                    $this->db->replace('gdae_aluno', $a);
                    @$numero[$a['numero']] = $a['numero'];
                }
                if (is_array(@$numero) && !empty($prodesp) && !empty($numero)) {
                    $sql = "DELETE FROM `gdae_aluno` "
                            . " WHERE `outNumClasse` =  " . $prodesp
                            . " AND `numero` NOT IN (" . implode(',', $numero) . ") ";
                    $query = pdoSis::getInstance()->query($sql);
                }
                return;
            } else {
                return '<br /> Erro Classe' . $v['outNrClasse'];
            }
        } else {
            return 'Erro Prodesp:' . @$alunos;
        }
    }

    public function pessoaInsertTbGdaePessoa($dados, $id_pessoa = NULL) {

        $ins['n_pessoa'] = $dados['outNomeAluno'];
        $ins['n_social'] = $dados['outNomeSocial'];
        $ins['dt_nasc'] = data::converteUS($dados['outDataNascimento']);
        $ins['ativo'] = '1';
        $ins['cpf'] = $dados['outCPF'];
        $ins['sexo'] = substr($dados['outSexo'], 0, 1);
        $ins['ra'] = gt_gdaeSet::raNormatiza($dados['outRA']);
        $ins['ra_dig'] = trim($dados['outDigitoRA']);
        $ins['ra_uf'] = $dados['outUF'];
        $ins['rg'] = $dados['outNumRG'];
        $ins['rg_dig'] = $dados['outDigitoRG'];
        $ins['rg_uf'] = $dados['outUFRG'];
        $ins['dt_rg'] = data::converteUS(@$dados['outDataEmissaoRG']);
        if (!empty($dados['outCertidaoResp'])) {
            $cert = (array) unserialize($dados['outCertidaoResp']);
            if (!empty($cert['outFolhaRegNum'])) {
                $ins['certidao'] = @$dados['outNumRegNasc'] . ' - ' . @$cert['outNumLivroReg'] . ' - ' . @$cert['outFolhaRegNum'];
            } elseif (!empty($cert['outCertMatr01'])) {
                $ins['novacert_cartorio'] = @$cert['outCertMatr01'];
                $ins['novacert_acervo'] = @$cert['outCertMatr02'];
                $ins['novacert_regcivil'] = @$cert['outCertMatr03'];
                $ins['novacert_ano'] = @$cert['outCertMatr04'];
                $ins['novacert_tipolivro'] = @$cert['outCertMatr05'];
                $ins['novacert_numlivro'] = @$cert['outCertMatr06'];
                $ins['novacert_folha'] = @$cert['outCertMatr07'];
                $ins['novacert_termo'] = @$cert['outCertMatr08'];
                $ins['novacert_controle'] = @$cert['outCertMatr09'];
            }
        }
        $ins['pai'] = $dados['outNomePai'];
        $ins['mae'] = $dados['outNomeMae'];
        $ins['nacionalidade'] = $dados['outNomePaisOrigem'];
        $ins['uf_nasc'] = $dados['outUFNascimento'];
        $ins['cidade_nasc'] = $dados['outDescMunNasc'];
        // $ins['deficiencia'] = empty($dados['outDeficiencias']) ? NULL : '1';
        $ins['cor_pele'] = $dados['outCorRaca'];
        if (!empty($dados['outFoneRecado'])) {
            $ins['tel2'] = $dados['outFoneRecado'];
            $ins['ddd2'] = $dados['outDDD'];
        }
        if (!empty($dados['outFoneResidencial'])) {
            $ins['tel3'] = $dados['outFoneResidencial'];
            $ins['ddd3'] = $dados['outDDD'];
        }
        if (!empty($dados['outFoneCel'])) {
            $ins['tel1'] = $dados['outFoneCel'];
            $ins['ddd1'] = $dados['outDDDCel'];
        }
        $ins['nis'] = $dados['outNumNis'];
        $ins['dt_gdae'] = date("Y-m-d");
        $ins['id_pessoa'] = @$id_pessoa;

        $pessoa = $this->db->ireplace('pessoa', $ins, 1);

        $this->pessoaInsertTbGdaePessoaEnd($dados, @$id_pessoa);

        return $id_pessoa;
    }

    public function pessoaInsertTbGdaePessoaEnd($dados, $id_pessoa = NULL) {

        if (empty($id_pessoa)) {
            $id_pessoa = sql::get('pessoa', 'id_pessoa', ['ra' => gt_gdaeSet::raNormatiza($dados['outRA']), 'ra_dig' => trim($dados['outDigitoRA']), 'ra_uf' => $dados['outUF']], 'fetch')['id_pessoa'];
        }

        $endereco = sql::get('endereco', ' logradouro, id_end ', ['fk_id_pessoa' => $id_pessoa, 'fk_id_tp' => 1], 'fetch');

        if (empty($endereco['logradouro'])) {
            $end['logradouro'] = @$dados['outLogradouro'];
            $end['num'] = @$dados['outNumero'];
        }

        $end['bairro'] = @$dados['outBairro'];
        $end['cep'] = @$dados['outCEP'];
        $end['cidade'] = @$dados['outCidade'];
        $end['logradouro_gdae'] = @$dados['outLogradouro'];
        $end['uf'] = @$dados['outUF'];
        $end['latitude'] = @$dados['outLatitude'];
        $end['longitude'] = @$dados['outLongitude'];
        $end['num_gdae'] = @$dados['outNumero'];
        $end['complemento'] = @$dados['outComplemento'];
        $end['id_end'] = @$endereco['id_end'];
        $end['fk_id_tp'] = 1;
        $end['fk_id_pessoa'] = $id_pessoa;
        $end['longitude'] = @$dados['outLongitude'];
        $end['latitude'] = @$dados['outLatitude'];

        $this->db->ireplace('endereco', $end, 1);
    }

    public function wverificamaternal() {
        //inclui pre
        $sql = "SELECT t.fk_id_ciclo FROM ge_turmas t"
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                . " WHERE fk_id_ciclo IN (19,20,21,22,23,24) AND pl.at_pl = 1"
                . " AND fk_id_inst = '" . tool::id_inst() . "'";

        $query = pdoSis::getInstance()->query($sql);
        $sit = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($sit)) {
            return TRUE;
        } else {
            return;
            $a = NULL;
        }
    }

    public function equipedttie($idpessoa) {
        /**
         * 
          Rafaela 6482
          Katia 2984
          Adilson 6485
          Karin 336283
          Thomas 19927
          Mario 6488
          Dimas 811
          Marco 1

         */
        $equipe = [2984, 811, 1, 6488];
        
        if (in_array($idpessoa, $equipe)) {
            return true;
        } else {
            return false;
        }
    }

    public function pegaalunoapdid($idpessoa) {

        $al = "SELECT ap.fk_id_pessoa FROM ge_aluno_apd ap"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = ap.fk_id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                . " WHERE pl.at_pl = 1 AND ap.fk_id_pessoa = '" . $idpessoa . "'"
                . " AND ta.situacao = '" . 'Frequente' . "'"
                . " AND ap.status_apd = '" . 'Sim' . "'";

        $query = pdoSis::getInstance()->query($al);
        $d = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($d)) {
            $a = 'Sim';
        } else {
            $a = 'Não';
        }

        return $a;
    }

}
