<div class="fieldBody">
    <div class="fieldBorder2">
        <div class="text-center">
            Relatórios
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-5 text-center">
                <form action="<?php echo HOME_URI ?>/cadam/relate" method="POST">
                    <div style="text-align: center">
                        <input type="hidden" name="teaSecr" value="1" />
                        <input class="btn btn-success" type="submit" value="Listagem por Disciplina com dados do CADAMPE (Visão da Escola)" />
                    </div>
                </form>  
            </div>  
            <div class="col-md-2 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/cadam/tea" method="POST">
                    <div style="text-align: center">
                        <input class="btn btn-success" type="submit" value="TEA" />
                    </div>
                </form>     
            </div>  
            <div class="col-md-2 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/cadam/cons" method="POST">
                    <div style="text-align: center">
                        <input class="btn btn-success" type="submit" value="QUADRO POR ESCOLA" />
                    </div>
                </form>          
            </div>  
            <div class="col-md-3 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/cadam/constt" method="POST">
                    <div style="text-align: center">
                        <input class="btn btn-success" type="submit" value="QUADRO POR ESCOLA CONSOLIDADO" />
                    </div>
                </form>        
            </div>  
        </div>
        <br /><br />
    </div>  
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
                    $sql = "SELECT *, c.ativo as situacao FROM cadam_cadastro c "
                            . " join pessoa p on p.id_pessoa = c.fk_id_pessoa "
                            . "join dtpg_seletivas s on s.id_sel = c.fk_id_sel "
                            . "left join banco b on b.id_ban = c.banco "
                    ?>
                    <input type="hidden" name="sql" value="<?php echo $sql ?>" />
                    <input style="width: 90%; margin: 0 auto" class="btn btn-success" type="submit" value="Tabela Principal" />
                </form> 
            </div>
            <div class="col-md-3 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                    <input type="hidden" name="tokenSql" value="<?php echo substr((date("yhdm") / 3.5288 * 68), 0, 20) ?>" />
                    <?php
                    $sql = "SELECT * FROM `cadam_cargo` ORDER BY `ordem` ASC "
                    ?>
                    <input type="hidden" name="sql" value="<?php echo $sql ?>" />
                    <input style="width: 90%; margin: 0 auto" class="btn btn-success" type="submit" value="Cargos" />
                </form> 
            </div>
            <div class="col-md-3 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                    <input type="hidden" name="tokenSql" value="<?php echo substr((date("yhdm") / 3.5288 * 68), 0, 20) ?>" />
                    <?php
                    $sql = "SELECT * FROM `cadam_class` "
                    ?>
                    <input type="hidden" name="sql" value="<?php echo $sql ?>" />
                    <input style="width: 90%; margin: 0 auto" class="btn btn-success" type="submit" value="classificação/inscrição/cargo" />
                </form> 
            </div>
            <div class="col-md-3 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                    <input type="hidden" name="tokenSql" value="<?php echo substr((date("yhdm") / 3.5288 * 68), 0, 20) ?>" />
                    <?php
                    $sql = "SELECT "
                            . " c.cad_pmb as N_PMB, dt_cad as DT_CAD, 	p.n_pessoa as NOME, 	p.rg as RG, "
                            . " p.cpf as CPF, c.pis as PIS, concat(p.tel1,';',p.tel2,';',p.tel3) as TELEFONES, "
                            . " b.n_ban, c.agencia, c.cc "
                            . " FROM cadam_cadastro c "
                            . " join pessoa p on p.id_pessoa = c.fk_id_pessoa "
                            . " join dtpg_seletivas s on s.id_sel = c.fk_id_sel "
                            . " join banco b on b.id_ban = c.banco "
                            . " where c.ativo = 1 "
                            . " order by p.n_pessoa "
                    ?>
                    <input type="hidden" name="sql" value="<?php echo $sql ?>" />
                    <input style="width: 90%; margin: 0 auto" class="btn btn-success" type="submit" value="Lista com Banco" />
                </form> 
            </div>
        </div>
        <br /><br />
    </div>
</div>