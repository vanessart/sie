<?php

$turmaEscola = explode('/', $_GET['path']);

$id_inst = $turmaEscola[2];
if (tool::id_nivel() == 43) {
    $id_inst = tool::id_inst();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST'):

    $alert1 = "<script> alert('E-mail já existe em nossa base!') </script>";
    $alert2 = "<script> alert('E-mail cadastrado com sucesso!') </script>";

    $dados = $_POST;
    $emailExiste = $model->verificaEmail($dados);

    if ($emailExiste) {
        echo $alert1;
    } else {
        $array = $model->cadastrarEmailAluno($dados);
        echo $alert2;
    }

    unset($_POST);
endif;

$codigo_classe = $turmaEscola[4];

$classe = $model->turmaEscola($id_inst, $codigo_classe);

//Verifica se nível é : Administrador E-mail Google
$nivel = $_SESSION['userdata']['n_nivel'];

###################
?>

<br>

<link rel="stylesheet" href="<?php echo HOME_URI ?>/_css/style.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <a href="<?= HOME_URI ?>/info/emailAlunoEscola/<?= $id_inst ?>" class="btn btn-enviar">Voltar</a>
        </div>
    </div>
</div>

<br>

<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1 class="title-escolas"><?= @$classe[0]['n_inst'] ?> <?= @$classe[0]['n_turma'] ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php if ($classe): ?>
                <table class="table table-striped table-bordered table-sm">
                    <thead class="card-header">
                        <tr>
                            <th scope="col" class="text-center" style="width: 50px;">RSE</th>
                            <th scope="col" class="text-center" style="width: 50px;">RA</th>
                            <th scope="col">Nome</th>
                            <th scope="col" class="text-center">Data de Nascimento</th>
                            <th scope="col" class="text-center" style="width: 300px;">E-mail</th>
                            <th scope="col" class="text-center" style="width: 100px;">Validar</th>
                            <th scope="col" class="text-center" style="width: 100px;">Acessar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($classe as $key => $value): ?>
                        <form action="#" method="post">
                            <input type="hidden" name="at_google" value="0" />
                            <tr>
                                <td class="text-center">
                                    <p class="text-prof"><?= $value['id_pessoa'] ?></p>
                                </td>
                                <td class="text-center">
                                    <p class="text-prof"><?= $value['ra'] ?></p>
                                </td>
                                <td>
                                    <p class="text-prof"><?= $value['n_pessoa'] ?></p>
                                </td>
                                <td>
                                    <p class="text-prof text-center"><?= data($value['dt_nasc']) ?></p>
                                </td>

                                <!-- Cadastrar -->
                                <?php if (!$value['emailgoogle'] || is_numeric($value['emailgoogle'])): ?>
                                    <td>
                                        <input type="email" name="emailgoogle" class="form-control email mascara"
                                               placeholder=" Insira o e-mail @aluno.barueri.br" id="cadastrar" required>
                                    </td>
                                    <td>
                                        <input <?= $value['at_google'] == 1 ? 'checked' : '' ?> type="checkbox" name="at_google" value="1" />
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-success" name="cadastrar">Cadastrar</button>
                                    </td>
                                    <!-- Editar -->
                                <?php elseif ($nivel == 'Administrador Email Google'): ?>
                                    <td>
                                        <input type="text" name="emailgoogle" id="emailgoogle" class="form-control"
                                               value="<?= $value['emailgoogle'] ?>"  required>
                                    </td>
                                    <td>
                                        <input <?= $value['at_google'] == 1 ? 'checked' : '' ?> type="checkbox" name="at_google" value="1" />
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-editar" name="editar" id="botaoEditar"
                                                >Editar</button>
                                    </td>
                                    <!-- Visualizar -->
                                <?php else: ?>
                                    <td>
                                        <input type="text" name="emailgoogle" class="form-control"
                                               value="<?= $value['emailgoogle'] ?>"  required>
                                    </td>
                                    <td>
                                        <input <?= $value['at_google'] == 1 ? 'checked' : '' ?> type="checkbox" name="at_google" value="1" />
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-editar" name="editar" id="botaoEditar"
                                                >Cadastrado</button>
                                    </td>
                                <?php endif; ?>

                            <input type="hidden" name="id_pessoa" id="id_pessoa" value="<?= $value['id_pessoa'] ?>">
                            <input type="hidden" name="nome" id="nome" value="<?= $value['n_pessoa'] ?>">
                            <input type="hidden" name="ra" id="ra" value="<?= $value['ra'] ?>">
                        </form>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="container">
        <div class="row d-flex justify-content-center my-5">
            <div class="col-md-12 text-center">
                <div class="alert alert-danger" role="alert">
                    Nenhum aluno encontrado!
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    function liberaBotao() {
        const editar = document.querySelectorAll('#emailgoogle');
        const botaoEditar = document.querySelectorAll('#botaoEditar');

        function handleClick(index) {
            botaoEditar[index].removeAttribute('disabled');
        }

        editar.forEach((item, index) => {
            item.addEventListener('click', () => {
                item.removeAttribute('readonly');
                handleClick(index);
            });
        });
    }
    liberaBotao();

//formataEmail
    $(function () {
        var info = $('.mascara');

        $('#cadastrar').mailtip({

        });
    });
</script>
<script src="<?php echo HOME_URI ?>/views/_js/jquery.mailtip.js"></script>


<style>
    .title-escolas {
        color: #83295c;
        font-size: 2em;
        font-weight: bold;
    }

    .card-header {
        padding: .75rem 1.25rem;
        margin-bottom: 0;
        background-color: rgb(131 41 92);
        border-bottom: 1px solid rgba(0, 0, 0, .125);
        color: #FFF !important;
    }

    .text-prof {
        font-weight: bold;
        font-size: 1.1em;
        margin-top: 4px;
    }

    .btn-editar {
        width: 100%;
    }

    .btn-enviar {
        color: #FFF;
        background-color: #83295c;
        font-size: 1.1em;
        padding: 5px 15px 5px 15px;
    }
</style>
