<?php
$ano = date("Y");
$c = filter_input(INPUT_POST, 'c', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$cursos = ng_main::cursos();
?>
<form method="POST">
    <div class="row">
        <div class="col-10">
            <div class="row">
                <?php
                foreach ($cursos as $k => $v) {
                    if (!empty($c)) {
                        $cSet = in_array($k, $c) ? $k : null;
                    } else {
                        $cSet = in_array($k, [1, 5, 9]) ? $k : null;
                    }
                    ?>
                    <div class="col">
                        <?= formErp::checkbox('c[' . $k . ']', $k, $v, $cSet) ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="col-1">
            <?=
            formErp::button('Gerar E-mails')
            ?>
        </div>
    </div>
</form>
<br />
<div class="row">
    <div class="col-6">
        <?php
        if ($c) {
            $model->gerarEmail($c);
        }
        $sql = "SELECT COUNT(campo3) ct, nome_arquivo FROM `ge_controle_email` "
                . " WHERE `nome_arquivo` LIKE '$ano%' "
                . " GROUP BY nome_arquivo "
                . " ORDER BY nome_arquivo DESC";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $k => $v) {
                $array[$k]['nome'] = data::porExtenso($v['nome_arquivo']);
                $array[$k]['ac'] = formErp::submit('Exportar', null, ['nome_arquivo' => $v['nome_arquivo']], HOME_URI . '/cit/pdf/emailsNovos', 1);
            }
            $form['array'] = $array;
            $form['fields'] = [
                'Quantidade' => 'ct',
                'Data' => 'nome',
                '||1' => 'ac'
            ];
        }
        ?>       
    </div>
    <div class="col-6">
        <?php
        if (!empty($form)) {
            report::simple($form);
        }
        ?>
    </div>
</div>
<br />
