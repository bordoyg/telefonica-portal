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
            <h3 class="text_center">¿Donde esta mi t&eacute;cnico?</h3>
            
            <div>
            <div style="float:left;">
			<h4>Nombre del t&eacute;cnico: <?php echo $_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->name; ?></h4>
			</div>
            <div style="float:right;">
            <?php 
            if(isset($_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->imageData)){
                $mediaType=$_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->mediaType;
                $imageData=$_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->imageData;
                echo '<img src="data: ' . $mediaType . ';base64,' . $imageData . '" height="120" width="120" />';
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
                  return new ol.style.Style({
                    image: new ol.style.Icon(/** @type {module:ol/style/Icon~Options} */ ({
                      anchor: [0.5, 0.96],
                      crossOrigin: 'anonymous',
                      src: src,
                      img: img,
                      imgSize: img ? [img.width, img.height] : undefined
                    }))
                  });
                }
                var titleLayer=new ol.layer.Tile({ source: new ol.source.OSM() });
                  
                
                var iconTechFeature = new ol.Feature(new ol.geom.Point(ol.proj.fromLonLat([<?php $_REQUEST[Controlador::LOCATION_TECHNICAN_PARAM] ?>])));
                iconTechFeature.set('style', createStyle('/euf/assets/others/telefonica/images/tech-icon.png', undefined));

                var lonLatAddress = ol.proj.fromLonLat([<?php  $_REQUEST[Controlador::LOCATION_CUSTOMER_PARAM] ?>]);
                var iconHomeFeature = new ol.Feature(new ol.geom.Point(lonLatAddress));
                iconHomeFeature.set('style', createStyle('/euf/assets/others/telefonica/images/home-icon.png', undefined));
                
                var vectorLayer = new ol.layer.Vector({
                style: function(feature) {
                    return feature.get('style');
                }, source: new ol.source.Vector({features: [iconTechFeature, iconHomeFeature]}) });
                vectorLayer.iconTechFeature=iconTechFeature;
                <?php
                if ($GLOBALS['config']['show-map'] == "true") {
                ?>

                  var map = new ol.Map({
                    target: 'map',
                    layers: [titleLayer, vectorLayer],
                    view: new ol.View({
                      center: lonLatAddress,
                      zoom: 16
                    })
                  });
                  var extent = vectorLayer.getSource().getExtent();
                  map.getView().fit(extent);



                  setInterval((map, vectorLayer) => {
          			var strSlice="";
          			$.ajax({
          				method: "GET",
          				url: "/app/telefonica/ajaxCall"
          			
          			}).done(function(data) {
						try{
							var xToPaint = data.slice(data.indexOf('<x>')+3,data.indexOf('</x>'));
              				var yToPaint = data.slice(data.indexOf('<y>')+3,data.indexOf('</y>'));
    
              				console.log('x:'+xToPaint);
              				console.log('y:'+yToPaint);
              				
							if( xToPaint == "null" && yToPaint == "null" ){
              					return;
              				}
							xToPaint=parseFloat(xToPaint);
							yToPaint=parseFloat(yToPaint);

							vectorLayer.iconTechFeature.setGeometry(new ol.geom.Point(ol.proj.fromLonLat([xToPaint,  yToPaint])));
							var extent = vectorLayer.getSource().getExtent();
                            
						}catch(err){
							console.log(err);
						}
          			}).always(console.log('----'));
          		}, 5000, map, vectorLayer);
                  
                <?php
                }
                ?>

              </script>

			<div style="height:10px; clear: both;"></div>
    		<?php 
    		if(!isset($_REQUEST[Dispatcher::NO_VOLVER])){
    		    echo '<form action="" method="post">';
    		    echo '    <div><button type="submit" class="button btn btn-lg btn-primary" >Volver</button></div>';
    		    echo '</form>';
    		}
    		?>

		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

</body>
</html>