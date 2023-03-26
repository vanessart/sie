<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<style>
    #terminal{
        background-color: rgba(0,0,0,0.8);
        height: 300px;
        width: 80%;
        margin: 0 auto;
        border-radius: 5px;
        color: white;
        font-weight: bold;
        padding: 5px;
        font-family: "Courier New", Courier, monospace;
    }
    .blink_me {
        animation: blinker 1s linear infinite;
    }

    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
$dashboard = $model->dashboard();
?>
<div class="body">
    <div id="terminal">
        <?php
        foreach ($dashboard['terminal'] as $k => $v) {
            ?>
            <div id="<?= $k ?>"></div>
            <?php
        }
        ?>

        <div>
            ><span class="blink_me">_</span>
        </div>

    </div>
    <br /><br />
    <?php
    include ABSPATH . '/module/lab/views/_adm/1.php';
    ?>
</div>


<script>
    $(document).ready(function () {
<?php
$time = 5;
$tempo = 5;
foreach ($dashboard['terminal'] as $k => $v) {
    for ($c = 1; $c <= strlen($v); $c++) {
        $t= substr($v, 0, $c);
        ?>
                setTimeout(() => {
                    document.getElementById('<?= $k ?>').innerHTML = '<?= $t ?>';
                }, <?= $time ?>);
        <?php
        $time += $tempo;
        
        if(@substr($v, $c, 1)==':'){
            $tempo = 600;
        } else {
            $tempo = 5;
        }
    }
}
?>

    });
</script>