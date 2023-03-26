<div style="text-align: center; font-size: 22px; font-weight: bold">
    SADEB 2016
    <br /><br /><br />
    BOLETIM INDIVIDUAL
</div>
<br /><br /><br /><br /><br /><br /><br /><br />
<?php
$escolas = sql::get('sadeb');

foreach ($escolas as $e) {
    ?>
    <div style="width: 50%; margin: 0 auto; background-color: #B8D3EA; border: #1188FF solid 2px; padding: 8px">
        <?php echo $e['n_school'] ?>
    </div>
    <br />
    <div class="row" style="width: 50%; margin: 0 auto;">
        <div class="col-md-8" style=" background-color: #B8D3EA; border: #1188FF solid 2px; padding: 8px">
            IDEMB - META 2016
        </div>
        <div class="col-md-4" style=" background-color: #B8D3EA; border: #1188FF solid 2px; padding: 8px">
            <?php echo $e['meta'] ?>
        </div>
    </div>
    <br />
    <div class="row" style="width: 50%; margin: 0 auto;">
        <div class="col-md-8" style=" background-color: #91BADE; border: #1188FF solid 2px; padding: 8px">
            PROVA (AVALIAÇÃO EXTERNA)
        </div>
        <div class="col-md-4" style=" background-color: #91BADE; border: #1188FF solid 2px; padding: 8px">
            <?php echo $e['meta'] ?>
        </div>
    </div>


    <div style="page-break-after: always; "></div>
    <?php
}
?>
