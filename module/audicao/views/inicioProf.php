<div class="fieldBody">
    <div class="row">
        <div class="col-lg-8">
            <div style="margin: auto; width: 600px; padding: 20px">
                <video id="vd_aud" style="width: 100%; height: 100%" controls>
                    <source src="<?= HOME_URI ?>/pub/vd/audicao-prof-caminhos-do-som.mp4" type="video/mp4">
                        Seu navegador não suporta este vídeo
                </video>
            </div>
        </div>
        <div class="col-lg-4" style="padding-top: 20px">
            <div class="alert alert-warning" style="min-height: 35vh;">
                <table class="table table-bordered table-responsive table-striped" style="font-weight: bold">
                    <tr>
                        <td style="min-width: 25%">
                            Nome
                        </td>
                        <td>
                            <?php echo user::session('n_pessoa') ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            E-mail
                        </td>
                        <td>
                            <?php echo $_SESSION['userdata']['email'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Subsistema 
                        </td>
                        <td>
                            <?php echo $_SESSION['userdata']['n_sistema'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Nível de Acesso 
                        </td>
                        <td>
                            <?php echo $_SESSION['userdata']['n_nivel'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Instância
                        </td>
                        <td>
                            <?php echo $_SESSION['userdata']['n_inst'] ?>
                        </td>
                    </tr>

                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        Suas últimas interações com o Sistema
                    </div>
                    <div class="panel-body">
                        <div style=" overflow: auto;height: 25vh">                     <?php
                            $form['array'] = log::logGet(@$_SESSION['userdata']['id_pessoa']);
                            $form['fields'] = [
                                'Data' => 'data',
                                'Hora' => 'hora',
                                'Descrição' => 'descricacao',
                                'Sistema' => 'n_sistema',
                                'Instância' => 'n_inst'
                            ];
                            tool::relatSimples($form);
                            ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



</div>