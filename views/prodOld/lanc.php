<div class="fieldBody">
    <div class="fieldTop">
        Lançamentos
    </div>
    <br /><br />
    <?php
    $abas[1] = ['nome' => "Infantil e 1º, 2º e 3º Anos", 'ativo' => 1, 'hidden' => [], 'link' => "",];
    $activeNav = tool::abas($abas);
    ?>
    <br /><br /><br /><br />
    <?php
    include ABSPATH . '/views/prod/_lanc/' . $activeNav . '.php';
    ?>
</div>
