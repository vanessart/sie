<?php
if (!defined('ABSPATH'))
    exit;

$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_UNSAFE_RAW);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'fk_id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_projeto = filter_input(INPUT_POST, 'id_projeto', FILTER_SANITIZE_NUMBER_INT);
$id_ciclo = filter_input(INPUT_POST, 'fk_id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'fk_id_disc', FILTER_SANITIZE_NUMBER_INT);
$atual_letiva = filter_input(INPUT_POST, 'atual_letiva', FILTER_SANITIZE_NUMBER_INT);
// $hab = [];
// $hab = $model->getHabilidadesProjeto($id_ciclo,$id_disc,null,null,null,3)['a'];
$bimestres = $model->bimestreSelect($id_turma);
$titulo = 'Cadastro de Projeto';
$id_pessoa = toolErp::id_pessoa();
$id_status = 1;
$coord_vizualizar = 0;

if ($id_projeto) {
    $projeto = sql::get('profe_projeto', 'id_projeto, n_projeto, dt_inicio, dt_fim, habilidade, justifica, situacao, recurso, resultado, fonte, autores, devolutiva, fk_id_projeto_status, coord_vizualizar, atual_letiva ', 'WHERE id_projeto =' . $id_projeto, 'fetch', 'left');
    $titulo = 'Projeto: ' . $projeto['n_projeto'];
    $id_status = $projeto['fk_id_projeto_status'];
    $coord_vizualizar = $projeto['coord_vizualizar'];
    $devolutiva = $projeto['devolutiva'];
    //$id_autores =explode(',', $projeto['autores']);;
    $checkAutores = $model->checkAutores($projeto['autores']);
}
if (!empty($projeto)) {
    if (empty($atual_letiva)) {
        $atual_letiva = $projeto['atual_letiva'];
    }
    $autoRArr = explode(';', $projeto['autores']);
    if (in_array(toolErp::id_pessoa(), $autoRArr)) {
        $autores = $projeto['autores'];
    } else {
        $autoRArr[toolErp::id_pessoa()] = toolErp::id_pessoa();
        foreach ($autoRArr as $k => $v) {
            if (empty($v)) {
                unset($autoRArr[$k]);
            }
        }
        $autores = implode(',', $autoRArr);
    }
} else {
    $autores = toolErp::id_pessoa();
}
if (empty($atual_letiva)) {
   $atual_letiva = curso::unidLetivaAtual($id_turma); 
}
$hab = $model->getHab($atual_letiva,$id_ciclo,$id_disc,3);
$habil = $model->habProjeto($id_projeto,$atual_letiva);
?>
<style type="text/css">
    .input-group-text
    {
        width: 220px;
    }
</style>
<div class="body">
    <?php if ($coord_vizualizar == 1 AND $id_status == 2) { ?> 
        <div class="alert alert-danger" style="padding-top:  10px; padding-bottom: 0">
            <div class="row">
                <div class="col" style="font-weight: bold; text-align: center;">
                    <p style=" font-size: 14px">Este projeto está em análise. Aguarde o retorno do Coordenador para realizar alterações.</p>
                </div>
            </div>
        </div>
    <?php
}

if (!empty($devolutiva) AND $id_status <> 2) {
    ?> 
        <div class="alert alert-warning" style="padding-top:  10px; padding-bottom: 0">
            <div class="row">
                <div class="col" style="font-weight: bold; text-align: center;">
                    <p style=" font-size: 14px"><strong>Devolutiva do Coordenador: </strong><?= $devolutiva ?></p>
                </div>
            </div>
        </div>
        <?php }
    ?> 
    <div class="fieldTop" style="padding-bottom: 5%;">
        <?= $titulo ?>
    </div>
    <div class="row">
        <?php
        if (!empty($checkAutores)) {
            foreach ($checkAutores as $k => $v) {
                ?>
                <div class="col" id="<?= $v['id_pessoa'] ?>">
                    <button type="button" onclick="autorDel(<?= $v['id_pessoa'] ?>, '<?= $v['n_pessoa'] ?>')" class="btn btn-outline-danger"> X</button>
                    <?= $model->initials($v['n_pessoa']) ?> 
                </div>
                <?php
            }
        }
        ?>
    </div>
    <br>
    <form name="salvarForm" action="<?= HOME_URI ?>/profe/projeto" method="POST" target='_parent' >

        <div class="row">
            <div class="col">
                <?= formErp::input('1[n_projeto]', 'Título', @$projeto['n_projeto'], ' required ') ?>
            </div>
        </div>
        </br>
        <div class="row">
            <div class="col">
                <?= formErp::input('1[dt_inicio]', 'Início', @$projeto['dt_inicio'], ' required ', null, 'date') ?>
            </div>
            <div class="col">
                <?= formErp::input('1[dt_fim]', 'Termino', @$projeto['dt_fim'], ' required ', null, 'date') ?>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[justifica]', @$projeto['justifica'], 'Justificativa') ?>
            </div>
        </div>
        </br> 
        <?php  
        if (!empty($projeto['habilidade'])) {?>
           <div class="row">
                <div class="col">
                    <?= formErp::textarea('1[habilidade]', @$projeto['habilidade'], 'Habilidades') ?>
                </div>
            </div>
        </br>  
          <?php 
        }?>
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
                <?= formErp::textarea('1[recurso]', @$projeto['recurso'], 'Recursos') ?>
            </div>
        </div>
        </br>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[resultado]', @$projeto['resultado'], 'Resultado do Projeto') ?>
            </div>
        </div>
        </br>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[fonte]', @$projeto['fonte'], 'Fontes de Pesquisa') ?>
            </div>
        </div>
        </br>   
        <div class="row">

            <div class="col text-center">
                <input type="hidden" name="1[fk_id_projeto_status]" id="id_status" value="">   
                <input type="hidden" name="n_status" id="n_status" value="">   
                <?=
                formErp::hidden([
                    'activeNav' => 1,
                    '1[atual_letiva]' => $atual_letiva,
                    '1[fk_id_turma]' => $id_turma,
                    '1[autores]' => $autores,
                    'fk_id_turma' => $id_turma,
                    '1[id_projeto]' => @$id_projeto,
                    '1[fk_id_pessoa_prof]' => $id_pessoa,
                    'fk_id_disc' => $id_disc,
                    'fk_id_ciclo' => $id_ciclo,
                    'n_turma' => $n_turma,
                    'id_inst' => $id_inst
                ])
                . formErp::hiddenToken('profe_projeto_cadSalva')
                ?>            
                <?php
                $novamente = "";
                if ($id_status <> 1) {
                    $novamente = "novamente";
                }
                if ($coord_vizualizar == 1 AND $id_status == 2) {
                    ?> 
                    <div class="alert alert-danger" style="padding-top:  10px; padding-bottom: 0">
                        <div class="row">
                            <div class="col" style="font-weight: bold; text-align: center;">
                                <p style=" font-size: 14px">Este projeto está em análise. Aguarde o retorno do Coordenador para realizar alterações.</p>
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    if ($id_status <> 2) {
                        ?>
                        <button class=" btn btn-outline-info" onclick="salvar(event, 1, null)">
                            Apenas Salvar
                        </button>
                        <button class=" btn btn-outline-info" onclick="salvar(event, 2, 'Enviou o projeto <?= $id_projeto ?>')">
                            Salvar e enviar <?= $novamente ?> ao Coordenador
                        </button>
                        <?php } else {
                        ?>
                        <button class=" btn btn-outline-info" onclick="salvar(event, 2, null)">
                            Apenas Salvar
                        </button>
                        <button class=" btn btn-outline-info" onclick="salvar(event, 1, 'Voltou a situação do projeto <?= $id_projeto ?> para Não Enviado')">
                            Salvar e voltar a situação para 'Não Enviado'
                        </button>
                        <?php
                    }
                }
                ?>

            </div>
        </div>     

    </form>
</div>
<form id='formBimestre' method="POST"> 
<input type="hidden" id="atual_letiva" name="atual_letiva" value=""> 
    <?=
    formErp::hidden([
        'activeNav' => 1,
        'id_projeto' => $id_projeto,
        'fk_id_projeto' => $id_projeto,
        'fk_id_turma' => $id_turma,
        'id_reg' => @$id_reg,
        'fk_id_disc' => @$id_disc,
        'fk_id_ciclo' => @$id_ciclo,
        'n_projeto' => @$n_projeto,
        'msg_coord' => @$msg_coord,
        'n_turma' => $n_turma,
        'id_inst' => $id_inst
    ]) ?> 
</form>
<script type="text/javascript">
    function selectBimestre(){
        select = document.getElementById("atualLetivaB_");
        bimestre = select.options[select.selectedIndex].value;
        document.getElementById("atual_letiva").value = bimestre;
        document.getElementById("formBimestre").submit();
    }
    hab = <?= json_encode($hab) ?>;
    <?php
    if (empty($habil)) {?>
        exist = [];
        <?php
    }else {?>
        exist = <?= json_encode($habil) ?>;
        <?php
    }?>
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
    function salvar(e, id_status, status) {
        e = e || window.event;
        e.preventDefault();

        document.getElementById("id_status").value = id_status;
        data1 = document.getElementsByName("1[dt_inicio]")[0].value;
        data2 = document.getElementsByName("1[dt_fim]")[0].value;
        nome = document.getElementsByName("1[n_projeto]")[0].value;

        var data = new Date();
        // Guarda cada pedaço em uma variável
        var dia     = data.getDate();
        var mes     = data.getMonth()+1;
        var ano4    = data.getFullYear();
        var hoje = ano4  + '-' + String(mes).padStart(2,'0') + '-' + String(dia).padStart(2,'0');

        if(nome == ""){
            alert("Informe o Nome do Projeto.");
            return false;
        }
        
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
        if(data2){
            var aDataLimite = data2.split("-");
            var iDataLimite = parseInt(aDataLimite[0].toString() + aDataLimite[1].toString() + aDataLimite[2].toString()); 
                
            if(iDataInicio > iDataLimite){
                alert("A data Final não pode ser anterior à data Inicial.");
                return false;
            }
        }
        if (status) {
            document.getElementById("n_status").value = status;
        }
        document.salvarForm.submit();
    }

    var arrAutores = Array.of(<?= $autores ?>);
    function autorDel(id_pessoa, n_pessoa) {
        if (arrAutores.length == 1) {
            alert("É preciso manter ao menos 1 professor");
            return false;
        } else {

            var arrEnd = [];
            for (var i = 0; i < arrAutores.length; i++) {
                if (arrAutores[i] !== id_pessoa) {
                    arrEnd.push(arrAutores[i]);
                }
            }

            if (arrEnd.length > 0) {
                document.getElementById(id_pessoa).style.display = "none";
                arrAutores = [...new Set(arrEnd.map(item => item))];
                document.getElementsByName('1[autores]')[0].value = arrAutores.join(',');
            } else {
                alert("É preciso manter ao menos 1 professor como autor do Projeto");
                return false;
            }

        }

    }
</script>