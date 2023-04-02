<?php
if (!defined('ABSPATH'))
    exit;
if ($model->db->tokenCheck('alunoNovoSet')) {
    $result = $model->alunoNovoSet();
}

$ra = filter_input(INPUT_POST, 'ra', FILTER_UNSAFE_RAW);
$uf = filter_input(INPUT_POST, 'uf', FILTER_UNSAFE_RAW);
?>
<div class="body">
    <div class="fieldTop">
        Cadastro de aluno não alocado
    </div>
    <form method="POST">
        <div class="row">
            <div class="col-5">
                <?= formErp::input('ra', 'RA', $ra, 'required') ?>
            </div>
            <div class="col-3">
                <?= formErp::input('uf', 'UF', $uf, 'required') ?>
            </div>
            <div class="col-4">
                <?=
                formErp::hiddenToken('alunoNovoSet')
                . formErp::button('Enviar')
                ?>
            </div>
        </div>
        <br />
    </form>
    <br /><br />
    <?php
    if ($ra) {
        if (!empty($result)) {
            if (is_array($result)) {
                ?>
                <table class="table table-bordered table-hover table-striped">
                    <tr style="font-weight: bold">
                        <td>
                            Nome
                        </td>
                        <td>
                            RSE
                        </td>
                        <td>
                            Data de Nascimento
                        </td>
                        <td>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?= $result['n_pessoa'] ?>
                        </td>
                        <td>
                            <?= $result['id_pessoa'] ?>
                        </td>
                        <td>
                            <?= data::converteBr($result['dt_nasc']) ?>
                        </td>
                        <td>
                            <?= formErp::submit('Acessar', null, ['id_pessoa' => $result['id_pessoa']], HOME_URI . '/sed/aluno') ?>
                        </td>
                    </tr>
                </table>
                <?php
            } else {
                ?>
                <div class="alert alert-danger">
                    <?= str_replace(['inNumRA', 'inDigitoRA', 'inSiglaUFRA'], ['RA', 'Dígito', 'UF'], $result) ?>
                </div>
                <?php
            }
        }
    }
    ?>
</div>
