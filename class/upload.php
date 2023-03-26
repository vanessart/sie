<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of upload
 *
 * @author mc
 */
class upload {

    // Pasta onde o arquivo vai ser salvo
    public $_file;
    // Tamanho máximo do arquivo (em Bytes)
    public $_size;
    // Array com as extensões permitidas
    public $_extensions;
    // Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
    public $_rename;
    public $_prefix;
    public $_nomeArquivo = 'arquivo';
    public $_resize = true;
    private $_max_height = 600;
    private $_max_width = 600;

    public function __construct(
            $file,
            $prefix = NULL,
            $size = 2100000,
            $extensions = ['jpg', 'png', 'gif', 'pdf', 'mp4'],
            $rename = TRUE,
            $resizeAuto = TRUE,
            $max_height = 600,
            $max_width = 600
    ) {
        $this->_file = $file;
        $this->_size = $size;
        $this->_extensions = $extensions;
        $this->_prefix = $prefix;
        $this->_rename = $rename;
        $this->_resize = $resizeAuto;
        $this->_max_height = $max_height;
        $this->_max_width = $max_width;
    }

    public function up() {

        // Array com os tipos de erros de upload do PHP
        $_UP['erros'][0] = 'Não houve erro';
        $_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
        $_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
        $_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
        $_UP['erros'][4] = 'Não foi feito o upload do arquivo';
        // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
        if ($_FILES[$this->_nomeArquivo]['error'] != 0) {
            tool::alert("Não foi possível fazer o upload, erro:" . $_UP['erros'][$_FILES[$this->_nomeArquivo]['error']]);
            $exitUP = 1; // Para a execução do script
        }
        // Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
        // Faz a verificação da extensão do arquivo

        @$extensao = strtolower(end(explode('.', $_FILES[$this->_nomeArquivo]['name'])));
        // if (!in_array($extensao, $this->_extensions)) {
        //   tool::alert("Por favor, envie arquivos com as seguintes extensões: " . implode(', ', $this->_extensions));
        //   $exitUP = 1;
        //  }
        // Faz a verificação do tamanho do arquivo
        /**
          if ($this->_size < $_FILES[$this->_nomeArquivo]['size']) {
          tool::alert("O arquivo enviado é muito grande, envie arquivos de até " . str_replace('.', ',', ($this->_size / 1000000)) . "Mb.");
          $exitUP = 1;
          }
         * 
         */
        if (empty($exitUP)) {
            // O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
            // Primeiro verifica se deve trocar o nome do arquivo
            if ($this->_rename == true) {
                // Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
                $nome_final = (!empty($this->_prefix) ? $this->_prefix . '_' : '') . md5(time()) . '.' . $extensao;
            } elseif (!empty($this->_prefix)) {
                // muda o nome para o prefixo
                $nome_final = $this->_prefix . '.' . $extensao;
            } else {
                // Mantém o nome original do arquivo
                $nome_final = $_FILES[$this->_nomeArquivo]['name'];
            }

            // Depois verifica se é possível mover o arquivo para a pasta escolhida
            if (move_uploaded_file($_FILES[$this->_nomeArquivo]['tmp_name'], $this->_file . $nome_final)) {
                // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
                //tool::alert("Upload efetuado com sucesso!");

                if ($this->_resize && self::isImage($this->_file . $nome_final))
                {
                    $resizeObj = new resizeImg($this->_file . $nome_final, $this->_max_height, $this->_max_width);
                    $resizeObj->needResize();
                }

            } else {
                // Não foi possível fazer o upload, provavelmente a pasta está incorreta
                tool::alert("Não foi possível enviar o arquivo, tente novamente");
            }

            return $nome_final;
        }
    }

    public function upMultiple() {

        // Array com os tipos de erros de upload do PHP
        $_UP['erros'][0] = 'Não houve erro';
        $_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
        $_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
        $_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
        $_UP['erros'][4] = 'Não foi feito o upload do arquivo';

        $qtde = count($_FILES[$this->_nomeArquivo]['name']);
        $names = array();
        $exitUP = false;

        for ($i=0; $i < $qtde; $i++) {
                
            // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
            if ($_FILES[$this->_nomeArquivo]['error'][$i] != 0) {
                tool::alert("Não foi possível fazer o upload, erro:" . $_UP['erros'][$_FILES[$this->_nomeArquivo]['error'][$i]]);
                $exitUP = 1; // Para a execução do script
            }
            // Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
            // Faz a verificação da extensão do arquivo

            @$extensao = strtolower(end(explode('.', $_FILES[$this->_nomeArquivo]['name'][$i])));
            // if (!in_array($extensao, $this->_extensions)) {
            //   tool::alert("Por favor, envie arquivos com as seguintes extensões: " . implode(', ', $this->_extensions));
            //   $exitUP = 1;
            //  }
            // Faz a verificação do tamanho do arquivo
            /**
              if ($this->_size < $_FILES[$this->_nomeArquivo]['size']) {
              tool::alert("O arquivo enviado é muito grande, envie arquivos de até " . str_replace('.', ',', ($this->_size / 1000000)) . "Mb.");
              $exitUP = 1;
              }
             * 
             */

            if (empty($exitUP)) {
                // O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
                // Primeiro verifica se deve trocar o nome do arquivo
                if ($this->_rename == true) {
                    // Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
                    $nome_final = (!empty($this->_prefix) ? $this->_prefix . '_' : '') . md5($i.'_'.time()) . '.' . $extensao;
                } elseif (!empty($this->_prefix)) {
                    // muda o nome para o prefixo
                    $nome_final = $this->_prefix . '.' . $extensao;
                } else {
                    // Mantém o nome original do arquivo
                    $nome_final = $_FILES[$this->_nomeArquivo]['name'][$i];
                }

                // Depois verifica se é possível mover o arquivo para a pasta escolhida
                if (move_uploaded_file($_FILES[$this->_nomeArquivo]['tmp_name'][$i], $this->_file . $nome_final)) {
                    // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
                    //tool::alert("Upload efetuado com sucesso!");
                    $names[] = ['nome_original' => $_FILES[$this->_nomeArquivo]['name'][$i], 'link' => $nome_final ];

                    if ($this->_resize && self::isImage($this->_file . $nome_final))
                    {
                        $resizeObj = new resizeImg($this->_file . $nome_final, $this->_max_height, $this->_max_width);
                        $resizeObj->needResize();
                    }
                } else {
                    // Não foi possível fazer o upload, provavelmente a pasta está incorreta
                    tool::alert("Não foi possível enviar o arquivo, tente novamente");
                }
            }
        }

        return $names;
    }

    public static function getFileType( $filePath ) {
        return image_type_to_mime_type( exif_imagetype( $filePath ) );
    }

    public static function isImage( $filePath ) {
        if (empty($filePath)) return false;

        $filetype = self::getFileType($filePath);
        return ( strpos( $filetype, 'image' ) !== false );
    }

}
