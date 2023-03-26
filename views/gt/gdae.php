<div class="fieldBody">
    <form method="POST">
        <!--
        <input type="submit" name="sincronizar" value="Sincronizar" />
        -->
    </form>
    <br /><br />
    <?php
    if (!empty($_POST['sincronizar'])) {
        $sql = "SELECT * FROM ge_aloca_prof ap 
JOIN ge_turmas t on t.id_turma = ap.fk_id_turma 
JOIN ge_ciclos c on c.id_ciclo = t.fk_id_ciclo 
WHERE c.fk_id_curso not in (7,8) 
AND ap.prof2 = 2
";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $sql = "SELECT * FROM `ge_aloca_prof` "
                    . " WHERE `fk_id_turma` =  " . $v['fk_id_turma']
                    . " AND `iddisc` LIKE '" . $v['iddisc'] . "' "
                    . " AND `prof2` = 1 ";
            $query = pdoSis::getInstance()->query($sql);
            $teste = $query->fetchAll(PDO::FETCH_ASSOC);
            if (empty($teste)) {
                   $sql = "INSERT INTO `ge_aloca_prof` (`fk_id_turma`, `iddisc`, `fk_id_inst`, `rm`, `prof2`) VALUES ("
                        . "'" . $V['fk_id_turma'] . "', "
                        . "'" . $V['iddisc'] . "', "
                        . "'" . $V['fk_id_inst'] . "', "
                        . "'" . $V['rm'] . "', "
                        . "'1', "
                        . ");";
                $query = pdoSis::getInstance()->query($sql);
            } else {
               $sql = "update ge_aloca_prof set rm = '" . $v['rm'] . "' "
                        . " WHERE `fk_id_turma` =  " . $v['fk_id_turma']
                        . " AND `iddisc` LIKE '" . $v['iddisc'] . "' "
                        . " AND `prof2` = 1 ";
                $query = pdoSis::getInstance()->query($sql);
            }
        }
        
       $sql = "DELETE ap "
                . " FROM `ge_aloca_prof` ap "
."JOIN ge_turmas t on t.id_turma = ap.fk_id_turma "
."JOIN ge_ciclos c on c.id_ciclo = t.fk_id_ciclo "
."WHERE c.fk_id_curso not in (7,8) "
."AND ap.prof2 = 2"
                . "";
       $query = pdoSis::getInstance()->query($sql);
        //gt_gdaeSet::sincronizar();
    }
    @$metodo = empty($_POST['metodo']) ? $_POST[1]['metodo'] : $_POST['metodo'];
    require_once ABSPATH . '/class/gdae.php';
    $metod = get_class_methods('gdae');
    ?>
    <form name="fm" method="POST">
        <select  name="metodo" onchange="document.fm.submit()">
            <option></option>
            <?php
            foreach ($metod as $v) {
                ?>
                <option <?php echo $v == @$_POST['metodo'] ? 'selected' : '' ?>><?php echo $v ?></option>
                <?php
            }
            ?>

        </select>
        <input type="hidden" name="form" value="1" />
    </form>
    <?php
    if (!empty($_POST['form'])) {
        $mt = $metodo;

        $gdae = new gdaeForm();
        echo $gdae->$mt();
    }
    if (!empty($_POST[1])) {
        $g = new gdae();
        $array = $_POST[1];
        foreach ($array as $v) {
            $ar[] = $v;
        }
        ?>
        <pre>
            <?php
            print_r($ar)
            ?>
        </pre>
        <?php
        $arCont = count($ar);
        if ($arCont == 1) {
            $array = $g->$metodo($ar[0]);
        } elseif ($arCont == 2) {
            $array = $g->$metodo($ar[0], $ar[1]);
        } elseif ($arCont == 3) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2]);
        } elseif ($arCont == 4) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3]);
        } elseif ($arCont == 5) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4]);
        } elseif ($arCont == 6) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5]);
        } elseif ($arCont == 7) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6]);
        } elseif ($arCont == 8) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7]);
        } elseif ($arCont == 9) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8]);
        } elseif ($arCont == 10) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9]);
        } elseif ($arCont == 11) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10]);
        } elseif ($arCont == 12) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11]);
        } elseif ($arCont == 13) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12]);
        } elseif ($arCont == 14) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13]);
        } elseif ($arCont == 15) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14]);
        } elseif ($arCont == 16) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15]);
        } elseif ($arCont == 17) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16]);
        } elseif ($arCont == 18) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17]);
        } elseif ($arCont == 19) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18]);
        } elseif ($arCont == 20) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19]);
        } elseif ($arCont == 21) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20]);
        } elseif ($arCont == 22) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21]);
        } elseif ($arCont == 23) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22]);
        } elseif ($arCont == 24) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23]);
        } elseif ($arCont == 25) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24]);
        } elseif ($arCont == 26) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25]);
        } elseif ($arCont == 27) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26]);
        } elseif ($arCont == 28) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27]);
        } elseif ($arCont == 29) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28]);
        } elseif ($arCont == 30) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29]);
        } elseif ($arCont == 31) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30]);
        } elseif ($arCont == 32) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31]);
        } elseif ($arCont == 33) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32]);
        } elseif ($arCont == 34) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33]);
        } elseif ($arCont == 35) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34]);
        } elseif ($arCont == 36) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35]);
        } elseif ($arCont == 37) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36]);
        } elseif ($arCont == 38) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37]);
        } elseif ($arCont == 39) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38]);
        } elseif ($arCont == 40) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39]);
        } elseif ($arCont == 41) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40]);
        } elseif ($arCont == 42) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41]);
        } elseif ($arCont == 43) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42]);
        } elseif ($arCont == 44) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43]);
        } elseif ($arCont == 45) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44]);
        } elseif ($arCont == 46) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45]);
        } elseif ($arCont == 47) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46]);
        } elseif ($arCont == 48) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47]);
        } elseif ($arCont == 49) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48]);
        } elseif ($arCont == 50) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49]);
        } elseif ($arCont == 51) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50]);
        } elseif ($arCont == 52) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51]);
        } elseif ($arCont == 53) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52]);
        } elseif ($arCont == 54) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53]);
        } elseif ($arCont == 55) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54]);
        } elseif ($arCont == 56) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55]);
        } elseif ($arCont == 57) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56]);
        } elseif ($arCont == 58) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57]);
        } elseif ($arCont == 59) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58]);
        } elseif ($arCont == 60) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59]);
        } elseif ($arCont == 61) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60]);
        } elseif ($arCont == 62) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61]);
        } elseif ($arCont == 63) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62]);
        } elseif ($arCont == 64) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63]);
        } elseif ($arCont == 65) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64]);
        } elseif ($arCont == 66) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65]);
        } elseif ($arCont == 67) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66]);
        } elseif ($arCont == 68) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67]);
        } elseif ($arCont == 69) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68]);
        } elseif ($arCont == 70) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69]);
        } elseif ($arCont == 71) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70]);
        } elseif ($arCont == 72) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71]);
        } elseif ($arCont == 73) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72]);
        } elseif ($arCont == 74) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73]);
        } elseif ($arCont == 75) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74]);
        } elseif ($arCont == 76) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75]);
        } elseif ($arCont == 77) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76]);
        } elseif ($arCont == 78) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77]);
        } elseif ($arCont == 79) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78]);
        } elseif ($arCont == 80) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79]);
        } elseif ($arCont == 81) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80]);
        } elseif ($arCont == 82) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80], $ar[81]);
        } elseif ($arCont == 83) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80], $ar[81], $ar[82]);
        } elseif ($arCont == 84) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80], $ar[81], $ar[82], $ar[83]);
        } elseif ($arCont == 85) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80], $ar[81], $ar[82], $ar[83], $ar[84]);
        } elseif ($arCont == 86) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80], $ar[81], $ar[82], $ar[83], $ar[84], $ar[85]);
        } elseif ($arCont == 87) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80], $ar[81], $ar[82], $ar[83], $ar[84], $ar[85], $ar[86]);
        } elseif ($arCont == 88) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80], $ar[81], $ar[82], $ar[83], $ar[84], $ar[85], $ar[86], $ar[87]);
        } elseif ($arCont == 89) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80], $ar[81], $ar[82], $ar[83], $ar[84], $ar[85], $ar[86], $ar[87], $ar[88]);
        } elseif ($arCont == 90) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80], $ar[81], $ar[82], $ar[83], $ar[84], $ar[85], $ar[86], $ar[87], $ar[88], $ar[89]);
        } elseif ($arCont == 91) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80], $ar[81], $ar[82], $ar[83], $ar[84], $ar[85], $ar[86], $ar[87], $ar[88], $ar[89], $ar[90]);
        } elseif ($arCont == 92) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80], $ar[81], $ar[82], $ar[83], $ar[84], $ar[85], $ar[86], $ar[87], $ar[88], $ar[89], $ar[90], $ar[91]);
        } elseif ($arCont == 93) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80], $ar[81], $ar[82], $ar[83], $ar[84], $ar[85], $ar[86], $ar[87], $ar[88], $ar[89], $ar[90], $ar[91], $ar[92]);
        } elseif ($arCont == 94) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80], $ar[81], $ar[82], $ar[83], $ar[84], $ar[85], $ar[86], $ar[87], $ar[88], $ar[89], $ar[90], $ar[91], $ar[92], $ar[93]);
        } elseif ($arCont == 95) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80], $ar[81], $ar[82], $ar[83], $ar[84], $ar[85], $ar[86], $ar[87], $ar[88], $ar[89], $ar[90], $ar[91], $ar[92], $ar[93], $ar[94]);
        } elseif ($arCont == 96) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80], $ar[81], $ar[82], $ar[83], $ar[84], $ar[85], $ar[86], $ar[87], $ar[88], $ar[89], $ar[90], $ar[91], $ar[92], $ar[93], $ar[94], $ar[95]);
        } elseif ($arCont == 97) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80], $ar[81], $ar[82], $ar[83], $ar[84], $ar[85], $ar[86], $ar[87], $ar[88], $ar[89], $ar[90], $ar[91], $ar[92], $ar[93], $ar[94], $ar[95], $ar[96]);
        } elseif ($arCont == 98) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80], $ar[81], $ar[82], $ar[83], $ar[84], $ar[85], $ar[86], $ar[87], $ar[88], $ar[89], $ar[90], $ar[91], $ar[92], $ar[93], $ar[94], $ar[95], $ar[96], $ar[97]);
        } elseif ($arCont == 99) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80], $ar[81], $ar[82], $ar[83], $ar[84], $ar[85], $ar[86], $ar[87], $ar[88], $ar[89], $ar[90], $ar[91], $ar[92], $ar[93], $ar[94], $ar[95], $ar[96], $ar[97], $ar[98]);
        } elseif ($arCont == 100) {
            $array = $g->$metodo($ar[0], $ar[1], $ar[2], $ar[3], $ar[4], $ar[5], $ar[6], $ar[7], $ar[8], $ar[9], $ar[10], $ar[11], $ar[12], $ar[13], $ar[14], $ar[15], $ar[16], $ar[17], $ar[18], $ar[19], $ar[20], $ar[21], $ar[22], $ar[23], $ar[24], $ar[25], $ar[26], $ar[27], $ar[28], $ar[29], $ar[30], $ar[31], $ar[32], $ar[33], $ar[34], $ar[35], $ar[36], $ar[37], $ar[38], $ar[39], $ar[40], $ar[41], $ar[42], $ar[43], $ar[44], $ar[45], $ar[46], $ar[47], $ar[48], $ar[49], $ar[50], $ar[51], $ar[52], $ar[53], $ar[54], $ar[55], $ar[56], $ar[57], $ar[58], $ar[59], $ar[60], $ar[61], $ar[62], $ar[63], $ar[64], $ar[65], $ar[66], $ar[67], $ar[68], $ar[69], $ar[70], $ar[71], $ar[72], $ar[73], $ar[74], $ar[75], $ar[76], $ar[77], $ar[78], $ar[79], $ar[80], $ar[81], $ar[82], $ar[83], $ar[84], $ar[85], $ar[86], $ar[87], $ar[88], $ar[89], $ar[90], $ar[91], $ar[92], $ar[93], $ar[94], $ar[95], $ar[96], $ar[97], $ar[98], $ar[99]);
        }
        ?>
        <pre>
            <?php
            print_r($array)
            ?>
        </pre>
        <?php
    }
    /**
      for ($c = 1; $c <= 100; $c++) {
      $arg=NULL;
      for ($i = 0; $i < $c; $i++) {
      $arg[]= '$ar['.$i.']' ;
      }
      echo ' elseif ($arCont == ' . ($c) . ') {<br />';
      echo '$array = $g->$metodo(';
      echo implode(', ', $arg);
      echo ');';
      echo '<br />}';
      }
     * 
     */
    ?>
</div>
