<form method="POST">
    <input style="width: 100%;" class="art-button" type="submit" name="telefones" value="Junta Telefone" />  
</form>

<?php
if (!empty($_POST)) {
    if ($_POST['telefones'] == 'Junta Telefone') {

        $sql = "SELECT id_predio, num FROM predio";
        $query = $model->db->query($sql);
        $array = $query->fetchAll();

        if (!empty($array)) {
            foreach ($array as $v) {
                $tel = "SELECT * FROM telefones WHERE fkid = '" . $v['id_predio'] . "'";
                $query = $model->db->query($tel);
                $esc = $query->fetchAll();
                unset($ta);
                if (!empty($esc)) {
                    $n = 0;
                    foreach ($esc as $w) {
                        $ta[] = $w['num'];
                        $n++;
                    }
                }

                for ($x = 0; $x < $n; $x++) {
                    $y = $x + 1;
                    $grava = "UPDATE predio SET tel" . $y . " = '" . $ta[$x] . "'"
                            . " WHERE id_predio = '" . $w['fkid'] . "'";

                    $query = $model->db->query($grava);
                }
            }
        }
    }
}
?>                 