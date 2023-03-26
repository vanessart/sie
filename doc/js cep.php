<script>
    function consultacep(cep) {
        cep = cep.replace(/\D/g, "")
        url = "http://cep.correiocontrol.com.br/" + cep + ".js"
        s = document.createElement('script')
        s.setAttribute('charset', 'utf-8')
        s.src = url
        document.querySelector('head').appendChild(s)
    }

    function correiocontrolcep(valor) {
        if (valor.erro) {
            alert('Cep não encontrado');
            return;
        }
        ;
        document.getElementById('logradouro').value = valor.logradouro
        document.getElementById('bairro').value = valor.bairro
        document.getElementById('localidade').value = valor.localidade
        document.getElementById('uf').value = valor.uf
    }
</script>

<table>
     <tr>
                                            <td style="width: 130px">
                                                CEP
                                            </td>
                                            <td style="width: 150px">
                                                <input <?php echo $readonly ?> style="width: 150px" required id="cep" onblur="consultacep(this.value)" name="giz_prof[cep]" value="<?php echo @$giz['cep'] ?>"  class="f" />
                                            </td>
                                            <td>
                                                Logradouro:
                                            </td>
                                            <td colspan="2">
                                                <input <?php echo $readonly ?> required="" id="logradouro" name="giz_prof[logradouro]" value="<?php echo @$giz['logradouro'] ?>" style="width: 317px" class="f" />
                                            </td> 
                                            <td>
                                                Nº
                                            </td>
                                            <td style="width: 50px">
                                                <input <?php echo $readonly ?> style="width: 50px" required type="text" name="giz_prof[num]" value="<?php echo @$giz['num'] ?>"   class="f" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Complemento
                                            </td>
                                            <td colspan="6">
                                                <input <?php echo $readonly ?> type="text" name="giz_prof[compl]" value="<?php echo @$giz['compl'] ?>" style="width: 100%"  class="f" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Bairro
                                            </td>
                                            <td>
                                                <input <?php echo $readonly ?> style="width: 150px" style="" id="bairro"  name="giz_prof[bairro]" value="<?php echo @$giz['bairro'] ?>"  class="f" />
                                            </td>
                                            <td>
                                                Cidade
                                            </td>
                                            <td colspan="2">
                                                <input <?php echo $readonly ?> required="" id="localidade" name="giz_prof[localidade]" value="<?php echo @$giz['localidade'] ?>" class="f" />
                                            </td>
                                            <td>
                                                UF
                                            </td>
                                            <td>
                                                <input <?php echo $readonly ?> style="width: 50px" required=""  id="uf" name="giz_prof[uf]" value="<?php echo @$giz['uf'] ?>" size="4" class="f" />
                                            </td>
                                        </tr>
</table>