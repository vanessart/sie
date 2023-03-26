<?php

?>
<form method="POST">
    <input type="text" name="1[n_teste]" value="" />
    <?php echo form::hiddenToken('teste', 'ireplace') ?>
    <input type="submit" value="salvar" />
</form>