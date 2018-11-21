<!DOCTYPE html>
<html>
<head></head>
<?php require_once(APPPATH . 'widgets/custom/library/header.php'); ?>
<body>
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

              <div id="map" class="map"></div>
             

              <script type="text/javascript">
                  function createStyle(src, img) {
                      return new Style({
                        image: new Icon(/** @type {module:ol/style/Icon~Options} */ ({
                          anchor: [0.5, 0.96],
                          crossOrigin: 'anonymous',
                          src: src,
                          img: img,
                          imgSize: img ? [img.width, img.height] : undefined
                        }))
                      });
                    }
    
                    var iconFeature = new Feature(new Point([0, 0]));
                    iconFeature.set('style', createStyle('data/icon.png', undefined));
                    var vectorLayer = new ol.layer.VectorLayer({
                        style: function(feature) {
                          return feature.get('style');
                        },
                        source: new ol.source.VectorSource({features: [iconFeature]})
                      });
                    var titleLayer=new ol.layer.Tile({
                        source: new ol.source.OSM()
                      }),
                  <?php 
                      if(isset($_REQUEST[Controlador::LOCATION_CUSTOMER_PARAM])){
                          echo 'var lonLatAddress = ol.proj.fromLonLat([' . $_REQUEST[Controlador::LOCATION_CUSTOMER_PARAM] . ']);';
                      }else{
                          echo 'var lonLatAddress = ol.proj.fromLonLat([0, 0])';
                      }
                  ?>
                  var map = new ol.Map({
                    target: 'map',
                    layers: [titleLayer, vectorLayer],
                    view: new ol.View({
                      center: lonLatAddress,
                      zoom: 16
                    })
                  });
            
                <?php 
                    if(isset($_REQUEST[Controlador::LOCATION_TECHNICAN_PARAM])){
                        echo 'var lonLatTechnican = ol.proj.fromLonLat([' . $_REQUEST[Controlador::LOCATION_TECHNICAN_PARAM] . ']);';
                        echo 'var markers = new OpenLayers.Layer.Markers( "Markers" );';
                        echo 'map.addLayer(markers);';
                        echo 'markers.addMarker(new OpenLayers.Marker(lonLatTechnican));';
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