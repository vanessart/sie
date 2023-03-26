<style>
    .imgExpande {
        list-style: none;
        padding: 0;
    }
    .imgExpande li {
        display: inline;
    }
    .imgExpande li img {
        width: 100px; /* Aqui é o tamanho da imagem */
        -webkit-transition: 1s all ease; /* É para pega no Chrome e Safira */
        -moz-transition: 1s all ease; /* Firefox */
        -o-transition: 1s all ease; /* Opera */
    }
    .imgExpande li img:hover {
        -moz-transform: scale(2);
        -webkit-transform: scale(2);
        -o-transform: scale(2);
    }

</style>
<?php
tool::modalInicio();
$id_turma = @$_REQUEST['id_turma'];
$turma = gtTurmas::alunos($id_turma);
$cor1 = 'orange';
$cor2 = 'white';
$turmaDados = gtTurmas::get($id_turma);

$hab = coord::habilidades($turmaDados['id_ciclo'], $turmaDados['atual_letiva'], @$_REQUEST['id_disc']);
?>


<div class="row">
    <div class="col-sm-4">
        <div style="background-color: <?php echo $cor1 ?>; color: <?php echo $cor2 ?>; width: 400px; height: 50px;  border-radius: 20px; padding: 16px; text-align: center; font-size: 20px">
            <?php echo $turmaDados['n_turma'] ?>
            - 
            <?php echo disciplina::get(@$_REQUEST['id_disc']) ?>
        </div>
    </div>
    <div class="col-sm-4">
        <table style="background-color: <?php echo $cor1 ?>; color: <?php echo $cor2 ?>; width: 400px; height: 50px;  border-radius: 20px">
            <tr>
                <td style="color: <?php echo $cor2 ?>; padding: 16px; font-size: 16px;">
                    Aulas Dadas
                </td>
                <td style="font-size: 16px; text-align: center">
                    <input style="width: 90%" type="text" name="x" value="" />
                </td>
            </tr>
        </table>
    </div>

</div>
<br /><br />
<table style="width: 100%">

    <?php
    foreach ($turma as $v) {
        ?>
        <tr>
            <td colspan="2" style="height: 15px">
                <a id="l<?php echo $v['id_turma_aluno'] ?>"></a>
            </td>
        </tr>
        <tr>
            <td id="p<?php echo $v['id_turma_aluno'] ?>" style="background-color: <?php echo $cor1 ?>; width: 100px; border-radius: 100px 0 0px 100px ; padding-top: 10px; padding-left: 10px" >
                <ul class="imgExpande" >
                    <li>
                        <?php
                        if (file_exists(ABSPATH . '/pub/fotos/' . $v['id_pessoa'] . '.jpg')) {
                            $img = HOME_URI . '/pub/fotos/' . $v['id_pessoa'] . '.jpg';
                        } else {
                            $img = HOME_URI . "/pub/fotos/anonimo.png";
                        }
                        ?>
                        <img src="<?php echo $img ?>" style="width: 100px; height: 100px; border-radius: 50px;  z-index: 999" alt="Foto"/>
                    </li>
                </ul>
            </td>
            <td id="pp<?php echo $v['id_turma_aluno'] ?>" colspan="2" style="background-color: <?php echo $cor1 ?>; border-radius: 0px 100px 100px 0;">
                <div class="row">
                    <div class="col-md-2" style=" color: <?php echo $cor2 ?>; font-weight: bold; font-size: 16px; padding: 8px; padding-left: 50px">
                        <a href="#l<?php echo $v['id_turma_aluno'] ?>" class="scroll" data-id="<?php echo $v['id_turma_aluno'] ?>" >
                            <button style="border-radius: 20px" class="btn btn-default" type="button" class="btn btn-default btn-lg">
                                <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span> Lançar
                            </button>
                        </a>
                    </div>
                    <div class="col-md-4" style=" color: <?php echo $cor2 ?>; font-weight: bold; font-size: 16px; padding: 8px; padding-left: 50px">
                        <?php echo $v['chamada'] ?> - <?php echo $v['n_pessoa'] ?>
                    </div>
                    <div class="col-md-3" style="color: <?php echo $cor2 ?>; font-weight: bold; font-size: 16px; padding: 8px;">
                        Situação: <?php echo $v['situacao'] ?>
                    </div>
                    <div class="col-md-3" style="color: <?php echo $cor2 ?>; font-weight: bold; font-size: 16px; padding: 8px;">
                        RSE/ID: <?php echo $v['id_pessoa'] ?>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="lancHab" id="<?php echo $v['id_turma_aluno'] ?>" style="display: none; background-color: #ffc65e">
                    <table class="table table-bordered table-condensed table-hover table-responsive">
                        <?php
                        foreach ($hab as $vv) {
                            ?>
                            <tr>
                                <td>
                                    <div id="q<?php echo $v['id_turma_aluno'] . '_' . $vv['id_hab'] ?>" style="width: 100px; border: solid <?php echo $cor1 ?> 2px; height: 35px; border-radius: 8px;">
                                        <div style="position: relative; background-color: yellow; width: 0%; height: 31px; border-radius: 8px 3px 3px 8px">&nbsp;</div>
                                        <div style="position: relative; margin-top: -20px; font-size: 18px; font-weight: bold; text-align: center">
                                            0 de <?php echo $vv['qt_hab'] ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <input name="mais<?php echo $v['id_turma_aluno'] . '_' . $vv['id_hab'] ?>" style="border-radius: 35px; width: 35px; height: 35px" class="btn btn-primary" type="submit" value="+" />
                                </td>
                                <td>
                                    <input style="border-radius: 35px; width: 35px; height: 35px" class="btn btn-danger" type="submit" value="-" />
                                </td>
                                <td style="font-size: 16px">
                                    <?php echo $vv['n_hab'] ?>
                                </td>
                            </tr>
                            <?php
                            javaScript::divDinanmica('mais' . $v['id_turma_aluno'] . '_' . $vv['id_hab'], 'q' . $v['id_turma_aluno'] . '_' . $vv['id_hab'], HOME_URI . '/coord/lancq&qt=' . $vv['qt_hab'], 'click');
                        }
                        ?>
                    </table>
                    &nbsp;
                </div>
            </td>
        </tr>
        <tr>
            <td id="e<?php echo $v['id_turma_aluno'] ?>" colspan="3" style="background-color: <?php echo $cor1 ?>; border-radius: 0 0px 100px 100px ; height: 20px; display: none">
                &nbsp;
            </td>
        </tr>
        <?php
    }
    ?>
</table>
<script>
    function ocultar(id) {
        /*var top = $('#contato').offset().top();
         
         $(window).animation({
         scrollTop: top
         }, 300);
         // $('.lancHab').css('display', 'none');
         $('#p' + id).css({'border-radius': '50px 0 0 0 '});
         $('#pp' + id).css({'border-radius': '0 50px 0 0 '});
         $('.lancHab').hide();
         //$('#'+ id +', #e'+ id).show();
         $('#' + id + ', #e' + id).slideDown(1000);
         */
    }
var topPosition = 0;

    $(document).ready(function () {
        $(".scroll").click(function (e) {
            //prevent the default action for the click event
            e.preventDefault();

            id = $(this).data('id');
//            debugger;

            $('#p' + id).css({'border-radius': '50px 0 0 0 '});
            $('#pp' + id).css({'border-radius': '0 50px 0 0 '});
            $('.lancHab').hide();

            //get the full url - like mysitecom/index.htm#home
            var full_url = this.href;

            //$('#'+ id +', #e'+ id).show();
            $('#' + id + ', #e' + id).slideDown(1000);

            //split the url by # and get the anchor target name - home in mysitecom/index.htm#home
            var parts = full_url.split("#");
            var trgt = parts[1];

            //get the top offset of the target anchor
            var offsetModal = $('.modal').offset();
            var topModal = offsetModal.top;
            var target_offset = $("#" + trgt).offset();
            var target_top = topModal + target_offset.top;
            var target_position = $("#" + trgt).position();
            console.log(offsetModal)
            console.log(target_offset)
            console.log(target_position)
            console.log($(window).scrollTop())
            console.log('*****')
            topPosition = target_top;

//debugger

            //goto that anchor by setting the body scroll top to anchor top
            $('.modal').animate({scrollTop: target_top}, 300); //, 'easeInSine'

        });
    });
</script>
<?php
tool::modalFim();
