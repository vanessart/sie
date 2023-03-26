<?php

    @$aba = $_POST['aba'];
    if (empty($_POST['novo'])) {
        $pessoa = @$_POST['pessoa'];
    } else {

        if (!empty($_POST[1]['cpf'])) {
            $pessoa = @$_POST[1]['cpf'];
        } elseif (!empty($_POST[1]['email'])) {
            $pessoa = @$_POST[1]['email'];
        }
    }

    if (!empty($_POST['id_pessoa']) || !empty($_POST[1]['id_pessoa']) || !empty($_POST['id'])) {
        @$id = !empty($_POST['id']) ? $_POST['id'] : (!empty($_POST['id_pessoa']) ? $_POST['id_pessoa'] : $_POST[1]['id_pessoa']);
        @$dados = alunos::busca(@$id)[0];
        if (empty($dados['id_pessoa'])) {
            $aba = NULL;
            tool::alert("RSE não encontrado");
        }
    } elseif (!empty($model->db->last_id)) {
        @$id = $model->db->last_id;
        $dados = pessoa::get($id);
    } else {
        $requiredCad = "required";
        @$dados = $_POST[1];
    }
    formulario::telefonesScript(@$id);
    ?>
    <br />  
    <div class="fieldBody">
        <br />
        <?php
        if (!empty($dados) || !empty($_POST['novo'])) {

            if ($aba == 'esc') {
                $_POST['activeNav'] = 4;
            }
            $abas[1] = [
                'nome' => "Dados Gerais",
                'ativo' => 1,
                'hidden' => ['aba' => 'geral', 'novo' => 1, 'id_pessoa' => $id]
            ];
            if (!empty($dados)) {
                $abas[2] = [
                    'nome' => "Endereço",
                    'ativo' => 1,
                    'hidden' => ['aba' => 'end', 'novo' => 1, 'id_pessoa' => $id]
                ];
                $abas[3] = [
                    'nome' => "Foto",
                    'ativo' => 1,
                    'hidden' => ['aba' => 'foto', 'novo' => 1, 'id_pessoa' => $id]
                ];
                $abas[4] = [
                    'nome' => "Vida Escolar",
                    'ativo' => 1,
                    'hidden' => ['aba' => 'esc', 'novo' => 1, 'id_pessoa' => $id]
                ];
                $abas[5] = [
                    'nome' => "Prontuário",
                    'ativo' => 1,
                    'hidden' => ['aba' => 'pront', 'novo' => 1, 'id_pessoa' => $id]
                ];
                $abas[6] = [
                    'nome' => "Transporte",
                    'ativo' => 1,
                    'hidden' => ['aba' => 'transp', 'novo' => 1, 'id_pessoa' => $id]
                ];
            } else {
                $abas[2] = ['nome' => "Endereço", 'ativo' => 0];
                $abas[3] = ['nome' => "Foto", 'ativo' => 0];
                $abas[4] = ['nome' => "Vida Escolar", 'ativo' => 0];
                $abas[5] = ['nome' => "Prontuário", 'ativo' => 0];
                $abas[6] = ['nome' => "Transporte", 'ativo' => 0];
            }
            $abas[7] = ['nome' => "Sair", 'ativo' => 1];
            tool::abas($abas);
        }
        ?>
        <div class="fieldTop">
            <br /><br /><br /><br />

            <?php
            if (!empty(@$dados['n_pessoa'])) {
                echo 'Alun' . tool::sexoArt($dados['sexo']) . ': ' . @$dados['n_pessoa'] . ' - ';
            }
            if (empty(@$aba) || $aba == 'cad') {
                echo 'Alunos';
                $include = ABSPATH . '/views/gestao/cadaluno_cad.php';
                $activeCad = 'active';
            } elseif ($aba == 'geral') {
                echo 'Dados Gerais';
                $include = ABSPATH . '/views/gestao/cadaluno_geral.php';
                $activeGer = 'active';
            } elseif ($aba == 'end') {
                echo 'Endereço';
                $include = ABSPATH . '/views/gestao/cadaluno_end.php';
                $activeEnd = 'active';
            } elseif ($aba == 'esc') {
                echo 'Vida Escolar';
                $include = ABSPATH . '/views/gestao/cadaluno_esc.php';
                $activeEsc = 'active';
            } elseif ($aba == 'foto') {
                echo 'Foto';
                $include = ABSPATH . '/views/gestao/cadaluno_foto.php';
                $activefoto = 'active';
            } elseif ($aba == 'pront') {
                echo 'Prontuário';
                $include = ABSPATH . '/views/gestao/cadaluno_prontuario.php';
                $activepront = 'active';
            } elseif ($aba == 'transp') {
                echo 'Transporte';
                $include = ABSPATH . '/views/gestao/cadaluno_transp.php';
                $activepront = 'active';
            }
            ?>
        </div>
        <br />
        <?php
        @include $include;
        ?>
        <br />

    </div>
    <?php
                                  