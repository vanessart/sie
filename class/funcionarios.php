<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of professores
 *
 * @author marco
 */
class funcionarios {

    /**
     * 
     * @param type $id_inst escola
     * @param type $outro  incluir outra função na listagem. (like '%%')
     * @return type retorna informações da pessoa e funcionario dos professores
     */
    public static function professores($id_inst = NULL, $outro = NULL, $idName = NULL, $fields = '*') {
        if (empty($idName)) {
            $fields = 'id_pessoa, n_pessoa';
        }

        $outro = empty($outro) ? '' : "OR FUNCAO LIKE '%$outro%'";

        if ($id_inst == "disponivel") {
            $id_inst = "AND fk_id_inst = '' ";
        } elseif (!empty($id_inst)) {
            $id_inst = "AND fk_id_inst = '$id_inst' ";
        }

        $where = "where (FUNCAO LIKE 'profess%' $outro) $id_inst order by n_pessoa";
        $prof = sql::get(['pessoa', 'ge_funcionario'], $fields, "$where");
        if (empty($idName)) {
            return $prof;
        } else {
            foreach ($prof as $v) {
                $prof_[$v['id_pessoa']] = $v['n_pessoa'];
            }

            return @$prof_;
        }
    }

    /**
     * 
     * @param type $search string/número a buscar
     *              se for um array os key são os campos e valores são as buscas
     * @param type $field nome do campo. Por padrão é "rm"
     */
    public static function Get($search = NULL, $field = 'ge_funcionario.rm', $fields = '*') {
        if (!empty($search)) {
            $where = sql::where($search, $field);
        }
        return sql::get(['pessoa', 'ge_funcionario'], $fields, "$where");
    }

    /**
     * incluir id="busca" onkeypress="complete()" no input
     * funcao = prof retorna professor e instrutor 
     */
    public static function autocomplete($funcao = NULL, $completo = NULL) {
        if ($funcao == 'prof') {
            $funcao = "and (funcao like '%prof%' or funcao like '%instrut%')";
        } elseif (!empty($funcao)) {
            $funcao = "and funcao like '%$funcao%'";
        }
        if (empty($completo)) {
            $fields = "n_pessoa, ge_funcionario.rm";
        } else {
            $fields = " n_pessoa, id_pessoa, ge_funcionario.rm, n_inst, id_inst, tel1, tel2, instancia.email as email, pessoa.email as emailPessoa";
            $inst = "left join instancia on instancia.id_inst = ge_funcionario.fk_id_inst ";
        }
        $sql = "select $fields from pessoa "
                . "join ge_funcionario on ge_funcionario.fk_id_pessoa = pessoa.id_pessoa "
                . @$inst
                . " where 1 "
                . " $funcao "
                . "order by n_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $k => $v) {
            $func[$v['rm']] = $v;
        }
        ?>


        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="/resources/demos/style.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="<?php echo HOME_URI; ?>/views/_js/jquery-ui.js"></script>
        <script>
            function complete() {
                if (document.getElementById("busca").value.length > 4) {

                    var buscar = [
        <?php
        foreach ($func AS $value) {
            ?>
                            "<?php echo $value['n_pessoa'] . '|' . @strtoupper($value['rm']) . '|' . @strtoupper($value['id_pessoa']) . '|' . @strtoupper($value['n_inst']) . '|' . @strtoupper($value['id_inst']) . '|' . @strtoupper($value['tel1']) . '|' . @strtoupper($value['tel2']) . '|' . @strtoupper($value['email']) . '|' . @strtoupper($value['emailPessoa']) ?>",
            <?php
        }
        ?>
                    ];
                    $("#busca").autocomplete({
                        source: buscar,
                        campo_adicional: ['#rm', '#id_pessoa', '#n_inst', '#id_inst', '#tel1', '#tel2', '#email', '#emailPessoa']

                    });
                    }
                }
        </script>
        <?php
    }

    /**
     * incluir id="busca" onkeypress="complete()" no input
     * funcao = prof retorna professor e instrutor 
     */
    public static function autocompleteRm($funcao = NULL, $completo = NULL) {
        if ($funcao == 'prof') {
            $funcao = "and (funcao like '%prof%' or funcao like '%instrut%')";
        } elseif (!empty($funcao)) {
            $funcao = "and funcao like '%$funcao%'";
        }
        if (empty($completo)) {
            $fields = "n_pessoa, ge_funcionario.rm";
        } else {
            $fields = " n_pessoa, id_pessoa, ge_funcionario.rm, n_inst, id_inst, tel1, tel2, instancia.email as email, pessoa.email as emailPessoa";
            $inst = "left join instancia on instancia.id_inst = ge_funcionario.fk_id_inst ";
        }
        $sql = "select $fields from pessoa "
                . "join ge_funcionario on ge_funcionario.fk_id_pessoa = pessoa.id_pessoa "
                . @$inst
                . " where 1 "
                . " $funcao "
                . "order by n_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $k => $v) {
            $func[$v['rm']] = $v;
        }
        ?>


        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="/resources/demos/style.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="<?php echo HOME_URI; ?>/views/_js/jquery-ui.js"></script>
        <script>
                function complete() {
                    if (document.getElementById("busca").value.length > 2) {

                    var buscar = [
        <?php
        foreach ($func AS $value) {
            ?>
                        "<?php echo $value['rm'] . '|' . @strtoupper($value['n_pessoa']) . '|' . @strtoupper($value['id_pessoa']) . '|' . @strtoupper($value['n_inst']) . '|' . @strtoupper($value['id_inst']) . '|' . @strtoupper($value['tel1']) . '|' . @strtoupper($value['tel2']) . '|' . @strtoupper($value['email']) . '|' . @strtoupper($value['emailPessoa']) ?>",
            <?php
        }
        ?>
                    ];
                    $("#busca").autocomplete({
                        source: buscar,
                        campo_adicional: ['#n_pessoa', '#id_pessoa', '#n_inst', '#id_inst', '#tel1', '#tel2', '#email', '#emailPessoa']

                    });
                }
            }
        </script>
        <?php
    }

    public static function rh($id_inst = NULL, $funcao = NULL, $situacao = NULL, $search = NULL, $field = 'n_pessoa') {
        if (!empty($id_inst)) {
            $id_inst = " and  fk_id_inst  = $id_inst ";
        }
        if ($funcao == 'educadores') {
            $funcao = "and (f.funcao like '%professor%' or f.funcao like '%instrutor%' or f.funcao like '%libras%' ) ";
        } else {
            $funcao = "and f.funcao like '%$funcao%' ";
        }
        if (!empty($situacao)) {
            $situacao = " and situacao like '%$situacao%' ";
        }
        $sql = "select "
                . " funcao, rm, situacao, n_inst, n_pessoa, emailgoogle"
                . " from ge_funcionario f "
                . "join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . "join instancia i on i.id_inst = f.fk_id_inst "
                . " where 1 "
                . " $id_inst "
                . " $funcao "
                . @$situacao
                . " and $field like '%$search%'"
                . " order by n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $cont = 1;
        foreach ($array as $k => $v) {
            $array[$k]['ct'] = $cont++;
        }
        //$_SESSION['tmp'] = $sql;
        return $array;
    }

    public static function profEscola($id_inst = NULL, $iddisc = NULL, $rm = NULL, $fields = " a.rm, p.n_pessoa, p.id_pessoa ") {
        if (empty($id_inst)) {
            $id_inst = tool::id_inst();
        }
        if (!empty($iddisc)) {
            $iddisc = " AND a.iddisc in ($iddisc) ";
        } if (!empty($rm)) {
            $rm = " AND a.rm = '$rm' ";
        }
        $sql = "SELECT "
                . "distinct" . $fields
                . " FROM ge_aloca_prof a "
                . " JOIN ge_funcionario f on f.rm = a.rm "
                . " JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " WHERE a.fk_id_inst = $id_inst "
                . $iddisc
                . $rm
                . " order by n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function profEscolaDiscClasse($id_inst = NULL, $iddisc = NULL, $rm = NULL, $dia = NULL, $fields = " a.rm, p.n_pessoa, p.id_pessoa, d.n_disc, t.n_turma, a.iddisc") {
        if (empty($id_inst)) {
            $id_inst = tool::id_inst();
        }
        if (!empty($iddisc)) {
            $iddisc = " AND a.iddisc = '$iddisc' ";
        } if (!empty($rm)) {
            $rm = " AND a.rm = '$rm' ";
        }
        $sql = "SELECT "
                . $fields
                . " FROM ge_aloca_prof a "
                . " join ge_turmas t on t.id_turma = a.fk_id_turma "
                . " left join ge_disciplinas d on d.id_disc = a.iddisc "
                . " JOIN ge_funcionario f on f.rm = a.rm "
                . " JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " WHERE a.fk_id_inst = $id_inst "
                . $iddisc
                . $rm;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $k => $v) {
            if ($v['iddisc'] == 'nc') {
                $array[$k]['n_disc'] = 'Polivalente';
            }
        }
        return $array;
    }

    public static function funcInstancia($id_inst, $noProf = NULL) {

        if (!empty($noProf)) {
            $noProf = "AND funcao not like '%prof%'  ";
        }
        $id_inst = tool::id_inst($id_inst);
        $sql = "SELECT "
                . " u.id_user, p.n_pessoa, p.id_pessoa, p.dt_nasc, p.cpf, f.id_func, f.funcao, f.email, f.tel, f.cel, f.rm "
                . " FROM pessoa p "
                . " JOIN ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
                . " left join users u on u.fk_id_pessoa = p.id_pessoa "
                . " WHERE f.situacao IN('001-ATIVO','Ativado','Ativo') AND f.fk_id_inst = $id_inst "
                . $noProf
                . " order by n_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

}
