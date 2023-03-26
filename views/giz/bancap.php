<?php
$prof = $model->inscricoes(@$_POST['id_prof'], 'id_prof');
?>
<div class="fieldBody">
    <div class="fieldTop">
        Inscrição nº <?php echo $prof['id_prof'] ?>
    </div>
    <br /><br />
    <?php
    if ($prof['gestor'] == 2) {
        ?>
        <div style="text-align: center">
            <table style="margin: 0 auto">
                <tr>

                    <td>
                        <form target="_blank" action="<?php echo HOME_URI ?>/giz/aceite" method="POST">
                            <input type="hidden" name="id_prof" value="<?php echo $prof['id_prof'] ?>" />
                            <input class="btn btn-success" type="submit" value="PDF" />
                        </form>   
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    ?>
    <br /><br />
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <td style="width: 15%">
                Autor:
            </td>
            <td>
                <?php echo $prof['n_pessoa'] ?>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Situação:
            </td>
            <td>
                <?php
                if ($prof['gestor'] == 1) {
                    echo 'Aguardando efetivação pela Equipe de Gestão';
                } elseif ($prof['gestor'] == 2) {
                    echo 'Efetivado pela Equipe de Gestão';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Categoria:
            </td>
            <td>
                <?php echo $prof['n_cate'] ?>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Título:
            </td>
            <td>
                <?php echo $prof['titulo'] ?>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Escola:
            </td>
            <td>
                <?php echo $prof['n_inst'] ?>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                turmas:
            </td>
            <td>
                <?php echo $model->turmas($prof['turmas']) ?>
            </td>
        </tr>
        <?php
        /**
          $coautor = $model->coautor($prof['id_prof']);
          if (!empty($coautor)) {
          ?>
          <tr>
          <td style="width: 15%">
          Coautor<?php echo (count($coautor) > 1 ? 'es' : '') ?>:
          </td>
          <td>
          <?php
          foreach ($coautor as $v) {
          echo $v['n_pessoa'] . ' (RM: ' . $v['rm'] . ')<br />';
          }
          ?>
          </td>
          </tr>
          <?php
          }
         * 
         */
        ?>
        <tr>
            <td style="width: 15%">
                Tema:
            </td>
            <td>
                <?php echo $prof['tema'] ?>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Período:
            </td>
            <td>
                de 
                <?php echo data::converteBr($prof['dt_inicio']) ?> 
                a 
                <?php echo data::converteBr($prof['dt_fim']) ?> 
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Objetivo Geral:
            </td>
            <td>
                <div style="white-space: pre-wrap;"><?php echo $prof['objgeral'] ?></div>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Objetivo Específico:
            </td>
            <td>
                <div style="white-space: pre-wrap;"><?php echo $prof['objespec'] ?></div>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Justificativa:
            </td>
            <td>
                <div style="white-space: pre-wrap;"><?php echo $prof['justifica'] ?></div>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Método:
            </td>
            <td>
                <div style="white-space: pre-wrap;"><?php echo $prof['metodo'] ?></div>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Cronograma:
            </td>
            <td>
                <div style="white-space: pre-wrap;"><?php echo $prof['cronograma'] ?></div>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Recursos:
            </td>
            <td>
                <div style="white-space: pre-wrap;"><?php echo $prof['recurso'] ?></div>
            </td>
        </tr>
    </table>
    <?php
    if ($prof['gestor'] == 1) {
        ?>
        <br /><br />
        <!--
        Bla Bla Bla Bla Bla Bla Bla Bla Bla Bla Bla Bla Bla Bla Bla Bla Bla Bla Bla Bla Bla Bla Bla Bla 
        <br /><br />
        <div class="text-center">
            <label>
                <input id="conc" onclick="concordo()" type="checkbox" name="1[confirma]" value="1" />
                Ciente e de Acordo
            </label>
        </div>
        -->
        <form method="POST">
            <input type="hidden" name="activeNav" value="5" />
            <?php echo DB::hiddenKey('giz_prof', 'replace') ?>
            <input type="hidden" name="1[id_prof]" value="<?php echo $prof['id_prof'] ?>" />
            <input type="hidden" name="id_prof" value="<?php echo $prof['id_prof'] ?>" />
            <input type="hidden" name="1[gestor]" value="2" />
            <input type="hidden" name="1[idgestor]" value="<?php echo tool::id_pessoa() ?>" />
            <br /><br />
            <div style="text-align: center">
                <input id="conf"  class="btn btn-primary" type="submit" value="Efetivar a Inscricão" />
            </div>
        </form>
        <script>

            function concordo() {
                if (document.getElementById('conc').checked == true) {
                    document.getElementById('conf').disabled = false;
                } else {
                    document.getElementById('conf').disabled = true;
                }
            }
        </script>
        <?php
    } elseif ($prof['gestor'] == 2) {
        ?>
        <div style="text-align: center">
            <table style="margin: 0 auto">
                <tr>
                    <td>
                        <form target="_blank" action="<?php echo HOME_URI ?>/giz/aceite" method="POST">
                            <input type="hidden" name="id_prof" value="<?php echo $prof['id_prof'] ?>" />
                            <input class="btn btn-success" type="submit" value="Termo de Aceite" />
                        </form>   
                    </td>
                </tr>
            </table>
        </div>
        <?php
    } else {
        ?>
        <div class="alert alert-warning" style="text-align: center; font-size: 18px">
            O professor ainda não liberou este projeto para efetivação.
        </div>
        <?php
    }
    ?>
</div>
