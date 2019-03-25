<!DOCTYPE html>
<html>
<head></head>
<?php require_once(APPPATH . 'widgets/custom/library/header.php'); ?>
<body>
	<link href="/euf/assets/others/telefonica/css/tecnico/jquery-ui-themes.css" type="text/css" rel="stylesheet"/>
    <link href="/euf/assets/others/telefonica/css/tecnico/axure_rp_page.css" type="text/css" rel="stylesheet"/>
    <link href="/euf/assets/others/telefonica/css/tecnico/styles.css" type="text/css" rel="stylesheet"/>
    <link href="/euf/assets/others/telefonica/css/tecnico/styles2.css" type="text/css" rel="stylesheet"/>
   
    <div id="base" class="">

      <!-- Unnamed (Rectangle) -->
      <div id="u279" class="ax_default box_2">
        <div id="u279_div" class=""></div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u281" class="ax_default heading_1">
        <div id="u281_div" class=""></div>
        <div id="u281_text" class="text ">
          <p><span>Tu técnico esta en camino</span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u282" class="ax_default image">
        <img id="u282_img" class="img " src="/euf/assets/others/telefonica/images/u2.png"/>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u283" class="ax_default image">
        <img id="u283_img" class="img " src="/euf/assets/others/telefonica/images/u6.png"/>
      </div>

      <!-- Unnamed (Horizontal Line) -->
      <div id="u284" class="ax_default line">
        <img id="u284_img" class="img " src="/euf/assets/others/telefonica/images/u7.png"/>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u286" class="ax_default heading_1">
        <div id="u286_div" class=""></div>
        <div id="u286_text" class="text ">
          <p><span style="color:#00CC00;">Técnico:</span><span style="color:#666666;"><?php echo $_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->name; ?></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u288" class="ax_default image">
            <?php 
            if(isset($_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->imageData)){
                $mediaType=$_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->mediaType;
                $imageData=$_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->imageData;
                echo '<img id="u288_img" class="img " src="data: ' . $mediaType . ';base64,' . $imageData . '" />';
            }else{
                echo '<img id="u288_img" class="img" src="/euf/assets/others/telefonica/images/no_photo.jpg" ></img>';
            }
            
            ?>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u290" class="ax_default image">
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
          
        <?php 
            echo 'var iconTechFeature = new ol.Feature(new ol.geom.Point(ol.proj.fromLonLat([' . $_REQUEST[Controlador::LOCATION_TECHNICAN_PARAM] . '])));';
            echo 'iconTechFeature.set(\'style\', createStyle(\'/euf/assets/others/telefonica/images/tech-icon.png\', undefined));';

            echo 'var lonLatAddress = ol.proj.fromLonLat([' . $_REQUEST[Controlador::LOCATION_CUSTOMER_PARAM] . ']);';
            echo 'var iconHomeFeature = new ol.Feature(new ol.geom.Point(lonLatAddress));';
            echo 'iconHomeFeature.set(\'style\', createStyle(\'/euf/assets/others/telefonica/images/home-icon.png\', undefined));';
            
            echo 'var vectorLayer = new ol.layer.Vector({';
            echo 'style: function(feature) {';
            echo '    return feature.get(\'style\');';
            echo '}, source: new ol.source.Vector({features: [iconTechFeature, iconHomeFeature]}) });';
            if($GLOBALS['config']['show-map'] == "true"){
              
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

        <?php } ?>
      </script>
      </div>

      <!-- Unnamed (Horizontal Line) -->
      <div id="u291" class="ax_default line">
        <img id="u291_img" class="img " src="/euf/assets/others/telefonica/images/u7.png"/>
      </div>

      <!-- Unnamed (Group) -->
      <div id="u292" class="ax_default" data-left="113" data-top="467" data-width="32" data-height="37">

      </div>

      <!-- Unnamed (Group) -->
      <div id="u296" class="ax_default" data-left="256" data-top="365" data-width="33" data-height="39">

        <!-- Unnamed (Drop) -->
        <div id="u297" class="ax_default marker">
            <script src="/euf/assets/others/telefonica/js/easytimer.min.js"></script>
            <?php
            if($GLOBALS['config']['show-map'] == "true"){
            ?>
            <div style="color:black;" id="basicUsage"></div>
            <script>
                var timer = new Timer();
                timer.start();
                timer.addEventListener('secondsUpdated', function (e) {
                    $('#basicUsage').html('&uacute;ltima posici&oacute;n ' + timer.getTimeValues().toString() + 's');
                });
        	    </script>
            <?php } ?>
        	<?php 
        	if(!isset($_REQUEST[Dispatcher::NO_VOLVER])){
        	    echo '<form action="" method="post">';
        	    echo '    <div><button type="submit" class="button btn btn-lg btn-primary" >Volver</button></div>';
        	    echo '</form>';
        	}
        	?>
        </div>

      </div>

      <!-- Unnamed (Horizontal Line) -->
      <div id="u302" class="ax_default line">
        <img id="u302_img" class="img " src="/euf/assets/others/telefonica/images/u7.png"/>
      </div>
    </div>
  </body>
   
</body>
</html>