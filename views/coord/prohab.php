<div class="fieldBody">
    <?php
    if (!empty($id_pessoa)) {
        if ($escolas = professores::classesDisc($id_pessoa)) { //está configurado no constructor
            foreach ($escolas as $id_inst => $turma) {
                ?>
                <br />
                <div class="fieldBorder2" role="alert" style="text-align: center; font-weight: bold; font-size: 16px; padding: 25px; width: 80%; margin: 0 auto">
                    <?php echo $turma['escola'] ?>
                    <br /><br />
                    <div class="row">
                        <?php
                        foreach ($turma['disciplinas'] as $id_turma => $t) {
                            ?>
                            <div class="panel panel-default">
                                <div class="panel panel-heading">
                                    <?php echo $escolas[$id_inst]['turmas'][$id_turma] ?>
                                </div>
                                <div class="panel panel-body">
                                    <table style="width: 100%">
                                        <tr>
                                            <?php
                                            foreach ($t as $id_disc => $n_disc) {
                                                ?>
                                                <td style="text-align: center; width: <?php echo 100 / count($t) ?>%; padding: 10px">
                                                    <button onclick="setar('<?php echo $id_pessoa ?>', '<?php echo $id_inst ?>', '<?php echo $id_turma ?>', '<?php echo $id_disc ?>')" id="lancamento" class="btn btn-info" style="width: 100%">
                                                        <?php echo $n_disc ?>
                                                    </button>
                                                </td>

                                                <?php
                                            }
                                            ?>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <?php
                        }
                        ?>

                    </div>
                </div>
                <?php
            }
        }
    }
    ?>
    <script>
        function setar(id_pessoa, id_inst, id_turma, id_disc) {
            $('#lancc').load('<?php echo HOME_URI ?>/coord/prolanc?id_pessoa=' + id_pessoa + '&id_inst=' + id_inst + '&id_turma=' + id_turma + '&id_disc=' + id_disc);

        }
    </script>
    <div id="lancc">
       
    </div>
</div>