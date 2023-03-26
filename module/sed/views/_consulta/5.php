<?php

$ra = filter_input(INPUT_POST, 'ra', FILTER_SANITIZE_STRING);
$numClasse = filter_input(INPUT_POST, 'numClasse', FILTER_SANITIZE_STRING);
$uf = filter_input(INPUT_POST, 'uf', FILTER_SANITIZE_STRING);
$situacao = filter_input(INPUT_POST, 'situacao', FILTER_SANITIZE_STRING);
$result = array();
if ($ra && $numClasse && $uf && $situacao) {
    $result = rest::exibirMatriculaClasseRA($ra, $uf, null, $numClasse, $situacao);
}
?>

<form method="POST">

    <div class="row">
        <div class="col">

            <?= formErp::input("ra", "RA", $ra) ?>

        </div>

        <div class="col">

            <?= formErp::input("uf", "UF", $uf) ?>

        </div>


        <div class="col">

            <?= formErp::input("numClasse", "Numero de Classe", $numClasse) ?>

        </div>

        <div class="col">

            <?= formErp::select("situacao", ["Ativo", "Encerrado", "Abandonou", "Transferido", "Baixa - Transferência", "TRANSFERIDO - CEEJA / EAD"
        
        , "TRANSFERIDO (CONVERSÃO DO ABANDONO)", "REMANEJAMENTO", "REMANEJADO (CONVERSÃO DO ABANDONO)", "CESSÃO POR OBJETIVOS ATINGIDOS", "CESSÃO POR NÃO FREQUÊNCIA", "CESSÃO POR TRANSFERÊNCIA/REMANEJAMENTO",
        "CESSÃO POR DESISTÊNCIA", "CESSÃO POR EXAME", "CESSÃO POR NÚMERO REDUZIDO DE ALUNOS", "CESSÃO POR FALTA DE DOCENTE", "CESSÃO POR DISPENSA",
        "CESSÃO POR CONCLUSÃO DO CURSO", "FALECIDO", "NÃO COMPARECIMENTO", "NÃO COMPARECIMENTO / FORA DO PRAZO", "NÃO COMPARECIMENTO - CEEJA / EAD", "RECLASSIFICADO"], "Situacao") ?>

        </div>
        <div class="col">

            <?=
            formErp::hidden([
                'activeNav' => 5
            ]) .
                formErp::button('Buscar');
            ?>


        </div>
    </div>

</form>

<table class="table table-bordered table-hover table-striped">
    <?php if (!empty($result)) : ?>
        <?php foreach ($result['outMatricula'] as $key => $value) : ?>
            <tr>

                <td><?= str_replace('out', '', $key) ?></td>
                <td><?= $value ?></td>


            </tr>


        <?php endforeach ?>
    <?php endif ?>

</table>
