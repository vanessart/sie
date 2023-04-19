<?php
if (!defined('ABSPATH'))
    exit;
$pesq = filter_input(INPUT_POST, 'pesq');
$func = filter_input(INPUT_POST, 'func', FILTER_SANITIZE_NUMBER_INT);
$pessoas = [];
if ($pesq) {
    $pessoas = $model->pesqPessoas($pesq, $func);
}
?>
<div class="body">
    <div class="fieldTop">
        Gestão de  Pessoas
    </div>
    <br /><br />
    <form method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::input('pesq', 'CPF, Nome, Matrícula, Email ou Id', $pesq) ?>
            </div>
            <div class="col">
                <?= formErp::checkbox('func', 1, 'Só Funcionário', $func) ?>
            </div>
            <div class="col">
                <button class="btn btn-primary">
                    Pesquisar
                </button>
            </div>
        </div>
        <br />
    </form>
</div>
<?php
##################            
?>
  <pre>   
    <?php
      //print_r($pessoas);
    ?>
  </pre>
<?php
###################
?>