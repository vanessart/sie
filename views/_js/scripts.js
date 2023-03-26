function formjs(id, acao) {
    if (confirm(acao + " ?")) {
        document.getElementById(id).submit();
    }
}
function mascaraData(campoData, e) {
    if (e.keyCode==8) return false;
    
    var data = document.getElementById(campoData).value.replace(/\//g, '');
    var d = '', dAux = '';

    for(i=0;i<data.length;i++){
        dAux = data.substring(i, i+1);
        if(dAux.search(/[0-9]/g) >= 0){
            if(i==1 || i==3)
                dAux += '/';

            d += dAux;
        }
    }
    
    document.getElementById(campoData).value = d;
    return true;

}

function mascaraHora(campoHora) {
    var data = document.getElementById(campoHora).value;
    if (data.length == 2) {
        data = data + ':';
        document.getElementById(campoHora).value = data;
        return true;
    }

}
//-----------------------------------------------------------------
// Entrada DD/MM/AAAA
// <input id="data4"  OnKeyUp="mascaraData('data4');" onblur="fctValidaData(this);" maxlength="10" size="10" min="10">
//-----------------------------------------------------------------
function fctValidaData(obj)
{
    var data = obj.value;
    var dia = data.substring(0, 2)
    var mes = data.substring(3, 5)
    var ano = data.substring(6, 10)

    //Criando um objeto Date usando os valores ano, mes e dia.
    var novaData = new Date(ano, (mes - 1), dia);

    var mesmoDia = parseInt(dia, 10) == parseInt(novaData.getDate());
    var mesmoMes = parseInt(mes, 10) == parseInt(novaData.getMonth()) + 1;
    var mesmoAno = parseInt(ano) == parseInt(novaData.getFullYear());

    if (!((mesmoDia) && (mesmoMes) && (mesmoAno)))
    {
        alert('Data informada é inválida! Use o formato dd/mm/aaaa');
        obj.focus();
        return false;
    }
    return true;
}
function correiocontrolcep(valor) {
    if (valor.erro) {
        alert('Cep não encontrado');
        return;
    }
    ;
    document.getElementById('logradouro').value = valor.logradouro.toUpperCase()
    document.getElementById('bairro').value = valor.bairro.toUpperCase()
    document.getElementById('localidade').value = valor.localidade.toUpperCase()
    document.getElementById('uf').value = valor.uf.toUpperCase()
}
/*
 * 
 <fieldset>
 <legend>Consulta CEP</legend>
 <label>CEP</label>
 <input id="cep" onblur="consultacep(this.value)" /><br/>
 <label>Logradouro</label>
 <input id="logradouro" /><br/>
 <label>Bairro</label>
 <input id="bairro" /><br/>
 <label>Cidade</label>
 <input id="localidade" /><br/>
 <label>UF</label>
 <input id="uf" />
 </fieldset>
 */

function validarCPF(cpf) {
    var filtro = /^\d{3}\d{3}\d{3}\d{2}$/i;

    if (!filtro.test(cpf))
    {
        window.alert("CPF inválido. Tente novamente.");
        return false;
    }

    cpf = remove(cpf, ".");
    cpf = remove(cpf, "-");

    if (cpf.length != 11 || cpf == "00000000000" || cpf == "11111111111" ||
            cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" ||
            cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" ||
            cpf == "88888888888" || cpf == "99999999999")
    {
        window.alert("CPF inválido. Tente novamente.");
        return false;
    }

    soma = 0;
    for (i = 0; i < 9; i++)
    {
        soma += parseInt(cpf.charAt(i)) * (10 - i);
    }

    resto = 11 - (soma % 11);
    if (resto == 10 || resto == 11)
    {
        resto = 0;
    }
    if (resto != parseInt(cpf.charAt(9))) {
        window.alert("CPF inválido. Tente novamente.");
        return false;
    }

    soma = 0;
    for (i = 0; i < 10; i++)
    {
        soma += parseInt(cpf.charAt(i)) * (11 - i);
    }
    resto = 11 - (soma % 11);
    if (resto == 10 || resto == 11)
    {
        resto = 0;
    }

    if (resto != parseInt(cpf.charAt(10))) {
        window.alert("CPF inválido. Tente novamente.");
        return false;
    }

    return true;
}

function abreURL(url, metodo, onde) {
    if (metodo == 'POST') {
        // metodo post
        $.post(url, function (data) {
            // página do carregador (loading)
            $("#carregador").show();
            $("#" + onde).load(url);
        });
    }
    else if (metodo == 'GET') {
        // metodo get
        $.get(url, function (data) {
            // página do carregador (loading)
            $("#carregador").show();
            $("#" + onde).load(url);
        });
    }
}


//botão menu
function toggle(obj) {
    var el = document.getElementById(obj);
    if (el.style.display !== 'none') {
        el.style.display = 'none';
    }
    else {
        el.style.display = '';
    }
}
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

$(function () {
    var showLoaderAjax = true;
    $(document).ajaxStart(function(){
        showLoaderAjax = true;
        
        setTimeout(function (){
            if (showLoaderAjax) $('#ajaxLoader').show();
        }, 100);
    });
    $(document).ajaxStop(function(){
        showLoaderAjax = false;
        $('#ajaxLoader').hide();
    });
 });