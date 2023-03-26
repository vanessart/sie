<div class="field">
    <form method="POST">
        Sistema:
        <?php
        echo $model->selectSistemas('fk_id_sistema', 0);
        ?>
        &nbsp;&nbsp;&nbsp;&nbsp;
        Instância:
        <?php
        echo $model->selectInst('fk_id_inst');
        ?>       
        <input type="hidden" name="search" value="<?php echo $_POST['search'] ?>" />
        <input name="buscar" type="submit" value="Buscar" />
    </form>
    <br />
    <?php
    if (!empty($_POST['buscar'])) {
        $dados = $model->listNivel($form['id_pessoa'], $_POST['fk_id_sistema'], $_POST['fk_id_inst']);
        ?>
        <script>
            function atN(idnv) {
                if (confirm("Ativar Nível?")) {
                    document.getElementById("idn").value = idnv;
                    document.nva.submit();
                }
            }
            function desN(idnv) {
                if (confirm("Desativar Nível?")) {
                    document.getElementById("ida").value = idnv;
                    document.nvd.submit();
                }
            }
        </script>
        <form name="nva" method="POST">
            <?php
            $f = [
                '1[fk_id_pessoa]' => $dados[0]['fk_id_pessoa'],
                '1[fk_id_sistema]' => $dados[0]['fk_id_sistema'],
                '1[fk_id_inst]' => $dados[0]['fk_id_inst'],
                'fk_id_sistema' => $dados[0]['fk_id_sistema'],
                'fk_id_inst' => $dados[0]['fk_id_inst'],
            ];
            echo formOld::hidden($f);
            echo $model->db->hiddenKey('acesso', 'replace');
            ?>
            <input id="idn" type="hidden" name="1[fk_id_nivel]" value="" />
            <input type="hidden" name="search" value="<?php echo $_POST['search'] ?>" />
            <input type="hidden" name="buscar" value="1" />
        </form>

        <form name="nvd" method="POST">
            <?php
            $f = [
                'fk_id_sistema' => $dados[0]['fk_id_sistema'],
                'fk_id_inst' => $dados[0]['fk_id_inst'],
            ];
            echo formOld::hidden($f);
            echo $model->db->hiddenKey('acesso', 'delete');
            ?>
            <input id="ida" type="hidden" name="1[id_acesso]" value="" />
            <input type="hidden" name="buscar" value="1" />
            <input type="hidden" name="search" value="<?php echo $_POST['search'] ?>" />
        </form>

        <?php
        foreach ($dados as $v) {
            if (empty($v['id_acesso'])) {
                $botao = 'n';
                $at = "Desativado";
                $valor = $v['fk_id_nivel'];
                $funcao = "atN";
            } else {
                $botao = 'y';
                $at = "Ativado";
                $valor = $v['id_acesso'];
                $funcao = "desN";
            }
            ?>
            <a href="#" onclick="<?php echo $funcao ?>('<?php echo $valor ?>')">
                <table>
                    <tr>
                        <td>
                            <img src="<?php echo HOME_URI ?>/views/_images/<?php echo $botao ?>.png" width="38" alt="Botão"/>
                        </td>
                        <td style="padding-left: 20px">
                            <?php echo $v['n_nivel'] . ' (' . $at . ')' ?>
                        </td>
                    </tr>
                </table>
            </a>    
            <br />
            <?php
        }
    }
    ?>
</div>
