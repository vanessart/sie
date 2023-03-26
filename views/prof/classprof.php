<?php
$ano = date('Y');
?>

<div class="fieldBody">

    <div class="row">
        <div class="col-md-6">
            <?php
            $escClass = professores::classes(tool::id_pessoa());
            if ($_SESSION['userdata']['id_nivel'] == 24) {
                $options = $escClass['classesEscolas'];
            } else {
                $options = turmas::option();
            }

            formulario::select('id_turma', $options, 'Classe', @$_POST['id_turma'], 1);
            ?>
        </div>
    </div>
    <?php
    if (!empty($_POST['id_turma'])) {
        ?>
        <br /><br />
        <div class="row fieldWhite">
            <div class="col-md-6">
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listachamada"  method="POST">
                    <input type="hidden" name="sel[]" value="<?php echo @$_POST['id_turma'] ?>" />
                    <input type="hidden" name="id_inst" value="<?php echo @$escClass['inst'][$_POST['id_turma']] ?>" />
                    <div class="input-group">
                        <span class="input-group-addon">
                            Para imprimir lista chamada Selecione mês desejado   
                        </span>
                        <span class="input-group-addon">
                            <select required class="control-group" name="mes">
                                <option></option>
                                <?php
                                foreach (data::meses() as $k => $v) {
                                    ?>
                                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </span>
                    </div>
            </div>
            <div class="col-md-3">
                <button name="lchamada" value="Lista Chamada"  style="width: 300px" type="submit" class="btn btn-success" id="btnlc">
                    Lista de Chamada
                </button>
            </div>
            </form> 

        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-12 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listapiloto" id="listpiloto" method="POST">
                    <input type="hidden" name="sel[]" value="<?php echo @$_POST['id_turma'] ?>" />
                    <input type="hidden" name="id_inst" value="<?php echo @$escClass['inst'][$_POST['id_turma']] ?>" />
                    <button name="piloto" value="Lista Piloto" style="width: 300px" type="submit" class="btn btn-success" >
                        Lista Piloto
                    </button>
                </form> 
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-12 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listapresenca" id="presenca" method="POST">
                    <input type="hidden" name="sel[]" value="<?php echo @$_POST['id_turma'] ?>" />
                    <input type="hidden" name="id_inst" value="<?php echo @$escClass['inst'][$_POST['id_turma']] ?>" />

                    <button name="presenca" value="Lista Presença" style="width: 300px" type="submit" class="btn btn-success">
                        Lista de Presença
                    </button>
                </form> 
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-12 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listaparede" id="parede" method="POST">
                    <input type="hidden" name="sel[]" value="<?php echo @$_POST['id_turma'] ?>" />
                    <input type="hidden" name="id_inst" value="<?php echo @$escClass['inst'][$_POST['id_turma']] ?>" />

                    <button name="parede" value="Lista Parede" style="width: 300px" type="submit" class="btn btn-success">
                        Lista Piloto - Parede
                    </button>
                </form> 
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-12 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listaidadeano" id="idadeano" method="POST">
                    <input type="hidden" name="sel[]" value="<?php echo @$_POST['id_turma'] ?>" />

                    <input type="hidden" name="id_inst" value="<?php echo @$escClass['inst'][$_POST['id_turma']] ?>" />

                    <button name="idadeano" value="Lista Idade" style="width: 300px" type="submit" class="btn btn-success">
                        Lista Idade/Ano Escolar
                    </button>
                </form> 
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-12 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                    <?php
                    $sql = "Select "
                            . " id_pessoa as RSE, chamada as Chamada, pessoa.n_pessoa Nome_Aluno, codigo_classe,  "
                            . "  tel1, tel2, email, "
                            . "mae, pai, sexo,dt_nasc "
                            . "from pessoa"
                            . " Join ge_turma_aluno on ge_turma_aluno.fk_id_pessoa = pessoa.id_pessoa"
                            . " Join endereco on endereco.fk_id_pessoa = pessoa.id_pessoa"
                            . " Where fk_id_turma = " . @$_POST['id_turma']
                            . " order by codigo_classe, chamada";
                    ?>
                    <input type="hidden" name="tokenSql" value="<?php echo tool::tokenSql() ?>" />
                    <input type="hidden" name="sql" value="<?php echo $sql ?>" />
                    <button style="width: 300px" type="submit" class="btn btn-success">
                        Listagem Geral de Alunos (Excel)
                    </button>
                </form>   
            </div>
        </div>

    </div>
    <?php
}
?>
