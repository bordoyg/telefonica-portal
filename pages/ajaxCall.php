<?php
require_once(APPPATH . 'widgets/custom/library/dispatcher.php');
require_once(APPPATH . 'widgets/custom/library/utils.php');
$dispatcher = new Dispatcher();

if($dispatcher->getControlador()->findTechnicanLocationJson()){
    $coords=$dispatcher->getControlador()->findTechnicanLocationJson();
    echo $coords;
}
?>
<head>
</head>
<body>
</body>

