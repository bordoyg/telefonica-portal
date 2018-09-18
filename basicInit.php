<?php
if(!isset($GLOBALS['dispatcher'])){
    $GLOBALS['dispatcher']=new Dispatcher();
}
$dispatcher = $GLOBALS['dispatcher'];

if($dispatcher->getControlador()->isValidActivity()){
    $htmlFile=$dispatcher->resolveAction();
}else{
    $dispatcher->getControlador()->addMessageError(Controlador::ERROR_ORDEN_NO_VIGENTE);
    $htmlFile= Dispatcher::MESSAGES_URL;
}
isset($htmlFile) ? require_once(APPPATH .  '/widgets/custom/library/'. $htmlFile) :'';
?>
<head>
</head>
<body>
basicInit
</body>
