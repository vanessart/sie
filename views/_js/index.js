// INICIA MAPA
// let map;

// async function initMap() {

//     const cep = document.getElementById('location-input').value;
//     if (cep.length == 8) {

//         // const myLatlng = await getAddress(lat, lng);
//         const endereco = await geocode(lat, lng);

//         const myLatlng = { lat: -23.539098, lng: -46.8843471 };
//         // console.log(myLatlng);

//         const map = new google.maps.Map(document.getElementById("map"), {
//             zoom: 18,
//             center: myLatlng,
//         });
//         // Create the initial InfoWindow.
//         let infoWindow = new google.maps.InfoWindow({
//             content: "Clique no mapa para obter o endereço correto!",
//             position: myLatlng,
//         });
//         infoWindow.open(map);
//         // Configure the click listener.
//         map.addListener("click", (mapsMouseEvent) => {
//             // Close the current InfoWindow.
//             infoWindow.close();
//             // Create a new InfoWindow.
//             infoWindow = new google.maps.InfoWindow({
//                 position: mapsMouseEvent.latLng,
//             });
//             infoWindow.setContent(
//                 JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2)
//             );
//             infoWindow.open(map);
//         });
//     }
// }


// INICIA MONTA TELA
function montaHTMLTela(lat, lng) {
    // document.getElementById('logradouro').value = logradourofinal;
    // document.getElementById('bairro').value = bairrofinal;
    // document.getElementById('cidade').value = cidadeFinal;
    // document.getElementById('estado').value = estadofinal;
    // console.log(lat);
    // console.log(lng);
    document.getElementById('latitude_pessoa').value = lat;
    document.getElementById('longitude_pessoa').value = lng;
}



// REQUISIÇÕES
async function geocode(event) {

    const cep = document.getElementById('location-input').value;

    if (cep.length == 8) {

        $('#map_content').removeClass("mapa_invisible");
        $('#map_content').addClass('mapa_visible');


        const params = {
            params: {
                address: cep,
                key: 'AIzaSyB-fsEPEgboVq5ChY76XpqRe7ezvnZYxi4'
            }
        };

        const response = await axios.get('https://maps.googleapis.com/maps/api/geocode/json', params);

        const statuscep = response.data.results[0];

        if (statuscep === undefined) {
            alert('CEP não encontrado - insira um CEP valido');
            $('#map_content').addClass("mapa_invisible");
            $('#map_content').removeClass('mapa_visible');
            document.getElementById('logradouro').value = null;
            document.getElementById('bairro').value = null;
            document.getElementById('cidade').value = null;
            document.getElementById('estado').value = null;
        } else {

            const lat = response.data.results[0].geometry.location.lat;
            const lng = response.data.results[0].geometry.location.lng;


            const endereco = await getAddress(lat, lng);

            const enderecofinal = endereco.address_components;


            // FILTROS

            // LOGRADOURO
            function logradourofind(logradouro) {
                return logradouro.types[0] === 'route';
            }

            const logradouro = enderecofinal.find(logradourofind);

            // console.log(logradouro);


            //VALIDA LOGRADOURO
            if (logradouro === undefined) {
                async function logradourofind2(logradouro) {
                    return logradouro.types[0] === 'establishment';
                }
                const tipologradouro = enderecofinal.find(logradourofind2).types[0];

                if (tipologradouro === 'establishment') {
                    var logradourofinal = enderecofinal.find(logradourofind2).long_name;
                } else {
                    const enderecofinal2 = endereco.formatted_address;
                    var logradourofinal = enderecofinal2;
                    // console.log(logradourofinal);
                    var x = "FAT* FAT32*";
                    var array = logradourofinal.split(",");
                    var array = array[0].split("-");
                    var array = array[0].split("nº");
                    logradourofinal = array[0];
                    // console.log(logradourofinal);

                    // console.log(array[1]);
                }

            } else {
                var logradourofinal = logradouro.long_name;
                // console.log(logradourofinal);
            }




            // CIDADE
            function cidadefind(cidadefind) { return cidadefind.types[0] === 'administrative_area_level_2'; }
            const cidadeFinal = enderecofinal.find(cidadefind).long_name;

            // BAIRRO
            function bairrofind(bairro) { return bairro.types[1] === 'sublocality'; }
            const bairrofinal = enderecofinal.find(bairrofind).long_name;

            // ESTADO
            function estadofind(estado) { return estado.types[0] === 'administrative_area_level_1'; }
            const estadofinal = enderecofinal.find(estadofind).short_name;


            montaHTMLTela(lat, lng);
        }
    } else {
        alert('Insira um cep valido');
        alert('CEP não encontrado - insira um CEP valido');
        $('#map_content').addClass("mapa_invisible");
        $('#map_content').removeClass('mapa_visible');
        document.getElementById('logradouro').value = null;
        document.getElementById('bairro').value = null;
        document.getElementById('cidade').value = null;
        document.getElementById('estado').value = null;
    }
}

async function getAddress(lat, lng) {

    const latlong = lat + ',' + lng;

    const params = {
        params: {
            latlng: latlong,
            key: 'AIzaSyB-fsEPEgboVq5ChY76XpqRe7ezvnZYxi4'
        }
    };

    const response = await axios.get('https://maps.googleapis.com/maps/api/geocode/json', params);

    return response.data.results[0];
}

// FORMATAÇÃO DO CAMPO CEP
function somenteNumeros(num) {
    var er = /[^0-9.]/;
    er.lastIndex = 0;
    var campo = num;
    if (er.test(campo.value)) {
        campo.value = "";
    }
}



function somenteLetras(num) {
    var er = /[^\w\.]|\d/g;
    er.lastIndex = 0;
    var campo = num;
    if (er.test(campo.value)) {
        campo.value = "";
    }
};

// MASCARA/FORMATAÇÃO CAMPOS
$(document).ready(function() {
    $('#cep').mask('00000-000');
    $('#telefone').mask('(00) 00000-0000');
    $('#telefone2').mask('(00) 00000-0000');
    $('#telefone3').mask('(00) 0000-0000');
    $('#cpf').mask('000.000.000-00');
    $('#rg').mask('00.000.000-0');
});

$(document).ready(function() {

    function limpa_formulário_cep() {
        // Limpa valores do formulário de cep.
        $("#logradouro").val("");
        $("#bairro").val("");
        $("#cidade").val("");
        $("#estado").val("");
    }

    //Quando o campo cep perde o foco.
    $("#location-input").blur(function() {

        //Nova variável "cep" somente com dígitos.
        var cep = $(this).val().replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                $("#logradouro").val("...");
                $("#bairro").val("...");
                $("#cidade").val("...");
                $("#estado").val("...");

                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#logradouro").val(dados.logradouro);
                        $("#bairro").val(dados.bairro);
                        $("#cidade").val(dados.localidade);
                        $("#estado").val(dados.uf);
                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulário_cep();
                        alert("CEP não encontrado.");
                    }
                });
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
    });
});
