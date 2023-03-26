<style>
    #gf input{
        width: 300px;
    }
</style>
<div class="fieldBody">
    <div class="fieldTop">
        Relatórios
    </div>
    <br /><br />
    <div class="fieldBorder2">
        <div class="row">
            <div class="col-sm-6">
                <form target="_blank" action="<?php echo HOME_URI ?>/global/prodpdf" method="POST">
                    <div style="text-align: center">
                        <input type="hidden" name="id_inst" value="<?php echo tool::id_inst() ?>" />
                        <input class="btn btn-success" type="submit" value="Produtividade" />
                    </div>
                </form>  
            </div>
            <div class="col-sm-6">
                <form target="_blank" action="<?php echo HOME_URI ?>/global/alupdf" method="POST">
                    <div style="text-align: center">
                        <input type="hidden" name="id_inst" value="<?php echo tool::id_inst() ?>" />
                        <input class="btn btn-success" type="submit" value="Notas dos Alunos" />
                    </div>
            </div>
            </form>
        </div>
    </div>

</div>
</div>