<div class="row">
    <div class="col-md-7">
        <?php
        include ABSPATH . '/views/vagas/cada_1_dados.php';
        ?>
    </div>
    <div class="col-md-5">
        <?php
        include ABSPATH . '/views/vagas/cada_1_defere.php';
        include ABSPATH . '/views/vagas/cada_1_confere.php';
        
        tool::relatSimples($model->log($dados['id_vaga']))
        ?>
    </div>
</div>