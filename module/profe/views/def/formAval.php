<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = toolErp::id_pessoa();
$uniqid = filter_input(INPUT_POST, 'uniqid', FILTER_UNSAFE_RAW);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_UNSAFE_RAW);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_UNSAFE_RAW);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_UNSAFE_RAW);
$atual_letiva = filter_input(INPUT_POST, 'atual_letiva', FILTER_UNSAFE_RAW);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_UNSAFE_RAW);
$n_disc = filter_input(INPUT_POST, 'n_disc', FILTER_UNSAFE_RAW);
$alunos = ng_escola::alunoPorTurma($id_turma);
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_UNSAFE_RAW);
$hidden = [
    'id_inst' => $id_inst,
    'id_pl' => $id_pl,
    'id_turma' => $id_turma,
    'id_curso' => $id_curso,
    'n_turma' => $n_turma,
    'n_disc' => $n_disc,
    'atual_letiva' => $atual_letiva,
    'uniqid' => $uniqid,
    'id_disc' => $id_disc
];
$mongo = new mongoCrude('Diario');
$result = $mongo->query('instrumentos.' . $id_pl, ['uniqid' => $uniqid]);
if (!empty($result[0]->notas)) {
    $notas = (array) $result[0]->notas;
}
?>
<div class="body">
    <form target="_parent" action="<?= HOME_URI ?>/profe/notaTabela" method="post">
        <?php foreach ($alunos as $key => $value) { ?>
            <div class="border" role="alert" style="font-size:17px; margin-bottom:30px">

                <table style="width: 100%">
                    <tr>
                        <td style="padding: 5px">
                            <p style="font-size:22px">
                                <?= "Nº " . $value['chamada'] . ' - ' . $value['n_pessoa'] ?>
                            </p>  
                            <div class="input-group" style="width: 150px">
                                <span class="input-group-text" id="basic-addon1">Nota</span>
                                <input name="nota[<?= $value['id_pessoa'] ?>]"  type="text" class="form-control" describedby="basic-addon1" value="<?= @$notas[$value['id_pessoa']] ?>" >
                            </div>  
                        </td>
                        <td style="width: 80px">
                            <?php if (file_exists(ABSPATH . '/pub/fotos/' . $value['id_pessoa'] . '.jpg')) { ?>
                                <img style="width: 100%" src="<?= HOME_URI . '/pub/fotos/' . $value['id_pessoa'] . '.jpg' ?>">
                            <?php } else { ?>
                                <img style="width: 100%" src="<?= HOME_URI . '/includes/images/anonimo.jpg' ?>">
                            <?php } ?>  
                        </td>
                    </tr>
                </table>
            </div>
            <?php
        }
        ?>
        <div style="text-align: center" id="salvar" >
            <?=
            formErp::hidden([
                $key => $alunos[$key]['id_pessoa'],
                'id_inst' => $id_inst,
                'id_pessoa' => $id_pessoa
            ])
            . formErp::hidden($hidden)
            . formErp::hiddenToken('cadastroInstrumentoNota')
            ?>
            <button class="btn btn-success">
                Salvar
            </button>
        </div>
    </form>
</div>
