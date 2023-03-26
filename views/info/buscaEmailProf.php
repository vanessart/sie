<?php 

//Escolas
// $escola = escolas::liste();

$escola = $model->listaEscolas();

$totalEscolas = count($escola);

//Professores
$professores = $model->buscaEmailProf();

//Professores que possuem email google
for ($i=0; $i < count($professores); $i++) { 
    if($professores[$i]['email'] != NULL) {
        @$profComEmail++;
    }

    if($professores[$i]['email'] === NULL) {
        @$profSemEmail++;
    }
}

@$totalProf = count($professores);
?>

<!-- Painel -->
<div class="container">
    <div class="row center">
        <div class="count">
            <div class="col-md-3">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title">Professores com E-mail</h3>
                    </div>
                    <div class="panel-body">
                        <h3><b><span data-numero><?= $profComEmail ?></span></b></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">Professores sem E-mail</h3>
                    </div>
                    <div class="panel-body">
                        <h3><b><span data-numero><?= $profSemEmail ?></span></b></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Total Professores</h3>
                    </div>
                    <div class="panel-body">
                        <h3><b><span data-numero><?= $totalProf ?></span></b></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">Total Escolas</h3>
                    </div>
                    <div class="panel-body">
                        <h3><b><span data-numero><?= $totalEscolas ?></span></b></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabela -->
<div class="container">
    <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-10">
            <table class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        <th scope="col">Escola</th>
                        <th scope="col" class="text-center">Com E-mail</th>
                        <th scope="col" class="text-center">Sem E-mail</th>
                        <th scope="col" class="text-center">Acessar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($escola as $key => $value): ?>
                    <tr>
                        <td><b><?= $value['n_inst'] ?></b></td>
                        <td class="text-center">
                            <?php 
                   $contador = 0;
                   foreach($professores as $chave => $valor) {
                       if($valor['fk_id_inst'] === $value['id_inst']) {
                        if($valor['email'] != NULL) {
                            $contador++;
                        }
                       }
                   } 
                   ?>
                            <b class="text-success"><?= $contador ?></b>
                        </td>
                        <td class="text-center">
                            <?php 
                   $contador = 0;
                   foreach($professores as $chave => $valor) {
                       if($valor['fk_id_inst'] === $value['id_inst']) {
                        if($valor['email'] === NULL) {
                            $contador++;
                        }
                       }
                   } 
                   ?>
                            <b class="text-danger"><?= $contador ?></b>
                        </td>
                        <td><a href="<?= HOME_URI ?>/info/emailProfEscola/<?= $value['id_inst'] ?>"
                                class="btn btn-success btn-sm btn-acessar">Acessar</a></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<style>
.count {
    display: flex;
    justify-content: space-around;
    align-items: center;
    margin: 30px;
    text-align: center;
    flex-wrap: wrap;
}

.panel-body {
    padding: 0;
}

.btn-acessar {
    width: 100%;
}

</style>

<script>
function contaNumero() {

    const numeros = document.querySelectorAll('[data-numero]');

    numeros.forEach((numero) => {
        const total = +numero.innerText;
        const incremento = Math.floor(total / 100);
        let start = 0;
        const timer = setInterval(() => {
            start = start + incremento;
            numero.innerText = start;
            if (start > total) {
                numero.innerText = total;
                clearInterval(timer);
            }
        }, 25 * Math.random());
    })
}
contaNumero();
</script>