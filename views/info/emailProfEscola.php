<?php

$id_inst = explode('/', $_GET['path']);

$escola = $model->buscaEmailProfEscola($id_inst[2]);

?>

<link rel="stylesheet" href="<?php echo HOME_URI ?>/_css/style.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<br>
<!-- Tabela -->
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1 class="title-escolas"><?= @$escola[0]['escola'] ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
        <?php if($escola): ?>
            <table class="table table-striped table-bordered table-sm">
                <thead class="card-header">
                    <tr>
                        <th scope="col" class="text-center" style="width: 50px;">ID Pessoa</th>
                        <th scope="col" class="text-center">RM</th>
                        <th scope="col">Nome</th>
                        <th scope="col" class="text-center" style="width: 300px;">E-mail</th>
                        <th scope="col" class="text-center" style="width: 100px;">Acessar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($escola as $key => $value): ?>
                    <form action="#" method="post">
                        <tr>
                            <td class="text-center">
                                <p class="text-prof"><?= $value['id_pessoa'] ?></p>
                            </td>
                            <td class="text-center">
                                <p class="text-prof"><?= $value['rm'] ?></p>
                            </td>
                            <td>
                                <p class="text-prof"><?= $value['professor'] ?></p>
                            </td>

                            <!-- CADASTRAR -->
                            <?php if(!$value['email']): ?>
                            <td>
                                <input type="email" name="emailgoogle" class="form-control email mascara"
                                    placeholder=" Insira o e-mail @professor.barueri.br" id="cadastrar">
                            </td>
                            <td class="text-center">
                                <button class="btn btn-success" name="cadastrar">Cadastrar</button>
                            </td>


                            <?php else: ?>
                            <!-- EDITAR -->
                            <td>
                                <input type="text" name="emailgoogle" id="emailgoogle" class="form-control email"
                                    value="<?= $value['email'] ?>" readonly>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-editar" name="editar" id="botaoEditar"
                                    disabled>Editar</button>
                                <?php endif; ?>
                                <input type="hidden" name="id_pessoa" id="id_pessoa" value="<?= $value['id_pessoa'] ?>">
                            </td>
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
                Nenhum professor encontrado!
            </div>
            <a href="<?= HOME_URI ?>/info/buscaEmailProf/" class="btn btn-enviar">Voltar</a>
        </div>
    </div>
</div>
<?php endif; ?>

<?php 
$validaform = ($_SERVER['REQUEST_METHOD']); 
            if($validaform == 'POST'):
                @$dados = $_POST;
                @$array = $model->cadastrarEmailProf($dados);
                unset($_POST);
?>
<script>
alert('Email cadastrado com sucesso')
</script>
<meta http-equiv="refresh" content="0; URL='<?= $_SERVER['REDIRECT_URL'] ?>'" />
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
</script>

<script>
//formataEmail
$(function() {
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
</style>