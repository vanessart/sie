<?php
       if(@$_REQUEST['qt']==1){
           $cor = "#0480be";
           $porc = 100;
       } elseif(@$_REQUEST['qt']==2){
           $cor = "yellow";
           $porc = 50;
       } elseif(@$_REQUEST['qt']==3){
           $cor = "yellow";
           $porc = 33;
       } else {
           $cor="yellow";
           $porc = 20;
       }    
                                    ?>
<div style="position: relative; background-color: <?php echo $cor ?>; width: <?php echo $porc ?>%; height: 31px; border-radius: 8px 3px 3px 8px">&nbsp;</div>
<div style="position: relative; margin-top: -20px; font-size: 18px; font-weight: bold; text-align: center;">
    1 de <?php echo @$_REQUEST['qt'] ?>
</div>