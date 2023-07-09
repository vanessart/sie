function formjs(id, acao) {
    if (confirm(acao + " ?")) {
        document.getElementById(id).submit();
    }
}


$(function () {
    var showLoaderAjax = true;
    $(document).ajaxStart(function(){
        showLoaderAjax = true;
        
        setTimeout(function (){
            if (showLoaderAjax) jQuery('#ajaxLoader').show();
        }, 100);
    });
    $(document).ajaxStop(function(){
        showLoaderAjax = false;
        jQuery('#ajaxLoader').hide();
    });
 });

 /**
 * Copia o texto do elemento informado javaScript
 * @param type $id : ID do elemento HTML
 * @param type $type (opcional): Indica o tipo de conteúdo do elemento 
 *             : html (padrão)= usado para recuperar o texto de elementos HTML (div, span, td, etc) 
 *             : value = usado para recuperar o texto de elementos Formulário (input, select, textarea, etc) 
 */
function copyText($id, $type) {
    if (!$type) {
        $type = 'html';
    }

    var el = document.getElementById($id);
    var text = ($type=='html') ? el.innerHTML : el.value;
    
    if(!document.getElementById('copy_text_client')) {
        var input = document.createElement("textarea");
        input.setAttribute('id', 'copy_text_client');

        // adiciona o novo elemento criado e seu conteúdo ao DOM
        document.body.appendChild(input);
    } else {
        var input = document.getElementById('copy_text_client');
    }
    
    input.value = text+"\n\n";
    input.setAttribute('style', 'display:block');
    input.select();
    document.execCommand('copy');
    input.setAttribute('style', 'display:none');

    return 'Texto Copiado com Sucesso';
}

function _funcDisableButton(el){
    if (!el) return false;

    el.setAttribute('disabled', 'disabled');
    el.classList.add("disabled");

    if (el.tagName == 'BUTTON') {
        el.setAttribute('data-text-old', el.innerText);
        el.innerText = 'Aguarde ... Salvando os dados';
    } else {
        if (el.getAttribute('type') == 'submit'){
            el.setAttribute('data-text-old', el.value);
            el.value = 'Aguarde ... Salvando os dados';
        } else {
            el.setAttribute('data-text-old', el.innerText);
            el.innerText = 'Aguarde ... Salvando os dados';
        }
    }
    return true;
}
function _funcEnableButton(el){
    if (!el) return false;

    el.removeAttribute('disabled');
    el.classList.remove("disabled");

    if (el.tagName == 'BUTTON') {
        el.innerText = el.getAttribute('data-text-old');
    } else {
        if (el.getAttribute('type') == 'submit'){
            el.value = el.getAttribute('data-text-old');
        } else {
            el.innerText = el.getAttribute('data-text-old');
        }
    }
    return true;
}