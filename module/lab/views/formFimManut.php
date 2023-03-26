<?php
if (!defined('ABSPATH'))
    exit;
$id_ce = filter_input(INPUT_POST, 'id_ce', FILTER_SANITIZE_NUMBER_INT);
$emprest = $model->emppretimo($id_ce);
$sit = sql::get('lab_chrome_emprestimo_sit', '*', 'where id_ces in (5, 6, 7) ');
$sitEquip = toolErp::idName($sit, 'id_ces', 'equipamento');
$sit = sql::get('lab_chrome_emprestimo_sit');
$sitCarr = toolErp::idName($sit, 'id_ces', 'carregador');
?>
<div class="body">

    <form method="POST"  action="<?= HOME_URI ?>/lab/def/formManut.php">
        <div class="fieldTop">
            Encerrar processo de Empréstimo
        </div>
        <div class="row">
            <div class="col text-center">
                <?= formErp::input('dt_fim', 'Data da Devolução', date("Y-m-d"), null, null, 'date') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('fk_id_ces_equip', $sitEquip, 'Equipamento', 5, null, null, ' required ', null, 1) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('fk_id_ces_carr', $sitCarr, 'Carregador', 3, null, null, ' required ', null, 1) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::textarea('obs', null, 'Observações') ?>
            </div>
        </div>
        <br /><br />
        <div style="text-align: center">
            <?=
            formErp::hidden([
                '1[fk_id_pessoa_lanc]' => toolErp::id_pessoa(),
                '1[fk_id_cd]' => 5,
                'dt_inicio' => $emprest['dt_inicio'],
                '1[fk_id_cs]' => 1,
                '1[fk_id_pessoa]' => null,
                '1[email_google]' => null,
                '1[fk_id_ch]' => $emprest['id_ch'],
                '1[id_ch]' => $emprest['id_ch'],
                '1[recadastro]' => 0,
                'devolucao' => 1,
                'activeNav' => 2
            ])
            . formErp::hidden([
                'id_ce' => $id_ce,
                'id_pessoa' => $emprest['id_pessoa'],
                'id_ch' => $emprest['id_ch'],
                'fk_id_pessoa_lanc' => toolErp::id_pessoa(),
                'link'=> 2
            ])
            . formErp::button('Enviar')
            . formErp::hiddenToken('finalEmprestimoRede')
            ?>
        </div>
        <br /><br />
    </form>
</div>
<?php
toolErp::modalInicio()
?>
Descreva a ocorrência no campo Observações.
<?php
toolErp::modalFim();
?>
<script>
    function fk_id_ces_equip(id) {
        if (id != 3) {
            $('#myModal').modal('show');
            $('.form-class').val('');
            document.getElementById('obs').placeholder = "Descreva aqui a ocorrência.";
        }
    }
    function fk_id_ces_carr(id) {
        if (id != 3) {
            $('#myModal').modal('show');
            $('.form-class').val('');
            document.getElementById('obs').placeholder = "Descreva aqui a ocorrência.";
        }
    }
</script>