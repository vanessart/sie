<?php

/* meu CRUD - facilidador do sql
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sql
 *
 * @author mc
 */
class sqlErp {

    /**
     * 
     * @param type $table tabela(s) se for mais de 1 tem que ser um array
     * @param type $fields campos para exibir - padrão = todos (*)
     * @param type $where pode ser a where do sql ou um array sedo: key = campo e value = valor, tambem aceia key = > ou < e value = campo para ordenar
     * @param type $fetch padrao fetchAll (varias tuplas) pode ser fetch (uma tupla)
     * @param type $join  padrao é join pode ser left right
     * @return type
     */
    public static function get($table, $fields = '*', $where = NULL, $fetch = 'fetchAll', $join = NULL, $debug = NULL) {
        $fetch = empty($fetch) ? 'fetchAll' : $fetch;
        $where = sql::where($where);
        if (is_array($table)) {
            $tabela1 = $table[0];
            @$tableUsada[] = $tabela1;
            $sql = "SELECT $fields FROM `$tabela1` ";
            foreach ($table as $v) {
                $col = sql::columns($v);

                foreach ($col as $vv) {
                    if (substr($vv, 0, 6) == 'fk_id_') {
                        $fk[] = ['tb' => $v, 'col' => $vl = $vv];
                    } elseif (substr($vv, 0, 3) == 'id_') {
                        $id[$v] = $vv;
                    }
                }
            }
            @$tableUsada[] = $id[0]['tb'];
            foreach ($table as $v) {
                if (!empty($fk)) {
                    foreach ($fk as $vfk) {
                        if (substr($vfk['col'], 3) == @$id[$v]) {
                            if (!in_array($v, @$tableUsada)) {
                                $sql .= " $join JOIN " . $v . " ON " . $v . "." . $id[$v] . " = " . $vfk['tb'] . "." . $vfk['col'] . " ";
                                @$tableUsada[] = $v;
                            } elseif (!in_array($vfk['tb'], @$tableUsada)) {
                                $sql .= " $join JOIN " . $vfk['tb'] . " ON " . $vfk['tb'] . "." . $vfk['col'] . " = " . $v . "." . $id[$v] . " ";
                                @$tableUsada[] = $vfk['tb'];
                            }
                        }
                    }
                }
            }

            $sql .= " $where ";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->$fetch(PDO::FETCH_ASSOC);
        } else {
            $sql = "SELECT $fields FROM `$table` $where ";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->$fetch(PDO::FETCH_ASSOC);
        }
        if (!empty($debug)) {
            echo '--Debug--<br /><br />';
            echo $sql;
        }
        return $array;
    }

    /**
     * lista as colunas de uma tabela
     * @param type $table
     * @return type
     */
    public static function columns($table) {
        $sql = "SHOW COLUMNS FROM $table";
        $query = pdoSis::getInstance()->query($sql);
        $arr = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($arr as $v) {
            $col[$v['Field']] = $v['Field'];
        }
        return $col;
    }

    /**
     * retorna as tabelas do DB
     * @param type $id
     * @return type
     */
    public static function tables($id = NULL, $db = DB_NAME) {
        $sql = "SELECT * FROM information_schema.tables WHERE table_schema = '" . $db . "';";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            if (!empty($id)) {
                $id = preg_replace(array('/-/', '/:/', '/ /'), array('', '', ''), $v['CREATE_TIME']);
                $tb[$id] = $v['TABLE_NAME'];
            } else {
                $tb[$v['TABLE_NAME']] = $v['TABLE_NAME'];
            }
        }

        return $tb;
    }

    /**
     * 
     * @param type $where se for um array key = campo value = valor
     * se não ha field é um where de sql
     * se há field = valor
     * se o $where for  um array, irá ignorar o field
     * @param type $field = campo
     * @return type um where de sql
     */
    public static function where($where, $field = NULL) {
        if (is_array($where)) {
            $wh = "WHERE 1 = 1 ";
            foreach ($where as $k => $v) {
                if (!empty($v)) {
                    if (!is_array($v)) {
                        if (substr($k, 0, 1) == '>') {
                            @$r .= $v . ',';
                        } elseif (substr($k, 0, 1) == '<') {
                            @$r .= $v . ' desc,';
                        } else {
                            if (is_numeric($v)) {
                                $wh .= "AND $k = $v ";
                            } else {
                                $wh .= "AND $k like '$v' ";
                            }
                        }
                    } else {
                        $v = "'" . implode("', '", $v) . "'";
                        $wh .= "AND $k in ($v) ";
                    }
                }
            }
            if (!empty(@$r)) {
                $order = " order by " . substr(@$r, 0, -1);
            }
            $where = @$wh . @$order;
        } elseif (!empty($field)) {
            if (is_numeric($where)) {
                $where = "WHERE $field = '$where'";
            } else {
                $where = "WHERE $field LIKE '$where'";
            }
        }
        return $where;
    }

    /**
     * Devolve o id_ e o n_
     * @param type $table Nome da Tabela
     * @param type $ativo ativo se ativo = 1 e começar com at_
     */
    public static function idNome($table, $where = NULL) {
        $tb = sql::get($table);
        foreach (current($tb) as $k => $v) {
            $prefixo = explode('_', $k)[0];
            if ($prefixo == 'id') {
                $id = $k;
            }
            if ($prefixo == 'n') {
                $n = $k;
            }
            if ($prefixo == 'at') {
                $at = $k;
            }
        }
        if (!empty($where)) {
            if (!is_array($where)) {
                $where = " where $at = 1";
            }
        }
        $fields = $id . ', ' . $n;
        $tb = sql::get($table, $fields, $where);

        foreach ($tb as $v) {
            $idN[$v[$id]] = $v[$n];
        }
        if (!empty($idN)) {
            return $idN;
        } else {
            return;
        }
    }

    public static function debugPdo($stmt, $values) {
        $stmtBreak = explode('?', $stmt);
        $vl = 0;
        foreach ($stmtBreak as $v) {
            @$test .= $v . "'" . $values[$vl++] . "'";
        }
        return $test;
    }

    public static function count($table, $field, $where = NULL) {
        $conta = sql::get($table, "count($field) as ct", $where, 'fetch')['ct'];

        return $conta;
    }

    public static function create($table, $array, $pdo = 'pdoSis') {
        foreach ($array as $v) {
            foreach ($v as $coll => $value) {
                if (empty($type[$coll])) {
                    $type[$coll] = 'int';
                } elseif (is_string($type[$coll])) {
                    $type[$coll] = 'varchar';
                }
                $coll2[$coll] = $coll;
                @$colls[$coll] = @$colls[$coll] > strlen($value) ? @$colls[$coll] : strlen($value);
            }
        }
        foreach ($colls AS $k => $v) {
            $c[] = " `$k` " . $type[$k] . "(" . $v . ")";
        }
        $sql = "CREATE TABLE IF NOT EXISTS  $table (" . (implode(", ", $c)) . ")";
        $query = $pdo::getInstance()->query($sql);
        foreach ($array as $k => $v) {
            $sql = "INSERT INTO $table (`" . (implode('`, `', $coll2)) . "`) values (";
            foreach ($coll2 as $co) {
                if (empty($v[$co])) {
                     $sql .= " NULL, ";
                } else {
                    $sql .= "'" . str_replace("'", "", $v[$co]) . "', ";
                }
            }
            $sql = substr($sql, 0, -2) . ")";
            $query = $pdo::getInstance()->query($sql);
        }
    }

}
