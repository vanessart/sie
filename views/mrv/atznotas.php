<?php

$id_pessoa = $_POST['id_pessoa'];
@$nome_aluno = $_POST['n_pessoa'];
@$id_turma = $_POST['fk_id_turma'];

@$notas = $model->digitacaonotas($id_pessoa);

for ($c = 0; $c <= 10; $c += 0.1) {
    @$valores .= "'" . $c . "', ";
    $valores .= "'" . str_replace(".", ",", $c) . "', ";
}
for ($c = 0; $c <= 10; $c++) {
    @$valores .= "'" . $c . ".0', ";
    @$valores .= "'" . $c . ",0', ";
}

$valores = substr($valores, 0, -2);
$id_inst = tool::id_inst();
?>

<head>
    <style>
        .topo{
            font-size: 10pt;
            font-weight:bolder;
            text-align: center;
            padding: 1px 5px 1px 5px;
            border: 1px solid;
        }
        .topo2{
            font-size: 25pt;
            font-weight: bolder;
            text-align: center;
            padding: 1px;
            border: 1px solid;        
        }
    </style>
</head>

<div style="font-size: 20px; padding: 20px">
    <div class="panel panel-default">
        <div style="color: red" class="panel panel-heading">
            <b>Digitação de Notas</b>
            <br /><br />
            <?php echo $nome_aluno ?>
        </div>
        <div class="panel panel-body">
            <div class="row">         
                <div style="height: 40px" class="col-md-3 topo">
                    <br />Disciplinas
                </div>
                <div style="height: 40px" class="col-md-1 topo">
                    <br />6º Ano
                </div>      
                <div style="height: 40px" class="col-md-1 topo">
                    <br />7º Ano
                </div>
                <div style="height: 40px" class="col-md-1 topo">
                    <br />8º Ano
                </div>
                <div class="col-md-2">
                    <div style="height: 20px" class="row">
                        <div class="col-md-12 topo">
                            9º Ano
                        </div>
                    </div>
                    <div class="row">
                        <div style="height: 20px" class="col-md-6 topo">
                            1º Bimestre
                        </div>
                        <div style="height: 20px" class="col-md-6 topo">
                            2º Bimestre
                        </div>
                    </div>
                </div>
            </div>
            <form method="POST">
                <?php
                //juntei as notas de Inglês(no model)
                $linha = [9 => 'Língua Portuguesa', 13 => 'História', 14 => 'Geografia', 12 => 'Ciências Naturais', 6 => 'Matemática', 11 => 'Educação Física', 10 => 'Arte', 15 => 'L.E.Inglês'];
                $coluna = ['6', '7', '8', 'b1', 'b2'];

                foreach ($linha as $kl => $l) {
                    ?>      
                    <div class="row">
                        <div style="height: 52px" class="col-md-3 topo">
                            <br />
                            <?php echo $l ?>
                        </div>
                        <?php
                        foreach ($coluna as $c) {
                            ?>
                            <div class="col-md-1 topo2">
                                <?php
                                //$notas[$l][$c] = str_replace('.', ',', [$notas[$l][$c]]);
                                ?>
                                <input onblur="confMensao(this)" type="text" name="nota[<?php echo $kl ?>][<?php echo $c ?>]" value="<?php echo $notas[$l][$c] ?>" />                         
                            </div>      
                            <?php
                        }
                        ?>  
                    </div>
                    <?php
                }
                ?>
                <div style="color:red; font-size: 10pt; text-align: left">
                    <b>Aluno Reclassificado: Digitar a Nota de Reclassificação em todas as disciplinas</b>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-5">
                        <input type="hidden" name= "n_pessoa" value="<?php echo $nome_aluno ?>" />
                        <input type="hidden" name="id_pessoa" value=" <?php echo $id_pessoa ?>" />
                        <input name = "grava" type="submit" style="width: 40%" class="art-button" value="Salvar Alteração" />                    </div>
                    <div class="col-md-5">                           
                        <button type="button" onclick="$('#fecha').submit()" style="width: 40%" class="art-button">
                            Retornar
                        </button>   
                    </div>   

                </div>
            </form>
        </div>
    </div>

</div>

<form id = "fecha" action="<?php echo HOME_URI ?>/mrv/selecaoatz" method="POST">  
    <input type="hidden" name= "id_pessoa" value="<?php echo $id_pessoa ?>" />
    <input type="hidden" name= "n_pessoa" value="<?php echo $nome_aluno ?>" />
    <input type="hidden" name= "id_turma" value="<?php echo $id_turma ?>" />
</form>
<script>
    function confMensao(id) {
        var v = [<?php echo $valores ?>];
        var valor = id.value;
        var confere = null;
        var i;
        for (i = 0; i < v.length; i++) {
            if (v[i] == valor)
            {
                confere = 1;
            }
        }
        if (confere !== 1 && valor !== '') {
            alert("Valor inválido");
            id.style.backgroundColor = "red";
        } else {
            id.style.backgroundColor = "white";
        }
    }
</script>