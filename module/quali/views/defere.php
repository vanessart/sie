<?php
if (!defined('ABSPATH'))
    exit;

$situacao = filter_input(INPUT_POST, 'situacao', FILTER_SANITIZE_STRING);
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
$curso = filter_input(INPUT_POST, 'curso', FILTER_SANITIZE_NUMBER_INT);
$opt = filter_input(INPUT_POST, 'opt', FILTER_SANITIZE_NUMBER_INT);
$pag = filter_input(INPUT_POST, 'pagina', FILTER_SANITIZE_NUMBER_INT);
$tuplas = 100;
$formulario = $model->_formAdm;
if (!empty($nome)) {
    $nome_ = " and (cpf = '$nome' or nome like '%$nome%') ";
} else {
    $nome_ = null;
}
if (!empty($situacao)) {
    if ($situacao == 'X') {
        $situacao_ = " and situacao is null ";
    } else {
        $situacao_ = " and situacao = $situacao ";
    }
} else {
    $situacao_ = null;
}
if (!empty($curso)) {
    if ($opt == 2) {
        $id_cur = 'fk_id_cur_2';
    } else {
        $id_cur = 'fk_id_cur';
    }
    $curso_ = " and $id_cur = $curso ";
} else {
    $curso_ = null;
}
$limit = intval(@$pag) * $tuplas;
$pag_ = " limit $limit, $tuplas ";
$sql = "SELECT COUNT(id_inscr) as ct FROM `quali_incritos_$formulario` where 1 "
        . $nome_
        . $situacao_
        . $curso_;
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetch(PDO::FETCH_ASSOC);
$conta = $array['ct'];
$sql = "SELECT i.time_stamp, i.id_inscr, i.nome, i.cpf, c.n_cur, c2.n_cur as n_cur2, situacao FROM `quali_incritos_$formulario` i "
        . " join gt_curso c on c.id_cur = i.fk_id_cur "
        . " left join gt_curso c2 on c2.id_cur = i.fk_id_cur_2 "
        . " where 1 "
        . $nome_
        . $situacao_
        . $curso_
        . 'order by time_stamp '
        . $pag_;
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
$sitSet = [null => 'Aguardando Deferimento', 1 => 'Deferido', 2 => 'Indeferido'];
foreach ($array as $key => $value) {
    if ($value['situacao'] == 1) {
        $btn = "success";
    } elseif ($value['situacao'] == 2) {
        $btn = "danger";
    } else {
        $btn = "warning";
    }
    $array[$key]['hd'] = data::converteBr(substr($value['time_stamp'], 0, 10)) . substr($value['time_stamp'], 10);
    $array[$key]['sit'] = $sitSet[$value['situacao']];
    $array[$key]['ac'] = '<button onclick="acessar(\'' . $value['id_inscr'] . '\')" class="btn btn-dark">Acessar</button>';
    $array[$key]['muda'] = '<select class="btn btn-' . $btn . '" id="' . $value['id_inscr'] . '" onchange="muda(this)"><option value="">Aguardando Deferimento</option><option value="1" ' . ($value['situacao'] == 1 ? ' selected ' : null) . '>Deferido</option><option value="2" ' . ($value['situacao'] == 2 ? ' selected ' : null) . '>Indeferido</option></select>';
}
$form['array'] = $array;
$form['fields'] = [
    'Data e Hora' => 'hd',
    'Nome' => 'nome',
    'CPF' => 'cpf',
    'Curso 1ª Opção' => 'n_cur',
    'Curso 2ª Opção' => 'n_cur2',
    '||2' => 'muda',
    '||1' => 'ac'
];
$cursos = $model->CursoInscr($formulario);
?>
<div class="body">
    <div class="fieldTop">
        Inscritos
    </div>
    <br /><br />
    <form method="POST">
        <div class="border4" style="padding: 10px">
            <div class="row">
                <div class="col-9">
                    <?= form::input('nome', 'Nome ou CPF', $nome) ?>
                </div>
                <div class="col-3">
                    <?= form::select('situacao', ['X' => 'Aguardando Deferimento', 1 => 'Deferido', 2 => 'Indeferido'], ['Situação', 'Todos'], $situacao) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-6">
                    <?= form::select('curso', $cursos, ['Curso', 'Todos'], $curso) ?>
                </div>
                <div class="col-3">
                    <?php
                    if (!empty($curso)) {
                        echo form::input(null, 'Deferidos', null, ' readonly id="contDef" ');
                    }
                    ?>
                </div>
                <div class="col-3">
                    <?= form::select('opt', [2 => '2ª Opção'], ['Opção', '1ª Opção'], $opt) ?>
                </div>
            </div>
            <br /><br />    
            <div class="row">
                <div class="col text-center">
                    <button onclick="$('#exp').submit()" class="btn btn-success">
                        Exportar
                    </button>
                </div>
                <div class="col text-center">
                    <a href="">
                        <button type="button" class="btn btn-warning">
                            Limpar Filtros
                        </button>
                    </a>
                </div>
                <div class="col text-center">
                    <?= form::button('Buscar') ?>
                </div>
            </div>
            <br />
        </div>
    </form>
    <form target="_blank" id="exp" action="<?= HOME_URI ?>/quali/exportar" method="POST">
        <?=
        form::hidden([
            'nome' => $nome,
            'situacao' => $situacao,
            'curso' => $curso,
            'opt' => $opt,
            'formulario' => $formulario
        ])
        ?>
    </form>
    <br /><br />
    <?php
    report::pagination($tuplas, $conta, ['situacao' => $situacao, 'nome' => $nome, 'curso' => $curso, 'opt' => $opt]);
    report::simple($form);
    ?>
</div>
<form id="formFrame" target="frame" action="<?= HOME_URI ?>/quali/def/formInscrEdit.php" method="POST">
    <input type="hidden" id="id_inscr" name="id_inscr" value="" />
    <input type="hidden" name="formulario" value="<?= $formulario ?>" />
    <?=
    form::hidden([
        'nome' => $nome,
        'situacao' => $situacao,
        'curso' => $curso,
        'opt' => $opt,
        'pagina' => $pag
    ])
    ?>
</form>
<?php
tool::modalInicio();
?>
<iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    tool::modalFim();
    ?>
<script>
<?php
if (!empty($curso)) {
    ?>
        $(document).ready(function () {
            conta();
        });
        function conta() {
            const data = 'id_cur=<?= $curso ?>';
            fetch("<?= HOME_URI ?>/quali/api/2", {
                method: 'POST',
                body: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
                    .then(res => res.text()).
                    then(res => {
                        $('#contDef').val(res);
                    });

        }
    <?php
} else {
    ?>
        function conta() {
            console.log('x');
        }
    <?php
}
?>
    function acessar(id) {
        $('#id_inscr').val(id);
        $('#formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function muda(ob) {

        const  dados = "id_inscr=" + ob.id + '&situacao=' + ob.value;
        fetch(
                "<?= HOME_URI ?>/quali/api/1",
                {
                    method: 'POST',
                    body: dados,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                }
        ).then(res => res.json())
                .then(res => {
                    if (res['result'] == 1) {
                        if (ob.value == 1) {
                            $('#' + ob.id).removeClass('btn-warning');
                            $('#' + ob.id).removeClass('btn-danger');
                            $('#' + ob.id).addClass('btn-success');
                        } else if (ob.value == 2) {
                            $('#' + ob.id).removeClass('btn-warning');
                            $('#' + ob.id).removeClass('btn-success');
                            $('#' + ob.id).addClass('btn-danger');
                        } else {
                            $('#' + ob.id).removeClass('btn-danger');
                            $('#' + ob.id).removeClass('btn-success');
                            $('#' + ob.id).addClass('btn-warning');
                        }
                    }
                }).then(() => conta());
    }
</script>