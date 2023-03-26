<div class="fieldBody">
    <div class="fieldTop">
        Relatórios
    </div>
    <!--
    <br /><br />
    <div class="fieldBorder2">
        <form target="_blank" action="<?php echo HOME_URI ?>/dtgp/listProfDisc" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <?php
                    formulario::select('id_inst', escolas::idInst(), 'Escola', null, null, null, 'required');
                    ?>
                </div>
                <div class="col-md-6">
                    <input class="btn btn-info" type="submit" value="Listagem por Disciplina com dados do CADAMPE" />
                </div>
            </div>
        </form>
    </div>
    -->
    <br /><br />
    <form action="<?php echo HOME_URI ?>/dtgp/relate" method="POST">
        <div style="text-align: center">
            <input type="hidden" name="teaSecr" value="1" />
            <input class="btn btn-success" type="submit" value="Listagem por Disciplina com dados do CADAMPE (Visão da Escola)" />
        </div>
    </form>
    <br /><br />
    <form target="_blank" action="<?php echo HOME_URI ?>/dtgp/tea" method="POST">
        <div style="text-align: center">
            <input class="btn btn-success" type="submit" value="TEA" />
        </div>
    </form>
    <br /><br />
    <form target="_blank" action="<?php echo HOME_URI ?>/dtgp/cons" method="POST">
        <div style="text-align: center">
            <input class="btn btn-success" type="submit" value="QUADRO POR ESCOLA" />
        </div>
    </form>
    <br /><br />
    <form target="_blank" action="<?php echo HOME_URI ?>/dtgp/consu" method="POST">
        <div style="text-align: center">
            <input class="btn btn-success" type="submit" value="Usado = 0" />
        </div>
    </form>
    <br /><br />
    <form target="_blank" action="<?php echo HOME_URI ?>/dtgp/constt" method="POST">
        <div style="text-align: center">
            <input class="btn btn-success" type="submit" value="QUADRO POR ESCOLA CONSOLIDADO" />
        </div>
    </form>
    <br /><br />
    <form target="_blank" action="<?php echo HOME_URI ?>/dtgp/lista" method="POST">
        <div style="text-align: center">
            <input class="btn btn-success" type="submit" value="Lista com Classificação" />
        </div>
    </form>
    <br /><br />
    <div class="fieldBorder2">
        <div class="text-center">
            Exportação de base em excel (excel 97)
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-3 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                    <input type="hidden" name="tokenSql" value="<?php echo substr((date("yhdm") / 3.5288 * 68), 0, 20) ?>" />
                    <?php
                    $sql = "SELECT *, c.ativo as situacao FROM dtgp_cadampe c join dtpg_seletivas s on s.id_sel = c.fk_id_sel join banco b on b.id_ban = c.banco "
                    ?>
                    <input type="hidden" name="sql" value="<?php echo $sql ?>" />
                    <input class="btn btn-success" type="submit" value="Tabela Principal" />
                </form> 
            </div>
            <div class="col-md-3 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                    <input type="hidden" name="tokenSql" value="<?php echo substr((date("yhdm") / 3.5288 * 68), 0, 20) ?>" />
                    <?php
                    $sql = "SELECT * FROM `dtgp_cadampe_cargo` ORDER BY `dtgp_cadampe_cargo`.`ordem` ASC "
                    ?>
                    <input type="hidden" name="sql" value="<?php echo $sql ?>" />
                    <input class="btn btn-success" type="submit" value="Cargos" />
                </form> 
            </div>
            <div class="col-md-3 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                    <input type="hidden" name="tokenSql" value="<?php echo substr((date("yhdm") / 3.5288 * 68), 0, 20) ?>" />
                    <?php
                    $sql = "SELECT * FROM `dtpg_class` "
                    ?>
                    <input type="hidden" name="sql" value="<?php echo $sql ?>" />
                    <input class="btn btn-success" type="submit" value="classificação/inscrição/cargo" />
                </form> 
            </div>
            <div class="col-md-3 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                    <input type="hidden" name="tokenSql" value="<?php echo substr((date("yhdm") / 3.5288 * 68), 0, 20) ?>" />
                    <?php
                    $sql = "SELECT `fk_id_inscr`, `n_insc`, f.cpf , FUNCAO, `SUB_SECAO_TRABALHO` FROM dtgp_cadampe s join funcionarios f on f.cpf = s.cpf where f.funcao not like '%prof%'  "
                    ?>
                    <input type="hidden" name="sql" value="<?php echo $sql ?>" />
                    <input class="btn btn-success" type="submit" value="Cadastrados que Não são Professores " />
                </form> 
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-3 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                    <input type="hidden" name="tokenSql" value="<?php echo substr((date("yhdm") / 3.5288 * 68), 0, 20) ?>" />
                    <?php
                     $sql = "SELECT "
                    . " c.cad_pmb as N_PMB, dt_cad as DT_CAD, 	c.n_insc as NOME, 	rg as RG, "
                    . " cpf as CPF, pis as PIS, concat(tel1,';',tel2,';',tel3) as TELEFONES, "
                    . " b.n_ban, c.agencia, c.cc "
                    . " FROM dtgp_cadampe c "
                    . " join dtpg_seletivas s on s.id_sel = c.fk_id_sel "
                    . " join banco b on b.id_ban = c.banco "
                    . " where c.ativo = 1 "
                    . " order by c.n_insc "
                    ?>
                    <input type="hidden" name="sql" value="<?php echo $sql ?>" />
                    <input class="btn btn-success" type="submit" value="Quania" />
                </form> 
            </div>
        </div>
        <br /><br />
    </div>
</div>