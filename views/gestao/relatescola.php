
<div class="fieldBody">
    <br /><br />
    <div class="row">

        <div class="col-md-12 text-center">
            <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listaspe" name="spe" method="POST">
                <button style="width: 400px;  margin: 0 auto" type="submit" class="art-button">
                    Relação de Alunos Sem Paternidade Estabelecida
                </button>
            </form>  
        </div>
        <br /><br /><br />
        <div class="col-md-12 text-center">
            <form target="_blank" action="<?php echo HOME_URI ?>/gestao/lista_aluno_apd" name="apd" method="POST">
                <button style="width: 400px;  margin: 0 auto" type="submit" class="art-button">
                    Alunos APD
                </button>
            </form>  
        </div>
        <br /><br /><br />
        <div class="col-md-12 text-center">
            <form target="_blank" action="<?php echo HOME_URI ?>/gestao/lista_nao_frequente" name="transf" method="POST">
                <button style="width: 400px;  margin: 0 auto" type="submit" class="art-button">
                    Alunos Transferidos
                </button>
            </form>  
        </div>
        <br /><br /><br />
        <div class="col-md-12 text-center" style="display: none">
            <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listagemgeral" name="escola" method="POST">
                <button style="width: 400px;  margin: 0 auto" type="submit" class="art-button">
                    Listagem Geral de Alunos
                </button>
            </form>  
        </div>

        <div class="col-md-12 text-center">
            <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                <?php
                $sql = "SELECT"
                        . " p.id_pessoa AS RSE, p.ra AS RA, ra_dig AS Digito_RA, ta.chamada AS Chamada, p.n_pessoa AS Nome_Aluno,"
                        . " p.sus AS SUS, t.codigo AS Cod_Classe, p.rg AS RG, e.logradouro_gdae AS Endereço, "
                        . " e.num_gdae AS NR, e.complemento AS Compl, e.cep AS CEP, e.bairro AS Bairro, e.cidade AS Cidade, p.tel1 AS Tel1,"
                        . " p.tel2 AS Tel2,p.tel3 AS Tel3, p.email AS Email, p.mae AS Mãe, p.pai AS Pai, p.cidade_nasc AS Cid_Nasc,"
                        . " p.sexo AS Sexo, p.dt_nasc AS Dt_Nasc, p.emailgoogle AS Email_Google FROM pessoa p"
                        . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa"
                        . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma"
                        . " LEFT JOIN endereco e on e.fk_id_pessoa = p.id_pessoa"
                        . " JOIN ge_periodo_letivo le on le.id_pl = t.fk_id_pl"
                        . " Where t.fk_id_inst=" . tool::id_inst() . " "
                        . " AND le.at_pl = 1 "
                        . " AND ta.situacao = 'Frequente' "
                        . " ORDER BY t.codigo, ta.chamada";
                ?>
                <input type="hidden" name="tokenSql" value="<?php echo tool::tokenSql() ?>" />
                <input type="hidden" name="sql" value="<?php echo $sql ?>" />
                <button style="width: 400px" type="submit" class="art-button">
                    Listagem Geral de Alunos (Excel)
                </button>
            </form>   
        </div>
        <br /><br /><br />
        <div class="col-md-12 text-center">
            Listagem de Alunos por Classe (Excel)
        </div> 
        <br /><br /><br />
        <div class="col-md-12 text-center">
            <div class="row">
                <?php
                foreach (turmas::option() as $k => $v) {
                    ?>
                    <div class="col-md-3" style="padding: 5px">
                        <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                            <?php
                            $hj = date('Y-m-d');
                            $sql = "SELECT"
                                    . " p.id_pessoa AS RSE, p.ra AS RA, ra_dig AS Digito_RA, ta.chamada AS Chamada, p.n_pessoa AS Nome_Aluno,"
                                    . " p.sus AS SUS, t.codigo AS Cod_Classe, p.rg AS RG, e.logradouro_gdae AS Endereço, "
                                    . " e.num_gdae AS NR, e.complemento AS Compl, e.cep AS CEP, e.bairro AS Bairro, e.cidade AS Cidade, p.tel1 AS Tel1,"
                                    . " p.tel2 AS Tel2, p.email AS Email, p.mae AS Mãe, p.pai AS Pai, p.cidade_nasc AS Cid_Nasc,"
                                    . " p.sexo AS Sexo, p.dt_nasc AS Dt_Nasc, p.emailgoogle AS Email_Google,"
                                    . " TIMESTAMPDIFF(YEAR, p.dt_nasc, '$hj') AS Idade FROM pessoa p"
                                    . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa"
                                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma"
                                    . " LEFT JOIN endereco e on e.fk_id_pessoa = p.id_pessoa"
                                    . " Where ta.situacao = 'Frequente'  AND t.id_turma = " . $k . " ORDER BY ta.chamada";
                            ?>
                            <input type="hidden" name="tokenSql" value="<?php echo tool::tokenSql() ?>" />
                            <input type="hidden" name="sql" value="<?php echo $sql ?>" />
                            <button style="width: 100px" type="submit" class="art-button">
                                <?php echo $v ?>
                            </button>
                        </form>               
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>

</div>
