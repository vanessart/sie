<?php
/* manipula instancias
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of instupo
 *
 * @author marco
 */
class inst {

    public static function instancias($nome = NULL, $tipo = NULL) {
        if (!empty($tipo)) {
            $tipo_ = "and (fk_id_tp like '$tipo' or n_tp like '$tipo')";
        } else {
            $tipo_ = NULL;
        }
        if (!empty($nome)) {
            $nome = "and n_inst like '%$nome%' ";
        }
        $sql = "SELECT * FROM instancia "
                . "LEFT JOIN tipo_inst on tipo_inst.id_tp = instancia.fk_id_tp "
                . " where 1 "
                . " $nome "
                . " $tipo_ "
                . "ORDER BY n_inst";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function relat($nome = NULL, $tipo = NULL) {
        if (!empty($tipo)) {
            $tipo_ = "and n_tp like '%$tipo%' ";
        } else {
            $tipo_ = NULL;
        }
        if (!empty($nome)) {
            $nome = "and n_inst like '%$nome%' ";
        }
        $sql = "SELECT * FROM instancia "
                . "LEFT JOIN tipo_inst on tipo_inst.id_tp = instancia.fk_id_tp "
                . " where 1 "
                . " $nome "
                . " $tipo_ "
                . "ORDER BY n_inst";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($array)) {
            $sqlkey = DB::sqlKey('instancia', 'delete');
            foreach ($array as $k => $v) {
                if (empty($tipo)) {
                    $array[$k]['del'] = formulario::submit('Apagar', $sqlkey, ['1[id_inst]' => $v['id_inst']]);
                } elseif ($tipo == "Escola") {
                    $array[$k]['acesso'] = formulario::submit('Acessar', NULL, ['id_inst' => $v['id_inst'], 'aba' => 'escola']);
                }
                $array[$k]['edit'] = formOld::submit('Editar', NULL, $v);
                $array[$k]['ativo'] = tool::simnao($v['ativo']);
            }
            $form['array'] = $array;
            $form['fields'] = [
                'Nome' => 'n_inst',
                'Tipo' => 'n_tp',
                'ID' => 'id_inst',
                'ID Aut.' => 'fk_id_inst',
                'Abrev.' => 'abrev_inst',
                'Ativo' => 'ativo',
                '||1' => 'del',
                '||2' => 'edit',
                '||3' => 'acesso'
            ];

            tool::relatSimples($form);
        } else {
            echo 'Não encontrado';
        }
    }

    public static function get($search = null, $field = 'id_inst', $fields = '*') {
        if (!empty($search)) {
            $sql = "select * from instancia "
                    . "lEFT JOIN tipo_inst on tipo_inst.id_tp = instancia.fk_id_tp "
                    . "where $field = '$search'";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetch(PDO::FETCH_ASSOC);
            if ($array) {
                return $array;
            } else {
                return FALSE;
            }
        }
    }

    /**
     * incluir id="busca" onkeypress="complete()" no input
     */
    public static function autocomplete($escola = NULL) {
        if (!empty($escola)) {
            $escola = "where `fk_id_tp` = 1";
        }

        $fields = " n_inst, id_inst, cie_escola, email ";
        $sql = "select $fields from instancia i "
                . "left join ge_escolas e on e.fk_id_inst = i.id_inst "
                . " $escola "
                . "order by n_inst";
        $query = pdoSis::getInstance()->query($sql);
        $inst = $query->fetchAll(PDO::FETCH_ASSOC);
        ?>


        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="/resources/demos/style.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="<?php echo HOME_URI; ?>/views/_js/jquery-ui.js"></script>
        <script>
            function completeInst() {
                var buscar = [
        <?php
        foreach ($inst AS $value) {
            ?>
                        "<?php echo $value['n_inst'] . '|' . @strtoupper($value['id_inst']) . '|' . @strtoupper($value['cie_escola']) . '|' . @strtoupper($value['email']) ?>",
            <?php
        }
        ?>
                ];
                $("#n_inst").autocomplete({
                    source: buscar,
                    campo_adicional: ['#id_inst', '#cie_escola', '#email']

                });
            }
        </script>
        <?php
    }

}
