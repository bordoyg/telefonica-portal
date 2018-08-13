<!DOCTYPE html>
<html>
<?php include 'header.php'?>
<body>
    <style>
       /* Set the size of the div element that contains the map */
      #map {
        height: 400px;  /* The height is 400 pixels */
        width: 100%;  /* The width is the width of the web page */
       }
    </style>
	<div id="cont_agend">
		<div class="banner_top text_center">
			<h1>Telef&oacute;nica</h1>
			<h1>Portal de autogesti&oacute;n</h1>
		</div>
<!-- 		<div class="container"> -->
<!-- 			<ul class="steps_form"> -->
<!-- 				<li class="ok"><span><i class="demo-icon icon-icon_agenda"></i></span> -->
<!-- 				<p>Validaci&oacute;n de datos</p></li> -->
<!-- 				<li><span><i class="demo-icon icon-icon_calendar"></i></span> -->
<!-- 				<p>Agendamiento</p></li> -->
<!-- 				<li><span><i class="demo-icon icon-icon_ok"></i></span> -->
<!-- 				<p>Confirmaci&oacute;n</p></li> -->
<!-- 			</ul> -->
<!-- 		</div> -->
		<div class="container">
            <h3 class="text_center">Donde esta mi t&eacute;cnico?</h3>
            <?php include Controlador::MESSAGES_URL; ?>
            
            <div id="map"></div>
            
            <script>
                // Initialize and add the map
                function initMap() {
                  // El mapa, centrado en el domicilio del cliente
                  var map = new google.maps.Map(document.getElementById('map'), {zoom: 15 <?php echo isset($_REQUEST[Controlador::LOCATION_CUSTOMER_PARAM])? ', center:'.$_REQUEST[Controlador::LOCATION_CUSTOMER_PARAM] : ''; ?>});
                  // El marcador, seteado en la posision del tecnico si exsite
                  var marker = new google.maps.Marker({map: map <?php echo isset($_REQUEST[Controlador::LOCATION_TECHNICAN_PARAM])? ', position:'.$_REQUEST[Controlador::LOCATION_TECHNICAN_PARAM] : ''; ?>});
                }
            </script>
            <!--Load the API from the specified URL
            * The async attribute allows the browser to render the page while the API loads
            * The key parameter will contain your own API key (which is not needed for this tutorial)
            * The callback parameter executes the initMap() function
            -->
            <script async defer
            	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTCrs1Dqz_aMDCQx4UWjD87f9_0xq6nQc&callback=initMap">
            </script>
		</div>
	</div>
	<script
		src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>