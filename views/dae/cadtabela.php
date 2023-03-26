<?php
$hab[0] = 1;
$hab[1] = 1;
$hab[2] = 1;
$hab[3] = 1;
$hab[4] = 1;
$hab[5] = 1;

if (empty($_REQUEST['tabClass'])) {
    $tabClass = '0';
} else {
    $tabClass = $_REQUEST['tabClass'];
}
?>

<div class="fieldBody">
    <div style="color: red" class="fieldTop">
        <br />
        Cadastro
    </div>
    <br />
    <div class="row">
        <?php
        $titulo = ['Departamento', 'Motivo', 'Tipo Contato', 'Tipo Ensino', 'Status', 'Sair'];
        for ($c = 0; $c < 6; $c++) {
            if (empty($onclik[$c])) {
                ?>
                <div class="col-md-2">
                    <form method="POST">
                        <input type="hidden" name="tabClass" value="<?php echo $c ?>" />
                        <button class="btn btn-<?php echo $tabClass == $c ? 'success' : 'info' ?>" style="width: 100%; font-weight: bold;">
                            <?php echo $titulo[$c] ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span style="" class="badge"><?php echo $c + 1 ?></span>
                        </button>
                    </form>
                </div>
                <?php
            } 
        }
        ?>
    </div>
    <?php
    ##################            
?>
  <pre>   
    <?php
      print_r($tabClass);
    ?>
  </pre>
<?php
###################
    switch ($tabClass) {
        case 1:
            ?>
    <div>
        aqui 1
        s##################            

  <pre>   
    <?php
      print_r($tabClass);
    ?>
  </pre>
###################
    </div>
        <?php
        break;
        case 2:
             ?>
    <div>
        aqui 2
    </div>
        <?php
            break;
        case 5:
            //  require ABSPATH . '/views/gest/convocacao.php';
            break;
        default :
        //      include ABSPATH . '/views/gest/eventos' . $tabClass . '.php';
    }
    ?>   
</div>

