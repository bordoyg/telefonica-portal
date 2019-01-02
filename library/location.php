<!DOCTYPE html>
<html>
<head></head>
<?php require_once(APPPATH . 'widgets/custom/library/header.php'); ?>
<body>


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
                        echo 'iconTechFeature.set(\'style\', createStyle(\'/euf/assets/others/etb/images/tech-icon.png\', undefined));';
    
                        echo 'var lonLatAddress = ol.proj.fromLonLat([' . $_REQUEST[Controlador::LOCATION_CUSTOMER_PARAM] . ']);';
                        echo 'var iconHomeFeature = new ol.Feature(new ol.geom.Point(lonLatAddress));';
                        echo 'iconHomeFeature.set(\'style\', createStyle(\'/euf/assets/others/etb/images/home-icon.png\', undefined));';
                        
                        echo 'var vectorLayer = new ol.layer.Vector({';
                        echo 'style: function(feature) {';
                        echo '    return feature.get(\'style\');';
                        echo '}, source: new ol.source.Vector({features: [iconTechFeature, iconHomeFeature]}) });';
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