<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of email
 *
 * @author mc
 */
class sendEmail {

    public static function send($destinatario, $n_destinatario, $assunto, $texto, $titulo = NULL, $remetente = NULL, $n_remetente = NULL, $token = NULL, $url = NULL, $alert = NULL) {
        if (empty($n_remetente)) {
            $n_remetente = 'WebMaster';
        }

        if (empty($remetente)) {
            $remetente = 'webmaster@sendmail.eco.br';
        }
        if (empty($token)) {
            $token = $_SESSION['userdata']['user_session_id'];
        }

        $dados = [
            'remetente' => $remetente,
            'no_remetente' => $n_remetente,
            'texto' => $texto,
            'titulo' => $titulo,
            'destinatario' => $destinatario,
            'n_destinatario' => $n_destinatario,
            'assunto' => $assunto,
            'token' => $token,
            'data' => @date("Y-m-d"),
            'url' => $url,
            'alert' => $alert
        ];
        ?>

        <form target="sd" action="https://sendmail.eco.br/emailsend/email.php" name="sen" method = "POST">
            <?php
            foreach ($dados as $k => $v) {
                ?>
                <input type="hidden" name="<?php echo $k ?>" value='<?php echo $v ?>' />
                <?php
            }
            ?>
        </form>
        <iframe  style="display: none; width: 0px; height: 0px" name="sd"></iframe>
        <script>
            document.sen.submit();
        </script>
        <?php
    }

    public static function recuperaEmail($n_pessoa, $email, $id, $token, $url) {
        $assunto = $titulo = "Recuperação de Senha";
        $remetente = "webmaster@sendmail.eco.br";
        $n_remetente = 'WebMaster';
        $destinatario = $email;
        $n_destinatario = $n_pessoa;
        $texto = "Caro(a) $n_pessoa<br /><br />"
                . "Para recuperar a sua senha clique no link abaixo:<br /><br />"
                . "<a target=\"_blank\" href=\"".BASE_URL."sie/home/recupera?vr=" . substr(uniqid(), 0, 4) . $id . '-' . $token . "\">".BASE_URL."sie/home/recupera?vr=" . substr(uniqid(), 0, 4) . $id . '-' . $token . "</a>";

        sendEmail::send($destinatario, $n_destinatario, $assunto, $texto, $titulo, $remetente, $n_remetente, $token, $url);
    }

    public static function enviaEmail($n_pessoa, $email, $senha, $user, $url) {
        $assunto = $titulo = "Recuperação de Senha";
        $remetente = "webmaster@sendmail.eco.br";
        $n_remetente = 'WebMaster';
        $destinatario = $email;
        $n_destinatario = $n_pessoa;
        $texto = CLI_NOME ."<br />"
                . "SECRETARIA DE EDUCAÇÃO<br />"
                . "DTTIE-DEPARTAMENTO TÉCNICO DE TECNOLOGIA DA INFORMAÇÃO EDUCACIONAL<br />"
                . "=======================================================================<br /><br />"
                . "Prezado(a), $n_pessoa <br /><br />"
                . "Seja bem-vindo! <br />"
                . "Segue abaixo seu USUÁRIO e SENHA para acesso ao ".SISTEMA_NOME.". - Sistemas Integrados da Educação de ".CLI_CIDADE.", em:<br /><br />"
                . "<a href=\"". CLI_URL ."/portal/\">". CLI_URL ."</a>.<br /><br />"
                . "|———————————————————><br />"
                . "|<br />"
                . "|       USUÁRIO:  $user<br />"
                . "|       SENHA: $senha<br />"
                . "|<br />"
                . "|———————————————————><br /><br />"
                . "ATENÇÃO:<br />"
                . "=========<br />"
                . "* Caso você tenha permissão de acesso para algum outro subsistema, poderá usar a mesma senha;<br />"
                . "* Caso esqueça sua senha, poderá clicar em “Esqueci a Senha”, para tanto, mantenha seu e-mail sempre atualizado no ".SISTEMA_NOME.";<br />"
                . "* As identificações e as senhas para acesso à rede corporativa da Secretaria de Educação (incluindo o ".SISTEMA_NOME.") são sigilosas, de uso pessoal e intransferível.<br />"
                . "* Você deve trocar sua senha sempre que existir qualquer indicação de possível comprometimento da rede corporativa ou da própria senha.<br />"
                . "* Para trocar sua senha, acesse o menu superior << MAIS  >> - Config. usuário. <br />"
                . "* Nossa política de segurança não permite o uso do mesmo CPF para dois usuários, isto é, quando um usuário entra no sistema, derruba automaticamente o outro.<br /><br /><br />"
                . "Atenciosamente,<br />"
                . "Equipe Técnica D.T.T.I.E.";

        sendEmail::send($destinatario, $n_destinatario, $assunto, $texto, $titulo, $remetente, $n_remetente, NULL, $url);
    }

    public static function suporte($n_pessoa, $email, $id_sup, $rastro) {
        $assunto = $titulo = "Suporte Escolar";
        $remetente = "webmaster@sendmail.eco.br";
        $n_remetente = 'WebMaster';
        $destinatario = $email;
        $n_destinatario = $n_pessoa;
        $texto = CLI_NOME."<br />"
                . "SECRETARIA DE EDUCAÇÃO<br />"
                . "DTTIE-DEPARTAMENTO TÉCNICO DE TECNOLOGIA DA INFORMAÇÃO EDUCACIONAL<br />"
                . "=======================================================================<br /><br />"
                . "Caro(a) Sr(a) , $n_pessoa <br /><br />"
                . "Informamos que seu chamado nr. $id_sup teve seu "
                . "<br />"
                . "STATUS alterado."
                . "<br /><br />"
                . "Você poderá consultar a qualquer momento o STATUS"
                . "<br />"
                . "de seu pedido através do site:  <a href = \"".CLI_URL."\"> ".CLI_URL."</a> "
                . "<br />"
                . "clicando no link “Rastreamento de Serviços”."
                . "<br /><br />"
                . "Seu código para rastreio é \" 002$rastro$id_sup\"."
                . "<br /><br />"
                . "Se preferir clique diretamente,  ou  cole  no navegador"
                . "<br />"
                . "o link abaixo, para obter informações completas sobre"
                . "<br />"
                . "sua solicitação."
                . "<br /><br />"
                . BASE_URL ."sie/dttie/webprot?id=002$rastro$id_sup"
                . "<br /><br />"
                . "Atenciosamente,<br />"
                . "Equipe Técnica D.T.T.I.E."
                . "<br />"
                . CLI_MAIL_TEC
                . "<br />"
                . "Depto. Técnico da Tecnologia de Informação Educacional";

        sendEmail::send($destinatario, $n_destinatario, $assunto, $texto, $titulo, $remetente, $n_remetente);
    }

}
