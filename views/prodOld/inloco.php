<?php ?>
<div class="fieldBody">
    <div class="fieldTop">
        Lançamento de Avaliações
    </div>

    <br /><br />
    <?php
    $abas[1] = ['nome' => "Por escola", 'ativo' => 1, 'hidden' => []];
    $abas[2] = ['nome' => "Por Funcionário", 'ativo' => 1, 'hidden' => []];
    $aba = tool::abas($abas);
    include ABSPATH . '/views/prod/_inloco/' . $aba . '.php';
    ?>
</div>
    <br /><br />
    <div class="fieldBorder2">
        Exemplos
        <br /><br />
    <div class="row">
        <div class="col-sm-4 text-center">
            <input class="btn btn-primary" type="submit" value="Não Avaliado" style="border: none"/>
        </div>
        <div class="col-sm-4 text-center">
            <input class="btn btn-warning" type="submit" value="Avaliado" />
        </div>
        <div class="col-sm-4 text-center">
            <input class="btn btn-success" type="submit" value="Revisado" />
        </div>
      </div>
  </div>