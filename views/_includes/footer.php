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
            <?= CLI_END ?> - <?= CLI_BAIRRO ?> - CEP: <?= CLI_CEP ?> - <?= CLI_CIDADE ?> - <?= CLI_UF ?>. Fone/PABX: <?= CLI_FONE ?>
            </p>
            <p>versão 1.2.2 <a href="<?= URL_BASE ?>ge/release.html" target="_blank" rel="noopener noreferrer" title="Ver release">ver</a></p>
        </div>
    </div>
</div>

</body>
</html>
