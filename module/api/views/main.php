<?php
if (!defined('ABSPATH'))
    exit;
if (in_array(tool::id_pessoa(), [1, 5])) {
    $json = @$_POST['json'];
    $endApi = "https://aluno.educ.net.br/coord/"
    ?>
    <div style="margin-left: 100px">
        <form method="POST">
            <div class="row">
                <div class="col-10">
                    <?= formErp::input('json', 'json') ?>
                </div>
                <div class="col-2">
                    <button>ir</button>
                </div>
            </div>
            <br />
        </form>
        <?php
        if ($json) {
            $json = json_decode($json)
            ?>
            <pre>   
                <?php
                print_r($json);
                ?>
            </pre>
            <hr>
            <?php
            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    echo ' - No errors';
                    break;
                case JSON_ERROR_DEPTH:
                    echo ' - Maximum stack depth exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    echo ' - Underflow or the modes mismatch';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    echo ' - Unexpected control character found';
                    break;
                case JSON_ERROR_SYNTAX:
                    echo ' - Syntax error, malformed JSON';
                    break;
                case JSON_ERROR_UTF8:
                    echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
                    break;
                default:
                    echo ' - Unknown error';
                    break;
            }
        }
        ?>
        <hr>
        api autentica
        <br />
        <form target="_blank" action="<?= $endApi ?>api/aluno/autentica" method="POST">
            <input type="text" name="email" value="macrotexxx@gmail.com" placeholder="e-mail" />
            <br />
            <input type="text" name="googleId" value="100799411944277465711" placeholder="googleId" />
            <br />
            <input type="submit" value="ir" />
        </form>
        <hr>
        api autentica
        <br />
        <form target="_blank" action="<?= $endApi ?>api/aluno/autentica" method="POST">
            <input type="text" name="email" value="marco@professor.barueri.br" placeholder="e-mail" />
            <br />
            <input type="text" name="googleId" value="115938417206163265443" placeholder="googleId" />
            <br />
            <input type="submit" value="ir" />
        </form>
        <hr>

        api nota
        <br />
        <form target="_blank" action="<?= $endApi ?>api/aluno/nota" method="POST">
            <input type="text" name="id_pessoa" value="237716" placeholder="id_pessoa" />
            <br />
            <input type="text" name="googleId" value="yt7nv5y34cv98nyvyp" placeholder="googleId" />
            <br />
            <input type="submit" value="ir" />
        </form>
        <hr>  
        api horário
        <br />
        <form target="_blank" action="<?= $endApi ?>api/aluno/horario" method="POST">
            <input type="text" name="id_pessoa" value="237716" placeholder="id_pessoa" />
            <br />
            <input type="text" name="googleId" value="yt7nv5y34cv98nyvyp" placeholder="googleId" />
            <br />
            <input type="submit" value="ir" />
        </form>
        <hr>
        api mural
        <br />
        <form target="_blank" action="<?= $endApi ?>api/aluno/mural" method="POST">
            <input type="text" name="id_pessoa" value="237716" placeholder="id_pessoa" />
            <br />
            <input type="text" name="googleId" value="yt7nv5y34cv98nyvyp" placeholder="googleId" />
            <br />
            <input type="submit" value="ir" />
        </form>
        <hr>
        api mural Pais
        <br />
        <form target="_blank" action="<?= $endApi ?>api/aluno/mural_pais" method="POST">
            <input type="text" name="id_pessoa" value="1" placeholder="id_pessoa" />
            <br />
            <input type="text" name="googleId" value="yt7nv5y34cv98nyvyp" placeholder="googleId" />
            <br />
            <input type="submit" value="ir" />
        </form>
        <hr>
        api escola
        <br />
        <form target="_blank" action="<?= $endApi ?>api/aluno/escola" method="POST">
            <input type="text" name="id_pessoa" value="237716" placeholder="id_pessoa" />
            <br />
            <input type="text" name="googleId" value="yt7nv5y34cv98nyvyp" placeholder="googleId" />
            <br />
            <input type="submit" value="ir" />
        </form>
        <hr>
        api pais
        <br />
        <form target="_blank" action="<?= $endApi ?>api/aluno/respAut" method="POST">
            <input type="text" name="email" value="macrotexxx@gmail.com" placeholder="id_pessoa" />
            <br />
            <input type="text" name="googleId" value="yt7nv5y34cv98nyvyp" placeholder="googleId" />
            <br />
            <input type="submit" value="ir" />
        </form>
        <hr>
        api gerar token
        <br />
        <form target="_blank" action="<?= $endApi ?>api/token" method="POST">
            <input type="text" name="id" value="1" placeholder="ID" />
            <br />
            <input type="pin" name="pin" value="yt7nv5y34cv98nyvyp" placeholder="PIN" />
            <br />
            <input type="submit" value="ir" />
        </form>
        <hr>

    </div>
    <?php
}