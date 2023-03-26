<?php
if(empty($_POST['id_inst'])){
    $id_inst = tool::id_inst();
}  else {
    $id_inst = $_POST['id_inst'];
}
$rm = @$_POST['rm'];

?>
<div class="fieldBody">
    <div class="fieldTop">
        Títulos, Tempo de serviço e Pontuações
    </div>
    
    
    <div class="row">
        <form method="POST">
            <div class="col-lg-6">
                Se o(a) professor(a) não estiver na lista abaixo, ensira a matrícula do(a) professor(a) e clique em buscar.
            </div>
            <div class="col-lg-2">
                <input type="text" name="rm" value="<?php echo $rm ?>" />
            </div>
            <div class="col-lg-1">
                <button class="btn btn-success">
                    Buscar
                </button>
            </div>
        </form>
        <div class="col-lg-3">
            <form method="POST">
                <button class="btn btn-success">
                    Limpar
                </button>
            </form>
        </div>
    </div>
    <br />
    <?php
  $model->relatProf($id_inst, $rm);
?>
</div>