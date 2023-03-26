<div style="width: 100%; background-color: white; min-height: 50px; margin-top: -2%">
    <?php
    @header("Content-Type: text/html; charset=ISO-8859-1", true);

    function listar($pasta = NULL) {
        @$diretorio = scandir(ABSPATH . '/pub/publicacoes_educacao/' . $pasta);

        if (!empty(@$diretorio)) {
            rsort($diretorio);
            foreach ($diretorio as $v) {
                if (empty(strpos($v, '.')) && substr($v, 0, 1) != '.') {
                    $cont['dir'] [] = $v;
                } elseif (!empty(strpos($v, '.')) && $v != 'index.html') {
                    $cont['arq'] [] = $v;
                }
            }
            @$cont['contDir'] = count($cont['dir']);
            @$cont['contArq'] = count($cont['arq']);
            return $cont;
        } else {
            return NULL;
        }
    }

    @$pasta = $_POST['pasta'];
    ?>


    <?php
    @$diretorio = scandir(ABSPATH . '/pub/publicacoes_educacao/' . $arquivo . $pasta);
    ?>
    <form id="voltar" method="POST">
        <div class="btn-group" style="padding-top: 17px">

        </div>
        <input type="hidden" name="pasta" value="<?php echo @$_POST['voltar'] ?>" />
    </form>
    <div>
        <?php
        if (!empty(@$pasta)) {
            ?>
            <button onclick="document.getElementById('voltar').submit();" type="button" class="btn btn-primary" style=" font-size: 13px; font-weight: normal">
                Voltar
            </button> 
            <?php
        }
        if (!empty($pasta)) {
            ?>
            <button class="btn btn-success" style=" font-size: 13px; font-weight: normal">
                <?php
                echo str_replace('_', ' ', $pasta);
                ?>   
            </button>
            <?php
        }
        ?>
        <?php
        $contador = listar();

        $dir = $contador['contDir'];
        $arq = $contador['contArq'];
        foreach ($contador['dir'] as $v) {
            $contador = listar(@$v);

            @$dir += $contador['contDir'];
            @$arq += $contador['contArq'];
            if (!empty($contador['dir'])) {
                foreach ($contador['dir'] as $vv) {

                    $contador = listar($v . '/' . @$vv);
                    @$dir += $contador['contDir'];
                    @$arq += $contador['contArq'];
                    if (!empty($contador['dir'])) {
                        foreach ($contador['dir'] as $vvv) {

                            $contador = listar($v . '/' . @$vv . '/' . @$vvv);
                            @$dir += $contador['contDir'];
                            @$arq += $contador['contArq'];
                            if (!empty($contador['dir'])) {
                                foreach ($contador['dir'] as $vvvv) {

                                    $contador = listar($v . '/' . @$vv . '/' . @$vvv . '/' . @$vvvv);
                                    @$dir += $contador['contDir'];
                                    @$arq += $contador['contArq'];
                                    if (!empty($contador['dir'])) {
                                        foreach ($contador['dir'] as $vvvvv) {

                                            $contador = listar($v . '/' . @$vv . '/' . @$vvv . '/' . @$vvvv . '/' . @$vvvvv);
                                            @$dir += $contador['contDir'];
                                            @$arq += $contador['contArq'];
                                            if (!empty($contador['dir'])) {
                                                foreach ($contador['dir'] as $vvvvvv) {

                                                    $contador = listar($v . '/' . @$vv . '/' . @$vvv . '/' . @$vvvv . '/' . @$vvvvv . '/' . $vvvvvv);
                                                    @$dir += $contador['contDir'];
                                                    @$arq += $contador['contArq'];
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        ?>

        <button class="btn btn-success" style=" font-size: 13px; font-weight: normal">
            <?php
            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Pastas: ' . $dir . '&nbsp;&nbsp;&nbsp; Total Arquivos: ' . $arq;
            ?>
        </button>
    </div>      
    <?php
    if (!empty(@$diretorio)) {
        ?>
        <div style="height: 410px; overflow-y: auto;overflow-x: hidden; background-color: white">
            <table style="width: 100%">
                <tr>
                    <td class="panel panel-default" style=" padding-top: 5px; background-color: white">


                        <?php
                        rsort($diretorio);
                        foreach ($diretorio as $v) {

                            if (empty(strpos($v, '.')) && substr($v, 0, 1) != '.') {
                                ?>
                                <form method="POST">
                                    <span><img src="<?php echo HOME_URI ?>/views/_images/icone_pasta.png" width="30" height="30" alt="icone_pasta"/>
                                    </span>
                                    <input type="hidden" name="voltar" value="<?php echo $pasta ?>" />
                                    <input type="hidden" name="pasta" value="<?php echo $pasta ?>/<?php echo $v ?>" />
                                    <input class="btn btn-link" type="submit" value="<?php echo utf8_encode(str_replace('_', ' ', $v)) ?>" />
                                </form>
                                <?php
                            }
                        }
                        ?>
                    </td>
                    <td class="panel panel-default" style=" padding-left: 8px; background-color: white">

                        <?php
                        foreach ($diretorio as $v){
                            $n = explode(' -', $v);
                            $diretorio_[$n[0]]= $v;
                        }
                        krsort($diretorio_);
                        foreach ($diretorio_ as $v) {
                            if (!empty(strpos($v, '.')) && $v != 'index.html') {
                                $arq = explode(".", $v);
                                if (end($arq) == "pdf" || $arq[1] == "PDF") {
                                    $icone = "icone_pdf";
                                    $target = 'target="_blank"';
                                } elseif (substr(end($arq), 0, 3) == "doc" || substr($arq[1], 0, 3) == "DOC") {
                                    $icone = "icone_word";
                                } elseif (substr(end($arq), 0, 3) == "xls" || substr($arq[1], 0, 3) == "XLS") {
                                    $icone = "icone_excel";
                                } elseif (substr(end($arq), 0, 3) == "ppt" || substr($arq[1], 0, 3) == "PPT") {
                                    $icone = "icone_point";
                                } else {
                                    $icone = "icone_geral";
                                }
                                ?>
                                <br>
                                <table>
                                    <tr>
                                        <td>
                                            <img src="<?php echo HOME_URI ?>/views/_images/<?php echo $icone ?>.png" width="30" height="30" alt=""/>
                                        </td>
                                        <td style="padding-left: 10px; vertical-align: middle">
                                            <a <?php echo @$target ?>href="<?php echo HOME_URI . '/pub/publicacoes_educacao/' . $pasta . '/' . utf8_decode($v) ?>" target="_blank">
                                                <?php
                                                echo '<br />' . utf8_decode($arq[0]);
                                                ?>
                                            </a> 
                                        </td>
                                    </tr>
                                </table>

                                <?php
                            }
                        }
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>
</div>


