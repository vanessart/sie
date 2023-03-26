<?php
if (!defined('ABSPATH'))
    exit;
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'fk_id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_projeto = filter_input(INPUT_POST, 'fk_id_projeto', FILTER_SANITIZE_NUMBER_INT);
$id_ciclo = filter_input(INPUT_POST, 'fk_id_ciclo', FILTER_SANITIZE_STRING);
$atual_letiva = filter_input(INPUT_POST, 'atual_letiva', FILTER_SANITIZE_NUMBER_INT);
$n_projeto = filter_input(INPUT_POST, 'n_projeto', FILTER_SANITIZE_STRING);
$id_disc = filter_input(INPUT_POST, 'fk_id_disc', FILTER_SANITIZE_NUMBER_INT);
$id_reg = filter_input(INPUT_POST, 'id_reg', FILTER_SANITIZE_NUMBER_INT);
$msg_coord = filter_input(INPUT_POST, 'msg_coord', FILTER_SANITIZE_STRING);
$titulo = 'Registro do Projeto: ' . $n_projeto;
$id_pessoa = toolErp::id_pessoa();

if (empty($atual_letiva)) {
    $projeto = sql::get('profe_projeto', 'atual_letiva', 'WHERE id_projeto =' . $id_projeto, 'fetch');
    if (!empty($projeto['atual_letiva'])) {
       $atual_letiva = $projeto['atual_letiva']; 
    }else{
        $atual_letiva = curso::unidLetivaAtual($id_turma); 
    } 
}

$hab = $model->getHab($atual_letiva,$id_ciclo,$id_disc,3);
$bimestres = $model->bimestreSelect($id_turma);
if ($id_reg) {
    $registro = sql::get('profe_projeto_reg', 'id_reg, situacao, fk_id_hab, dt_inicio, dt_fim ', 'WHERE id_reg =' . $id_reg, 'fetch', 'left');
    $habil = $model->getHabReg($atual_letiva,$id_reg);
}
?>
<style type="text/css">
    .input-group-text
    {
        width: 220px;
    }
</style>
<div class="body">
    <div class="fieldTop" style="padding-bottom: 5%;">
        <?= $titulo ?>
    </div>
    <form id='form'  action="<?= HOME_URI ?>/profe/projeto" method="POST" target='_parent'>   
        <div class="row">
            <div class="col">
                <?= formErp::input('1[dt_inicio]', 'Início', empty($registro['dt_inicio']) ? date("Y-m-d") : $registro['dt_inicio'], null, null, 'date') ?>
            </div>
            <div class="col">
                <?= formErp::input('1[dt_fim]', 'Término', @$registro['dt_fim'], null, null, 'date') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-2">
                <?= formErp::select('atualLetivaB', $bimestres,null, $atual_letiva,null,null,'onChange = "selectBimestre();"') ?>
            </div>
            <div class="col">
                <?= formErp::select('idhab', $hab, 'Habilidade', null, null, null, null, null, 1) ?>
            </div>
        </div>
        <br />
        <table class="table table-bordered table-hover table-striped">
            <tbody id="tbody">
                <?php
                if (!empty($habil)) {
                    foreach ($habil as $v) {
                        ?>
                        <tr id="<?= $v ?>">
                            <td>
                                <?= @$hab[$v] ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger" onclick="apaga(<?= $v ?>)">
                                    X
                                </button>
                                <input type="hidden" name="hab[<?= $v ?>]" value="<?= $v ?>" />
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[situacao]', @$registro['situacao'], 'Situação de Aprendizagem','300') ?>
            </div>
        </div>
        <br> 
        <div class="row">
            <div class="col text-center">

                <?=
                formErp::hidden([
                    'activeNav' => 2,
                    '1[fk_id_projeto]' => $id_projeto,
                    '1[fk_id_pessoa]' => $id_pessoa,
                    'fk_id_projeto' => $id_projeto,
                    'fk_id_turma' => $id_turma,
                    '1[id_reg]' => @$id_reg,
                    'fk_id_disc' => @$id_disc,
                    'fk_id_ciclo' => @$id_ciclo,
                    'n_projeto' => $n_projeto,
                    'msg_coord' => $msg_coord,
                    'n_turma' => $n_turma,
                    'id_inst' => $id_inst
                ])
                . formErp::hiddenToken('profe_projeto_regSalva')
                . formErp::button('Salvar',null,'enviar()');
                ?>            
            </div>
        </div>     
    </form>
</div>
<form id='formBimestre' method="POST"> 
<input type="hidden" id="atual_letiva" name="atual_letiva" value=""> 
    <?=
    formErp::hidden([
        'activeNav' => 2,
        'id_projeto' => $id_projeto,
        'fk_id_projeto' => $id_projeto,
        'fk_id_turma' => $id_turma,
        'id_reg' => @$id_reg,
        'fk_id_disc' => @$id_disc,
        'fk_id_ciclo' => @$id_ciclo,
        'n_projeto' => $n_projeto,
        'msg_coord' => $msg_coord,
        'n_turma' => $n_turma,
        'id_inst' => $id_inst
    ]) ?> 
</form>
<script>
    function selectBimestre(){
        select = document.getElementById("atualLetivaB_");
        bimestre = select.options[select.selectedIndex].value;
        document.getElementById("atual_letiva").value = bimestre;
        document.getElementById("formBimestre").submit();
    }
    hab = <?= json_encode($hab) ?>;
    <?php
    if (empty($habil)) {
        ?>
            exist = [];
        <?php
    } else {
        ?>
            exist = <?= json_encode($habil) ?>;
        <?php
    }
    ?>
    function enviar(){
        data1 = document.getElementsByName("1[dt_inicio]")[0].value;
        data2 = document.getElementsByName("1[dt_fim]")[0].value;

        var data = new Date();
        // Guarda cada pedaço em uma variável
        var dia     = data.getDate();
        var mes     = data.getMonth()+1;
        var ano4    = data.getFullYear();
        var hoje = ano4  + '-' + String(mes).padStart(2,'0') + '-' + String(dia).padStart(2,'0');
        
        if(data1 == ""){
            alert("Informe a Data Inicial.");
            return false;
        }

        if(data2 == ""){
            alert("Informe a Data Final.");
            return false;
        }

        var iDataInicio = data1.split("-");
        var iDataInicio = parseInt(iDataInicio[0].toString() + iDataInicio[1].toString() + iDataInicio[2].toString());

        var iDataHoje = hoje.split("-");
        var iDataHoje = parseInt(iDataHoje[0].toString() + iDataHoje[1].toString() + iDataHoje[2].toString());

        if(data2 == ""){
            data2 = data1;
        }else{

            var aDataLimite = data2.split("-");
            var iDataLimite = parseInt(aDataLimite[0].toString() + aDataLimite[1].toString() + aDataLimite[2].toString()); 
                
            if(iDataInicio > iDataLimite){
                alert("A data Final não pode ser anterior à data Inicial.");
                return false;
            }
        }

        document.getElementById("form").submit();
    }
    function idhab(id) {
        if (exist[id]) {
            alert("Habilidade já Selecionada");
        } else {
            exist[id] = id;
            tbodyTex = tbody.innerHTML;
            tbody.innerHTML = tbodyTex + "<tr id='" + id + "'><td>" + hab[id] + "</td><td><button type=\"button\" class=\"btn btn-danger\" onclick=\"apaga(" + id + ")\">X</button><input type=\"hidden\" name=\"hab[" + id + "]\" value=\"" + id + "\" /></td></tr>";
        }
    }
    function apaga(id) {
        delete exist[id];
        document.getElementById(id).innerHTML = '';
    }
</script>
