<style>
    input{
        text-transform: uppercase;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
if ($_SESSION['TMP']['SIT'] == 1) {
    $disable = '';
    ?>
    <form method="POST">
        <?php
    } else {
        $disable = ' disabled ';
    }
    ?>

    <div class="row">
        <div class="col">
            <?= formErp::input(null, 'CPF', $dados['id_cpf'], ' disabled ') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[rg]', 'RG', $dados['rg'], '  ' . $disable) ?>
        </div>
        <div class="col">
            <?= formErp::input('1[rg_dig]', 'Dig. RG', $dados['rg_dig'], '  ' . $disable) ?>
        </div>
    </div>
    <br />
    <div class="row">

        <div class="col">
            <?= formErp::input('1[rg_oe]', 'O.Exp. RG', $dados['rg_oe'], '  ' . $disable) ?>
        </div>
        <div class="col">
            <?= formErp::input('1[dt_rg]', 'RG - Data de Expedição', $dados['dt_rg'], '  ' . $disable, null, 'date') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[nome]', 'Nome*', $dados['nome'], ' required ' . $disable) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[nome_social]', 'Nome Social', $dados['nome_social'], $disable) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[mae]', 'Nome da Mãe*', $dados['mae'], ' required ' . $disable) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[pai]', 'Nome do Pai', $dados['pai'], '  ' . $disable) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[municipio_nasc]', 'Município de Nascimento*', $dados['municipio_nasc'], ' required ' . $disable) ?>
        </div>
        <div class="col">
            <?= formErp::input('1[estado_nasc]', 'Estado de Nascimento*', $dados['estado_nasc'], ' required ' . $disable) ?>
        </div>
        <div class="col">
            <?= formErp::input('1[pais_nasc]', 'País de Nascimento*', $dados['pais_nasc'], ' required ' . $disable) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-8">
            <?= formErp::input('1[nacionalidade]', 'Nacionalidade*', $dados['nacionalidade'], ' required ' . $disable) ?>
        </div>
        <div class="col-4">
            <?= formErp::selectDB('sed_raca_cor', '1[fk_id_rc]', 'Raça Cor*', $dados['fk_id_rc'], null, null, null, null, ' required ' . $disable) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::select('1[sexo]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo*', $dados['sexo'], null, null, ' required ' . $disable) ?>
        </div>
        <div class="col">
            <?= formErp::input('1[dt_nasc]', 'D. Nasc.*', $dados['dt_nasc'], ' required ' . $disable, null, 'date') ?>
        </div>
        <div class="col">
            <?= formErp::selectDB('civil', '1[fk_id_civil]', 'Estado Civil*', $dados['fk_id_civil'], null, null, null, null, ' required ' . $disable) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[email]', 'E-mail*', $dados['email'], $disable. ' required ', null, 'email') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[fixo]', 'Fixo com DDD', $dados['fixo'], ' onkeyup="mascara(this, mtel)" maxlength="15" ' . $disable, null, 'tel') ?>
        </div>
        <div class="col">
            <?= formErp::input('1[celular]', 'Celular com DDD*', $dados['celular'], ' required onkeyup="mascara(this, mtel)" maxlength="15" ' . $disable, null, 'tel') ?>
        </div>
        <div class="col">
            <?= formErp::checkbox('1[whatsapp]', 1, 'O celular tem Whatsapp', $dados['whatsapp'], $disable) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-4">
            <?= formErp::input('1[cep]', 'CEP*', $dados['cep'], ' onblur="pesquisacep(this.value);" required id="cep"  size="10" maxlength="9" ' . $disable) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[logradouro]', 'Logradouro*', $dados['logradouro'], ' required id="rua" ' . $disable) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-3">
            <?= formErp::input('1[num]', 'Nº*', $dados['num'], ' required id="num" ' . $disable) ?>
        </div>
        <div class="col-9">
            <?= formErp::input('1[bairro]', 'Bairro*', $dados['bairro'], ' required id="bairro" ' . $disable) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[complemento]', 'Complemento', $dados['complemento'], ' id="complemento" ' . $disable) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-9">
            <?= formErp::input('1[cidade]', 'Cidade*', $dados['cidade'], ' required id="cidade" ' . $disable) ?>
        </div>
        <div class="col-3">
            <?= formErp::input('1[uf]', 'UF*', $dados['uf'], ' required id="uf" ' . $disable) ?>
        </div>
    </div>
    <br />
    <!--    
<div class="border">
 <div class="row">
     <div class="col">
    <?= formErp::selectNum('1[filhos]', [0, 20], 'Quantidade de filhos menores de 18 anos', $dados['filhos'], null, null, $disable) ?>
     </div>
 </div>
 <br />
 </div>
    -->
    <?php
    if ($_SESSION['TMP']['SIT'] == 1) {
        ?>
        <div style="text-align: center; padding: 10px">
            <?=
            formErp::hiddenToken('inscr_incritos_' . $form, 'ireplace', null, ['id_cpf' => $_SESSION['TMP']['CPF']])
            . formErp::hidden([
                'id_cate' => $id_cate,
                'activeNav' => 2
            ])
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
    <div class="alert alert-warning">
        * Campos Obrigatórios
    </div>
    <script>
        function limpa_formulário_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('rua').value = ("");
            document.getElementById('bairro').value = ("");
            document.getElementById('cidade').value = ("");
            document.getElementById('uf').value = ("");
            document.getElementById('ibge').value = ("");
        }

        function meu_callback(conteudo) {
            if (!("erro" in conteudo)) {
                //Atualiza os campos com os valores.
                document.getElementById('rua').value = (conteudo.logradouro);
                document.getElementById('bairro').value = (conteudo.bairro);
                document.getElementById('cidade').value = (conteudo.localidade);
                document.getElementById('uf').value = (conteudo.uf);
            } //end if.
            else {
                //CEP não Encontrado.
                limpa_formulário_cep();
                alert("CEP não encontrado.");
            }
        }

        function pesquisacep(valor) {

            //Nova variável "cep" somente com dígitos.
            var cep = valor.replace(/\D/g, '');

            //Verifica se campo cep possui valor informado.
            if (cep != "") {

                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;

                //Valida o formato do CEP.
                if (validacep.test(cep)) {

                    //Preenche os campos com "..." enquanto consulta webservice.
                    document.getElementById('rua').value = "...";
                    document.getElementById('bairro').value = "...";
                    document.getElementById('cidade').value = "...";
                    document.getElementById('uf').value = "...";

                    //Cria um elemento javascript.
                    var script = document.createElement('script');

                    //Sincroniza com o callback.
                    script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';

                    //Insere script no documento e carrega o conteúdo.
                    document.body.appendChild(script);

                } //end if.
                else {
                    //cep é inválido.
                    limpa_formulário_cep();
                    alert("Formato de CEP inválido.");
                }
            } //end if.
            else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep();
            }
        }
    </script>
    <?php
    javaScript::telMascara();
}