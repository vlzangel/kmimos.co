<?php
	//$keyApi = 'AIzaSyBB_j_ufdmyvN2cqhvtl-6xY-xk-PWNHgg';
	$keyApi = 'AIzaSyD-xrN3-wUMmJ6u2pY_QEQtpMYquGc70F8';
	$latitud = $_GET['lat'];
	$longitud = $_GET['lng'];
?>

<html>
  <head>
    <style type="text/css">
      html, body { height: 100%; margin: 0; padding: 0; }
      #map { height: 300px; }
    </style>
  </head>
  <body>

    <div id="map"></div>

    <script type="text/javascript">

		var map;
		function initMap() {
			var latitud = <?php echo $latitud; ?>;
			var longitud = <?php echo $longitud; ?>;
			map = new google.maps.Map(document.getElementById('map'), {
				zoom: 10,
				center:  new google.maps.LatLng(latitud, longitud), 
				mapTypeId: google.maps.MapTypeId.ROADMAP
			});
			marker = new google.maps.Marker({
				map: map,
				draggable: false,
				animation: google.maps.Animation.DROP,
				position: new google.maps.LatLng(latitud, longitud),
				icon: "https://www.kmimos.com.mx/wp-content/themes/pointfinder/vlz/img/pin.png"
			});
		}

    </script>
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=<?php echo $keyApi; ?>&callback=initMap">
    </script>
  </body>
</html>

