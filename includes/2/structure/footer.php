<?php if (!defined('ABSPATH')) exit; ?>
<style>
    /*Esconde a dive de classe Overlay caso seja identificado que o width Mobile maximo deseja igual ou menor que 980px*/
    @media only screen and (max-width: 980px){
        .overlay { display: none; }
    }
</style>
<div style="height: 20px"></div>
<!--
    <div class="overlay fieldWhite fixed-bottom" style="width: 100%; text-align: center;">
        <?= date("Y") ?> - ERP Educ - Versão 1.0
    </div>
<div id="ajaxLoader"><img src="<?=HOME_URI ."/". INCLUDE_FOLDER ."/images/prod_loading.gif"?>" ></div>
-->

</body>
</html>
