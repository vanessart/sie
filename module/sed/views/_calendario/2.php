<?php
if (!defined('ABSPATH'))
    exit;
foreach (range(1, 12) as $v) {
     $dini = date('w', strtotime(date("Y") . '-' . str_pad($v, 2, "0", STR_PAD_LEFT) . '-01'));
   $dfim = date('w', strtotime(date("Y") . '-' . str_pad($v, 2, "0", STR_PAD_LEFT) . '-131'));
    echo '<br />' . $v . '-.' . $dini.'='.$dfim;
}
?>
<div class="body">
    2
</div>
