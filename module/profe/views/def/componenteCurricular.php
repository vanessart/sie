<?php
if (!defined('ABSPATH'))
    exit;

$id_comp = filter_input(INPUT_POST, 'id_componente', FILTER_SANITIZE_NUMBER_INT);
$id_aluno_adaptacao = filter_input(INPUT_POST, 'id_aluno_adaptacao', FILTER_SANITIZE_NUMBER_INT);
$id_porte = filter_input(INPUT_POST, 'id_porte', FILTER_SANITIZE_NUMBER_INT);
$dt_nasc = filter_input(INPUT_POST, 'dt_nasc', FILTER_UNSAFE_RAW); 
$fk_id_pessoa = filter_input(INPUT_POST, 'fk_id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_UNSAFE_RAW);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_hab = filter_input(INPUT_POST, 'id_hab', FILTER_SANITIZE_NUMBER_INT);
$titulo = 'Componente Curricular';
$fk_id_nota = 1;
if ($id_comp) {
    $apd_componente = sql::get('apd_componente', '*', ['id_componente' => $id_comp], 'fetch');
    if ($apd_componente) {
        $n_componente = $apd_componente['n_componente'];
        $unidade = $apd_componente['unidade'];
        $objeto = $apd_componente['objeto'];
        $habilidade = $apd_componente['habilidade'];
        $fk_id_nota = $apd_componente['fk_id_nota'];
        $sit_didatica = $apd_componente['sit_didatica'];
        $recurso = $apd_componente['recurso'];
    }
}else{
    $habilidades = $model->getHabil();
    if ($id_hab) {
        $arrayHab = $model->adaptCurrHabil($id_hab);
        if ($arrayHab) {
            foreach ($arrayHab as $v) {
                $n_componente =  preg_replace("/<br\s?\/?>/", '', $v['n_disc']);
                $unidade =  preg_replace("/<br\s?\/?>/", '',$v['n_ut']);
                $objeto =  preg_replace("/<br\s?\/?>/", '',$v['n_oc']);
                $habilidade =  preg_replace("/<br\s?\/?>/", '',$v['descricao']);

            }
        }
    }

}
$opt = [
    'a' => 'Ano Inteiro',
];
foreach ($opt as $k => $v) {
    $diplay[$k] = 'none';
    $btn[$k] = 'btn btn-outline-warning';
}

if (!empty($habilidades['p'])) {
    $btn['p'] = 'btn btn-warning';
    $diplay['p'] = 'block';
} else if (!empty($habilidades['b'])) {
    $btn['b'] = 'btn btn-warning';
    $diplay['b'] = 'block';
} else {
    $btn['a'] = 'btn btn-warning';
    $diplay['a'] = 'block';
}

?>
<style type="text/css">
    .input-group-text{width: 200px;}
    .esconde{ display: none; }
    .input-group-text
    {
    display: none;
    }

    .titulo { 
        color: #888;
        font-size: 16px;
        margin-bottom: 5px;
    }
    .tituloG { 
        font-weight: bold;
        color: #888;
        font-size: 18px;
        margin-bottom: 5px;
        text-align: center;
        padding: 10px;
        padding-bottom: 20px;
    }
    .info{
        margin-bottom: 5px;
    }
</style>
<div class="body">
   <div class="fieldTop" style="padding-bottom: 5%;">
        <?= $titulo ?>
   </div>
   <form name="form" id='form1' action="<?= HOME_URI ?>/profe/adaptCurriculo" method="POST" target='_parent'> 
       <div class="row">
            <?php
            if (!empty($habilidades['b']) ) {
                foreach ($opt as $k => $v) {
                    ?>
                    <div class="col" style=" text-align: center; font-weight: bold">
                        <label style="white-space: nowrap;" onclick="habilidades('<?= $k ?>')">
                            <button style="margin: 10px" type="button" id="btn_<?= $k ?>" class="<?= $btn[$k] ?> rounded-button_min border"></button>
                    <?= $v ?>
                        </label>
                    </div>
                        <?php
                }
            }
            ?>
        </div>
        <?php
        if (!empty($habilidades)) {?>
            <div style="display: <?= $diplay[$k] ?>" id="<?= $k ?>">
                <div class="dropdown">
                    <button class="btn btn-outline-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Selecione uma Habilidade
                    </button>
                    <span type="button" class=" btn-outline-warning info rounded-circle" data-toggle="tooltip" data-placement="top" title="Escolha a habilidade que necessita ser trabalhada com a criança/aluno.">&#9432;</span>
                    <?php
                if (!empty($habilidades[$k])) {?>
                    <div style="height: 480px; width: 100%" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <div style="padding: 10px">
                            <input class="form-control" id="myInput<?= $k ?>" type="search" placeholder="Pesquisa..">
                        </div>
                        <div style="height: 400px; overflow: auto; width: 100%">
                            <table id="myTable<?= $k ?>"><?php
                                foreach ($habilidades[$k] as $kh => $h) {?>
                                    <tr>
                                        <td style="padding: 3px">
                                            <div class="alert alert-dark" onclick="habilidadeSet(<?= $kh ?>, 'i')" style="width: 98%; margin: auto; word-break: normal; cursor: pointer; text-align: justify">
                                                <?= $h ?>   
                                            </div>
                                        </td>
                                    </tr><?php
                                }?>
                            </table>
                        </div>
                    </div><?php
                } else {?>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <div class="alert alert-dark" style="width: 98%; margin: auto;">
                            Sem Cadastro de Habilidades
                        </div>
                    </div><?php
                    }?>
                </div>
            </div>
            <br />
            <?php
        }?>
        <div class="row">
            <div class="col">
                <span class="titulo"> Componente Curricular </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[n_componente]', @$n_componente) ?>
            </div>
        </div>
        </br>
        <div class="row">
            <div class="col">
                <span class="titulo"> Unidade Temática </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[unidade]', @$unidade) ?>
            </div>
        </div>
        </br>
        <div class="row">
            <div class="col">
                <span class="titulo"> Objeto de Conhecimento </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[objeto]', @$objeto) ?>
            </div>
        </div>
        </br> 
        <div class="row">
            <div class="col">
                <span class="titulo"> Habilidades </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[habilidade]', @$habilidade) ?>
            </div>
        </div>
        </br> 
        <div class="row pqnPorte">
            <div class="col">
                <span class="titulo"> Situação Didática </span>
            </div>
        </div>
        <div class="row pqnPorte">
            <div class="col">
                <?= formErp::textarea('1[sit_didatica]', @$sit_didatica) ?>
            </div>
        </div>
        </br>
        <div class="row pqnPorte">
            <div class="col">
                <span class="titulo"> Recursos </span>
            </div>
        </div>
        <div class="row pqnPorte">
            <div class="col">
                <?= formErp::textarea('1[recurso]', @$recurso) ?>
            </div>
        </div>
        </br>     
        <div class="row">
            <div class="col gdPorte">
            <?= formErp::selectDB('apd_nota','1[fk_id_nota]', 'Classificação', @$fk_id_nota,NULL,NULL,NULL,NULL,'required') ?>
            </div>

            <div class="col">

                <?=
                formErp::hidden([
                    'activeNav' => 3,
                    '1[fk_id_aluno_adaptacao]' => $id_aluno_adaptacao,
                    'id_aluno_adaptacao' => $id_aluno_adaptacao,
                    '1[id_componente]' => @$id_comp,
                    '1[fk_id_hab]' => @$id_hab,
                    'fk_id_pessoa' => $fk_id_pessoa,
                    'n_pessoa' => $n_pessoa,
                    'id_porte' => $id_porte,
                    'dt_nasc' => $dt_nasc,
                    'id_turma' => $id_turma
                ])
                .formErp::hiddenToken('apd_componente', 'ireplace')
                .formErp::button('Salvar',null,'salvar();');
                ?>            
            </div>
        </div>     

   </form>
</div>

<form id="formComp" target="frame" action="<?= HOME_URI ?>/profe/def/componenteCurricular.php" method="POST">
    <input type="hidden" name="id_aluno_adaptacao" id="id_aluno_adaptacao" value="<?= $id_aluno_adaptacao ?>" />
    <input type="hidden" name="id_componente" id="id_componente" value="" />
    <input type="hidden" name="fk_id_pessoa" id="fk_id_pessoa" value="<?= $fk_id_pessoa ?>" />
    <input type="hidden" name="n_pessoa" id="n_pessoa" value="<?= $n_pessoa ?>" />
    <input type="hidden" name="id_turma" id="id_turma" value="<?= $id_turma ?>" />
    <input type="hidden" name="id_porte" id="id_porte" value="<?= $id_porte ?>" />
    <input type="hidden" name="dt_nasc" id="dt_nasc" value="<?= $dt_nasc ?>" />
    <input type="hidden" name="id_hab" id="id_hab" value="" />
</form>

<script type="text/javascript">
<?php 
if ($id_porte == 3) {?>
    $('.pqnPorte').addClass('esconde');
<?php 
}else{?> 
    $('.gdPorte').addClass('esconde');
    <?php 
}?>
    function habilidades(id) {
            document.getElementById('btn_b').classList.remove('btn-warning');
            document.getElementById('btn_a').classList.remove('btn-warning');
            document.getElementById('btn_b').classList.add('btn-outline-warning');
            document.getElementById('btn_a').classList.add('btn-outline-warning');
            document.getElementById('btn_' + id).classList.remove('btn-outline-warning');
            document.getElementById('btn_' + id).classList.add('btn-warning');
            document.getElementById('b').style.display = 'none';
            document.getElementById('a').style.display = 'none';
            document.getElementById(id).style.display = 'block';
        }

    function habilidadeSet(id) {
        document.getElementById('id_hab').value = id;
        document.getElementById("formComp").submit();

    }

    $(document).ready(function () {
        $("#myInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
            
            <?php 
        foreach ($opt as $k => $v) {?>
            $("#myInput<?= $k ?>").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#myTable<?= $k ?> tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            <?php
        }?>
    });

    function salvar(){
        <?php 
        if ($id_porte == 3) {?>
            fk_id_nota = document.getElementsByName('1[fk_id_nota]')[0].value;
            if(fk_id_nota == ""){
                alert("Informe uma Classificação.");
                return false;
            }
        <?php 
        }?>
        n_componente = document.getElementsByName('1[n_componente]')[0].value;
        if(n_componente == ""){
            alert("Informe o Componente Curricular.");
            return false;
        }

        unidade = document.getElementsByName('1[unidade]')[0].value;
        if(unidade == ""){
            alert("Informe a Unidade Temática.");
            return false;
        }

        objeto = document.getElementsByName('1[objeto]')[0].value;
        if(objeto == ""){
            alert("Informe o Objeto de Conhecimento.");
            return false;
        }

        habilidade = document.getElementsByName('1[habilidade]')[0].value;
        if(habilidade == ""){
            alert("Preencha o campo Habilidades.");
            return false;
        }

        document.getElementById("form1").submit();
    }
</script>
<div id="load" style="position: fixed; top: 20%; left: 30%; display: none">
    <img src="<?= HOME_URI ?>/includes/images/loading.gif" alt="alt"/>
</div>