<?php
if (!defined('ABSPATH'))
    exit;
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
if ($id_polo) {
    $p = sql::get('tdics_polo', '*', ['id_polo' => $id_polo], 'fetch');
    $h = $model->getHorarios($id_polo);
}

if (empty($h)) {
    $h = [
        [
            'horario' => '1',
            'periodo' => 'M',
            'inicio' => '',
            'termino' => '',
        ],
        [
            'horario' => '1',
            'periodo' => 'T',
            'inicio' => '',
            'termino' => '',
        ],
        [
            'horario' => '2',
            'periodo' => 'M',
            'inicio' => '',
            'termino' => '',
        ],
        [
            'horario' => '2',
            'periodo' => 'T',
            'inicio' => '',
            'termino' => '',
        ],
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Cadastro de Núcleo
    </div>
    <form action="<?= HOME_URI ?>/<?= $this->controller_name ?>/poloCad" target="_parent" method="POST">
        <div class="row">
            <div class="col-md-8">
                <?= formErp::input('1[n_polo]', 'Polo', @$p['n_polo']) ?>
            </div>
            <div class="col-md-4">
                <?= formErp::select('1[ativo]', [1 => 'Sim', 2 => 'Não'], 'Ativo', @$p['ativo']) ?>
            </div>
        </div>
        <br />
        <br />
        <div class="row">
            <div class="col-md-12 text-center h5">
                Horários
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-12">
                <table class="table text-center">
                    <thead>
                        <tr>
                            <td style="border-right: 1px solid #333;"></td>
                            <td colspan="2" style="border-right: 1px solid #333;">Manhã</td>
                            <td colspan="2">Tarde</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $horario = '';
                        foreach ($h as $key => $value) {

                            if ($horario != $value['horario']) {
                                $style = 'style="border-right: 1px solid #333;"';
                                if (!empty($horario)) {
                                    echo '</tr>';
                                }
                                ?>
                                    <tr>
                                        <td <?php echo $style ?>><?php echo $value['horario'] ?>º Horário</td>
                                <?php
                                $horario = $value['horario'];
                            } else {
                                $style = '';
                            }
                            echo '<td>';
                            echo formErp::input('1[horario]['.$value['periodo'].']['.$value['horario'].'][inicio]', NULL, $value['inicio'], NULL, 'início');
                            echo '</td><td '. $style .'>';
                            echo formErp::input('1[horario]['.$value['periodo'].']['.$value['horario'].'][termino]', NULL, $value['termino'], NULL, 'término');
                            echo formErp::hidden([
                                '1[horario]['.$value['periodo'].']['.$value['horario'].'][id_horarios]' => $value['id_horarios']??NULL
                            ]);
                            echo '</td>';
                        }
                        if (!empty($horario)) {
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div style="text-align: center; padding: 5px">
                    <?=
                    formErp::hidden([
                        '1[id_polo]' => $id_polo
                    ])
                    . formErp::hiddenToken('novoPolo')
                    . formErp::button('Salvar')
                    ?>
                </div>
            </div>
        </div>
        <br />
    </form>
</div>