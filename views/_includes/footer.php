<?php
if (!defined('ABSPATH')) {
    exit;
}

?>
<br />
<?php
if (!empty($_SESSION['error1'])) {
    ?>
    <script>
        alert("<?php echo $_SESSION['error1'] ?>")
    </script>
    <?php
unset($_SESSION['error1']);
}
?>
</div>

<div id="ajaxLoader"><img src="<?=HOME_URI . "/views/_images/prod_loading.gif"?>" ></div>

<hr />
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <p>
            Secretaria de Educação<br>
            Rua C.PM José Maria Schiavelli, 125 - Jd. dos Camargos - CEP: 06410-335 - Barueri - SP. Fone/PABX: (11) 4199-2900
            </p>
            <p>versão 1.2.2 <a href="https://portal.educ.net.br/ge/release.html" target="_blank" rel="noopener noreferrer" title="Ver release">ver</a></p>
        </div>
    </div>
</div>

</body>
</html>
