<?php
$id_turma = $_POST['id_turma'];
$id_pessoa = $_POST['id_pessoa'];
$aluno = new aluno($id_pessoa);
$aluno->vidaEscolar();
$professor = professores::classesDisc(tool::id_pessoa());
foreach ($professor as $v) {
    if (!empty($v['nucleoComum'][$_POST['id_turma']])) {
        $disc = $professor[$_POST['id_inst']]['nucleoComum'][$_POST['id_turma']];
    } elseif (!empty($v['disciplinas'][$_POST['id_turma']])) {
        $disc = $v['disciplinas'][$_POST['id_turma']];
    }
}

$sql = "SELECT disc.id_disc, hab.n_competencia_opcoes, hab.id_competencia_opcoes, grupo.n_disc_grupo, hab.peso1, hab.peso2, hab.peso3,  hab.peso4, hab.peso5, hab.peso6, hab.peso7, hab.peso8, hab.peso9, hab.peso10, hab.hab1, hab.hab2, hab.hab3, hab.hab4, hab.hab5, hab.hab6, hab.hab7, hab.hab8, hab.hab9, hab.hab10 "
        . " FROM hab_competencia_opcoes hab"
        . " INNER JOIN hab_disc_grupo grupo ON hab.fk_id_disc_grupo = grupo.id_disc_grupo"
        . " INNER JOIN ge_turmas tur ON grupo.fk_id_ciclo = tur.fk_id_ciclo"
        . " INNER JOIN ge_disciplinas disc ON grupo.fk_id_disc = disc.id_disc"
        . " WHERE tur.id_turma = " . $id_turma;
$query = $model->db->query($sql);
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

<div class="fieldTop">
    Habilidades
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
                //pegar notas no banco
                $sql = "SELECT * FROM `hab_aluno` "
                        . " WHERE hab_aluno.fk_id_pessoa = " . $id_pessoa;
                //echo $sql;
                $query = $model->db->query($sql);
                $array = $query->fetchAll();
                foreach ($array as $v) {
                    $hab_=sql::columns('hab_competencia_opcoes');
                    foreach ($hab_ as $h){
                        if(substr($h, 0, 3)=='hab'){
                              $habAluno[$v['fk_id_competencia_opcoes']][substr($h, 0, 3)] = $v[$h];
                        }
                    }
                    
                }
                ?>
                <tr style="background-color: #7E9AA7;" >
                    <td style="width: 100px ;" >
                        <?php
                        if (file_exists(ABSPATH . "/pub/fotos/" . $id_pessoa . ".jpg")) {
                            ?>
                            <ul class="imgExpande">
                                <li><a target="_blank"><img src="<?php echo HOME_URI ?>/pub/fotos/<?php echo $id_pessoa ?>.jpg" width="100" height="120" alt="foto"/></a></li>
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
                                <?php echo $aluno->_chamada ?>
                            </span>

                            <br />

                            <?php echo tool::abrevia($aluno->_nome, 20) ?>
                            <br /><br />
                            RSE/ID
                            <?php echo $id_pessoa ?>
                            <br />
                            Situação: 
                            <?php echo $aluno->_situacaoAtual ?>
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
                //if (!empty($escolas[$id_inst]['nucleoComum'][$id_turma])) {



                foreach ($habilidades as $j) {

                    if (array_key_exists($j['id_disc'], $disc)) { //compara o array habilidades com o array de disciplinas para imprimir apenas as habilidades da matéria do professor
                        for ($i = 1; $i < 10; $i++) {
                            if (!empty($j['hab' . $i])) {
                                $idh = "1[" . $j['id_competencia_opcoes'] . "][hab][$i]";
                                ?>

                                <tr>
                                    <td id="<?php echo $idh ?>t1" style="cursor: pointer; background-color: <?php echo @$habAluno[$j['id_competencia_opcoes']][$i] == 1 ? '#3366FF' : '#FFFFFF' ?>" onclick="sethab('<?php echo $idh ?>',1)">
                                        <input type="hidden" name="1[<?php echo $j['id_competencia_opcoes'] ?>][fk_id_competencia_opcoes]" value="<?php echo $j['id_competencia_opcoes'] ?>" />
                                    </td>
                                    <td id="<?php echo $idh ?>t2" style="cursor: pointer; background-color: <?php echo @$habAluno[$j['id_competencia_opcoes']][$i] == 0.5 ? '#33ff33' : '#FFFFFF' ?>"  onclick="sethab('<?php echo $idh ?>',2)">
                                    </td>
                                    <td id="<?php echo $idh ?>t3" style="cursor: pointer; background-color: <?php echo @$habAluno[$j['id_competencia_opcoes']][$i] == 0 ? '#FFFF00' : '#FFFFFF' ?>"  onclick="sethab('<?php echo $idh ?>',3)">
                                        <input id="<?php echo $idh ?>h" type="hidden" name="<?php echo $idh ?>" value="<?php echo @$habAluno[$j['id_competencia_opcoes']][$i] ?>" />
                                    </td>
                                    <td>
                                        <?php echo $j['hab' . $i] ?> 
                                    </td>
                                    <td>
                                        <?php echo $j['n_competencia_opcoes'] ?> 
                                    </td>
                                    <td>
                                        <?php echo $j['n_disc_grupo'] ?> 
                                    </td>
                                </tr>
                                <?php
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
            ?>
            </tbody>
        </table>
        <br /><br />
        <div style="text-align: center">
            <?php echo DB::hiddenKey('inserirHab') ?>
            <input type="hidden" name="id_pessoa" value="<?php echo @$_POST['id_pessoa'] ?>" />
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


</form>
<script>
function sethab(hab, qs){
    if(qs==1){
      document.getElementById(hab+'t1').style.backgroundColor = '#3366FF';
      document.getElementById(hab+'t2').style.backgroundColor = '#FFFFFF';
      document.getElementById(hab+'t3').style.backgroundColor = '#FFFFFF';
      document.getElementById(hab+'h').value = '1';
    }else if(qs==2){
      document.getElementById(hab+'t1').style.backgroundColor = '#FFFFFF';
      document.getElementById(hab+'t2').style.backgroundColor = '#33ff33';
      document.getElementById(hab+'t3').style.backgroundColor = '#FFFFFF';
       document.getElementById(hab+'h').value = '0.5';
   }else if(qs==3){
      document.getElementById(hab+'t1').style.backgroundColor = '#FFFFFF';
      document.getElementById(hab+'t2').style.backgroundColor = '#FFFFFF';
      document.getElementById(hab+'t3').style.backgroundColor = '#FFFF00';
       document.getElementById(hab+'h').value = '0';
   }
}
  
</script>  
