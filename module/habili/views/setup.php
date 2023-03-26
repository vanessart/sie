<?php
if (!defined('ABSPATH'))
    exit;
$set = filter_input(INPUT_POST, 'set', FILTER_SANITIZE_NUMBER_INT);
?>
<style>
    .bt{
        width: 100%;
    }
    .topo{
        text-align: center;
        font-weight: bold;
        font-size: 1.3em;
        padding-top: 15px;
    }
</style>
<div class="body">

    <?php
    if (empty($set)) {
        ?>
        <div class="fieldTop">
            Controles
        </div>
        <div style="width: 90%; margin: 0">
            <div class="row">
                <div class="col">
                    <form method="POST">
                        <input type="hidden" name="set" value="1" />
                        <button class="btn btn-primary border bt">
                            Abrir e Fechar Lançamento de Notas
                        </button>
                    </form>
                </div>
                <div class="col">
                    <form method="POST">
                        <input type="hidden" name="set" value="2" />
                        <button class="btn btn-primary border bt">
                            Liberar as Notas para os alunos
                        </button>
                    </form>
                </div>
                <div class="col">
                    <form method="POST">
                        <input type="hidden" name="set" value="3" />
                        <button class="btn btn-primary border bt">
                            Liberar Bimestres do Diário
                        </button>
                    </form>
                </div>
            </div>
            <br />
        </div>
        <br />
        <div class="row">
            <div class="col">
                <form method="POST">
                    <input type="hidden" name="set" value="4" />
                    <button class="btn btn-primary border bt">
                        Configurar Sondagem de Habilidades
                    </button>
                </form>
            </div>
            <div class="col">
                <form method="POST">
                    <input type="hidden" name="set" value="5" />
                    <button class="btn btn-primary border bt">
                        Liberar Conselho de classe
                    </button>
                </form>
            </div>
            <div class="col">

            </div>
        </div>
        <br />
        <?php
    } else {
        include ABSPATH . "/module/habili/views/_setup/$set.php";
    }
    ?>
</div>
