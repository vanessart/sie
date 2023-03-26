
var map;
function initialize() {


	const cep = document.getElementById('location-input').value;
	// console.log(cep);
	if (cep.length == 8) {
		var directionsService = new google.maps.DirectionsService();
		var info = new google.maps.InfoWindow({maxWidth: 200});

		var marker = new google.maps.Marker({
			title: 'Escolas',
			icon: 'marker.png',
			position: new google.maps.LatLng('-23.4985596', '-46.8663941')
		});


		var options = {
			zoom: 15,
			center: marker.position,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};

		map = new google.maps.Map($('#map_content')[0], options);

		marker.setMap(map);

		google.maps.event.addListener(marker, 'click', function() {
			info.setContent('Avenida Bias Fortes, 382 - Lourdes, Belo Horizonte - MG, 30170-010, Brasil');
			info.open(map, marker);
		});
	}
}

// function initrota(){

// 		const cep = document.getElementById('location-input').value;
// 		// console.log(cep);
// 		if (cep.length == 8) {

// 		var directionsService = new google.maps.DirectionsService();
// 		var info = new google.maps.InfoWindow({maxWidth: 200});

// 		var marker = new google.maps.Marker({
// 			title: 'Google Belo Horizonte',
// 			icon: 'marker.png',
// 			position: new google.maps.LatLng('-23.4985596', '-46.8663941')
// 		});


// 		info.close();
// 		marker.setMap(null);

// 		var directionsDisplay = new google.maps.DirectionsRenderer();

// 		const cep = document.getElementById('location-input').value;

// 		var request = {
// 			origin: cep,
// 			destination: marker.position,
// 			travelMode: google.maps.DirectionsTravelMode.DRIVING
// 		};

// 		// console.log(request);

// 		directionsService.route(request, function(response, status) {
// 			if (status == google.maps.DirectionsStatus.OK) {
// 				directionsDisplay.setDirections(response);
// 				directionsDisplay.setMap(map);
// 			}
// 		});

// 		}
// 	return false;
// }
