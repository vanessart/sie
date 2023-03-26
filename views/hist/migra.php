<div class="row">
    <div class="col-md-12">
        <form method="POST" class="text-center">
            <input type="text" name="id_aluno" value="<?php echo @$_POST['id_aluno'] ?>" />
            <input name="migrar" type="submit" value="Histórico" />
        </form>
    </div>
</div>

<?php
if (!empty($_POST['migrar'])) {
    $aluno = new historico($_POST['id_aluno']);
    ?>
    <pre>
        <?php
        print_r($aluno)
        ?>
    </pre>
    <?php
}
?>
<?php
 session_start();
 if(isset($_POST['send']))
 {
  $getname = $_POST['myname'];
  require('connection.php');
  $idvalue = $_SESSION['myvalue'];
  $sql = 'UPDATE entry SET name = '.
   mysqli_real_escape_string($con, $getname).
   ' where id='.intval($idvalue);
  $result = mysqli_query($con,$sql) or die('error in query');
  if($result)
  {
   echo 'Updated '.$_SESSION['myvalue'];
  }
  else
  {
   echo "Error nahi hua";
  }
 }