<?php
$directory = ABSPATH . '/views/global/doc';
@$diretorio = array_diff(scandir($directory), array('..', '.'));
?>
<div class="fieldBody">
    <div class="fieldTop">
        Documentação
    </div>
    <br /><br />
    <div class="row">
        <?php
        if (!empty($diretorio)) {
            foreach ($diretorio as $v) {
                ?>
                <div class="col-lg-4">
                    <a class="btn btn-info" style="width: 90%; margin: 0 auto" target="_blank" href="<?php echo HOME_URI .'/views/global/doc/'.$v ?>">
                    <?php echo $v ?>
                    </a>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="col-lg-4 btn btn-info">
                Não há documentação
            </div>
            <?php
        }
        ?>
        </div>
    </div>
</div>

