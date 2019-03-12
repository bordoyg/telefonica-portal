<!DOCTYPE html>
<html>
<?php require_once(APPPATH . 'widgets/custom/library/header.php'); ?>
<body>
  <link rel="stylesheet" href="https://js.arcgis.com/4.8/esri/css/main.css">
  <script src="https://js.arcgis.com/4.8/"></script>

    <header>
        <div class="menu-head">
            <div class="logo-head">
                <a href="http://etb.com">
                    <img alt="ETB" src="/euf/assets/others/etb/img/logoetb2.png" /></a>
            </div>
        </div>
    </header>

    <div class="content">
        <div class="wrap">
            <section class="type1-cont">
                <h1>En Camino</h1>
                <p>Tu técnico esta en camino</p>
                <div class="data-tec">
                    <?php 
                        if(isset($_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->imageData)){
                            $mediaType=$_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->mediaType;
                            $imageData=$_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->imageData;
                            echo '<img src="data: ' . $mediaType . ';base64,' . $imageData . '" height="120" width="120" />';
                        }else{
                            echo '<img class="avatar" src="/euf/assets/others/etb/img/avatar.png" />';
                        }
                        
                    ?>
                    <p>
                        <span>Técnico:</span><?php echo $_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->name; ?><br>
<!--                    <span>Número de Contacto:</span> 1167876765 -->
                    </p>
<!--                <a href="tel:1167876765" class="ccall"><img src="/euf/assets/others/etb/img/tel.png"/></a> -->
                </div>
               <div id="viewDiv"></div>
                  <script type="text/javascript">
                  
                      	require([
                      	  "esri/Map",
                      	  "esri/views/MapView",
                      	  "esri/widgets/BasemapToggle",
                      	  "esri/Graphic",
                      	  "esri/layers/GraphicsLayer",
                      	  "esri/geometry/Extent",
                      	  "esri/geometry/SpatialReference"
                      	], function(
                      	  Map,
                      	  MapView,
                      	  BasemapToggle,
                      	  Graphic,
                      	  GraphicsLayer,
                      	  Extent,
                      	  SpatialReference
                      	) {
        
                      	  // Create the Map with an initial basemap
                      	  var map = new Map({
                      		basemap: "topo"
                      	  });
        
                      	  // Create the MapView and reference the Map in the instance
                      	  var view = new MapView({
                      		container: "viewDiv",
                      		map: map,
                      	  });
        
                      	  var toggle = new BasemapToggle({
                      		view: view, // view that provides access to the map's 'topo' basemap
                      		nextBasemap: "hybrid" // allows for toggling to the 'hybrid' basemap
                      	  });
        
                      	  // Add widget to the top right corner of the view
                      	  view.ui.add(toggle, "top-right");
                      	  
                      	  var pictureGraphic = new Graphic({
                      	   geometry: {
                      		 type: "point",
                      		 x: <?php echo $_REQUEST[Controlador::LOCATION_TECHNICAN_LON_PARAM]?>,
                      		 y: <?php echo $_REQUEST[Controlador::LOCATION_TECHNICAN_LAT_PARAM]?>
                      	   },
                      	   symbol: {
                      		 type: "picture-marker",
                      		 url: "/euf/assets/others/etb/images/tech-icon.png",
                      		 width: "14px",
                      		 height: "26px"
                      	   }
                      	 });
                      	  var pictureGraphic2 = new Graphic({
                      	   geometry: {
                      		 type: "point",
                      		x: <?php echo $_REQUEST[Controlador::LOCATION_CUSTOMER_LON_PARAM]?>,
                            y: <?php echo $_REQUEST[Controlador::LOCATION_CUSTOMER_LAT_PARAM]?>
                      	   },
                      	   symbol: {
                      		 type: "picture-marker",
                      		 url: "/euf/assets/others/etb/images/home-icon.png",
                      		 width: "14px",
                      		 height: "26px"
                      	   }
                      	 });
                      	 view.graphics.add(pictureGraphic);
                      	 view.graphics.add(pictureGraphic2);
                      	 
                      	  var layer = new GraphicsLayer({
                      		graphics: [pictureGraphic, pictureGraphic2]
                      	  });
        
                      	  map.add(layer);
                      	  view.when(function(){
                      	  // All the resources in the MapView and the map have loaded. Now execute additional processes 
                      		view.goTo(layer.graphics).then(function () {
                      			view.zoom = view.zoom - 1;
                      		});
                      	  }, function(error){
                      	  // Use the errback function to handle when the view doesn't load properly
                      	     console.log("The view's resources failed to load: ", error);
                      	  });
                      	});
                  </script>
                <a class="smallbtnfull" href="https://etb.com/">Finalizar</a>
            </section>
        </div>
    </div>

    <footer>
        <p class="credits">2019 © ETB S.A. ESP. Todos los derechos reservados</p>
    </footer>
</body>
</html>