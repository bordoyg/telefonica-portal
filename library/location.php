<!DOCTYPE html>
<html>
<?php require_once(APPPATH . 'widgets/custom/library/header.php'); ?>
<body>
  <link rel="stylesheet" href="https://js.arcgis.com/4.8/esri/css/main.css">
  <script src="https://js.arcgis.com/4.8/"></script>
 <style>
   #viewDiv {
      padding: 0;
      margin: 0;
      height: 100%;
      width: 100%;
    }
  </style>
<script type="text/javascript">
function initMap(){
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
      		 width: "32px",
      		 height: "32px"
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
      		 width: "32px",
      		 height: "32px"
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

		setInterval(() => {
			var strSlice="";
			$.ajax({
				method: "GET",
				url: "/app/etb/ajaxCall",
			
			}).done(function(data) {
				// var start;
				// var end;
				// var coords;
				// var x;
				// var y;
				// //strSlice=data.slice(29195,29227);
				// dataSplitted = $(data).find("coordinates");
				// console.table(dataSplitted); 
				// start=strSlice.indexOf("{");
				// end=strSlice.indexOf("}");
				// strSlice=strSlice.slice(start+1,end);
				// coords=strSlice.split(',');

				view.graphics.remove(pictureGraphic);
				
				var xToPaint = data.slice(data.indexOf('<x>')+3,data.indexOf('</x>'));
				var yToPaint = data.slice(data.indexOf('<y>')+3,data.indexOf('</y>'));

				if( xToPaint !== "null" && yToPaint !== "null" ){
					pictureGraphic.geometry={
						type: "point",
						x:xToPaint ,
						y:yToPaint
					};
				}

				console.log('x:'+pictureGraphic.geometry.x);
				console.log('y:'+pictureGraphic.geometry.y);
			}).always(console.log('----'));
		}, 5000);
	});
}
  </script>
  <script>
  function ubicarMapa(){
	  $('#divMapForMobile').html('');
	  $('#divMapForDesktop').html('');
	  
	  if($( window ).width()<=760){
		$('#divMapForMobile').html('<div id="viewDiv"></div>');
  	  }else{
  		$('#divMapForDesktop').html('<div id="viewDiv"></div>');	  
  		
  	  }
  }
  $(document).ready(function() {
	  ubicarMapa();
	  initMap();
  });  
  </script>
    <header>
        <a href="http://www.etb.com.co"><img src="/euf/assets/others/etb/img/etblogo.png" /></a>
    </header>
    <div class="content">
        <div class="cont-left">

            <div class="cont-square" style="margin-top: 15%;">
                <h4 class="titlecam">Tu técnico está<br>en camino</h4>
                
                <div class="data-tec">
                	<?php 
                        if(isset($_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->imageData)){
                            $mediaType=$_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->mediaType;
                            $imageData=$_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->imageData;
                            echo '<img class="rpv imgtec2" src="data: ' . $mediaType . ';base64,' . $imageData . '" />';
                        }else{
                            echo '<img class="rpv imgtec2" src="/euf/assets/others/etb/img/avatar.png" />';
                        }
                    ?>
                    <p>
                       	<span>Técnico:</span><?php echo '  ' . $_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->name; ?><br>
                    </p>
                </div>
                
                <?php 
                    if(isset($_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->imageData)){
                        $mediaType=$_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->mediaType;
                        $imageData=$_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->imageData;
                        echo '<img class="imgtec" src="data: ' . $mediaType . ';base64,' . $imageData . '" />';
                    }else{
                        echo '<img class="imgtec" src="/euf/assets/others/etb/img/avatar.png" />';
                    }
                ?>
                <div id="divMapForMobile" class="rpv map2">
                     <!-- map content for mobile -->
                </div>
                <a class="bigbtn2" href="https://etb.com/">Finalizar</a>
            </div>

            <img src="/euf/assets/others/etb/img/logofibra.png" class="logofibra" />
        </div>
        <div class="cont-right">
            <div id="divMapForDesktop" class="map">
                <!-- map content for desktop -->
            </div>
        </div>
    </div>
    <footer>
        <p class="credits">2019 © ETB S.A. ESP. Todos los derechos reservados</p>
    </footer>
</body>
</html>