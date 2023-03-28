<?php

class profModel extends MainModel {

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

        if (!empty($_POST['cadprof'])) {
            @$prof['disciplinas'] = '|' . implode('|', $_POST['disc']) . '|';
            $prof['fk_id_inst'] = tool::id_inst();
            $prof['rm'] = $_POST['rm'];
            @$prof['n_pe'] = $_POST['n_pe'];
            @$prof['id_pe'] = $_POST['id_pe'];
            @$prof['hac_dia'] = $_POST['hac_dia'];
            @$prof['hac_periodo'] = $_POST['hac_periodo'];
            @$prof['nao_hac'] = $_POST['nao_hac'];
            @$prof['email'] = $_POST['email'];
            @$prof['fk_id_psc'] = $_POST['fk_id_psc'];
            $rm = sql::get('ge_funcionario', 'rm', ['rm' => $prof['rm']], 'fetch')['rm'];
            if (!empty($prof['email']) ) {
                $emailDomain = explode('@', @$prof['email']);
                if (isset($emailDomain[1]) && $emailDomain[1] == CLI_MAIL_DOMINIO) {
                    $prof['email'] = NULL;
                    tool::alert("O e-mail não foi salvo. Não é permitido e-mails institucionais");
                }
            }
            if (empty($rm)) {
                tool::alert("Não foi encontrado Professor com a matrícula " . $prof['rm']);
            } else {
                $sql = "select p.id_pessoa, p.email, pe.rm, pe.fk_id_inst from ge_prof_esc pe "
                        . " left join ge_funcionario f on f.rm = pe.rm "
                        . " left join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                        . " where f.rm = '" . $rm . "'";
                $query = $this->db->query($sql);
                $dados = $query->fetch();


                if (!empty($dados['rm']) && !empty($dados['fk_id_inst']) && @$dados['fk_id_inst'] == tool::id_inst()) {
                    $sql = "select email from pessoa "
                            . "where email like '" . $prof['email'] . "' "
                            . "and id_pessoa <> " . $dados['id_pessoa'];
                    $query = $this->db->query($sql);
                    $emailVerif = $query->fetch()['email'];
                    if (!empty($emailVerif)) {
                        tool::alert("Este e-mail já está cadastrado e não foi salvo");
                        $prof['email'] = NULL;
                    }
                    $sql = "UPDATE `ge_prof_esc` SET `disciplinas` = '" . @$prof['disciplinas'] . "', "
                            . "`email` = '" . @$prof['email'] . "', "
                            . "`hac_dia` = '" . @$prof['hac_dia'] . "', "
                            . "`hac_periodo` = '" . @$prof['hac_periodo'] . "',"
                            . "`fk_id_psc` = '" . @$prof['fk_id_psc'] . "',"
                            . "`nao_hac` = '" . @$prof['nao_hac'] . "'"
                            . " WHERE `fk_id_inst` = " . tool::id_inst() . " AND `rm` = '" . $prof['rm'] . "'";

                    $query = pdoSis::getInstance()->query($sql);


                    if (!empty($prof['email']) && $prof['email'] <> $dados['email']) {

                        $fields['email'] = $prof['email'];
                        $fields['id_pessoa'] = $dados['id_pessoa'];
                        $sql = "update pessoa set email = '" . $fields['email'] . "' where id_pessoa = " . $fields['id_pessoa'];
                        $query = $this->db->query($sql);
                    }
                    log::logSet('Alterou as disciplinas do(a) professor(a) ' . funcionarios::Get($prof['rm'], 'rm', 'n_pessoa')[0]['n_pessoa']);
                    tool::alert(user::session('n_pessoa') . " alterou dados com sucesso!");
                } else {

                    $this->db->ireplace('ge_prof_esc', $prof);
                    $sql = "select p.id_pessoa, p.email, pe.rm, pe.fk_id_inst from ge_prof_esc pe "
                            . " left join ge_funcionario f on f.rm = pe.rm "
                            . " left join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                            . " where f.rm = '" . $rm . "'";
                    $query = $this->db->query($sql);
                    $dados = $query->fetch();
                    $sql = "select email from pessoa "
                            . "where email like '" . $prof['email'] . "' "
                            . "and id_pessoa <> '" . $dados['id_pessoa'] . "'";
                    $query = $this->db->query($sql);
                    $emailVerif = $query->fetch()['email'];
                    if (!empty($emailVerif)) {
                        tool::alert("Este e-mail já está cadastrado e não foi salvo");
                        $prof['email'] = NULL;
                    }
                    if (!empty($dados['id_pessoa'])) {
                        $fields['email'] = $prof['email'];
                        $fields['id_pessoa'] = $dados['id_pessoa'];
                        $this->db->ireplace('pessoa', $fields, 1);
                    }
                }
            }
        }
#################################


        if (!empty($_POST['cadproftmp'])) {
            @$prof['disciplinas'] = '|' . implode('|', $_POST['disc']) . '|';
            $prof['fk_id_inst'] = tool::id_inst();
            $prof['rm'] = $_POST['rm'];
            @$prof['n_pe'] = $_POST['n_pe'];
            @$prof['id_pe'] = $_POST['id_pe'];
            @$prof['hac_dia'] = $_POST['hac_dia'];
            @$prof['hac_periodo'] = $_POST['hac_periodo'];
            @$prof['nao_hac'] = $_POST['nao_hac'];
            @$prof['email'] = $_POST['email'];
            $rm = sql::get('ge_funcionario', 'rm', ['rm' => $prof['rm']], 'fetch')['rm'];
            f (!empty($prof['email']) ) {
                $emailDomain = explode('@', @$prof['email']);
                if (isset($emailDomain[1]) && $emailDomain[1] == CLI_MAIL_DOMINIO) {
                    $prof['email'] = NULL;
                    tool::alert("O e-mail não foi salvo. Não é permitido e-mails institucionais");
                }
            }
            if (empty($rm)) {
                tool::alert("Não foi encontrado Professor com a matrícula " . $prof['rm']);
            } else {
                $sql = "select p.id_pessoa, p.email, pe.rm, pe.fk_id_inst from ge_prof_esc_tmp pe "
                        . " left join ge_funcionario f on f.rm = pe.rm "
                        . " left join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                        . " where f.rm = '" . $rm . "'";
                $query = $this->db->query($sql);
                $dados = $query->fetch();


                if (!empty($dados['rm']) && !empty($dados['fk_id_inst']) && @$dados['fk_id_inst'] == tool::id_inst()) {
                    $sql = "select email from pessoa "
                            . "where email like '" . $prof['email'] . "' "
                            . "and id_pessoa <> " . $dados['id_pessoa'];
                    $query = $this->db->query($sql);
                    $emailVerif = $query->fetch()['email'];
                    if (!empty($emailVerif)) {
                        tool::alert("Este e-mail já está cadastrado e não foi salvo");
                        $prof['email'] = NULL;
                    }
                    $sql = "UPDATE `ge_prof_esc_tmp` SET `disciplinas` = '" . @$prof['disciplinas'] . "', "
                            . "`email` = '" . @$prof['email'] . "', "
                            . "`hac_dia` = '" . @$prof['hac_dia'] . "', "
                            . "`hac_periodo` = '" . @$prof['hac_periodo'] . "',"
                            . "`nao_hac` = '" . @$prof['nao_hac'] . "'"
                            . " WHERE `fk_id_inst` = " . tool::id_inst() . " AND `rm` = '" . $prof['rm'] . "'";

                    $query = pdoSis::getInstance()->query($sql);


                    if (!empty($prof['email']) && $prof['email'] <> $dados['email']) {

                        $fields['email'] = $prof['email'];
                        $fields['id_pessoa'] = $dados['id_pessoa'];
                        $sql = "update pessoa set email = '" . $fields['email'] . "' where id_pessoa = " . $fields['id_pessoa'];
                        $query = $this->db->query($sql);
                    }
                    log::logSet('Alterou as disciplinas do(a) professor(a) ' . funcionarios::Get($prof['rm'], 'rm', 'n_pessoa')[0]['n_pessoa']);
                    tool::alert(user::session('n_pessoa') . " alterou dados com sucesso!");
                } else {

                    $this->db->ireplace('ge_prof_esc_tmp', $prof);
                    $sql = "select p.id_pessoa, p.email, pe.rm, pe.fk_id_inst from ge_prof_esc_tmp pe "
                            . " left join ge_funcionario f on f.rm = pe.rm "
                            . " left join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                            . " where f.rm = '" . $rm . "'";
                    $query = $this->db->query($sql);
                    $dados = $query->fetch();
                    $sql = "select email from pessoa "
                            . "where email like '" . $prof['email'] . "' "
                            . "and id_pessoa <> '" . $dados['id_pessoa'] . "'";
                    $query = $this->db->query($sql);
                    $emailVerif = $query->fetch()['email'];
                    if (!empty($emailVerif)) {
                        tool::alert("Este e-mail já está cadastrado e não foi salvo");
                        $prof['email'] = NULL;
                    }
                    if (!empty($dados['id_pessoa'])) {
                        $fields['email'] = $prof['email'];
                        $fields['id_pessoa'] = $dados['id_pessoa'];
                        $this->db->ireplace('pessoa', $fields, 1);
                    }
                }
            }
        }

        ##############
        if (DB::sqlKeyVerif('alocahorario')) {
            for ($c = 1; $c <= 5; $c++) {
                for ($y = 1; $y <= 5; $y++) {
                    @$disc[@$_POST['aula'][$c][$y]] ++;
                }
            }
            $discGrade = sql::get('ge_aloca_disc', '*', ['fk_id_grade' => @$_POST['id_grade']]);

            foreach ($discGrade as $v) {
                if (@$disc[$v['fk_id_disc']] > $v['aulas'] && $v['nucleo_comum'] != 1) {
                    $ndisc = sql::get('ge_disciplinas', 'n_disc', ['id_disc' => $v['fk_id_disc']], 'fetch')['n_disc'];
                    tool::alert($ndisc . " só tem " . $v['aulas'] . " aula" . ($v['aulas'] > 1 ? 's' : ''));
                    $erro = 1;
                }
            }

            if (empty($erro)) {
                $insert['fk_id_turma'] = @$_POST['id'];
                foreach ($_POST['aula'] as $dia => $aulas) {
                    $insert['dia_semana'] = $dia;
                    foreach ($aulas as $naula => $id_disc) {
                        $insert['aula'] = $naula;
                        $insert['iddisc'] = @$id_disc;
                        $this->db->replace('ge_horario', $insert);
                    }
                }
                if (!empty($_POST['reforco'])) {
                    $sql = "DELETE FROM `ge_horario_ref` WHERE `ge_horario_ref`.`fk_id_turma` = '" . @$_POST['id'] . "' ";
                    $query = $this->db->query($sql);
                    foreach ($_POST['reforco'] as $dia => $aula) {
                        if (!empty($aula)) {
                            $insert['dia_semana'] = $dia;
                            $this->db->insert('ge_horario_ref', ['dia_semana' => $dia, 'fk_id_turma' => $insert['fk_id_turma']]);
                        }
                    }
                }
                tool::alert("Salvo");
            }
        }
        if (DB::sqlKeyVerif('alocaProf')) {
            $pr = @$_POST['pr'];
            $pr1 = @$_POST['pr1'];
            $supl = @$_POST['supl'];

            $idTurma = $_POST['id_turma'];
            $idInst = $_POST['id_inst'];
            foreach ($pr as $k => $v) {
                $sql = "REPLACE INTO `ge_aloca_prof` (`fk_id_turma`, `iddisc`, `fk_id_inst`, `rm`, prof2, suplementar) VALUES ('$idTurma', '$k', '$idInst', '$v', '1', '" . @$supl[1][$k] . "');";
                $query = pdoSis::getInstance()->query($sql);
            }
            if (!empty($pr1)) {
                foreach ($pr1 as $k => $v) {
                    $sql = "REPLACE INTO `ge_aloca_prof` (`fk_id_turma`, `iddisc`, `fk_id_inst`, `rm`, prof2, suplementar) VALUES ('$idTurma', '$k', '$idInst', '$v', '2', '" . @$supl[2][$k] . "');";
                    $query = pdoSis::getInstance()->query($sql);
                }
            }
            log::logSet('Alocou professores');
            tool::alert("Salvo com Sucesso!");
        }
    }

    public function pegadescricaop($d) {

        switch ($d) {
            case "M":
            case "0":
                $desc = "Manhã";
                break;
            case "T":
            case "1":
                $desc = "Tarde";
                break;
            case "I":
            case "2":
                $desc = "Integral";
                break;
            case "G":
            case "3":
                $desc = "Integral";
                break;
            case "N":
            case "4":
                $desc = "Noite";
                break;
            default :
                $desc = "-";
                break;
        }

        return $desc;
    }

    public function geraquadroprof($id_inst = NULL) {

        if (empty($id_inst)) {
            $id_inst = tool::id_inst();
        }

        $wsql = "SELECT t.periodo, c.id_ciclo, c.n_ciclo, t.fk_id_inst, COUNT(periodo_letivo) as totalp FROM ge_turmas t"
                . " JOIN ge_ciclos c on c.id_ciclo = t.fk_id_ciclo"
                . " GROUP BY t.periodo, t.fk_id_ciclo, c.n_ciclo, t.fk_id_inst"
                . " HAVING (t.fk_id_inst = " . $id_inst . ") ORDER BY t.fk_id_ciclo, t.periodo";

        $query = $this->db->query($wsql);
        $dados = $query->fetchAll();

        return $dados;
    }

    public function atztabelageprofesc() {

        $sql = "UPDATE ge_prof_esc pe"
                . " JOIN ge_funcionario f ON f.rm = pe.rm"
                . " JOIN pessoa p ON p.id_pessoa = f.fk_id_pessoa SET pe.n_pe = p.n_pessoa";

        $query = $this->db->query($sql);
    }

    public function pegadisciplinahac($d) {
        $hac = 'Não Informado';
        $sql = "SELECT id_disc, n_disc FROM ge_disciplinas";

        $query = $this->db->query($sql);
        $dados = $query->fetchAll();

        if (!empty($dados)) {
            foreach ($dados as $v) {
                $res[$v['id_disc']] = $v['n_disc'];
            }
        }
        $res['nc'] = 'Núcleo Comum';

        $discip = explode('|', $d);

        $conta = 0;

        foreach ($discip as $di) {
            if ($di != '') {
                if ($conta == 0) {
                    $hac = $res[$di];
                } else {
                    $hac = $hac . ' - ' . $res[$di];
                }
                $conta = 1;
            }
        }
        
        return $hac;
    }

}
