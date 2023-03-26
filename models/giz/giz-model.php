<?php

class gizModel extends MainModel {

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

        if (DB::sqlKeyVerif('itens')) {
            $this->db->ireplace('giz_prof', $_POST[1], 1);
        }

        if (DB::sqlKeyVerif('salvaInscr')) {
            if (!empty($_POST['turmas'])) {
                $insert['fk_id_cate'] = @$_POST['id_cate'];
                $insert['fk_id_inst'] = @$_POST['id_inst'];
                $insert['fk_id_mod'] = @$_POST['id_mod'];
                $insert['fk_id_pessoa'] = @$_POST['id_pessoa'];
                $insert['fk_id_disc'] = @$_POST['id_disc'];
                $insert['id_prof'] = @$_POST['id_prof'];
                $insert['turmas'] = serialize(@$_POST['turmas']);
                $insert['dt_prof'] = empty($_POST['dt_prof']) ? date("Y-m-d") : $_POST['dt_prof'];
                $this->db->ireplace('giz_prof', $insert);
            } else {
                tool::alert("Precisa escolher ao menos uma classe");
                $_POST['activeNav'] = 1;
            }
        } elseif (DB::sqlKeyVerif('lancNotas')) {
            $nota_projeto = $_POST['nota_projeto'];
            $nota_portfolio = $_POST['nota_portfolio'];
            if ($nota_projeto <= 2) {
                $ins['nota_projeto'] = str_replace(',', '.', $nota_projeto);
            }
            $ins['just_projeto'] = addslashes($_POST['just_projeto']);
            if ($nota_portfolio <= 3) {
                $ins['nota_portfolio'] = str_replace(',', '.', $nota_portfolio);
            }
            $ins['just_portfolio'] = addslashes($_POST['just_portfolio']);
            $ins['id_prof'] = $_POST['id_prof'];
            $ins['eixo_1_1'] = $_POST['eixo_1_1'];
            $ins['eixo_v_1_1'] = $_POST['eixo_v_1_1'];
            $ins['eixo_t_1_1'] = $_POST['eixo_t_1_1'];
            $ins['eixo_1_2'] = $_POST['eixo_1_2'];
            $ins['eixo_v_1_2'] = $_POST['eixo_v_1_2'];
            $ins['eixo_t_1_2'] = $_POST['eixo_t_1_2'];
            $ins['eixo_2_1'] = $_POST['eixo_2_1'];
            $ins['eixo_v_2_1'] = $_POST['eixo_v_2_1'];
            $ins['eixo_t_2_1'] = $_POST['eixo_t_2_1'];
            $ins['eixo_2_2'] = $_POST['eixo_2_2'];
            $ins['eixo_v_2_2'] = $_POST['eixo_v_2_2'];
            $ins['eixo_t_2_2'] = $_POST['eixo_t_2_2'];
            $ins['eixo_3_1'] = $_POST['eixo_3_1'];
            $ins['eixo_v_3_1'] = $_POST['eixo_v_3_1'];
            $ins['eixo_t_3_1'] = $_POST['eixo_t_3_1'];
            $ins['eixo_3_2'] = $_POST['eixo_3_2'];
            $ins['eixo_v_3_2'] = $_POST['eixo_v_3_2'];
            $ins['eixo_t_3_2'] = $_POST['eixo_t_3_2'];
            $ins['eixo_3_3'] = $_POST['eixo_3_3'];
            $ins['eixo_v_3_3'] = $_POST['eixo_v_3_3'];
            $ins['eixo_t_3_3'] = $_POST['eixo_t_3_3'];
            $ins['eixo_4'] = $_POST['eixo_4'];
            $ins['eixo_v_4'] = $_POST['eixo_v_4'];
            $ins['eixo_t_4'] = $_POST['eixo_t_4'];
            $ins['eixo_5'] = $_POST['eixo_5'];
            $ins['eixo_v_5'] = $_POST['eixo_v_5'];
            $ins['eixo_t_5'] = $_POST['eixo_t_5'];
            $ins['eixo_6'] = $_POST['eixo_6'];
            $ins['eixo_v_6'] = $_POST['eixo_v_6'];
            $ins['eixo_t_6'] = $_POST['eixo_t_6'];
            $ins['eixo_7'] = $_POST['eixo_7'];
            $ins['eixo_v_7'] = $_POST['eixo_v_7'];
            $ins['eixo_t_7'] = $_POST['eixo_t_7'];
            $ins['eixo_8'] = $_POST['eixo_8'];
            $ins['eixo_v_8'] = $_POST['eixo_v_8'];
            $ins['eixo_t_8'] = $_POST['eixo_t_8'];
            $ins['eixo_9'] = $_POST['eixo_9'];
            $ins['eixo_v_9'] = $_POST['eixo_v_9'];
            $ins['eixo_t_9'] = $_POST['eixo_t_9'];
            $ins['eixo_entrevista'] = $_POST['eixo_entrevista'];
            $ins['eixo_v_entrevista'] = $_POST['eixo_v_entrevista'];
            $ins['eixo_t_entrevista'] = $_POST['eixo_t_entrevista'];
            $ins['obs'] = $_POST['obs'];
            $this->db->ireplace('giz_notas', $ins, 1);
            $this->db->ireplace('giz_notas', $ins);
        }
    }

    public function inscricoes($search = NULL, $field = 'p.id_pessoa', $banca = NULL, $fields = NULL) {
        if (!empty($banca)) {
            $banca = " and gestor = 2 ";
        }
        if (empty($fields)) {
            $fields = "pf.`id_prof`, pf.turmas, pf.titulo, pf.tema, pf.dt_inicio, pf.dt_fim, pf.palavras, pf.objgeral, pf.objespec, pf.justifica, pf.metodo, pf.cronograma, pf.gestor, pf.recurso, pf.dt_prof, "
                    . "p.n_pessoa, p.id_pessoa, p.sexo, "
                    . "p2.n_pessoa as n_gestor, idgestor, "
                    . "c.n_cate, c.id_cate,"
                    . "i.n_inst, i.id_inst, "
                    . "d.n_disc";
        }
        $sql = "SELECT $fields FROM giz_prof pf "
                . " JOIN pessoa p on p.id_pessoa = pf.fk_id_pessoa "
                . " JOIN giz_categoria c on c.id_cate = pf.fk_id_cate "
                . " LEFT JOIN ge_disciplinas d on d.id_disc = pf.fk_id_disc "
                . " JOIN instancia i on i.id_inst = pf.fk_id_inst "
                . " LEFT JOIN pessoa p2 on p2.id_pessoa = idgestor "
                . " where $field in ($search) "
                . $banca
                . " order by p.n_pessoa";
        $query = $this->db->query($sql);
        if ($field == 'id_prof') {
            $array = $query->fetch();
        } else {
            $array = $query->fetchAll();
        }
        return $array;
    }

    public function turmas($turmas) {
        $sql = "select n_turma from ge_turmas "
                . "where id_turma in (" . (implode(',', unserialize($turmas))) . ")";
        $query = $this->db->query($sql);
        $array = $query->fetchAll();
        foreach ($array as $v) {
            $id[] = $v['n_turma'];
        }

        return tool::virgulaE($id);
    }

    public function coautor($id_prof) {
        $sql = "SELECT "
                . " p.id_pessoa, c.rm, p.n_pessoa "
                . " FROM giz_coautor c JOIN ge_funcionario f ON f.rm = c.rm "
                . " JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " WHERE c.fk_id_prof = $id_prof ";
        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        return $array;
    }

    public function quest() {
        $qt['Eixo 1 - Contemporaneidade:'] = [
            '_1_1' => '1.1 - Criatividade.',
            '_1_2' => '1.2 - Foco e comprometimento na realização da proposta e no contexto no qual foi desenvolvido.',
        ];
        $qt['Eixo 2 - Adesão Discente'] = [
            '_2_1' => '2.1 - Aplicabilidade.',
            '_2_2' => '2.2 - Envolvimento, Participação e Inclusão.'
        ];
        $qt['Eixo 3 - Registros'] = [
            '_3_1' => '3.1 - Relatos / Relatórios.',
            '_3_2' => '3.2 - Portfólio e Filmagens.',
            '_3_3' => '3.3 - Produção dos alunos.'
        ];

        $qt['Eixo 4 - Estratégias Inovadoras'] = [
            '_4' => '4.1 - Estratégias diferenciadas na prática e no desenvolvimento do projeto.'
        ];

        $qt['Eixo 5 - Educação Inclusiva'] = [
            '_5' => '5.1 - Dificuldade de aprendizagem/ desenvolvimento, multirrepetentes ou necessidades educativas especiais.'
        ];

        $qt['Eixo 6 - Objetividade'] = [
            '_6' => '6.1 - Clareza no registro de todas as etapas do projeto, bem como sua funcionalidade.'
        ];
        $qt['Eixo 7 - Equipe Gestora'] = [
            '_7' => '7.1 - Acompanhamento da Equipe Gestora da Unidade Escolar.',
        ];

        $qt['Eixo 8 - Resultados'] = [
            '_8' => '8.1 - Dados Quantificados e Mensuráveis.'
        ];

        $qt['Eixo 9 - Assiduidade'] = [
            '_9' => '9.1 - comprometimento do docente por meio da frequência aferida no ano letivo.'
        ];

        $qt['entrevista'] = [
            '_entrevista' => ''
        ];

        return $qt;
    }

//4,62
    public function value() {
        $value['_1_1'] = [0.09, 0.04, 0.02, 0];
        $value['_1_2'] = [0.09, 0.04, 0.02, 0];
        $value['_1_3'] = [0.09, 0.04, 0.02, 0];
        $value['_2_1'] = [0.25, 0.12, 0.06, 0];
        $value['_2_2'] = [0.25, 0.12, 0.06, 0];
        $value['_3_1'] = [0.04, 0.02, 0.01, 0];
        $value['_3_2'] = [0.04, 0.02, 0.01, 0];
        $value['_3_3'] = [0.04, 0.02, 0.01, 0];
        $value['_4'] = [0.70, 0.35, 0.17, 0];
        $value['_5'] = [0.30, 0.15, 0.07, 0];
        $value['_6'] = [0.40, 0.20, 0.10, 0];
        $value['_7'] = [0.71, 0.35, 0.17, 0];
        $value['_8'] = [1, 0.5, 0.25, 0];
        $value['_9'] = [0.09, 0.04, 0.02, 0];
        $value['_entrevista'] = [1, 0.5, 0.25, 0];

        return $value;
    }

    public function descri() {
        $value['_1_1'] = ['Percebe o problema e propõe solução - converte a ideia mental - em ideia prática de forma - inovadora e criativa.', 'Percebe o problema e propõe solução - converte a ideia mental em ideia prática.', 'Apresenta ações condizentes com uma boa aula, mas não pertinentes a um projeto', 'Percebe o problema e propõe solução sem a demonstração de criatividade.'];
        $value['_1_2'] = ['Tem objetividade, elabora planejamento e estabelece prioridades sem descuidar do conteúdo programático (aulas regulares).', 'Tem objetividade e elabora planejamento sem descuidar do conteúdo programático (aulas regulares).', 'Elabora planejamento sem descuidar do conteúdo programático (aulas regulares).', 'Descuida do conteúdo programático (aulas regulares) se dedicando exclusivamente ao desenvolvimento do projeto.'];
        $value['_1_3'] = ['Articula o projeto com a Proposta Pedagógica da escola, bem como com os conteúdos programáticos.', 'Articula o projeto com os conteúdos programáticos.', 'O projeto atende parcialmente os conteúdos programáticos.', 'O projeto não atende a proposta pedagógica nem os conteúdos programáticos.'];
        $value['_2_1'] = ['Apresenta diagnóstico inicial no qual demonstra a viabilidade da aplicação do projeto bem como os caminhos a serem percorridos dentro da realidade discente.', 'Apresenta diagnóstico inicial no qual demonstra os caminhos a serem percorridos dentro da realidade discente.', 'Apresenta diagnóstico inicial no qual demonstra parcialmente os caminhos a serem percorridos. ', 'Não apresenta diagnóstico inicial e sim apenas os possíveis caminhos a serem percorridos.'];
        $value['_2_2'] = ['Contextualiza e motiva os alunos despertando o protagonismo e a capacidade criativa potencializando a educação inclusiva', 'Motiva os alunos despertando a capacidade criativa potencializando a educação inclusiva.', 'Motiva os alunos despertando a capacidade criativa.', 'Pouca motivação dos alunos. Aprendizagem não potencializada. '];
        $value['_3_1'] = ['Apresenta relatos concisos de pais, comunidade escolar e gestão, que comprovem a execução  e o êxito do projeto.', 'Apresenta relatos concisos de pais, comunidade escolar e gestão, que comprovem a execução do projeto.', 'Apresenta relatos superficiais de pais, comunidade escolar e gestão.', 'Não apresenta relatos de pais, comunidade escolar e gestão.'];
        $value['_3_2'] = ['Apresenta portfólio e filmagem que comprovem todo o processo de desenvolvimento e execução do projeto. (Máximo de 16 fotos e 10 minutos de filmagem)', 'Apresenta portfólio e filmagem que comprovem parcialmente o processo de desenvolvimento e execução do projeto. (Máximo de 16 fotos e 10 minutos de filmagem)', 'Apresenta portfólio e filmagem apenas da culminância do projeto. (Máximo de 16 fotos e 10 minutos de filmagem)', 'Não apresenta portfólio ou filmagem ou então está em desacordo com o estabelecido. '];
        $value['_3_3'] = ['Apresenta, por amostragem, produções concisas (relatórios, avaliações, pesquisas, roda de conversas etc.) que comprovem a execução e o êxito do projeto.', 'Apresenta, por amostragem, produções (relatórios, avaliações, pesquisas, roda de conversas etc.) que comprovem a execução do projeto.', 'Apresenta, por amostragem, produções pouco concisas (relatórios, avaliações, pesquisas, roda de conversas etc.) que comprovem parcialmente a execução do projeto ', 'Não apresenta produções (relatórios, avaliações, pesquisas, roda de conversas etc.) que comprovem a execução e o êxito do projeto.'];
        $value['_4'] = ['Comprova em sua prática a aplicação de estratégias diferenciadas e inovadoras apresentando metodologia que promova o envolvimento, a interação dos alunos, comunidade escolar e aprendizagem significativa no desenvolvimento do projeto.', 'Comprova em sua prática a aplicação de estratégias diferenciadas e inovadoras no desenvolvimento do projeto.', 'Comprova em sua prática parcialmente a aplicação de estratégias diferenciadas e inovadoras no desenvolvimento do projeto.', 'Não comprova em sua prática a aplicação de estratégias diferenciadas e inovadoras no desenvolvimento do projeto.'];
        $value['_5'] = ['Elabora plano de ação e utiliza diferentes instrumentos e recursos adequando e respeitando as especificidades, com comprovação de resultados efetivos.', 'Utiliza diferentes instrumentos e recursos adequando e respeitando as especificidades, com comprovação de resultados efetivos.', 'Utiliza instrumentos e recursos que não atendem as especificidades.', 'Não utiliza diferentes instrumentos e recursos portanto não atende as especificidades.'];
        $value['_6'] = ['Apresenta clareza e consistência no registro de todas as etapas do projeto, bem como sua funcionalidade.', 'Apresenta clareza no registro de todas as etapas do projeto, bem como sua funcionalidade.', 'Apresenta pouca clareza no registro de todas as etapas do projeto, bem como sua funcionalidade.', 'Não apresenta clareza no registro de todas as etapas do projeto, bem como sua funcionalidade.'];
        $value['_7'] = ['Houve acompanhamento durante todo o desenvolvimento do projeto e com relatórios com resultado muito satisfatório.', ' Houve acompanhamento durante o desenvolvimento do projeto , sem relatórios, mas com resultado satisfatório.', 'Houve pouco acompanhamento  do projeto .', 'Não houve acompanhamento do projeto.'];
        $value['_8'] = ['Apresenta dados precisos e relevantes que comprovam todas as etapas (diagnóstico, desenvolvimento e resultado).', 'Apresenta dados que comprovam todas as etapas (diagnóstico, desenvolvimento e resultado).', 'Apresenta dados que comprovam parcialmente as etapas do projeto.', 'Não apresenta dados comprobatórios.'];
        $value['_9'] = ['Não apresentou nenhuma ausência durante o desenvolvimento do projeto, exceto as previstas por lei.', 'Apresenta ausências previstas na lei, não comprometendo o desenvolvimento do projeto.', 'Apresenta ausências,  além das amparadas por lei, prejudicando parcialmente a execução do projeto.', 'Apresenta ausências que comprometeram o desenvolvimento do projeto.'];
        $value['_entrevista'] = ['Ao discorrer sobre seu projeto o docente demonstra precisão na justificativa da realização de seu projeto; Demonstra ainda clareza nos objetivos a serem alcançados, isto é, onde ele quis chegar com o projeto; Contextualiza os objetivos do projeto com o conteúdo de suas aulas; Apresenta resultados concretos de melhoria na aprendizagem proporcionada pela execução de seu projeto.', 'Ao discorrer sobre seu projeto o docente apresenta consistência em suas colocações e apresenta resultados concretos de melhoria na aprendizagem proporcionada pela execução de seu projeto.', 'Ao discorrer sobre seu projeto o docente apresenta parcial consistência em suas colocações e apresenta resultados de melhoria na aprendizagem proporcionada pela execução de seu projeto.', 'Ao discorrer sobre seu projeto o docente não demonstra clareza ao discorrer sobre seu projeto ou não apresenta resultados concretos de melhoria na aprendizagem proporcionada pela execução de seu projeto.'];

        return $value;
    }

    public function nivel($name, $value = NULL, $post = NULL, $descri = NULL) {
        $nivel = [
            'Muito Satisfatório',
            'Satisfatório',
            'Pouco Satisfatório',
            'Insatisfatório',
        ];
        ?>
        <select name="<?php echo $name ?>" onchange="this.options[this.selectedIndex].onclick()" >
            <option></option>
            <?php
            for ($c = 0; $c < 4; $c++) {
                ?>
                <option onclick="document.getElementById('<?php echo $name ?>').value = '<?php echo $value[$c] ?>';
                                    document.getElementById('<?php echo $name ?>descri').value = '<?php echo $descri[$c] ?>'" <?php echo $post == $nivel[$c] ? 'selected' : '' ?> ><?php echo $nivel[$c] ?></option>
                        <?php
                    }
                    ?>
        </select>
        <?php
    }

    public function classifica($id_cate) {
        $sql = "SELECT "
                . " pe.id_pessoa, p.fk_id_cate, i.id_inst, n.* "
                . " FROM giz_prof p "
                . " JOIN giz_notas n on p.id_prof = n.id_prof "
                . " LEFT JOIN pessoa pe on pe.id_pessoa = p.fk_id_pessoa "
                . " LEFT JOIN instancia i on i.id_inst = p.fk_id_inst "
                . " LEFT JOIN giz_modalidade m on m.id_mod = p.fk_id_mod "
                . " WHERE p.gestor = 2 "
                . " AND p.fk_id_cate = '$id_cate'";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $prof) {
            foreach ($prof as $k => $v) {
                if ($k == 'nota_projeto') {
                    $result[$prof['id_pessoa']]['notas']['projeto'] = $v;
                } elseif ($k == 'nota_portfolio') {
                    $result[$prof['id_pessoa']]['notas']['portfolio'] = $v;
                } elseif (substr($k, 0, 7) == 'eixo_v_') {
                    $result[$prof['id_pessoa']]['notas'][$k] = $v;
                } elseif (substr($k, 0, 4) != 'eixo') {
                    $result[$prof['id_pessoa']][$k] = $v;
                }
                if (substr($k, 0, 7) == 'eixo_v_') {
                    $result[$prof['id_pessoa']]['notaEixo'][substr($k, 7, 1)][] = $v;
                }
            }
        }
        if (!empty($result)) {
            foreach ($result as $k => $v) {

                $total = array_sum($v['notas']);
                unset($eixo);
                foreach ($v['notaEixo'] as $ke => $e) {
                    $eixo[$ke] = array_sum($e);
                }


                $sql = "REPLACE INTO `giz_result` ("
                        . "`id_pessoa`, `fk_id_cate`, `fk_id_inst`, `projeto`, `portfolio`,"
                        . " `eixo1`, `eixo2`, `eixo3`, `eixo4`, `eixo5`, `eixo6`, `eixo7`, `eixo8`, `eixo9`, `eixoe`,"
                        . " `total`) VALUES ("
                        . "'$k', "
                        . "'" . $v['fk_id_cate'] . "', "
                        . "'" . $v['id_inst'] . "', "
                        . "'" . $v['notas']['projeto'] . "', "
                        . "'" . $v['notas']['portfolio'] . "', "
                        . "'" . $eixo[1] . "', "
                        . "'" . $eixo[2] . "', "
                        . "'" . $eixo[3] . "', "
                        . "'" . $eixo[4] . "', "
                        . "'" . $eixo[5] . "', "
                        . "'" . $eixo[6] . "', "
                        . "'" . $eixo[7] . "', "
                        . "'" . $eixo[8] . "', "
                        . "'" . $eixo[9] . "', "
                        . "'" . $eixo['e'] . "', "
                        . "'$total');";
                $query = pdoSis::getInstance()->query($sql);
            }
            $cate = sql::get('giz_categoria');
            foreach ($cate as $ct) {
                $class = $this->classicacao($ct['id_cate']);
                $classif = 1;
                foreach ($class as $cl) {
                    $sql = "UPDATE `giz_result` SET `class` = '$classif' "
                            . " WHERE `giz_result`.`id_pessoa` = " . $cl['id_pessoa'];
                    $query = pdoSis::getInstance()->query($sql);
                    $classif++;
                }
            }
        }
        tool::alert('Pronto');
    }

    public function classicacao($id_cate) {
        $sql = "SELECT "
                . " p.id_pessoa, p.n_pessoa, "
                . " pf.titulo, i.n_inst, m.n_mod, r.* "
                . " FROM giz_result r "
                . " JOIN giz_prof pf on pf.fk_id_pessoa = r.id_pessoa "
                . " JOIN pessoa p on p.id_pessoa = r.id_pessoa "
                . " JOIN instancia i on i.id_inst = r.fk_id_inst "
                . " JOIN giz_modalidade m on m.id_mod = pf.fk_id_mod "
                . " WHERE r.fk_id_cate = $id_cate "
                . " ORDER BY `r`.`total` DESC, r.ordem ASC ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $sql = "SELECT `rm`, fk_id_pessoa FROM `ge_funcionario`  ";
        $query = pdoSis::getInstance()->query($sql);
        $rmm = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rmm as $v) {
            $rm[$v['fk_id_pessoa']] = $v['rm'];
        }
        
        foreach ($array as $k => $v){
            $array[$k]['rm']= $rm[$v['id_pessoa']];
        }
        return $array;
    }

    public function devolutiva($id_pessoa) {
        $sql = "SELECT "
                . " p.id_pessoa, p.n_pessoa, p.sexo, c.n_cate, f.rm, "
                . " pf.titulo, i.n_inst, m.n_mod, r.*, n.* "
                . " FROM giz_result r "
                . " JOIN giz_prof pf on pf.fk_id_pessoa = r.id_pessoa "
                . " JOIN pessoa p on p.id_pessoa = r.id_pessoa "
                . " JOIN instancia i on i.id_inst = r.fk_id_inst "
                . " JOIN giz_modalidade m on m.id_mod = pf.fk_id_mod "
                . " JOIN ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
                . " JOIN giz_categoria c on c.id_cate = pf.fk_id_cate"
                . " JOIN giz_notas n on n.id_prof = pf.id_prof "
                . " WHERE  p.id_pessoa = $id_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return @$array;
    }

}
