
<?php
$id_aluno1 = 253216;
$id_turma = $_POST['id_turma'];
$professor = professores::classesDisc(tool::id_pessoa());
if (!empty($professor[$_POST['id_inst']]['nucleoComum'][$_POST['id_turma']])) {
    $disc = $professor[$_POST['id_inst']]['nucleoComum'][$_POST['id_turma']];
} elseif (!empty($professor[$_POST['id_inst']]['disciplinas'][$_POST['id_turma']])) {
    $disc = $professor[$_POST['id_inst']]['disciplinas'][$_POST['id_turma']];
}

$query = $model->db->query("SELECT disc.id_disc, hab.n_competencia_opcoes, hab.id_competencia_opcoes, grupo.n_disc_grupo, hab.peso1, hab.peso2, hab.peso3,  hab.peso4, hab.peso5, hab.peso6, hab.peso7, hab.peso8, hab.peso9, hab.peso10, hab.hab1, hab.hab2, hab.hab3, hab.hab4, hab.hab5, hab.hab6, hab.hab7, hab.hab8, hab.hab9, hab.hab10 "
        . " FROM hab_competencia_opcoes hab"
        . " INNER JOIN hab_disc_grupo_" . date('Y') . " grupo ON hab.fk_id_disc_grupo = grupo.id_disc_grupo"
        . " INNER JOIN ge_turmas tur ON grupo.fk_id_ciclo = tur.fk_id_ciclo"
        . " INNER JOIN ge_disciplinas disc ON grupo.fk_id_disc = disc.id_disc"
        . " WHERE tur.id_turma = " . $id_turma);
$habilidades = $query->fetchAll();

$abrevDisc = disciplina::abrevId();
?>
<style>
    .imgExpande {
        list-style: none;
        padding: 0;
    }
    .imgExpande li {
        display: inline;
    }
    .imgExpande li img {
        width: 100px; /* Aqui é o tamanho da imagem */
        -webkit-transition: 1s all ease; /* É para pega no Chrome e Safira */
        -moz-transition: 1s all ease; /* Firefox */
        -o-transition: 1s all ease; /* Opera */
    }
    .imgExpande li img:hover {
        -moz-transform: scale(2);
        -webkit-transform: scale(2);
        -o-transform: scale(2);
    }

</style>
<pre>
    <?php
    foreach ($_POST as $k => $v) {
        if (is_array($v)) {

            $model->db->ireplace('hab_aluno', $v, 1);
        }
    }
    ?>
</pre>
<div class="fieldTop">

    <?php
    echo $escolas[$id_inst]['turmas'][$id_turma];
    ?> 
</div>

<?php
if (!empty($habilidades)) {
    //seu codigo
    ?>
    <form method="POST">
        <br /><br />
        <table class="table table-bordered table-responsive fieldBorder2" style="margin: 0 auto; width: 100%; background-color: white;">
            <thead>
                <?php
                $alunos_ = turmas::classe($id_turma);
                foreach ($alunos_ as $v) {
                    //capturar os id_pessoa para pegar as notas
                    if ($v['id_pessoa'] == $id_aluno1) {
                        $id_aluno[] = $v['id_pessoa'];
                        $aluno[$v['chamada']] = $v;
                    }
                }

                //pegar notas no banco
                $sql = "SELECT * FROM `hab_aluno` "
                        . " WHERE `fk_id_pessoa` = " . $id_aluno1;
                $query = $model->db->query($sql);
                $array = $query->fetchAll();
                foreach ($array as $v) {
                    $habAluno[$v['fk_id_pessoa']][$v['fk_id_competencia_opcoes']] = $v;
                }
                for ($c = 1; $c <= count($aluno); $c++) {
                    ?>
                    <tr style="background-color: #7E9AA7;" >
                        <td style="width: 100px ;" >
                            <?php
                            if (file_exists(ABSPATH . "/pub/fotos/" . $aluno[$c]['id_pessoa'] . ".jpg")) {
                                ?>
                                <ul class="imgExpande">
                                    <li><a target="_blank"><img src="<?php echo HOME_URI ?>/pub/fotos/<?php echo $aluno[$c]['id_pessoa'] ?>.jpg?ass=<?php echo uniqid() ?>" width="100" height="120" alt="foto"/></a></li>
                                </ul>
                                <?php
                            } else {
                                ?>
                                <img src="<?php echo HOME_URI ?>/pub/fotos/anonimo.png" width="100" height="120" alt="foto"/>
                                <?php
                            }
                            ?>
                        </td>
                        <td colspan="5">
                            <div style="width: 200px">
                                Chamada:
                                <span style="font-size: 20px">
                                    <?php echo $c ?>
                                </span>

                                <br />

                                <?php echo tool::abrevia($aluno[$c]['n_pessoa'], 20) ?>
                                <br /><br />
                                RSE/ID
                                <?php echo $aluno[$c]['id_pessoa'] ?>
                                <br />
                                Situação: 
                                <?php echo $aluno[$c]['situacao'] ?>
                            </div>
                        </td>
                </thead>
                <tbody>
                    <tr style="vertical-align:middle; text-align: center;">
                        <td style="width: 5px; vertical-align:middle;">
                            Adquirida
                        </td>
                        <td style="width: 5px; vertical-align:middle;">
                            Parcialmente Adquirida
                        </td>
                        <td style="width: 5px; vertical-align:middle;">
                            Não Adquirida
                        </td>

                        <td style="vertical-align:middle;">
                            Habilidade
                        </td>

                        <td style="vertical-align:middle;">
                            Competência
                        </td>

                        <td style="vertical-align:middle;">
                            Grupo
                        </td>


                    </tr>
                    <?php
                    if (!empty($escolas[$id_inst]['nucleoComum'][$id_turma])) {



                        foreach ($habilidades as $v) {

                            if (array_key_exists($v['id_disc'], $disc)) { //compara o array habilidades com o array de disciplinas para imprimir apenas as habilidades da matéria do professor
                                for ($i = 1; $i < 10; $i++) {
                                    if (!empty($v['hab' . $i])) {
                                        ?>

                                        <tr>
                                            <td style="text-align: center">
                                                <input type="hidden" name="<?php echo $aluno[$c]['id_pessoa'] . '_' . $v['id_competencia_opcoes'] ?>[id_aluno]" value="<?php echo @$habAluno[$aluno[$c]['id_pessoa']][$v['id_competencia_opcoes']]['id_aluno'] ?>" />
                                                <input type="hidden" name="<?php echo $aluno[$c]['id_pessoa'] . '_' . $v['id_competencia_opcoes'] ?>[fk_id_pessoa]" value="<?php echo $aluno[$c]['id_pessoa'] ?>" />
                                                <input type="hidden" name="<?php echo $aluno[$c]['id_pessoa'] . '_' . $v['id_competencia_opcoes'] ?>[fk_id_competencia_opcoes]" value="<?php echo $v['id_competencia_opcoes'] ?>" />
                                                <input <?php echo @$habAluno[$aluno[$c]['id_pessoa']][$v['id_competencia_opcoes']]['hab' . $i] == 1 ? 'checked' : '' ?> style="cursor:pointer;" type="radio" name="<?php echo $aluno[$c]['id_pessoa'] . '_' . $v['id_competencia_opcoes'] ?>[<?php echo 'hab' . $i ?>]" value="1" />
                                            </td>
                                            <td style="text-align: center">
                                                <input  <?php echo @$habAluno[$aluno[$c]['id_pessoa']][$v['id_competencia_opcoes']]['hab' . $i] == 0.5 ? 'checked' : '' ?> style="cursor:pointer;" type="radio" name="<?php echo $aluno[$c]['id_pessoa'] . '_' . $v['id_competencia_opcoes'] ?>[<?php echo 'hab' . $i ?>]" value="0.5" />
                                            </td>
                                            <td style="text-align: center">

                                                <input <?php echo @$habAluno[$aluno[$c]['id_pessoa']][$v['id_competencia_opcoes']]['hab' . $i] <= 0 ? 'checked' : '' ?> style="cursor:pointer;" type="radio" name="<?php echo $aluno[$c]['id_pessoa'] . '_' . $v['id_competencia_opcoes'] ?>[<?php echo 'hab' . $i ?>]" value="0" />
                                            </td>
                                            <td>
                                                <?php echo $v['hab' . $i] ?> 
                                            </td>
                                            <td>
                                                <?php echo $v['n_competencia_opcoes'] ?> 
                                            </td>
                                            <td>
                                                <?php echo $v['n_disc_grupo'] ?> 
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                        }
                    }
                    if (!empty($escolas[$id_inst]['disciplinas'][$id_turma])) {
                        foreach ($escolas[$id_inst]['disciplinas'][$id_turma] as $kd => $d) {
                            ?>
                        <td style="text-align: center; vertical-align: middle">
                            Não há habilidades cadastrada para sua disciplina nesta turma!
                        </td>

                        <?php
                    }
                }
                ?>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <br /><br />
        <div style="text-align: center">
            <input type="hidden" name="id_turma" value="<?php echo $_POST['id_turma'] ?>" />
            <input type="hidden" name="id_inst" value="<?php echo $_POST['id_inst'] ?>" />
            <button class="btn btn-success">
                Salvar
            </button>
        </div>

        <?php
    } else {
        ?>
        <div style="margin-top: 20px;"> Não há habilidades cadastradas para a sua disciplina nesta turma!</div>
        <?php
    }
    ?>
    <br /><br />
    <pre>
        <?php
        print_r($_POST)
        ?>
    </pre>
    <?php ?>
</form>