<?php
if (!defined('ABSPATH'))
    exit;
extract(toolErp::postFilter([
            'nao_hac',
            'rm',
            'edit',
            'disciplinas',
            'n_pessoa',
            'fk_id_psc',
            'hac_periodo',
            'id_pe',
            'hac_periodo',
            'hac_dia'
                ], 2));
@$fun = sql::get(['ge_funcionario', 'pessoa'], 'rm, n_pessoa', " where funcao like '%prof%' order by n_pessoa ");
foreach ($fun as $v) {
    $funcionarios[$v['rm']] = $v['rm'] . ' - ' . $v['n_pessoa'];
}
$disc = sql::get('ge_disciplinas', '*', 'WHERE status_disc = True');
foreach ($disc as $v) {
    $disci[$v['id_disc']] = $v['n_disc'];
}

$disci['nc'] = "Professor Polivalente";
$disci['20'] = 'Informática';
$disci['16'] = 'Filosofia';

if (!empty($disciplinas)) {
    $disciplinas = explode('|', $disciplinas);
}
if (!empty($n_pessoa)) {
    $_POST['n_pe'] = $n_pessoa;
    $edit = 1;
}
?>
<div class="body">
    <form method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::select('rm', @$funcionarios, 'Matrícula e Nome', $rm, null, null, ' required ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::selectDB('ge_prof_sit_classe', 'fk_id_psc', 'Tipo Contrato', $fk_id_psc) ?>
            </div>
            <div class="col">
                <?= formErp::checkbox('nao_hac', 1, 'Não faz HTPC nesta escola', $nao_hac, ' onclick="hac()" id="hcc" ') ?>
            </div>
        </div>
        <br />
        <div id="hc" class="border">
            <div style="text-align: center">
                HTPC (<span style="color: red">De acordo com documento de atribuição</span>)
            </div>
            <br />
            <div class="row">
                <div class="col">
                    <?= formErp::select('hac_dia', [2 => '2ª Feira', 3 => '3ª Feira', 4 => '4ª Feira', 5 => '5ª Feira'], 'Dia da Semana', $hac_dia) ?>
                </div>
                <div class="col">
                    <?= formErp::select('hac_periodo', ['Tarde' => 'Tarde', 'Noite' => 'Noite'], 'Período', $hac_periodo) ?>
                </div>
            </div>
            <br />
        </div>
        <br />
        <div class="row">
            <?php
            foreach ($disci as $k => $v) {
                if (!empty($disciplinas)) {
                    if (in_array($k, $disciplinas)) {
                        $checked = "checked";
                    } else {
                        $checked = NULL;
                    }
                } else {
                    $checked = NULL;
                }
                ?>
                <div class="col-4">
                    <?= formErp::checkbox('disc[]', $k, $v, ($checked ? $k : null), ' id="' . $k . '" ') ?>
                </div>
                <?php
            }
            ?>
        </div>
        <input type="hidden" name="id_pe" value="<?php echo $id_pe ?>" />

        <br /><br />
        <div class="row">
            <div class="col text-center">
                <a href="#" onclick="document.lp.submit()">
                    <button type="button" class=" btn btn-info">
                        Limpar
                    </button>
                </a>
            </div>
            <div class="col text-center">
                <button onmousemove="verif()" name="cadprof" value="1" type="submit" class=" btn btn-success">
                    Incluir/Editar
                </button>
            </div>
        </div>
        <br />
    </form>
    <form name="lp" method="POST">
        <input type="hidden" name="edit" value="1" />
    </form>

</div>
<script>
    function verif() {
        if (document.getElementById('9').checked == true && document.getElementById('12').checked == true && document.getElementById('6').checked == true) {
            alert('Tem certeza? Você não está querendo cadastrar um "Professor Polivalente" ');
        }
    }

    function sem() {
        if (document.getElementById("emailPessoa").value == 'Professor(a) sem e-mail') {
            document.getElementById("emailPessoa").checked = true;
            document.getElementById("emailPessoa").value = document.getElementById("emailOld").value;
            document.getElementById("emailPessoa").readOnly = false;
        } else {
            document.getElementById("emailPessoa").checked = false;
            document.getElementById("emailOld").value = document.getElementById("email").value;
            document.getElementById("emailPessoa").value = 'Professor(a) sem e-mail';
            document.getElementById("emailPessoa").readOnly = true;
        }
    }

    function hac() {
        if (document.getElementById("hcc").checked == true) {
            document.getElementById('hc').style.display = 'none'
        } else {
            document.getElementById('hc').style.display = ''
        }

    }
</script>