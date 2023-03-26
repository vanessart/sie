<?php
if (!defined('ABSPATH'))
    exit;
$calendario = [
    1 => 'emef',
    3 => 'emei',
    5 => 'emef',
    7 => 'emm',
    8 => 'emm',
    9 => 'emef',
    10 => 'emef'
];
if (empty($arg[0])) {
    $curso = 1;
} elseif (in_array($arg[0], [1, 3, 5, 7, 8, 9, 10])) {
    $curso = $arg[0];
} else {
    $curso = 1;
}
$end = 'https://dados.barueri.br/' . HOME_URI . '/pub/doc/' . $calendario[$curso] . '.pdf'
?>
<embed src="<?= HOME_URI ?>/pub/doc/<?= $calendario[$curso] ?>.pdf" style="width: 100%; height: 100vh"  type="application/pdf">
