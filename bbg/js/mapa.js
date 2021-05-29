var imei = _GET("imei");
var inicio = _GET("inicio");
var fim = _GET("fim");

var map;
var idInfoBoxAberto;
var infoBox = [];
var markers = [];

function initialize() {	
	var latlng = new google.maps.LatLng(-18.8800397, -47.05878999999999);
	
    var options = {
        zoom: 5,
		center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById("mapa"), options);
}

initialize();

function abrirInfoBox(id, marker) {
	if (typeof(idInfoBoxAberto) == 'number' && typeof(infoBox[idInfoBoxAberto]) == 'object') {
		infoBox[idInfoBoxAberto].close();
	}
	infoBox[id].open(map, marker);
	idInfoBoxAberto = id;
}


function carregarPontos() {
	
	$.getJSON('bbg/js/pontos.php?imei='+imei+'&inicio='+inicio+'&fim='+fim, function(pontos) {
		
		var latlngbounds = new google.maps.LatLngBounds();
		
		$.each(pontos, function(index, ponto) {
			
			var latlng = new google.maps.LatLng(ponto.Latitude, ponto.Longitude);
			
			var geocoder = new google.maps.Geocoder();
			
			var marker = new google.maps.Marker({
				//position: new google.maps.LatLng(ponto.Latitude, ponto.Longitude),
				position: latlng,
				title: ponto.Data,
				icon: 'bbg/img/'+ponto.Descricao+'.png'
			});
			
			
			var myOptions = {
				content:'<p>' + ponto.Descricao + '</p><p>' + ponto.Data + '</p>',
				pixelOffset: new google.maps.Size(-150, 0)
        	};

			infoBox[ponto.Id] = new InfoBox(myOptions);
			infoBox[ponto.Id].marker = marker;
			
			infoBox[ponto.Id].listener = google.maps.event.addListener(marker, 'click', function () {
				geocoder.geocode({'latLng': latlng}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						if (results[0]) {  
							$('#txtEndereco').val(results[0].formatted_address);
						}
					}
				})
				abrirInfoBox(ponto.Id, marker);
			});
			
			
			markers.push(marker);
			
			latlngbounds.extend(marker.position);
			
		});
		
		var markerCluster = new MarkerClusterer(map, markers,{
			maxZoom: 19,
			gridSize: 40,
			zoomOnClick: true
		});
		
		map.fitBounds(latlngbounds);
		
	});
	
}

carregarPontos();