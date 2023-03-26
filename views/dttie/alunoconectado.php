<?php
if (!empty($_POST['id_sup'])) {
    $id = $_POST['id_sup'];
} elseif (!empty($model->_last_id)) {
    $id = $model->_last_id;
}

if (!empty($id)) {
    $chamada = sql::get('dttie_suporte_trab', '*', ['id_sup' => $id], 'fetch');
    @$retornoe = $_POST['retorno_esc'];
    @$retornot = $_POST['retorno_turma'];
    @$retornoa = $_POST['retorno_aluno'];
    @$retornod = $_POST['descr'];
    @$retornoemail = $_POST['retorno_email'];
} else {
    $chamada = NULL;
}
$hiddenKey = DB::hiddenKey('dttie_suporte_trab');

@$idinst = $_POST['id_inst'];
@$idclasse = $_POST['id_turma'];
@$rse_aluno = $_POST['id_pessoa'];

$escola = $model->listaescoladttie();

if (!empty($idinst)) {
    @$classe = $model->listaturmadttie($idinst);
}

if (!empty($idclasse)) {
    @$aluno = $model->listaalunodttie($idclasse);
}
?>
<br />
<div style="font-weight:bold; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center">
    Projeto Aluno Conectado
</div>
<div class="fieldTop" style="padding: 10px">
    Suporte <?php echo!empty($chamada['id_sup']) ? ' - Serviço nº ' . $chamada['id_sup'] : '' ?>
</div>
<div class="row">
    <div class="col-md-4">
        <?php
        echo formulario::select('id_inst', @$escola, 'Nome Escola', @$idinst, 1);
        ?>     
    </div>
    <div class="col-md-3">
        <?php
        echo formulario::select('id_turma', @$classe, 'Classe', @$idclasse, 1, ['id_inst' => @$idinst])
        ?>
    </div>
    <div class="col-md-5">
        <?php
        echo formulario::select('id_pessoa', @$aluno, 'Nome Aluno', @$rse_aluno, 1, ['id_inst' => @$idinst, 'id_turma' => @$idclasse])
        ?>
    </div>
</div>
<br />
<div>
    <label style="font-size: 14px; font-weight: bold; color: red">
        Dados Selecionados
    </label>   
    <table class="table table-bordered table-striped table-hover" style="font-size: 12px; font-weight:bold" >
        <tr>
            <td>
                Nome Escola
            </td>
            <td>
                Classe
            </td>  
            <td>
                Nome Aluno
            </td>
            <td>
                Email Google
            </td>
        </tr>
        <tr>
            <td>
                <?php echo (!empty($escola[$idinst]) ? $escola[$idinst] : @$retornoe) ?>
            </td>
            <td>
                <?php echo (!empty($classe[$idclasse]) ? $classe[$idclasse] : @$retornot) ?>
            </td>
            <td>
                <?php echo (!empty($aluno[$rse_aluno]) ? $aluno[$rse_aluno] : @$retornoa) ?>
            </td>
            <td>
                <?php
                if (!empty($aluno[$rse_aluno])) {
                    $retornoemail = $model->pegaemailgoogle($rse_aluno);                 
                }
                echo @$retornoemail;
                ?>
            </td>
        </tr>
    </table>
</div>
<div class="row">
    <form id='aluno_conectado' method="POST">
        <div class="panel panel-default col-md-10">       
            <input id="descr" type="text" name="descr" value= "<?php echo @$retornod ?>" required placeholder="Escreva Aqui" />
        </div>   
        <div class="col-md-2 text-center">
            <?php
            if (!empty(@$rse_aluno)) {
                ?>
                <input  style="width: 200px; height: 35px" name="salvaaluno" class="btn btn-success" type="submit" value="Salvar" />
                <?php
            } else {
                ?>
                <button disabled class = "btn btn-default" style="width: 200px; height: 35px">
                    Salvar
                </button>
                <?php
            }
            $dadoaluno = @$escola[$idinst] . ' ' . @$classe[$idclasse] . ' ' . @$aluno[$rse_aluno] . '<br />' . @$retornoemail;
            ?>

            <input type="hidden" name="dadosaluno" value="<?php echo @$dadoaluno ?>" />
            <input type="hidden" name="1[dt_sup]" value="<?php echo date("Y-m-d") ?>" />
            <input type="hidden" name="1[status_sup]" value="Aberto" />
            <input type="hidden" name="1[tipo_sup]" value="78"/>
            <input type="hidden" name="1[descr_sup]" value="Projeto Aluno Conectado"/>
            <input type="hidden" name="1[priori_sup]" value="Media"/>
            <input type="hidden" name="1[local_sup]" value= "<?php echo @$escola[$idinst] ?>" />
            <input type="hidden" name="1[rse_aluno]" value= "<?php echo $rse_aluno ?>" />  
            <input type="hidden" name="1[ultimo_lado]" value="Call Center" />
            <input type="hidden" name="1[id_sup]" value="<?php echo @$id ?>" />
            <input id="id_pessoa" type="hidden" name="1[fk_id_pessoa]" value="<?php echo tool::id_pessoa() ?>"/>
            <input id="n_pessoa" type="hidden" name="1[n_pessoa]" value="<?php echo user::session('n_pessoa') ?>"/>
            <input type="hidden" name="1[rastro_sup]" value="<?php echo empty($chamada['rastro_sup']) ? substr(uniqid(), 0, 4) : $chamada['rastro_sup'] ?>" />
            <input type="hidden" name="[id_sup]" value="<?php echo @$id ?>" />
            <input type="hidden" name="retorno_esc" value="<?php echo @$escola[$idinst] ?>" />
            <input type="hidden" name="retorno_turma" value="<?php echo @$classe[$idclasse] ?>" />
            <input type="hidden" name="retorno_aluno" value="<?php echo @$aluno[$rse_aluno] ?>" />  
            <input type="hidden" name="retorno_email" value="<?php echo @$retornoemail ?>" />
            <?php echo $hiddenKey ?>
        </div>
    </form>
</div>
<div class="row">
    <div class="col-md-4 text-center">
        <?php if (!empty($chamada['id_sup'])) {
            ?>
            <a onclick="cancela()" class="btn btn-primary" >
                Cancelar Solicitação
            </a>
            <?php
        } else {
            ?>
            <button disabled class = "btn btn-default">
                Cancelar Solicitação
            </button>
            <?php
        }
        ?>
    </div>
    <div class="col-md-4">
        <?php if (!empty($chamada['id_sup'])) { ?>
            <a href="<?php echo HOME_URI; ?>/dttie/supprot?id=002<?php echo $chamada['rastro_sup'] . @$id ?>&p=1"  target="_blank">
                <input  style="width: 150px" class="btn btn-primary" type="button" value="Gerar Protocolo" />
            </a>
        <?php } else {
            ?>
            <button disabled class = "btn btn-default">
                Gerar Protocolo
            </button>
            <?php
        }
        ?>
    </div>
    <div class="col-md-4">
        <a href="<?php echo HOME_URI; ?>/dttie/escolapesq" >
            <input  style="width: 150px" class="btn btn-danger" type="button" value="Voltar" />
        </a>
    </div>
</div>
<form id="canc" method="POST">
    <input type="hidden" name="[status_sup]" value="Solicitação Cancelada" />
    <input type="hidden" name="id_sup" value="<?php echo @$id ?>" />
    <input type="hidden" name="cancelaChamado" value="1" />
</form>

<script>
    function cancela() {
        if (confirm("Cancelar esta Solicitação? ")) {
            document.getElementById('canc').submit();
        }
    }

    window.onload = conta();
</script>