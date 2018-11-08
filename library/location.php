<!DOCTYPE html>
<html>
<head></head>
<?php require_once(APPPATH . 'widgets/custom/library/header.php'); ?>
<body>
	<div id="cont_agend">
		<div class="banner_top text_center">
			<h1>EBT</h1>
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
            
            <div>
            <div style="float:left;">
			<h4>Nombre del t&eacute;nico: <?php echo $_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->name; ?></h4>
			</div>
            <div style="float:right;">
            <?php 
            if(isset($_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->imageData)){
                $mediaType=$_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->mediaType;
                $imageData=$_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->imageData;
                echo '<img src="data: ' . $mediaType . ';base64,' . $imageData . '" />';
            }else{
                echo '<img class="display_no_photo" height="120" width="120" ></img>';
            }
            
            ?>
            </div>
            </div>
            <div style="height:10px; clear: both;"></div>
            
              <div id="mapdiv" style="height: 400px; width: 100%;"></div>
              <script type="text/javascript" src="/euf/assets/others/telefonica/js/OpenLayers.js"></script>
              <script>
                map = new OpenLayers.Map("mapdiv");
                map.addLayer(new OpenLayers.Layer.OSM());

                <?php 
                    if(isset($_REQUEST[Controlador::LOCATION_TECHNICAN_PARAM])){
                        echo 'var lonLatTechnican = new OpenLayers.LonLat(' . $_REQUEST[Controlador::LOCATION_TECHNICAN_PARAM] . ')';
                        echo '.transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());';
                        echo 'var markers = new OpenLayers.Layer.Markers( "Markers" );';
                        echo 'map.addLayer(markers);';
                        echo 'markers.addMarker(new OpenLayers.Marker(lonLatTechnican));';
                    }
                ?>
                var zoom=16;
                <?php 
                    if(isset($_REQUEST[Controlador::LOCATION_CUSTOMER_PARAM])){
                        echo 'var lonLatAddress = new OpenLayers.LonLat(' . $_REQUEST[Controlador::LOCATION_CUSTOMER_PARAM] . ')';
                        echo '.transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());';
                        echo 'map.setCenter(lonLatAddress, zoom);';
                    }
                ?>

              </script>
     
            <div style="height:10px; clear: both;"></div>
            <script src="/euf/assets/others/telefonica/js/easytimer.min.js"></script>
            <div style="float:right;" id="basicUsage"></div>
            <script>
                var timer = new Timer();
                timer.start();
                timer.addEventListener('secondsUpdated', function (e) {
                    $('#basicUsage').html('&uacute;ltima posici&oacute;n ' + timer.getTimeValues().toString() + 's');
                });
    		</script>
    		<?php 
    		if(!isset($_REQUEST[Dispatcher::NO_VOLVER])){
    		    echo '<form action="" method="post">';
    		    echo '    <div><button type="submit" class="button btn btn-lg btn-primary" >Volver</button></div>';
    		    echo '</form>';
    		}
    		?>

		</div>
	</div>
	<script
		src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>