<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="border">
    <div style="text-align: center; font-weight: bold; padding-bottom: 10px">
        Documentos em Branco
    </div>
    <div class="row">
        <div class="col text-center">
            <form target="_blank" action="<?= HOME_URI ?>/sed/relat/fichaCadastral.php">
                <button class="btn btn-info">
                    Ficha Cadastral do Aluno
                </button>
            </form>
        </div>
        <div class="col text-center">
            <form target="_blank" action="<?= HOME_URI ?>/gestao/perfildoalunopdfbranco">
                <button class="btn btn-info">
                    Perfil do Aluno
                </button>
            </form>
        </div>
    </div>
</div>
<br />
<div class="row grd">
    <?php
    if (in_array(tool::id_pessoa(), [1, 6, 6488])) {
        ?>
        <div class="col">
            <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listaspe" name="spe" method="POST">
                <button class="btn btn-info" >
                    Relação de Alunos Sem Paternidade Estabelecida
                </button>
            </form> 
        </div>
        <?php
    }
    ?>
    <div class="col">
        <form target="_blank" action="<?php echo HOME_URI ?>/gestao/lista_nao_frequente" name="transf" method="POST">
            <button class="btn btn-info" >
                Alunos Transferidos
            </button>
        </form>  
    </div>
</div>
<br />
<div class="row grd">

    <div class="col">
        <form target="_blank" action="<?php echo HOME_URI ?>/sed/pdf/planAlunoGeral.php" method="POST">
            <button class="btn btn-info" >
                Listagem Geral de Alunos (Excel)
            </button>
        </form>  
    </div>
</div>
<br />