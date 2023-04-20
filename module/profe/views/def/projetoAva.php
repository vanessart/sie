<?php
if (!defined('ABSPATH'))
    exit;
if(tool::id_pessoa()){
$hab = [];
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_UNSAFE_RAW);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'fk_id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_projeto = filter_input(INPUT_POST, 'fk_id_projeto', FILTER_SANITIZE_NUMBER_INT);
$id_ciclo = filter_input(INPUT_POST, 'fk_id_ciclo', FILTER_UNSAFE_RAW);
$n_projeto = filter_input(INPUT_POST, 'n_projeto', FILTER_UNSAFE_RAW);
$id_disc = filter_input(INPUT_POST, 'fk_id_disc', FILTER_SANITIZE_NUMBER_INT);
$id_ava = filter_input(INPUT_POST, 'id_ava', FILTER_SANITIZE_NUMBER_INT);
$msg_coord = filter_input(INPUT_POST, 'msg_coord', FILTER_UNSAFE_RAW);
$titulo = 'Registro do Projeto: ' . $n_projeto;

if ($id_ava) {
    $registro = sql::get('profe_projeto_ava', '*', 'WHERE id_ava =' . $id_ava, 'fetch');
}
?>
<style type="text/css">
    .input-group-text
    {
        width: 220px;
    }
</style>
<div class="body">
    <div class="fieldTop" style="padding-bottom: 5%;">
        Avaliação Processual
    </div>
    <form name='form'  action="<?= HOME_URI ?>/profe/projeto" method="POST" target='_parent'>   
        <div class="row">
            <div class="col">
                <?= formErp::input('1[dt_ava]', 'Início', empty($registro['dt_ava']) ? date("Y-m-d") : $registro['dt_ava'], null, null, 'date') ?>
            </div>
            <div class="col">
                <?= formErp::input('1[dt_fim]', 'Término', @$registro['dt_fim'], null, null, 'date') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[situacao]', @$registro['situacao'], 'Avaliação Processual') ?>
            </div>
        </div>
        <br> 
        <div class="row">
            <div class="col text-center">

                <?=
                formErp::hidden([
                    'activeNav' => 4,
                    '1[fk_id_projeto]' => $id_projeto,
                    '1[fk_id_pessoa]' => tool::id_pessoa(),
                    'fk_id_projeto' => $id_projeto,
                    'fk_id_turma' => $id_turma,
                    '1[id_ava]' => @$id_ava,
                    'fk_id_disc' => @$id_disc,
                    'fk_id_ciclo' => @$id_ciclo,
                    'n_projeto' => $n_projeto,
                    'n_turma' => $n_turma,
                    'msg_coord' => $msg_coord,
                    'id_inst' => $id_inst
                ])
                . formErp::hiddenToken('profe_projeto_ava', 'ireplace')
                . formErp::button('Salvar');
                ?>            
            </div>
        </div>     
    </form>
</div>
<script>
    hab = <?= json_encode($hab) ?>;
<?php
if (empty($habil)) {
    ?>
        exist = [];
    <?php
} else {
    ?>
        exist = <?= json_encode($habil) ?>;
    <?php
}
?>

    function idhab(id) {
        if (exist[id]) {
            alert("Habilidade já Selecionada");
        } else {
            exist[id] = id;
            tbodyTex = tbody.innerHTML;
            tbody.innerHTML = tbodyTex + "<tr id='" + id + "'><td>" + hab[id] + "</td><td><button type=\"button\" class=\"btn btn-danger\" onclick=\"apaga(" + id + ")\">X</button><input type=\"hidden\" name=\"hab[" + id + "]\" value=\"" + id + "\" /></td></tr>";
        }
    }
    function apaga(id) {
        delete exist[id];
        document.getElementById(id).innerHTML = '';
    }
</script>
<?php
} else {
    echo 'Algo errado não está certo :(';    
}
?>
