<?php

require_once(ABSPATH . '/app/phpmailer/src/PHPMailer.php');
require_once(ABSPATH . '/app/phpmailer/src/SMTP.php');
require_once(ABSPATH . '/app/phpmailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class mailer {

    public static function send($email, $subject, $body, $attachment = []) {
        if (!empty($email) && !empty($subject) && !empty($body)) {

            $mail = new PHPMailer(true);

            try {
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'sieb@educbarueri.sp.gov.br';
                $mail->Password = 'aptN@3@ui';
                $mail->Port = 587;
                $mail->ContentType = 'text/plain; charset=utf-8';
                $mail->CharSet = 'UTF-8';

                $mail->setFrom('sieb@educbarueri.sp.gov.br');
                $mail->addAddress($email);

                if (!empty($attachment) && is_array($attachment)) {
                    foreach ($attachment as $nameFile => $file) {
                        $mail->AddAttachment($file, $nameFile, 'base64', 'application/octet-stream');
                    }
                }

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $body;
                if ($mail->send()) {
                    return true;
                } else {
                    return null;
                }
            } catch (Exception $e) {
                return null;
            }
        }
    }

    public static function emailSenha($id_pessoa, $senha) {

        $sql = " SELECT p.n_pessoa, p.sexo, p.emailgoogle as email FROM pessoa p "
                . " WHERE p.id_pessoa = $id_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $arr = $query->fetch(PDO::FETCH_ASSOC);

        if ($arr['email']) {

            $body = ''
                    . ' Olá ' . $arr['n_pessoa'] . '.'
                    . '<br /><br />'
                    . ' Para acessar o SIEB, entre no site http://portal.educ.net.br e utilize a senha "' . $senha . '" '
                    . '<br /><br />'
                    . 'Se preferir, acesse o SIEB  utilizando o Google com o e-mail ' . $arr['email']
                    . '<br /><br />'
                    . 'Este e-mail é enviado automaticamente e não recebe mensagens.';


            $body .= '<br /><br /><br /><br />'
                    . 'SIEB';

            $exito = mailer::send($arr['email'], 'Senha de acesso', $body);
            if ($exito) {
                return $arr['email'];
            }
        }
    }

    public static function recuperaEmail($n_pessoa, $email, $id, $token) {

        $subject = "Recuperar Senha";
        $body = "Caro(a) $n_pessoa<br /><br />"
                . "Foi solicitada a redefinição de senha do seu login do SIEB '$email' no site portal.educ.net.br.<br /><br /> "
                . "Para confirmar este pedido e definir uma nova senha para sua conta, por favor, acesse o seguinte link:<br /><br /> "
                . "<a target=\"_blank\" href=\"https://portal.educ.net.br/ge/home/recupera?vr=" . substr(uniqid(), 0, 4) . $id . '-' . $token . "\">http://portal.educ.net.br/ge/home/recupera?vr=" . substr(uniqid(), 0, 4) . $id . '-' . $token . "</a><br /><br />"
                //  . "<a target=\"_blank\" href=\"http://187.84.96.130/ge/home/recupera?vr=" . substr(uniqid(), 0, 4) . $id . '-' . $token . "\">http://portal.educ.net.br/ge/home/recupera?vr=" . substr(uniqid(), 0, 4) . $id . '-' . $token . "</a><br /><br />"
                . '(Este link é válido por 30 minutos, contados a partir da solicitação no SIEB<br /><br />'
                . 'Se não foi solicitado, por favor, ignore este e-mail.<br><br>'
                . 'Caso haja alguma dúvida, favor entrar em contato com o DTTIE.';


        mailer::send($email, $subject, $body);
    }

    public static function enviaEmail($n_pessoa, $email, $senha, $user, $url) {
        $subject = "Recuperação de Senha";
        $body = "PREFEITURA MUNICIPAL DE BARUERI<br />"
                . "SECRETARIA DE EDUCAÇÃO<br />"
                . "DTTIE-DEPARTAMENTO TÉCNICO DE TECNOLOGIA DA INFORMAÇÃO EDUCACIONAL<br />"
                . "=======================================================================<br /><br />"
                . "Prezado(a), $n_pessoa <br /><br />"
                . "Seja bem-vindo! <br />"
                . "Segue abaixo seu USUÁRIO e SENHA para acesso ao S.I.E.B. - Sistemas Integrados da Educação de Barueri, em:<br /><br />"
                . "<a href=\"https://www.educbarueri.sp.gov.br/portal/\">educ.net.br</a>.<br /><br />"
                . "|———————————————————><br />"
                . "|<br />"
                . "|       USUÁRIO:  $user<br />"
                . "|       SENHA: $senha<br />"
                . "|<br />"
                . "|———————————————————><br /><br />"
                . "ATENÇÃO:<br />"
                . "=========<br />"
                . "* Caso você tenha permissão de acesso para algum outro subsistema, poderá usar a mesma senha;<br />"
                . "* Caso esqueça sua senha, poderá clicar em “Esqueci a Senha”, para tanto, mantenha seu e-mail sempre atualizado no SIEB;<br />"
                . "* As identificações e as senhas para acesso à rede corporativa da Secretaria de Educação (incluindo o SIEB) são sigilosas, de uso pessoal e intransferível.<br />"
                . "* Você deve trocar sua senha sempre que existir qualquer indicação de possível comprometimento da rede corporativa ou da própria senha.<br />"
                . "* Para trocar sua senha, acesse o menu superior << MAIS  >> - Config. usuário. <br />"
                . "* Nossa política de segurança não permite o uso do mesmo CPF para dois usuários, isto é, quando um usuário entra no sistema, derruba automaticamente o outro.<br /><br /><br />"
                . "Atenciosamente,<br />"
                . "Equipe Técnica D.T.T.I.E.";

        mailer::send($email, $subject, $body);
    }

    public static function enviaEmailCadampe($n_pessoa, $email, $senha) {
        $subject = "Sua Senha";
        $body = "PREFEITURA MUNICIPAL DE BARUERI<br />"
                . "SECRETARIA DE EDUCAÇÃO<br />"
                . "PROCESSO SELETIVO CADAMPE<br />"
                . "=======================================================================<br /><br />"
                . "Prezado(a), $n_pessoa <br /><br />"
                . "Segue abaixo sua SENHA para acesso ao PROCESSO SELETIVO CADAMPE, em:<br /><br />"
                . "<a href=\"https://portal.educ.net.br/ge/inscr/inscr/3/\">educ.net.br</a>.<br /><br />"
                . "|———————————————————><br />"
                . "|<br />"
                . "|       SENHA: $senha<br />"
                . "|<br />"
                . "|———————————————————><br /><br />"
                . "Atenciosamente,<br />"
                . "Equipe Técnica D.T.T.I.E.";

        return mailer::send($email, $subject, $body);
    }

    public static function enviaEmailCadampeCall($n_pessoa, $email, $pedido, $id_pedido, $mensagemEmail) {



        $n_inst = $pedido[0]['n_inst'];
        $rua = $pedido[0]['logradouro'];
        $num = $pedido[0]['num'];
        $tel = $pedido[0]['tel1'];
        $categoria = $pedido[0]['n_categoria'];
        $data = "De " . data::converteBr($pedido[0]['dt_inicio']) . " a " . data::converteBr($pedido[0]['dt_fim']);
        $subject = "Nova Atribuição CADAMPE - PROTOCOLO: $id_pedido";
        $body = "PREFEITURA MUNICIPAL DE BARUERI<br />"
                . "SECRETARIA DE EDUCAÇÃO<br />"
                . "PROCESSO DE ATRIBUIÇÃO CADAMPE<br />"
                . "=======================================================================<br /><br />"
                . "Prezado(a), $n_pessoa <br /><br />"
                . "Segue abaixo as informações necessárias para apresentar-se na escola:<br /><br />"
                . "|———————————————————><br />"
                . "|<br />"
                . "|       PROTOCOLO: $id_pedido<br />"
                . "|       CATEGORIA: $categoria<br />"
                . "|       DATA: $data<br />"
                . "|<br />"
                . "|       ESCOLA: $n_inst<br />"
                . "|       ENDEREÇO: $rua, $num<br />"
                . "|       TELEFONE: $tel<br />"
                . "|<br />"
                . "|———————————————————><br /><br />"
                . "<strong>IMPORTANTE: $mensagemEmail</strong><br />"
                . "Atenciosamente,<br />"
                . "Equipe Técnica D.T.T.I.E.";

        return mailer::send($email, $subject, $body);
    }

    public static function enviaEmailTransporteListaEspera($email_dest, $dados = [], $anexos = []) {
        $nome_contato = $dados['nome_contato'];
        $n_inst = strtoupper($dados['n_inst']);

        $subject = "LISTA DE ESPERA: $n_inst";
        $centerBody = "<table cellspacing='0' cellpadding='5' border='1'>"
                . "<tr>"
                . "<th>Aluno</th>"
                . "<th>Período</th>"
                . "<th>Linha</th>"
                . "<th>Viagem</th>"
                . "<th>Data da Solicitação</th>"
                . "<th>Capacidade</th>"
                . "<th>Vagas</th>"
                . "</tr>";

        foreach ($dados['dados'] as $key => $value) {
            $centerBody .= "<tr>"
                    . "<td>" . $value['n_pessoa'] . "</td>"
                    . "<td style='text-align:center'>" . dataErp::periodoDoDia($value['periodo']) . "</td>"
                    . "<td>" . $value['n_li'] . "</td>"
                    . "<td style='text-align:center'>" . $value['viagem'] . "</td>"
                    . "<td style='text-align:center'>" . $value['dt_solicita'] . "</td>"
                    . "<td style='text-align:center'>" . $value['capacidade'] . "</td>"
                    . "<td style='text-align:center'>" . $value['vagas'] . "</td>"
                    . "</tr>";
        }

        $centerBody .= "</table>";

        $body = "PREFEITURA MUNICIPAL DE BARUERI<br />"
                . "SECRETARIA DE EDUCAÇÃO<br />"
                . "LISTA DE ESPERA<br />"
                . "=======================================================================<br /><br />"
                . "Prezado(a), $nome_contato <br /><br />"
                . "Segue a lista de espera:<br /><br />"
                . $centerBody
                . "<br /><br />"
                . "Atenciosamente,<br />"
        // . "Equipe Técnica D.T.T.I.E."
        ;

        echo '<pre>';
        $params = [
            'id_inst' => $dados['id_inst'],
            'mes' => $dados['mes'],
        ];
        $content = self::getContentsURL($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . HOME_URI . '/transporte/esperaexcel', 'POST', $params);
        $attachment = ['relatorio.xls' => self::createTemporaryFile('relatorio.xls', $content)];
        return self::send($email_dest, $subject, $body, $attachment);
    }

    public static function getContentsURL($url, $method = 'POST', $params = []) {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            // CURLOPT_CONNECTTIMEOUT => 10,
            // CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $params,
            // CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            // CURLOPT_HTTPHEADER => $headers,
            // CURLOPT_HTTPPROXYTUNNEL => false,
            // CURLOPT_PROXY => '127.0.0.0:8002',
            // CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_BUFFERSIZE => 128, // more progress info
            CURLOPT_NOPROGRESS => false,
        ));
        curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function(
                $DownloadSize, $Downloaded, $UploadSize, $Uploaded
        ) {
            // If $Downloaded exceeds 1KB, returning non-0 breaks the connection!
            return ($Downloaded > (1 * 2048)) ? 1 : 0;
        });
        $content = curl_exec($ch);
        $info = curl_getinfo($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);
        return $content;
    }

    public static function createTemporaryFile($name, $content) {
        $file = DIRECTORY_SEPARATOR .
                trim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) .
                DIRECTORY_SEPARATOR .
                ltrim($name, DIRECTORY_SEPARATOR);

        file_put_contents($file, $content);

        register_shutdown_function(function() use($file) {
            unlink($file);
        });

        return $file;
    }

    public static function enviaEmailAvalia($n_pessoa, $email, $texto) {
        $subject = "Alteração de notas e faltas";
        $body = "$n_pessoa <br /><br />"
                . $texto . '<br /><br /><br />'
                . "Atenciosamente,<br />"
                . "Equipe Técnica D.T.T.I.E.";

        return mailer::send($email, $subject, $body);
    }

}
