<?php

/**
 * acessar só alunos ativos
 * merge nos recados do mesmo reponsável
 * calendário escolar 
 * criar declaração de escolaridade
 * validade 
 * id@ para o infantil
 * 
 */
class alunoModel extends MainModel {

    public $db;
    private $googleIdGenerico = 'yt7nv5y34cv98nyvyp';

    public function __construct($db = false, $controller = null) {
        // Configura o DB (PDO)
        $this->db = new crud();
        // Configura o controlador
        $this->controller = $controller;

        // Configura os parâmetros
        $this->parametros = $this->controller->parametros->variavel;

        // Configura os dados do usuário
        $this->userdata = $this->controller->userdata;
    }

    public function mural() {
        $id_pessoa = $this->authorize();
        $mural = $this->muralAluno($id_pessoa);

        echo json_encode($mural);
    }

    public function mural_pais() {
        $id_pessoa = $this->authorize();
        $mural = $this->muralPais($id_pessoa);

        echo json_encode($mural);
    }

    private function muralPais($id_pessoa) {
        if ($id_pessoa) {
            $sql = " SELECT p.id_pessoa, p.n_pessoa, p.sexo, p.dt_nasc, c.id_curso, emailgoogle, google_user_id "
                    . " FROM ge_aluno_responsavel ar "
                    . " JOIN pessoa p on p.id_pessoa = ar.fk_id_pessoa_aluno "
                    . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa "
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
                    . " JOIN instancia i on i.id_inst = t.fk_id_inst "
                    . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                    . " JOIN ge_cursos c on c.id_curso = ci.fk_id_curso AND c.extra <> 1 "
                    . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                    . " WHERE ar.fk_id_pessoa_resp = " . $id_pessoa;
            ################################## esconde #####################################
if (in_array(tool::id_pessoa(), [1, 6])) {
    echo '<br />'.$sql;
    exit();
}
            $query = pdoSis::getInstance()->query($sql);
            $alunos = $query->fetchAll(PDO::FETCH_ASSOC);
            if (empty($alunos)) {
                return;
            } else {
                foreach ($alunos as $k => $v) {
                    $ma = $this->muralAluno($v['id_pessoa']);
                    if ($ma) {
                        foreach ($ma as $km => $m) {
                            if (is_array($m)) {
                                if (empty($mural[$km]['alunos'])) {
                                    $mAlu = [];
                                } else {
                                    $mAlu = $mural[$km]['alunos'];
                                }
                                $mural[$km] = $m;
                                $mural[$km]['alunos'] = $mAlu;
                                $mural[$km]['alunos'][$v['id_pessoa']] = explode(' ', $v['n_pessoa'])[0];
                            }
                        }
                    }
                }

                if (!empty($mural)) {
                    foreach ($mural as $k => $v) {
                        if (!empty($v['alunos'])) {
                            $mural[$k]['alunosList'] = toolErp::virgulaE($v['alunos']);
                        }
                    }
                }
                return $mural;
            }
        }
    }

    public function horario() {
        $id_pessoa = $this->authorize();
        if ($id_pessoa) {

            $turma = $this->alunoPlCursoTurma($id_pessoa);
            $id_turma = $turma['id_turma'];
            $hor = ng_escola::horario($id_turma, 1);

            echo json_encode($hor);
        }
    }

    public function nota() {
        $id_pessoa = $this->authorize();
        if ($id_pessoa) {
            $nota = [];

            $plCursoTurma = $this->alunoPlCursoTurma($id_pessoa);
            $disc = ng_main::disciplinas($plCursoTurma['id_turma']);
            $sql = "SELECT "
                    . " * "
                    . " FROM `aval_mf_" . $plCursoTurma['id_curso'] . "_" . $plCursoTurma['id_pl'] . "` "
                    . " WHERE `fk_id_pessoa` = $id_pessoa ";
            $query = pdoHab::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($array as $k => $v) {
                foreach ($v as $ky => $y) {
                    if ((substr($ky, 0, 6) == 'media_') && !empty($disc[substr($ky, 6)])) {
                        $d = $disc[substr($ky, 6)];
                        $nota['Bimestre_' . $v['atual_letiva']]['notas'][$d['n_disc']] = $y;
                    } elseif ((substr($ky, 0, 6) == 'falta_') && !empty($disc[substr($ky, 6)])) {
                        $d = $disc[substr($ky, 6)];
                        $nota['Bimestre_' . $v['atual_letiva']]['falta'][$d['n_disc']] = intval($y);
                    }
                }
            }

            $mediaTurma = $this->mediaTurma($plCursoTurma, $disc);

            foreach ($nota as $k => $v) {
                foreach ($v['notas'] as $ky => $y) {
                    $nota[$k]['media_turma'][$ky] = @$mediaTurma[$k]['media_turma'][$ky];
                }
            }
            //$notaMedia = array_merge_recursive($nota, $mediaTurma);
            //$notaMedia['dados_turma'] = $plCursoTurma;
            echo json_encode($nota);
        }
    }

    private function authorize() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $googleId = addslashes(filter_input(INPUT_POST, 'googleId', FILTER_SANITIZE_STRING));
        if (empty($id_pessoa) || empty($googleId)) {
            echo json_encode(['error' => 'Falta argumentos', 'error_id' => 1]);
            return false;
        }

        $sql = "SELECT google_user_id, id_pessoa FROM `pessoa` WHERE `id_pessoa` = $id_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $pessoa = $query->fetch(PDO::FETCH_ASSOC);

        if (empty($pessoa['id_pessoa'])) {
            echo json_encode(['error' => 'id_pessoa inexistente', 'error_id' => 3]);
            return;
        } else {
            $googleIdDb = $pessoa['google_user_id'];
        }
        if ($googleId == $this->googleIdGenerico) {
            return $id_pessoa;
        }

        if (empty($googleIdDb) || $googleIdDb == 0) {
            $sql = "UPDATE `pessoa` SET `google_user_id` = '$googleIdDb' "
                    . " WHERE `pessoa`.`id_pessoa` = $id_pessoa ";
            $query = pdoSis::getInstance()->query($sql);
            return $id_pessoa;
        } elseif ($googleIdDb == $googleId) {
            return $id_pessoa;
        } else {
            return;
        }
    }

    public function autentica() {

        $email = addslashes(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));
        $googleId = addslashes(filter_input(INPUT_POST, 'googleId', FILTER_SANITIZE_STRING));
        if (empty($email) || empty($googleId)) {
            echo json_encode(['error' => 'Falta argumentos', 'error_id' => 1]);
            return;
        }
        $array = $this->alunoAut($email, $googleId);
        //se não form aluno, verifica se é responsável
        if (empty($array['id_pessoa'])) {
            $array = $this->respAut($email, $googleId);
        }
        if (!empty($array['id_pessoa'])) {
            $id_pessoa = $array['id_pessoa'];
            if (empty($array['google_user_id']) && $googleId != $this->googleIdGenerico) {
                $this->googleIdUpdate($id_pessoa, $googleId);
            }
            $file = ABSPATH . '/pub/fotos/' . $array['id_pessoa'] . '.jpg';
            if (file_exists($file)) {
                $img = file_get_contents($file);
                $array['image'] = base64_encode($img);
            } 
            echo json_encode(['success' => $array]);
        } else {
            echo json_encode(['error' => 'Usuario Nao Encontrado', 'error_id' => 2]);
        }
    }

    private function respAut($email, $googleId) {
        $sql = " SELECT "
                . " id_pessoa, n_pessoa, sexo, google_user_id, 2 userType "
                . " FROM pessoa "
                . " WHERE emailgoogle LIKE '$email' "
                . " AND "
                . " ( "
                . " google_user_id LIKE '$googleId' "
                . " OR "
                . " google_user_id IS null "
                . " OR "
                . " google_user_id LIKE '0' "
                . " OR "
                . " google_user_id = '' "
                . ")";
        $query = pdoSis::getInstance()->query($sql);
        $resp = $query->fetch(PDO::FETCH_ASSOC);
        if (empty($resp)) {
            return;
        } else {
            $sql = " SELECT p.id_pessoa, p.n_pessoa, p.sexo, p.dt_nasc, c.id_curso, emailgoogle, google_user_id "
                    . " FROM ge_aluno_responsavel ar "
                    . " JOIN pessoa p on p.id_pessoa = ar.fk_id_pessoa_aluno "
                    . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa "
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
                    . " JOIN instancia i on i.id_inst = t.fk_id_inst "
                    . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                    . " JOIN ge_cursos c on c.id_curso = ci.fk_id_curso AND c.extra <> 1 "
                    . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                    . " WHERE ar.fk_id_pessoa_resp = " . $resp['id_pessoa']
                    . " AND ar.app = 1 ";
            $query = pdoSis::getInstance()->query($sql);
            $alunos = $query->fetchAll(PDO::FETCH_ASSOC);
            if (empty($alunos)) {
                return;
            } else {
                $tituloFilho = 'Filhas';
                foreach ($alunos as $k => $v) {
                    if ($v['sexo'] == 'M') {
                        $tituloFilho = 'Filhos';
                    }
                    $alunos[$k]['dt_nasc'] = dataErp::converte($v['dt_nasc']);
                    $alunos[$k]['escolaridade'] = $this->qrCodeEscol($v);
                    if (empty($v['google_user_id'])) {
                        $alunos[$k]['google_user_id'] = $this->googleIdGenerico;
                    }
                }
                if (count($alunos) == 1) {
                    $tituloFilho = explode(' ', $alunos[0]['n_pessoa'])[0];
                }
                $resp['tituloFilho'] = $tituloFilho;
                $resp['alunos'] = $alunos;
                return $resp;
            }
        }
    }

    private function mediaTurma($plCursoTurma, $disc) {
        foreach ($disc as $k => $v) {
            $fields[] = "AVG(media_" . $k . ") as '" . $v['n_disc'] . "' ";
        }
        $sql = " SELECT `atual_letiva`, " . implode(',', $fields) . "  FROM "
                . " `aval_mf_" . $plCursoTurma['id_curso'] . "_" . $plCursoTurma['id_pl'] . "` "
                . " WHERE `fk_id_turma` = " . $plCursoTurma['id_turma'] . " GROUP BY `atual_letiva`";
        $query = pdoHab::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $m['Bimestre_' . $v['atual_letiva']]['media_turma'] = $v;
            unset($m['Bimestre_' . $v['atual_letiva']]['media_turma']['atual_letiva']);
        }
        unset($array);
        if (!empty($m)) {
            return $m;
        }
    }

    private function alunoAut($email, $googleId) {
        $sql = " SELECT "
                . " p.id_pessoa, p.n_pessoa, p.sexo, p.dt_nasc, p.google_user_id, t.n_turma, i.n_inst, c.id_curso, 1 as userType "
                . " FROM pessoa p "
                . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa "
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " JOIN instancia i on i.id_inst = t.fk_id_inst "
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " JOIN ge_cursos c on c.id_curso = ci.fk_id_curso AND c.extra <> 1 "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " WHERE p.emailgoogle LIKE '$email' "
                . " AND p.at_google = 1"
                . " AND "
                . " ( "
                . " google_user_id LIKE '$googleId' "
                . " OR "
                . " google_user_id IS null "
                . " OR "
                . " google_user_id LIKE '0' "
                . " OR "
                . " google_user_id = '' "
                . ")";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if ($array) {
            if (!empty($googleId) && @$array['google_user_id'] == 0) {
                $sql = "UPDATE `pessoa` SET `google_user_id` = '$googleId' "
                        . " WHERE `pessoa`.`id_pessoa` =  " . @$array['id_pessoa'];
                $query = pdoSis::getInstance()->query($sql);
            }
            $array['escolaridade'] = $this->qrCodeEscol($array);
            $array['dt_nasc'] = dataErp::converteBr($array['dt_nasc']);
            return $array;
        }
    }

    private function qrCodeEscol($array) {
        $password_hash = new PasswordHash(8, FALSE);
        $token = $password_hash->HashPassword($array['dt_nasc']);

        //$site = 'https://dados.barueri.br/';
        $site = 'https://portal.educ.net.br/';
        $qr = $site . HOME_URI . '/sed/pdf/declaracaoQr.php?id=' . $array['id_pessoa'] . '&token=' . urlencode($token);
        return urldecode($qr);
    }

    private function googleIdUpdate($id_pessoa, $googleId) {
        $sql = "update pessoa set "
                . " google_user_id = '$googleId' "
                . " where id_pessoa = " . $id_pessoa;
        $query = pdoSis::getInstance()->query($sql);
    }

    private function alunoPlCursoTurma($id_pessoa) {
        $sql = " SELECT "
                . " pl.id_pl, t.id_turma, t.n_turma, c.id_curso, i.n_inst, i.id_inst "
                . " FROM ge_turma_aluno ta "
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " JOIN ge_cursos c on c.id_curso = ci.fk_id_curso "
                . " join instancia i on i.id_inst = t.fk_id_inst "
                . " WHERE ta.fk_id_pessoa = '$id_pessoa ' "
                . " AND pl.at_pl = 1 "
                . " AND c.extra = 0 ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    private function muralAluno($id_pessoa) {
        $dados = $this->alunoPlCursoTurma($id_pessoa);
        if ($dados) {
            $sql = " SELECT m.*, concat('Turma: ', t.n_turma) as origem FROM sed_mural m "
                    . " LEFT JOIN ge_turmas t on t.id_turma = m.fk_id_turma "
                    . " WHERE ( m.fk_id_inst = " . $dados['id_inst'] . " OR m.fk_id_inst is null ) "
                    . " AND ( m.fk_id_turma = " . $dados['id_turma'] . " OR m.fk_id_turma is null ) "
                    . " AND m.dt_inicio <= '" . date("Y-m-d") . "' "
                    . " AND m.dt_fim >= '" . date("Y-m-d") . "' "
                    . " AND m.at_mural = 1 "
                    . " AND m.fk_id_gr is null "
                    . " UNION "
                    . " SELECT m.*, concat('Grupo: ',g.n_gr) as origem FROM sed_mural m "
                    . " JOIN sed_grupo g on g.id_gr = m.fk_id_gr "
                    . " JOIN sed_grupo_aluno ga on ga.fk_id_gr = g.id_gr "
                    . " AND ga.fk_id_pessoa = $id_pessoa "
                    . " WHERE m.dt_inicio <= '" . date("Y-m-d") . "' "
                    . " AND m.dt_fim >= '" . date("Y-m-d") . "' "
                    . " AND m.at_mural = 1";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($array) {
                foreach ($array as $v) {
                    if (!empty($v['msg'])) {
                        $msgArr = explode(' ', $v['msg']);
                        foreach ($msgArr as $y) {
                            $posi = strripos($y, 'http');
                            if ($posi !== false) {
                                $link = substr($y, $posi);
                                $sobra = substr($y, 0, $posi);
                                $troca = '<a target="_blank" href="' . $link . '">' . $link . '</a>';
                                $v['msg'] = str_replace($y, $sobra . $troca, $v['msg']);
                            }
                        }
                    }
                    if (empty($v['origem']) && empty($v['fk_id_inst'])) {
                        $m[$v['id_mural']]['origem'] = 'Secretaria de Educação';
                    } elseif (empty($v['origem']) && $v['fk_id_inst'] == $dados['id_inst']) {
                        $m[$v['id_mural']]['origem'] = $dados['n_inst'];
                    } else {
                        $m[$v['id_mural']]['origem'] = $v['origem'];
                    }
                    $m[$v['id_mural']]['Título'] = $v['n_mural'];
                    $m[$v['id_mural']]['Mensagem'] = $v['msg'];
                    $m[$v['id_mural']]['dt_inicio'] = dataErp::converteBr($v['dt_inicio']);
                }
            }
            if (!empty($m)) {
                return $m;
            } else {
                return;
            }
        } else {
            return;
        }
    }

    public function escola() {
        $id_pessoa = $this->authorize();
        $escola = $this->escolaDados($id_pessoa);

        echo json_encode($escola);
    }

    private function escolaDados($id_pessoa) {
        $sql = "SELECT "
                . " t.n_turma Turma, i.n_inst Escola, p.cep CEP, logradouro Logradouro,"
                . " num Número, bairro Bairro, cidade Cidade, uf UF, tel1 Tel1, tel2 Tel2, tel3 Tel3, "
                . " e.maps Maps, e.latitude Latitude, e.longitude Longitude, esc_site Site, esc_contato Contato, "
                . " c.id_curso "
                . " FROM ge_turma_aluno ta JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl "
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " JOIN ge_cursos c on c.id_curso = ci.fk_id_curso "
                . " JOIN instancia i on i.id_inst = t.fk_id_inst "
                . " join instancia_predio ip on ip.fk_id_inst = t.fk_id_inst "
                . " join predio p on p.id_predio = ip.fk_id_predio "
                . " join ge_escolas e on e.fk_id_inst = t.fk_id_inst "
                . " WHERE ta.fk_id_pessoa = $id_pessoa "
                . " AND pl.at_pl = 1 "
                . " and c.extra = 0";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    /**
     * lista id_pessoa -> n_inst
     */
    public function idEscola() {
        $ids = implode(',', @$_POST);
        if ($ids) {
            $sql = " SELECT "
                    . " ta.fk_id_pessoa as id_pessoa, i.n_inst "
                    . " FROM ge_turma_aluno ta "
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
                    . " JOIN instancia i on i.id_inst = t.fk_id_inst "
                    . " WHERE ta.fk_id_pessoa in ($ids)";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(toolErp::idName($array));
        }
    }

}
