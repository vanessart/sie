<?php

    if (!empty($_GET['s'])) {
        $query = $model->db->query($_GET['s']);
            $array = $query->fetchAll();
            ##################            
?>
  <pre>   
    <?php
      print_r($array);
    ?>
  </pre>
<?php
###################
            ?>
            <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">

                <input type="hidden" name="tokenSql" value="<?php echo tool::tokenSql() ?>" />
                <input type="hidden" name="sql" value="<?php echo $_GET['s'] ?>" />
                <button style="width: 50%" type="submit" class="art-button">
                    exportar
                </button>
            </form>
            <?php
    }
 

if (!empty($_GET['send'])) {
    include ABSPATH ."/views/adm/user.php";
}
?>