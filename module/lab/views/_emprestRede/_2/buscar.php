<?php
if (!defined('ABSPATH'))
    exit;
?>
  <form method="POST">
        <div class="row">
            <div class="col-10">
                <?=
                formErp::input('search', 'Nome, E-mail, CPF ou Matrícula')
                . formErp::hidden([
                    'activeNav' => 2
                ])
                ?>
            </div>
            <div class="col-2">
                <button class="btn btn-info" type="submit">
                    Buscar
                </button>
            </div>
        </div>
    </form>
