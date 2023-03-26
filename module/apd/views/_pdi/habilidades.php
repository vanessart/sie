<?php
if (!defined('ABSPATH'))
    exit;

$id_pessoa_aluno = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$bimestre = filter_input(INPUT_POST, 'bimestre', FILTER_SANITIZE_NUMBER_INT);
$id_pdi_habP = filter_input(INPUT_POST, 'id_pdi_hab', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$edit = filter_input(INPUT_POST, 'edit', FILTER_SANITIZE_NUMBER_INT);
$id_turma_AEE = filter_input(INPUT_POST, 'id_turma_AEE', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$id_pdi = filter_input(INPUT_POST, 'id_pdi', FILTER_SANITIZE_NUMBER_INT);
$id_atend = filter_input(INPUT_POST, 'id_atend', FILTER_SANITIZE_NUMBER_INT);
$activeNav = filter_input(INPUT_POST, 'activeNav', FILTER_SANITIZE_NUMBER_INT);
$id_hab = filter_input(INPUT_POST, 'id_hab', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa_prof = toolErp::id_pessoa();
$id_hab_bd = null;

if (!empty($edit)) {
    $hab = sql::get('apd_pdi_hab', 'id_pdi_hab, fk_id_hab, recursos, didatica, obs,habilidade', ['id_pdi_hab' => $id_pdi_habP ], 'fetch');
    if ($hab) {
        $habilidade = $hab['habilidade'];
    }
    if (!empty($hab)) {
        $id_pdi_hab = $hab['id_pdi_hab'];
        $id_hab = $hab['fk_id_hab']?:$id_hab;
        $id_hab_bd = $hab['fk_id_hab'];

        if (empty($id_hab_bd)){
            $habilidades = $model->getHabil();
        }
    }
}else{
    $habilidades = $model->getHabil();
    if ($id_hab) {
        $arrayHab = $model->adaptCurrHabil($id_hab);
        if ($arrayHab) {
            foreach ($arrayHab as $v) {
                 $habilidade =  preg_replace("/<br\s?\/?>/", '',$v['codigo']." - ".$v['descricao']);

            }
        }
    }
}

if (!empty($id_hab)) {
   $arrayHab = $model->adaptCurrHabil($id_hab);
    if ($arrayHab) {
        foreach ($arrayHab as $v) {
             $FIXO_n_componente =  preg_replace("/<br\s?\/?>/", '', $v['n_disc']);
             $FIXO_unidade =  preg_replace("/<br\s?\/?>/", '',$v['n_ut']);
             $FIXO_objeto =  preg_replace("/<br\s?\/?>/", '',$v['n_oc']);
             $FIXO_habilidade =  preg_replace("/<br\s?\/?>/", '',$v['codigo']." - ".$v['descricao']);
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
<style>
    .mensagens {}
    .mensagens .mensagem {
        border: 1px solid #e1e1d1;
        box-shadow: #e1e1d1 5px 5px 14px;
        margin: 10px auto;
        padding: 4px;
        /*min-height: 80px;*/
    }
    .mensagens .nomePessoa {text-transform: lowercase;}
    .mensagens .nomePessoa:first-letter { text-transform: uppercase; }
    .mensagens .nomePessoa { 
        color: #888;
        font-weight: normal;
        font-size: 100%;
    }

    .mensagens .dataMensagem { 
        font-weight: bold;
        color: #888;
        font-size: 18px;
    }
    .mensagens .tituloHab{
        font-weight: bold;
        color: #7ed8f5;
        font-size: 100%; 
    }
    .mensagens .corpoMensagem {
        display: block;
        /*margin-top: 10px;*/
        font-weight: normal;        
        white-space: pre-wrap;
        padding: 5px;
    }
    .mensagens .mensagemLinha-0{border-left: 5px solid #7ed8f5;}
    .tituloBox{
        font-size: 17px;
        font-weight: bold;
    }
    .tituloBox.box-0{ color: #7ed8f5;}

    .input-group-text
    {
    display: none;
    }

    .titulo { 
        color: #888;
        font-size: 16px;
        padding-bottom: 5px;
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
<script>
    $(document).ready(function () {
        $("#myInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
<div class="body">
    <form id="formEnvia" target="_parent" action="<?= HOME_URI ?>/apd/pdi" method="POST">
        <div class="row">
            <?php
            if (!empty($habilidades['b'])) {
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
        <?php if (!empty($habilidades) && empty($id_hab_bd)) {?>
            <div style="display: <?= $diplay[$k] ?>" id="<?= $k ?>">
                <div class="dropdown">
                    <button class="btn btn-outline-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Selecione uma Habilidade
                    </button>
                    <?= toolErp::tooltip('Elencar as habilidades que necessitam ser trabalhadas com a criança/aluno com deficiência. Pontuar as habilidades que julga necessárias e as que já foram avaliadas pelo professor AEE','130vh') ?>
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
        }

        if (!empty($id_hab)) {?>
            <div class="row">
                <div class="col-md-12 mensagens">
                    <div class="mensagem mensagemLinha-0" >
                        <div>
                            <p class="tituloBox box-0">HABILIDADE</p>
                            <label class="dataMensagem"><?= $FIXO_habilidade ?></label><br><br>
                            <div class="row">
                                <div class="col-2 tituloHab">
                                    Disciplina
                                </div>
                                <div class="col-4 tituloHab">
                                    Unidade Temática
                                </div>
                                <div class="col-6 tituloHab">
                                    Objeto de Conhecimento
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-2 nomePessoa">
                                    <?= $FIXO_n_componente ?>
                                </div>
                                <div class="col-4 nomePessoa">
                                    <?= $FIXO_unidade ?>
                                </div>
                                <div class="col-6 nomePessoa">
                                    <?= $FIXO_objeto ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
           <?php 
        }?>
        <div class="row">
            <div class="col">
                <span class="titulo"> ADAPTAÇÃO DA HABILIDADE </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[habilidade]', @$habilidade, 'Habilidades') ?>
            </div>
        </div>
        </br>   

        <div class="row">
            <div class="col">
                <span class="titulo"> RECURSOS </span>
                <?= toolErp::tooltip('Recursos utilizados (tecnológicos ou  não) para o desenvolvimento das habilidades, de acordo com a opção metodológica','160vh') ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[recursos]', @$hab['recursos']) ?>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col">
                <span class="titulo"> SITUAÇÃO DIDÁTICA / SEQUÊNCIA DIDÁTICA </span>
                <?= toolErp::tooltip('Descrição das ações estratégicas para efetivação do processo de aprendizagem do aluno','110vh') ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[didatica]', @$hab['didatica']) ?>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col">
                <span class="titulo"> OBSERVAÇÕES </span>
                <?= toolErp::tooltip('Registrar quais ações ainda são necessárias para o desenvolvimento do aluno','100vh') ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[obs]', @$hab['obs']) ?>
            </div>
        </div>
        <br>

        <br>
        <div class="row">
            <div class="col text-center">
                <?=
                formErp::hidden([
                    '1[fk_id_pdi]' => $id_pdi ,
                    '1[id_pdi_hab]' => @$id_pdi_hab ,
                    '1[fk_id_hab]' => @$id_hab ,
                    '1[atualLetiva]' => @$bimestre ,
                    '1[fk_id_pessoa_prof]' => $id_pessoa_prof,
                    'id_pessoa' => $id_pessoa_aluno,
                    'id_turma' => $id_turma,
                    'id_turma_AEE' => $id_turma_AEE,
                    'n_pessoa' => $n_pessoa,
                    'bimestre' => $bimestre,
                    'id_pdi' => $id_pdi ,
                    'activeNav' => 2,

                ])
                . formErp::hiddenToken('PdiHab')
                . formErp::button('Salvar', null, 'salvar()');
                ?>            
            </div>
        </div>
    </form>
</div>
<form id="formComp" target="frame" action="<?= HOME_URI ?>/apd/hab" method="POST">
    <?=
    formErp::hidden([
        'id_pessoa' => $id_pessoa_aluno,
        'id_turma' => $id_turma,
        'id_turma_AEE' => $id_turma_AEE,
        'n_pessoa' => $n_pessoa,
        'bimestre' => $bimestre,
        'id_pdi' => $id_pdi,
        'edit' => $edit,
        'id_pdi_hab' => $id_pdi_habP,
        'activeNav' => 2,
    ])?>
    <input type="hidden" name="id_hab" id="id_hab" value="<?php echo $id_hab ?>" />
</form>

<?php
    toolErp::modalInicio(null, null, 'mais');
    ?>
    <iframe name="frame2" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
    <script>
        $(document).ready(function () {
            <?php foreach ($opt as $k => $v) { ?>
                $("#myInput<?= $k ?>").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#myTable<?= $k ?> tr").filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            <?php } ?>
        });

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

        function salvar(){
            document.getElementById("formEnvia").submit();
        }
    </script>
<div id="load" style="position: fixed; top: 20%; left: 30%; display: none">
    <img src="<?= HOME_URI ?>/includes/images/loading.gif" alt="alt"/>
</div>
