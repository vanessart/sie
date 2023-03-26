<style>
    /*Using CSS for table*/
    .demotbl {
        border-collapse: collapse;
        border: 1px solid #69899F;
        width: 100%;
    }
    .demotbl th{
        border: 2px solid #69899F;
        color: #3E5260;
        padding:5px;
    }
    .demotbl td{
        text-align: center;
        border: 1px dotted black;
        color: #002F5E;
        padding:8px;
        width:100px;

    }
    .nota{
        background-color: ghostwhite;
    }
    .disc{
        background-color: green;
        color: white !important;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$apd = $model->alunosApd($id_pessoa);
if ($apd) {
    ?>
    <div class="alert alert-danger" style="font-weight: bold;">
        As notas de Aluno com deficiência de Grande Porte não podem ser alteradas
    </div>
    <?php
    die();
}
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$pess = sqlErp::get('pessoa', 'n_pessoa, sexo', ['id_pessoa' => $id_pessoa], 'fetch');
$notas = $model->mediaFaltaAluno($id_pessoa, $id_turma);
$turma = $model->_turma;
$pl = sqlErp::get('ge_periodo_letivo', 'n_pl', ['id_pl' => $turma['id_pl']], 'fetch')['n_pl'];
$super = $model->supervisor($turma['id_inst'], $turma['id_curso']);
$disciplinas = $model->_disciplinas; // a chave é o id_disc
$temNc = 0;
foreach ($disciplinas as $d) {
    if ($d['nucleo_comum'] == 1) {
        $temNc = 1;
        @$col++;
    } else {
        @$col += 2;
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Alterações de notas e faltas
    </div>
    <div class="alert alert-danger" style="font-weight: bold;">
        <p style="text-align: center">
            <?= explode(' ', toolErp::n_pessoa())[0] ?>, Atenção
        </p>
        <p>
            Utilize este recurso com responsabilidade.
        </p>
        <p>
            As alterações realizadas nesta página serão registradas na ATA como ementa.
        </p>
        <p>
            Comunicaremos por e-mail (<?= $super['emailgoogle'] ?>) <?= $super['sexo'] == 'F' ? 'à' : 'ao' ?> supervisor<?= $super['sexo'] == 'F' ? 'a' : '' ?> <?= $super['n_pessoa'] ?> as alterações realizadas
        </p>
        <p>
            Disponibilizaremos as alterações à coordenadoria para eventuais auditorias.
        </p>
    </div>
    <br />
    <div class="fieldTop">
        alun<?= toolErp::sexoArt($pess['sexo']) ?> <?= $pess['n_pessoa'] ?> no Período Letivo de <?= $pl ?>
    </div>
    <br />
    <form action="<?= HOME_URI ?>/avalia/def/bimEditFim" method="POST">
        <table class="demotbl border" >
            <tr>
                <td colspan="2" class="disc">
                    Disciplinas
                </td>
                <?php
                foreach ($disciplinas as $d) {
                    if ($d['nucleo_comum'] == 1) {
                        ?>
                        <td class="disc">
                            <?= $d['n_disc'] ?>
                        </td>
                        <?php
                    }
                }
                foreach ($disciplinas as $d) {
                    if ($d['nucleo_comum'] == 0) {
                        ?>
                        <td class="disc" colspan="2">
                            <?= $d['n_disc'] ?>
                        </td>
                        <?php
                    }
                }
                if ($temNc == 1) {
                    ?>
                    <td class="disc">
                        Núcleo Comum
                    </td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <td colspan="2">

                </td>
                <?php
                foreach ($disciplinas as $d) {
                    if ($d['nucleo_comum'] == 1) {
                        ?>
                        <td class="nota" style="text-align: center">
                            Notas
                        </td>
                        <?php
                    }
                }
                foreach ($disciplinas as $d) {
                    if ($d['nucleo_comum'] == 0) {
                        ?>
                        <td class="nota">
                            Notas
                        </td>
                        <td>
                            Faltas
                        </td>
                        <?php
                    }
                }
                if ($temNc == 1) {
                    ?>
                    <td>
                        Faltas
                    </td>
                    <?php
                }
                ?> 
            </tr>
            <?php
            foreach ($notas as $bim => $v) {
                ?>
                <tr>
                    <td colspan="2">
                        <?= $bim ?>º <?= $turma['un_letiva'] ?>
                    </td>
                    <?php
                    foreach ($disciplinas as $id_disc => $d) {
                        if ($d['nucleo_comum'] == 1) {
                            $nota = str_replace('.', ',', @$notas[$bim]['media_' . $id_disc]);
                            ?>
                            <td class="nota" style="text-align: center;">
                                <input style="width: 100%; border: #69899F medium solid " type="text" name="<?= 'notaEdit[' . $bim . '][' . $id_disc . ']' ?>" value="<?= $nota ?>" />
                            </td>
                            <?php
                        }
                    }
                    foreach ($disciplinas as $id_disc => $d) {
                        if ($d['nucleo_comum'] == 0) {
                            $nota = str_replace('.', ',', @$notas[$bim]['media_' . $id_disc]);
                            $falta = @$notas[$bim]['falta_' . $id_disc];
                            ?>
                            <td class="nota">
                                <input style="width: 100%; border:#69899F medium solid " type="text" name="<?= 'notaEdit[' . $bim . '][' . $id_disc . ']' ?>" value="<?= $nota ?>" />
                            </td>
                            <td>
                                <input style="width: 100%; border: #69899F medium solid " type="text" name="<?= 'faltaEdit[' . $bim . '][' . $id_disc . ']' ?>" value="<?= intval($falta) ?>" />
                            </td>
                            <?php
                        }
                    }
                    if ($temNc == 1) {
                        $falta = @$notas[$bim]['falta_nc'];
                        ?>
                        <td>
                            <input style="width: 100%; border: #69899F medium solid " type="text" name="<?= 'faltaEdit[' . $bim . '][nc]' ?>" value="<?= intval($falta) ?>" />
                        </td>
                        <?php
                    }
                    ?> 
                </tr>
                <?php
            }
            ?>
        </table>
        <br /><br />
        <div class="row">
            <div class="col">
                <?= formErp::textarea('motivo', null, 'Justifique as alterações<br />(obrigatório)') ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 40px">
            <?=
            formErp::hidden([
                'id_turma' => $id_turma,
                'id_pessoa' => $id_pessoa,
            ])
            . formErp::button('Continuar')
            ?>
        </div>
    </form>
</div>