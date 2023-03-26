<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of migraRH
 *
 * @author mc
 */
class migraRH {

    public static function funcionarios() {

        $_db = new DB;



        $sql = "SELECT "
                . "DISTINCT `SUBSECAO_TRABALHO`, `ID_SUB_TRA`, fk_id_inst "
                . "FROM funcionarios_rh f "
                . "LEFT JOIN func_integra fi on fi.id_cit = f.ID_SUB_TRA "
                . "WHERE fk_id_inst is null ";
        $query = $_db->query($sql);
        $erro = $query->fetchAll();
        if (!empty($erro)) {
            ?>
            <pre>
                <?php
                print_r($erro)
                ?>
            </pre>
            <?php
        } else {
            $sql = "SELECT f.*, fi.*, "
                    . " gf.fk_id_pessoa, gf.id_func FROM funcionarios_rh f "
                    . " JOIN func_integra fi on fi.id_cit = f.ID_SUB_TRA "
                    . " LEFT JOIN ge_funcionario gf on gf.rm = f.MATRICULA ";
            $query = $_db->query($sql);
            $func = $query->fetchAll();

            foreach ($func as $v) {

                if (strlen($v['NASCIMENTO']) == 8) {
                    $dia = substr($v['NASCIMENTO'], 0, 2);
                } else {
                    $dia = '0' . substr($v['NASCIMENTO'], 0, 1);
                }
                $pessoa['dt_nasc'] = substr($v['NASCIMENTO'], -4) . '-' . substr($v['NASCIMENTO'], -6, 2) . '-' . $dia;
                $pessoa['n_pessoa'] = $v['FUNCIONARIO'];
                $pessoa['sexo'] = $v['SEXO'];
                $pessoa['ativo'] = 1;
                if (empty($v['fk_id_pessoa'])) {
                    $pessoa['n_social'] = explode(' ', $v['FUNCIONARIO'])[0];
                    $pessoa['cpf'] = str_pad($v['CPF'], 11, "0", STR_PAD_LEFT);
                    $pessoa['id_pessoa'] = null;
                } else {
                    $pessoa['n_social'] = NULL;
                    $pessoa['cpf'] = str_pad($v['CPF'], 11, "0", STR_PAD_LEFT);
                    $pessoa['id_pessoa'] = $v['fk_id_pessoa'];
                }
                $id = $_db->ireplace('pessoa', $pessoa, 1);
                echo '<br />' . $sql = "select id_pessoa from pessoa "
                . "where cpf = " . $pessoa['cpf'];
                $query = pdoSis::getInstance()->query($sql);
                $id = $query->fetch(PDO::FETCH_ASSOC)['id_pessoa'];
                ?>
                <pre>
                    <?php
                    print_r($pessoa)
                    ?>
                </pre>
                <?php
                $id = empty($id) ? $v['fk_id_pessoa'] : $id;
                $idPessoa[] = $id;

                $rm = [
                    'id_func' => @$v['id_func'],
                    'fk_id_pessoa' => $id,
                    'rm' => intval($v['MATRICULA']),
                    'funcao' => $v['CARGO'],
                    'situacao' => $v['SIT_ATUAL'],
                    'fk_id_inst' => $v['fk_id_inst']
                ];


                $_db->ireplace('ge_funcionario', $rm, 1);
            }
            $sql = "UPDATE ge_funcionario"
                    . " SET `situacao` = 'Desativado' "
                    . "where fk_id_pessoa not in ("
                    . implode(',', $idPessoa)
                    . ") "
                    . " And LENGTH(rm)<10 "
                    . " and rm not like '%t%' ";
            $query = pdoSis::getInstance()->query($sql);

            echo $sql = "DELETE FROM ge_funcionario f "
            . " JOIN acesso_pessoa ap on ap.fk_id_pessoa = f.fk_id_pessoa "
            . " WHERE f.situacao NOT IN ('Ativo', '001-ATIVO') "
            . " and ap.fk_id_pessoa not in ("
            . implode(',', $idPessoa)
            . ") ";
            // $query = pdoSis::getInstance()->query($sql);
        }
    }

    public static function periodoletivoconselho() {
        // Acrescentar ano a ano
        $wperiodo = [
            '27' => '2019',
            '28' => '1º Sem. 2019',
            '80' => '2º Sem. 2019',
            '81' => '2020',
            '82' => '1º Sem. 2020',
            '83' => '2º Sem. 2020'
            
        ];

        return $wperiodo;
    }

    public static function escolasconselho($idpl) {

        $sql = "SELECT DISTINCT i.id_inst, i.n_inst FROM instancia i"
                . " JOIN ge_turmas t ON t.fk_id_inst = i.id_inst"
                . " WHERE t.fk_id_pl = '" . $idpl . "' ORDER BY i.n_inst";

        $query = pdoSis::getInstance()->query($sql);
        $escola = $query->fetchAll();

        if (!empty($escola)) {
            foreach ($escola as $v) {
                $wescola[$v['id_inst']] = $v['n_inst'];
            }
            return $wescola;
        }
    }

    public static function turmaconselho($idpl, $idinst) {
        $sql = "SELECT * FROM ge_turmas"
                . " WHERE fk_id_inst = '" . $idinst . "' AND fk_id_pl = '" . $idpl . "'"
                . " ORDER BY n_turma";

        $query = pdoSis::getInstance()->query($sql);
        $turma = $query->fetchAll();

        if (!empty($turma)) {
            foreach ($turma as $v) {
                $wturma[$v['id_turma']] = $v['n_turma'];
            }

            return $wturma;
        }
    }

}
