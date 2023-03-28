<?php
$nome = @$_REQUEST['nome'];
$cargo = @$_REQUEST['cargo'];
$email = @$_REQUEST['email'];
$tel = @$_REQUEST['tel'];
##################            
?>
  <pre>   
    <?php
      print_r($_REQUEST);
    ?>
  </pre>
<?php
###################

    if ($nome && $cargo && $email) {

// Carregar imagem já existente no servidor
        $imagem = imagecreatefromjpeg(ABSPATH . "/includes/images/etiqueta.jpg");
        /* @Parametros
         * "foto.jpg" - Caminho relativo ou absoluto da imagem a ser carregada.
         */

// Cor de saída
        $cor = imagecolorallocate($imagem, 0, 0, 0);
        /* @Parametros
         * $imagem - Imagem previamente criada Usei imagecreatefromjpeg
         * 255 - Cor vermelha ( RGB )
         * 255 - Cor verde ( RGB )
         * 255 - Cor azul ( RGB )
         * -- No caso acima é branco
         */
 
        $font = "/includes/font/arial.ttf";
        $fontNegrito = "/includes/font/ArialNegrito.ttf";
        $nome = urldecode($nome);
        imagettftext($imagem, 13, 0, 240, 25, $cor, ABSPATH . $fontNegrito, $nome);

        $cargo = urldecode($cargo);
        imagettftext($imagem, 13, 0, 240, 48, $cor, ABSPATH . $fontNegrito, $cargo);

        $tel = urldecode( $tel);
        imagettftext($imagem, 13, 0, 240, 71, $cor, ABSPATH . $font, $tel);

        $email = urldecode($email);
        imagettftext($imagem, 13, 0, 240, 108, $cor, ABSPATH . $font, $email);

        $site = CLI_URL;
        imagettftext($imagem, 13, 0, 240, 128, $cor, ABSPATH . $font, $site);

        /* @Parametros
         * $imagem - Imagem previamente criada Usei imagecreatefromjpeg
         * 5 - tamanho da fonte. Valores de 1 a 5
         * 15 - Posição X do texto na imagem
         * 515 - Posição Y do texto na imagem
         * $nome - Texto que será escrito
         * $cor - Cor criada pelo imagecolorallocate
         */

// Header informando que é uma imagem JPEG
        header('Content-type: image/jpeg');

// eEnvia a imagem para o borwser ou arquivo
        imagejpeg($imagem, ABSPATH . '/pub/tmp/' . $nome . '.jpeg', 80);
        /* @Parametros
         * $imagem - Imagem previamente criada Usei imagecreatefromjpeg
         * NULL - O caminho para salvar o arquivo.
          Se não definido NULL a imagem será mostrado no browser.
         * 80 - Qualidade da compresão da imagem.
         */
    }