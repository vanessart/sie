<div class="fieldBody">
    <div class="col-lg-12">
        <div class="panel panel-default">

            <div class="panel-body" style="min-height: 300px;overflow: auto">
                <div class="row">
                    <div class="col-lg-6">
                        <form name="fk_id_instForm" method="POST">
                            <?php
                            $sql = "SELECT fk_id_inst  FROM `acesso_pessoa` WHERE `fk_id_pessoa` = " . $_SESSION['userdata'] ['id_pessoa'] . " AND `fk_id_gr` = 19";
                            $query = $this->db->query($sql);
                            $array = $query->fetch();

                            formulario::selectDB('instancia', 'fk_id_inst', 'Mudar para Instância do Grupo DTTIE - Suporte', $array['fk_id_inst'], ' style="width: 100%"  required', NULL, NULL, NULL, 'where ativo = 1 order by n_inst');
                            ?>
                            <?php echo DB::hiddenKey('mudaInst') ?>
                        </form>

                    </div>

                </div>
            </div>
        </div> 
    </div>
</div>
