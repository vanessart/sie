<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="body">
    <?php
        if (!empty($formhist)) {
            report::simple($formhist);
        }
    ?>
</div>

