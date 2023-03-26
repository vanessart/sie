(function (ctx, $) {
    
    //teste de funcionamento
    ctx.getTeste = function() {
        console.log('testeando...');
        alert('teste ok');
    },
    
    //obtem json dos produtos
    ctx.getProdesp = function (id_turma) {
        $.ajax({
            type: "POST",
            url: HOME_URI + '/vagas/ajaxgetprodesp',
            data: {id_turma : id_turma},
            beforeSend: function (jqXHR, settings) {
                console.log('beforeSend...');
                $('#btnConfirmar').fadeOut('slow');
                //console.log(jqXHR);
                //console.log(settings);
            },
            success: function (data, textStatus, jqXHR) {
                console.log('success...');
                console.log(data);
                
                $('#dados-turma').fadeIn('slow', function(){
                    var dados_turma = "";
                    dados_turma+="Turma: " + data.turma.n_turma + "<br />";
                    dados_turma+="Período: " + data.turma.periodo + "<br />";
                    dados_turma+="Ano Letivo: " + data.turma.periodo_letivo + "<br />";
                    dados_turma+="Código: : " + data.turma.codigo + "<br />";
                    dados_turma+="data gdae: " + data.turma.dt_gdae + "<br />";
                    dados_turma+="ciclo: " + data.turma.fk_id_ciclo + "<br />";
                    dados_turma+="grade: " + data.turma.fk_id_grade + "<br />";
                    dados_turma+="turma_id: " + data.turma.id_turma;
                    
                    $('#dados-turma').html(dados_turma);
                    
                });
                
                $('#dados-vaga').fadeIn('slow', function(){
                    var dados_vaga = "";
                    dados_vaga+="Nome aluno: " + data.vaga.n_aluno + "<br />";
                    dados_vaga+="Data Nasc: " + data.vaga.dt_aluno + "<br />";
                    dados_vaga+="Certidão Nasc: " + data.vaga.cn_matricula + ' ' + data.valida_cn + " <br />";
                    dados_vaga+="Gênero: " + data.vaga.sx_aluno + "<br />";
                    
                    dados_vaga+="Mãe: " + data.vaga.mae + "<br />";
                    dados_vaga+="CPF Resp: " + data.vaga.cpf_resp + "<br />";
                    dados_vaga+="Data Resp: " + data.vaga.dt_resp + "<br />";
                    
                    dados_vaga+="Criterio 1: " + data.vaga.criterio1 + "<br />";
                    
                    dados_vaga+="Vaga id: " + data.vaga.id_vaga + "<br />";
                    
                    dados_vaga+="Cep: " + data.vaga.cep + "<br />";
                    dados_vaga+="Logradouro: " + data.vaga.logradouro + "<br />";
                    dados_vaga+="Compl: " + data.vaga.compl + "<br />";
                    dados_vaga+="Bairro: " + data.vaga.bairro + "<br />";
                    dados_vaga+="Cidade: " + data.vaga.cidade + "<br />";
                    dados_vaga+="Uf: " + data.vaga.uf + "<br />";
                    
                    $('#dados-vaga').html(dados_vaga);
                });
                
                $('#dados-coleta').fadeIn('slow', function(){
                    var dados_coleta = "";
                    dados_coleta+="Cód Escola: "+data.coleta.outCodEscola+"<br />";
                    dados_coleta+="Nome Escola: "+data.coleta.outNomeEscola+"<br />";
                    dados_coleta+="Tipo: "+data.coleta.outTipoEnsino+"<br />";
                    dados_coleta+="Turma: "+data.coleta.outTurma+"<br />";
                    dados_coleta+="Turno: "+data.coleta.outTurno+"<br />";
                    
                    $('#dados-coleta').html(dados_coleta);
                });
                
                //console.log(textStatus);
                //console.log(jqXHR);
            },
            complete: function( jqXHR, textStatus) {
                console.log('complete...');
                $('#btnConfirmar').fadeIn('slow');
                //console.log(jqXHR);
                //console.log(textStatus);
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log('error...');
                //console.log(jqXHR);
                //console.log(textStatus);
                //console.log(errorThrown);
            }
        });
    },
    
    
    //geocoder
    ctx.geocoder = function(dados) {
        
        var geocoder = new google.maps.Geocoder();
        var address = dados.logradouro + ' ' + dados.bairro + ' ' + dados.localidade + ' ' + dados.uf;
        //console.log(address);
        
        $.ajax({
            type: "POST",
            url: HOME_URI + '/vagas/ajaxgeocode',
            data: {address : address},
            beforeSend: function (jqXHR, settings) {
                console.log('beforeSend...');
                //console.log(jqXHR);
                //console.log(settings);
            },
            success: function (data, textStatus, jqXHR) {
                
                //habilida divi para exibir map
                $('#gmap_canvas').show();
                
                console.log('success...');
                console.log(data);
                //console.log(geocoder);
                
                var myOptions = {
                    zoom: 18,
                    center: new google.maps.LatLng(data.latitude, data.longitude),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                
                map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
                
                marker = new google.maps.Marker({
                    map: map,
                    draggable: true,
                    position: new google.maps.LatLng(data.latitude, data.longitude)
                });

                infowindow = new google.maps.InfoWindow({
                    content: data.endereco
                });  
                
                google.maps.event.addListener(marker, "click", function () {
                    alert('...');
                });
                
                google.maps.event.addListener(marker, "dragstart", function () {
                    infowindow.close();
                });

                google.maps.event.addListener(marker, "drag", function () {
                    infowindow.close();
                });

                google.maps.event.addListener(marker, 'dragend', function () { 
                    ctx.geocodePosition(marker.getPosition(), geocoder);
                });

                ctx.geocodePosition(marker.getPosition(), geocoder);
                
                google.maps.event.addDomListener(window, 'load');

            },
            complete: function( jqXHR, textStatus) {
                console.log('complete...');
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log('error...');
            }
        });
    },
    
    ctx.geocodePosition = function(pos, geocoder) {
  
        geocoder.geocode({ latLng: pos }, function(responses) {
            //console.log(responses[0].geometry.location.lat());

            if (responses && responses.length > 0) {

                ctx.updateEndereco(responses[0].formatted_address);
                //updateCoordenadas(responses[0].geometry.location);
                ctx.updateCoordenadas(responses[0].geometry.location);

                infowindow.close();
                infowindow = new google.maps.InfoWindow({
                    content: responses[0].formatted_address
                });
               
                map.center = new google.maps.LatLng(responses[0].geometry.location.lat(), responses[0].geometry.location.lng()),
                
                infowindow.open(map, marker);

            } else {
                //updateMarkerAddress('Nenhum endereço informado para ser localizado.');
            }
        });
    },
    
    ctx.updateCoordenadas = function(latLng) {
        $('#latitude').val(latLng.lat());
        $('#longitude').val(latLng.lng()); 
    },
    
    ctx.updateEndereco = function(endereco) {
        //console.log( endereco );
        var arrEnd = endereco.split(',');
        //console.log(arrEnd);
        var rua = arrEnd[0];
        var numeroBairro = arrEnd[1].split('-');
        var numero = numeroBairro[0];
        var bairro = numeroBairro[1];
        var cidadeEstado = arrEnd[2].split('-');
        var cidade = cidadeEstado[0];
        var uf = cidadeEstado[1];
        var cep = arrEnd[3];
        
        //load dados endereço
        $('#cep').val(cep);
        $('#rua').val(rua);
        //$('#numero').val(rua);
        $('#bairro').val(bairro);
        $('#cidade').val(cidade);
        $('#uf').val(uf);
        
        $('#numero').focus();
    }
    

})(siebsed, $);