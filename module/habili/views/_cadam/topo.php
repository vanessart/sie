<?php
$background = 'white';
$color = '#000000';
?>
<style>
    #user-photo{
        border-radius: 50px;   
        width: 50px;
    }

    #top-off{
        display: none;  
    }
    #top{
        background-color: <?= $background ?>;
        color: <?= $color ?>;
        width: 100%; 
        height: 90px; 
        border-bottom: #cce5ff solid 2px;
        top: 0;
        margin-bottom: 10px;
    }

    .titulo{
        font-weight: bold;
        text-align: center;
        padding-bottom: 10px;
    }

    .fotoGoogle{
        width: 36px;
        border-radius: 50%;
    }

    .tabela{
        width: 90%;
        height: 50px;
    }

</style>
<div id="top">
    <table class="tabela" style="width: 100%;">
        <tr>
            <td style="text-align: center; font-weight: bold; color: #000000">
                <img src="<?= HOME_URI ?>/includes/images/brasao.png" style="height: 68px" />
            </td>
            <td style="text-align: right">
                <div style="padding: 10px; width: 100%; text-align: right">
                    <img src="<?= HOME_URI ?>/includes/images/topo.png" style="height: 68px" />
                </div>
            </td>
        </tr>
    </table>
</div>
