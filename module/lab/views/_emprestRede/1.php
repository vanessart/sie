<?php
if (!defined('ABSPATH'))
    exit;

$teste = relatGeral($par, $model);
if (empty($teste)) {
    if (!empty($cpf)) {

        $busca = [$model->servidor(null, $cpf)];
        if (empty($busca[0])) {
            ?>
            <div class="alert alert-danger">
                Não encontramos o CPF "<?= $cpf ?>" em nossa base de dados
            </div>
            <?php
            $search = null;
        } else {
            //lista do resultado
            include ABSPATH . '/module/lab/views/_emprestRede/_2/listResultados.php';
        }
    } else {
        ?>
        <br /><br />
        <div class="alert alert-danger" style="text-align: center; width:  300px; margin: 0 auto">
            Não Encontrado
        </div>
        <?php
    }
}

function relatGeral($par = [], $model) {
    $todos = filter_input(INPUT_POST, 'todos', FILTER_SANITIZE_NUMBER_INT);
    $tuplas = 100;
    $par['tuplas'] = $tuplas;
    $chromeCount = $model->chromeRedeEmprestadoCount($par, $todos);
    if ($chromeCount < 1) {
        return;
    }

    $par['limit'] = report::pagination($tuplas, $chromeCount, $par);

    $chrome = $model->chromeRedeEmprestado($par, null, $todos);

    if (!empty($chrome)) {
        foreach ($chrome as $k => $v) {
            $chrome[$k]['ac'] = formErp::submit('Acessar', null, ['id_ce' => $v['id_ce'], 'activeNav' => 2, 'id_pessoa' => $v['id_pessoa']]);
            $chrome[$k]['sitEmpret'] = empty($v['dt_fim']) ? 'Ativo' : 'Inativo';
        }
        $form['array'] = $chrome;
        $form['fields'] = [
            'nº de Série' => 'serial',
            'Modelo' => 'n_cm',
            'Responsável' => 'n_pessoa',
            'Matrícula' => 'rm',
            'E-mail' => 'emailgoogle',
            'Emprétimo' => 'sitEmpret',
            '||1' => 'ac'
        ];
        echo '<br /><br />';
        report::simple($form);

        if (!empty($chrome)) {
            return 1;
        }
    }
}
