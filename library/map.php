<!DOCTYPE html>
<html>
<head></head>
<?php require_once(APPPATH . 'widgets/custom/library/header.php'); ?>
<body>
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
</body>
</html>