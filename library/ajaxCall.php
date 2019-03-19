<?php
$dispatcher = $GLOBALS['dispatcher'];
if($dispatcher->getControlador()->findTechnicanLocationJson()){
    $coords=$dispatcher->getControlador()->findTechnicanLocationJson();
    echo $coords;
}
?>