<?php
if (!defined('ABSPATH'))
    exit;

$nome = filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'userEmail', FILTER_SANITIZE_STRING);
$emailFixo = explode('@', $email)[1];
$prefixo = [
    'os',
    'call',
    'contato',
    'coord',
    'coord1',
    'coord2',
    'coord3',
    'coord4',
    'coord5',
    'dae',
    'dap',
    'dir',
    'edu',
    'ef',
    'olhonaescola',
    'orient',
    'orient1',
    'orient2',
    'orient3',
    'sec',
    'sec1',
    'sec2',
    'sec3',
    'sec4',
    'sec5',
    'ue',
    'bin',
];

$emailPrefixo = explode('.', $email)[0];
if (in_array($emailFixo, [CLI_MAIL_DOMINIO])) {
    //  if (in_array($emailPrefixo, $prefixo) || in_array($email, $emailNo)) {
    ?>
    <!--
            <div class="alert alert-danger">
                O prefixo "<?= $emailPrefixo ?>" não é válido. Utilize seu email institucional NOMINAL, terminado em @professor.barueri.br ou @educbarueri.sp.gov.br.
            </div>
    -->
    <?php
    //  } else {
    $p = sql::get('pessoa', 'id_pessoa, n_pessoa', ['emailgoogle' => $email], 'fetch');
    if (!empty($p)) {
        $nome = $p['n_pessoa'];
        $id_pessoa = $p['id_pessoa'];

        $sql = "SELECT c.id_ch, c.serial, m.n_cm FROM `lab_chrome` c "
                . " join lab_chrome_modelo m on m.id_cm = c.fk_id_cm "
                . " join lab_chrome_emprestimo e on e.fk_id_ch = c.id_ch and e.dt_fim is null  "
                . " join pessoa p on p.id_pessoa = e.fk_id_pessoa and emailgoogle LIKE '" . trim($email) . "' ";
        /**
          $sql = "SELECT c.id_ch, c.serial, m.n_cm FROM `lab_chrome` c "
          . " join lab_chrome_modelo m on m.id_cm = c.fk_id_cm "
          . "WHERE `email_google` LIKE '" . trim($email) . "' "
          . ' and recadastro != 1 '
          . ' and fk_id_cd not in (1, 3) ';
         * * */
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="border">
            <p>Olá, <?= ucfirst(strtolower(explode(' ', $nome)[0])) ?></p>
            <br />
            <?php
            if (!empty($array)) {
                if (count($array) > 1) {
                    ?>
                    <p>
                        Encontramos esses Chormebooks em que Você logou.
                    </p>
                    <br />
                    <p>
                        Verifique pelo número de série qual está sob sua responsabilidade.
                    </p>
                    <br />
                    <p>
                        O número de série está na parte de de baixo do seu Chromebook conforme mostra a imagem acima.
                    </p>
                    <br /><br />
                    <?php
                } else {
                    ?>
                    <p>
                        Encontramos esse Chormebooks em que Você logou.
                    </p>
                    <br />
                    <p>
                        Verifique pelo número de série se é este que está sob sua responsabilidade.
                    </p>
                    <br />
                    <p>
                        O número de série está na parte de de baixo do seu Chromebook conforme mostra a imagem acima.
                    </p>
                    <br /><br />
                    <?php
                }
                foreach ($array as $v) {
                    $serial[] = $v['serial'];
                    ?>

                    <table>
                        <tr>
                            <td class="border" style="padding: 6px; ">
                                <table>
                                    <tr>
                                        <td>
                                            Número de série: <span style="color: red; font-weight: bold"><?= $v['serial'] ?></span>
                                        </td>
                                        <td>&nbsp;&nbsp;</td>
                                        <td>
                                            Modelo: <?= $v['n_cm'] ?>    
                                        </td>
                                        <td>&nbsp;&nbsp;</td>
                                        <?php
                                        if (count($array) == 1) {
                                            /**
                                              ?>
                                              <td>
                                              <form id="del<?= $v['id_ch'] ?>" method="POST">
                                              <?=
                                              formErp::hidden([
                                              'id_pessoa' => $id_pessoa,
                                              'email' => $email,
                                              'serials' => "'" . implode("','", $serial) . "'",
                                              'nome' => $nome
                                              ])
                                              . formErp::hiddenToken('chromeProfDelTodos')
                                              ?>
                                              </form>

                                              <button onclick="if (confirm('Retirar o chromebook <?= $v['serial'] ?> da sua lista?')) {
                                              document.getElementById('del<?= $v['id_ch'] ?>').submit()
                                              }" class="btn btn-danger">
                                              NÃO é este
                                              </button>
                                              </td>
                                              <td>&nbsp;&nbsp;</td>

                                              <?php
                                             * 
                                             */
                                        }
                                        ?>
                                        <td>
                                            <?php
                                            if (false) {
                                                ?>
                                                <form id="add<?= $v['id_ch'] ?>" method="POST">
                                                    tag
                                                    <?=
                                                    formErp::hidden([
                                                        'id_ch' => $v['id_ch'],
                                                        'email' => $email,
                                                        'id_pessoa' => $id_pessoa,
                                                        'serial' => $v['serial'],
                                                        'nome' => $nome
                                                    ])
                                                    . formErp::hiddenToken('chromeProfEdit')
                                                    ?>
                                                    <button onclick="if (confirm('O chromebook <?= $v['serial'] ?> está sob sua responsabilidade?')) {
                                                                document.getElementById('add<?= $v['id_ch'] ?>').submit()
                                                            }" class="btn btn-success">
                                                        SIM, é este
                                                    </button>
                                                </form>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <br /><br />
                    <?php
                }
                if (count($array) > 1) {
                    /**
                      ?>
                      <table>
                      <tr>
                      <td class="border" style="padding: 6px; ">
                      <form id="del" method="POST">
                      <?=
                      formErp::hidden([
                      'id_pessoa' => $id_pessoa,
                      'email' => $email,
                      'serials' => implode(",", $serial),
                      'nome' => $nome
                      ])
                      . formErp::hiddenToken('chromeProfDelTodos')
                      ?>
                      </form>

                      <button onclick="if (confirm('Nenhum Chromebook relacionado acima está sob minha responsabilidade?')) {
                      document.getElementById('del').submit()
                      }" class="btn btn-danger">
                      Nenhum Chromebook relacionado acima está sob minha responsabilidade
                      </button>
                      </td>
                      </tr>
                      </table>
                      <?php
                     * 
                     */
                }
            } else {
                /**
                  $sql = "SELECT c.id_ch, c.serial, m.n_cm FROM `lab_chrome` c "
                  . " join lab_chrome_modelo m on m.id_cm = c.fk_id_cm "
                  . "WHERE `email_google` LIKE '" . trim($email) . "' "
                  . ' and recadastro = 1';
                 * 
                 */
                $sql = "SELECT c.id_ch, c.serial, m.n_cm FROM `lab_chrome` c "
                        . " join lab_chrome_modelo m on m.id_cm = c.fk_id_cm "
                        . " join lab_chrome_emprestimo e on e.fk_id_ch = c.id_ch and e.dt_fim is null  "
                        . " join pessoa p on p.id_pessoa = e.fk_id_pessoa and emailgoogle LIKE '" . trim($email) . "' ";
                $query = pdoSis::getInstance()->query($sql);
                $array = $query->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($array)) {
                    if (count($array) > 1) {
                        ?>
                        <p>
                            Os Chromebooks abaixo estão relacionados em nosso sistema, como sendo de sua responsabilidade.
                        </p>

                        <?php
                    } else {
                        ?>
                        <p>
                            O Chromebook abaixo está relacionado em nosso sistema, como sendo de sua responsabilidade.
                        </p>
                        <?php
                    }
                    ?>
                    <br />
                    <p>
                        Havendo alguma irregularidade, abra uma ocorrência.
                    </p>
                    <br /><br />
                    <?php
                    foreach ($array as $v) {
                        ?>
                        <table>
                            <tr>
                                <td class="border" style="padding: 6px; ">
                                    <table>
                                        <tr>
                                            <td>
                                                Número de séria: <span style="color: red; font-weight: bold"><?= $v['serial'] ?></span>
                                            </td>
                                            <td>&nbsp;&nbsp;</td>
                                            <td>
                                                Modelo: <?= $v['n_cm'] ?>    
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    &nbsp;&nbsp;
                                </td>
                                <?php
                                $sql = "SELECT serial FROM `lab_chrome_critica` WHERE `serial` LIKE '" . $v['serial'] . "' AND `respondida` != 1 ";
                                $query = pdoSis::getInstance()->query($sql);
                                @$srl = $query->fetch(PDO::FETCH_ASSOC)['serial'];
                                /**
                                  if (empty($srl)) {
                                  ?>
                                  <td>
                                  <button onclick="ocorre(<?= $v['id_ch'] ?>)" class="btn btn-danger">
                                  Abrir Ocorrência
                                  </button>
                                  </td>
                                  <?php
                                  } else {
                                  ?>
                                  <td>
                                  <button onclick="alert('Aguarde o contato da Secretaria de Educação')" class="btn btn-warning">
                                  Em Análise
                                  </button>
                                  </td>
                                  <?php
                                  }
                                 * 
                                 */
                                ?>
                                <td>
                                    &nbsp;&nbsp;
                                </td>
                                <td>
                                    <form action="<?= HOME_URI ?>/lab/protProf" target="_blank" method="POST">
                                        <?=
                                        formErp::hidden([
                                            'email' => $email,
                                            'id_ch' => $v['id_ch']
                                        ]);
                                        ?>
                                        <button type="submit" class="btn btn-info">
                                            Termo de Responsabilidade
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        </table>
                        <br /><br />
                        <?php
                    }
                    ?>
                    <div id="sn">
                        <p>
                            Tem mais algum chromebook sob sua responsabilidade?
                        </p>
                        <br /><br />
                        <div class="text-center">
                            <button class="btn btn-success" onclick="document.getElementById('maisCh').style.display = '';
                                    document.getElementById('sn').style.display = 'none';">
                                Incluir um outro Chromebook
                            </button>
                        </div>
                    </div>
                    <br />
                    <?php
                    $display = 'none';
                } else {
                    $display = 'block';
                }
                ?>
                <br />
                <div id="maisCh" style="display:<?= $display ?>">
                    <p>
                        Inclua, utilizando o formulário abaixo, o número de série do chromebook que está sob sua responsabilidade.
                    </p>
                    <br />
                    <p>
                        O número de série está na parte de baixo do seu Chromebook conforme mostra a imagem acima.
                    </p>
                    <br /><br />
                    <form method="POST">
                        <table>
                            <tr>
                                <td style="min-width: 500px">
                                    <?= formErp::input('1[serial]', 'Número de Série', null, '  style="text-transform:uppercase" required') ?>
                                </td>
                                <td>
                                    &nbsp;&nbsp; 
                                </td>
                                <td style="width: 10px">
                                    <?=
                                    formErp::hidden([
                                        '1[fk_id_pessoa_lanc]' => $id_pessoa,
                                        '1[fk_id_pessoa]' => $id_pessoa,
                                        '1[email_google]' => $email,
                                        'nome' => $nome
                                    ])
                                    . formErp::hiddenToken('chromeProfnovo')
                                    . formErp::button('Cadastrar')
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="alert alert-danger">
                Seu email é válido, porém não o encontramos relacionado a um usuário.
                Em caso de dúvida, contacte o depto de Informática.
            </div>
            <?php
        }
        ?>
    </div>
    <?php
    //  }
} else {
    ?>
    <div class="alert alert-danger">
        São válidos apenas os e-mails nominais dos domínios "<?= CLI_MAIL_DOMINIO ?>"
    </div>
    <?php
}
