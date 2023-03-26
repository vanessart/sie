<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="row">
    <div class="col">
        <div class="border" style="padding: 8px">
            <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listapresenca" id="presenca" method="POST">
                <div class="row">
                    <div class="col-9">
                        <?= formErp::input('titulo', 'Título') ?>
                    </div>
                    <div class="col-3">
                        <input type="hidden" name="id_turma" value="<?= $id_turma ?>" />                   
                        <?= hi() ?>
                        <button class="btn btn-info">
                            Lista de Presença
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col">
        <div class="border" style="padding: 8px">
            <?php
            if (!empty($id_pl)) {
                @$ano = sql::get('ge_periodo_letivo', 'ano', ['id_pl' => $id_pl], 'fetch')['ano'];
            } else {
                $ano = date("Y");
            }
            ?>
            <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listachamada" id="lchamada" method="POST">
                <div class="row">
                    <div class="col">
                        <?= formErp::select('mes', data::meses(), 'mês', date("m"), null, null, ' required ') ?>
                    </div>
                    <div class="col">
                        <input type="hidden" name="ano" value="<?= $ano ?>" />
                        <?= hi() ?>
                        <button class="btn btn-info" >
                            Lista de Chamada
                        </button>
                    </div>
                </div>
            </form> 
        </div>
    </div>
</div>
<br />
<div class="row grd">
    <div class="col">
        <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listapiloto" id="listpiloto" method="POST">
            <?= hi() ?>
            <input type="hidden" name="id_turma" value="<?= $id_turma ?>" />                
            <button class="btn btn-info" >
                Lista Piloto
            </button>
        </form> 
    </div>
    <div class="col">
        <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listaparede" id="parede" method="POST">
            <?= hi() ?>
            <input type="hidden" name="id_turma" value="<?= $id_turma ?>" />                
            <button class="btn btn-info" >
                Lista Piloto - Parede
            </button>
        </form> 
    </div>
    <div class="col">
        <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listaemail" id="email" method="POST">
            <?= hi() ?>
            <input type="hidden" name="id_turma" value="<?= $id_turma ?>" />                
            <button class="btn btn-info" >
                Lista Email
            </button>
        </form> 
    </div>
    <div class="col">
        <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listapaternidade" id="paternidade" method="POST">
            <?= hi() ?>
            <input type="hidden" name="id_turma" value="<?= $id_turma ?>" />                
            <button class="btn btn-info" >
                Lista com Filiação
            </button>
        </form>
    </div>
</div>
<br />
<div class="row grd">
    <div class="col">
        <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listaidadeano" id="idadeano" method="POST">
            <?= hi() ?>
            <input type="hidden" name="id_turma" value="<?= $id_turma ?>" />                
            <button class="btn btn-info" >
                Lista Idade/Ano Escolar
            </button>
        </form>
    </div>
    <div class="col">
        <form target="_blank" action="<?php echo HOME_URI ?>/gestao/lista_piloto_doc" id="listadoc" method="POST">
            <?= hi() ?>
            <input type="hidden" name="id_turma" value="<?= $id_turma ?>" />                
            <button class="btn btn-info" >
                Lista Piloto - Documentos
            </button>
        </form> 
    </div>
    <div class="col">
        <form target="_blank" action="<?php echo HOME_URI ?>/gestao/lista_piloto_end" id="listaen" method="POST">
            <?= hi() ?>
            <input type="hidden" name="id_turma" value="<?= $id_turma ?>" />                
            <button class="btn btn-info" >
                Lista Piloto - Endereço
            </button>
        </form> 
    </div>
    <div class="col">
        <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
            <?php
            $hj = date('Y-m-d');
            $sql = "SELECT"
                    . " p.id_pessoa AS RSE, p.ra AS RA, ra_dig AS Digito_RA, ta.chamada AS Chamada, p.n_pessoa AS Nome_Aluno,"
                    . " p.sus AS SUS, t.codigo AS Cod_Classe, p.rg AS RG, p.cpf as CPF, e.logradouro_gdae AS Endereço, "
                    . " e.num_gdae AS NR, e.complemento AS Compl, e.cep AS CEP, e.bairro AS Bairro, e.cidade AS Cidade, p.tel1 AS Tel1,"
                    . " p.tel2 AS Tel2, p.email AS Email, p.mae AS Mãe, p.pai AS Pai, p.cidade_nasc AS Cid_Nasc,"
                    . " p.sexo AS Sexo, p.dt_nasc AS Dt_Nasc, p.emailgoogle AS Email_Google,"
                    . " TIMESTAMPDIFF(YEAR, p.dt_nasc, '$hj') AS Idade FROM pessoa p"
                    . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa"
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma"
                    . " LEFT JOIN endereco e on e.fk_id_pessoa = p.id_pessoa"
                    . " Where ta.situacao = 'Frequente'  AND t.id_turma in (" . implode(', ', $id_turma) . ") ORDER BY ta.chamada";
            ?>
            <input type="hidden" name="tokenSql" value="<?php echo tool::tokenSql() ?>" />
            <input type="hidden" name="sql" value="<?php echo $sql ?>" />
            <button class="btn btn-info" >
                Exportar Excel
            </button>
        </form> 
    </div>
</div>
<br />
<div class="row">
    <div class="col">
        <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listaparedeFinal" id="parede" method="POST">
            <?= hi() ?>
            <input type="hidden" name="id_turma" value="<?= $id_turma ?>" />                
            <button class="btn btn-info" >
                Lista Piloto - Resultado Final
            </button>
        </form> 
    </div>
    <div class="col">
        <form target="_blank" action="<?php echo HOME_URI ?>/sed/pdf/perfildoalunopdf.php" id="parede" method="POST">
            <?= hi() ?>
            <input type="hidden" name="id_turma" value="<?= $id_turma ?>" />                
            <button class="btn btn-info" >
                Perfil do Aluno
            </button>
        </form> 
    </div>
</div>
<br />
<div class="row grd">
    <div class="col">
        <!--
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/lista_conselhopdf" id="conselho" method="POST">
        <?= hi() ?>
                    <input type="hidden" name="id_turma" value="<?= $id_turma ?>" />                
                    <button class="btn btn-info" >
                        Lista Resultado Final
                    </button>
                </form> 
        -->
    </div>
    <div class="col">

    </div>
    <div class="col">

    </div>
</div>
<br />
<?php

function hi() {
    $id_turma = @$_POST['id_turma'];
    if (!is_array($id_turma)) {
        $id_turma = [$id_turma];
    }
    $t = '';
    foreach ($id_turma as $v) {
        if (!empty($v)) {
            $t .= '<input  type="hidden" name="sel[]" value="' . $v . '" />';
        }
    }
    return $t;
}
?>
