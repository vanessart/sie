<?php
if (!defined('ABSPATH'))
    exit;
$status = $model->historicoGet($id_protocolo);
if (!empty($status)) {
    report::simple($status);
}?>
