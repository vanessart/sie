<?php
$file = ABSPATH . '/module/' . @$_SESSION['systemData']['arquivo'] . '/menu.php';
if (file_exists($file)) {
    include $file;
    ?>
    <br /><br /><br /><br />
    <div  class="row" style="min-height: 80vh" >
        <?php
        $ct = 1;
        foreach ($menu[@$_SESSION['systemData']['id_nivel']] as $k => $v) {
            if (!is_numeric($k)) {
                ?>
                <div class="col-sm-<?php echo empty($v['col']) ? 3 : $v['col'] ?>">
                    <div style="padding: 10px"  class="border">

                        <?php
                        if (!empty($v['page'])) {
                            ?>
                            <div>
                                <div style="font-size: 20px; font-weight: bold; color: #004573">
                                    <?php echo @$k ?> <span class="caret"></span>
                                </div>                           
                                <?php
                                foreach ($v['page'] as $kk => $vv) {
                                    ?>
                                    <div style="color: #004573; padding: 10px;">
                                        <a <?php echo @$vv['target'] == 1 ? 'target="_blank"' : '' ?> style="color: #128812; font-weight: bold" href="<?php echo substr(@$vv['url'], 0, 4) != 'http' ? HOME_URI . @$vv['url'] : @$vv['url']; ?>">
                                            <div class="btni-dark" style="width: 100%; font-size: 18px">
                                                &nbsp;&nbsp;&nbsp;
                                                <?php echo @$kk ?>
                                            </div>    
                                        </a>
                                    </div>                           
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div>
                                <a <?php echo @$v['target'] == 1 ? 'target="_blank"' : '' ?> style="color: #128812; font-weight: bold" href="<?php echo substr(@$v['url'], 0, 4) != 'http' ? HOME_URI . @$v['url'] : @$vv['url']; ?>">
                                    <div class="btni-dark" style="font-size: 18px">
                                        <?php echo @$k ?>
                                    </div>
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
            if ($ct++ % 4 == 0) {
                ?>
            </div>
            <br /><br />
            <div class="row">
                <?php
            }
        }
        ?>
    </div>  
    <?php
}