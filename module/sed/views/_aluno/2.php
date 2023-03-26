<?php
if (!defined('ABSPATH'))
    exit;
$resp = $aluno->responsaveis();
$geral = $aluno->dadosPessoais;
$tel = ng_aluno::telefone($id_pessoa);

if ($resp) {
    foreach ($resp as $key => $value) {
        $tr[$value['id_rt']] = $value['id_rt'];
        @$pr[$value['responsavel']]++;
    }
    if (empty($pr[1]) && !empty($resp)) {
        toolErp::alertModal('Um dos CONTATOS precisa ser o Responsável Principal.');
    } elseif (@$pr[1] > 1 && !empty($resp)) {
        toolErp::alertModal('Só pode ter um único responsável Principal');
    }
}
?>
<br />
<div class="border">
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <td colspan="3">
                Contato direto com <?= toolErp::sexoArt($geral['sexo']) ?> Alun<?= toolErp::sexoArt($geral['sexo']) ?> <?= $geral['n_pessoa'] ?>
            </td>
        </tr>
        <tr>
            <td style="width: 200px">
                E-mail
            </td>
            <td>
                <?= $geral['email'] ?>
            </td>
            <td rowspan="2" style="text-align: center; vertical-align: auto">
                <button onclick="editarAluno()" class="btn btn-info">
                    Editar Contato Direto
                </button>
            </td>
        </tr>
        <tr>
            <td>
                Email Institucional
            </td>
            <td>
                <?= $geral['emailgoogle'] ?>
            </td>
        </tr>
        <?php
        if ($tel) {
            foreach ($tel as $v) {
                ?>
                <tr>
                    <td>
                        Telefone <?= $v['n_tt'] ?>
                    </td>
                    <td>
                        <?= !empty($v['ddd']) ? '(' . $v['ddd'] . ')' : '' ?> <?= $v['num'] ?>
                    </td>
                    <td>
                        <?= !empty($v['complemento']) ? 'Obs: ' . $v['complemento'] : '' ?>   
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="2">
                    Não há telefone cadastrado
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>
<br />
<div class="border">
    <div class="row">
        <div class="col-4">
            <button class="btn btn-primary" onclick="NovoResponsavel()">Novo Contato</button>
        </div>
        <div class="col-8">
            <form id="spe" method="POST">
                <?=
                formErp::hiddenToken('spe')
                . formErp::hidden(['id_pessoa' => $id_pessoa,
                    '1[id_pessoa]' => $id_pessoa,
                    'id_pessoa' => $id_pessoa,
                    'activeNav' => 2
                ])
                . formErp::checkbox('1[pai]', 'SPE', 'SPE (Sem Paternidade Estabelecida na Certidão de Nascimento)', ($aluno->dadosPessoais['pai'] == 'SPE' ? 'SPE' : 'X'), '  onclick="document.getElementById(\'spe\').submit();" ');
                ?>
            </form>
        </div>
    </div>
</div>
<br />
<?php
if ($resp) {
    foreach ($resp as $v) {
        if ($v['responsavel'] == 1) {
            $classResp = 'alert alert-primary';
        } else {
            $classResp = '';
        }
        ?>
        <div class="border <?= $classResp ?>">
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <td colspan="4">
                        <?= $v['n_pessoa'] ?>, <?= strtolower($v['n_rt']) ?> d<?= toolErp::sexoArt($geral['sexo']) ?> alun<?= toolErp::sexoArt($geral['sexo']) ?> <?= ($v['responsavel'] == 1) ? 'e responsável pela Matrícula' : '' ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 200px">
                        E-mail
                    </td>
                    <td>
                        <?= empty($v['emailgoogle']) ? 'E-mail não cadastrado' : $v['emailgoogle'] ?>
                    </td>
                    <td rowspan="4" style="text-align: center; width: 100px">
                        <form id="resp_<?= $v['id_pessoa'] ?>" method="POST">
                            <?=
                            formErp::hidden([
                                'fk_id_pessoa_resp' => $v['id_pessoa'],
                                'fk_id_pessoa_aluno' => $id_pessoa,
                                'id_pessoa' => $id_pessoa,
                                'activeNav' => 2
                            ])
                            . formErp::hiddenToken('excluirResp')
                            ?>
                        </form>
                        <button class="btn btn-danger" onclick="if (confirm('Excluir Responsável?')) {
                                    resp_<?= $v['id_pessoa'] ?>.submit()
                                }" type="button">
                            Excluir
                        </button>
                    </td>
                    <td rowspan="4" style="text-align: center; width: 100px">
                        <button class="btn btn-info" onclick="NovoResponsavel('<?= $v['id_pessoa'] ?>')">
                            Editar
                        </button>
                    </td>
                </tr>
                <?php
                if ($v['tel']) {
                    foreach ($v['tel'] as $t) {
                        ?>
                        <tr>
                            <td>
                                Telefone <?= $t['n_tt'] ?>
                            </td>
                            <td>
                                <?= !empty($t['ddd']) ? '(' . $t['ddd'] . ')' : '' ?> <?= $t['num'] ?>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="2">
                            Não há telefone cadastrado
                        </td>
                    </tr>
                    <?php
                }
                if (!empty($v['trabalho'])) {
                    ?>
                    <tr>
                        <td>
                            Empregador
                        </td>
                        <td>
                            <?= $v['trabalho'] ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
        <br />
        <?php
    }
}

if ((empty($tr[1]) or empty($tr[2])) && (!empty($aluno->dadosPessoais['pai']) or!empty($aluno->dadosPessoais['mae']))) {
    ?>
    <br /><br />
    <div class="border">
        <div class="alert alert-warning" style="font-weight: bold">
            <p>
                Filiação importada do SED
            </p>
            <p>
                Para incluir CPF, e-mail, telefone ou conceder acesso ao APP DO ALUNO, clique em "Editar"
            </p>
        </div>
        <br /><br />
        <table class="table table-bordered table-hover table-responsive">
            <?php
            foreach ([1 => 'mae', 2 => 'pai'] as $k => $v) {
                if (!empty($aluno->dadosPessoais[$v]) && empty($tr[$k])) {
                    ?>
                    <tr>
                        <td style="width: 200px">
                            Filiação <?= $k ?>
                        </td>
                        <td>
                            <?= $aluno->dadosPessoais[$v] ?>
                        </td>
                        <td style="width: 200px">
                            <button class="alert alert-warning" type="button" onclick="responsavelMaePai(<?= $k ?>, '<?= str_replace(['"', "'"], ['', ''], $aluno->dadosPessoais[$v]) ?>')">
                                Editar
                            </button>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
    </div>
    <?php
}
?>

<form action="<?= HOME_URI ?>/sed/def/formResp.php" target="frame" method="post" id="form">

    <input type="hidden" name="id_rt" id="id_rt">
    <input type="hidden" name="nome" id="nome">
    <input type="hidden" name="id_pessoa_resp" id="id_pessoa_resp">
    <?= formErp::hidden($hidden) ?>
</form>


<form action="<?= HOME_URI ?>/sed/def/formAlunoContato.php" target="frame" method="post" id="formAluno">
    <?= formErp::hidden($hidden) ?>
</form>


<?php
toolErp::modalInicio();
?>
<iframe name="frame" style="width:100%; height:80vh; border:none; "></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function NovoResponsavel($id) {
        if ($id) {
            document.getElementById('id_pessoa_resp').value = $id;
        } else {
            document.getElementById('id_pessoa_resp').value = '';

        }
        document.getElementById('form').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function responsavelMaePai($id_rt, $nome) {
        id_rt.value = $id_rt;
        nome.value = $nome;
        document.getElementById('form').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }

    function editarAluno() {
        document.getElementById('formAluno').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>