<?php
require_once(APPPATH . 'widgets/custom/library/utils.php');

if(!isset($GLOBALS['dispatcher'])){
    $GLOBALS['dispatcher']=new Dispatcher();
}
$dispatcher = $GLOBALS['dispatcher'];


//validacion de existencia
if($dispatcher->getControlador()->existActivity()){
    //Validacion vigencia
    if($dispatcher->getControlador()->isValidActivity()){
        $htmlFile=$dispatcher->resolveAction();
    }else{
        $dispatcher->getControlador()->addMessageError(Controlador::ERROR_ORDEN_NO_VIGENTE);
        $htmlFile= Dispatcher::MESSAGES_URL;
    }
}else{
    $dispatcher->getControlador()->addMessageError(Controlador::ERROR_ORDEN_INEXISTENTE);
    $htmlFile= Dispatcher::MESSAGES_URL;
}

isset($htmlFile) ? require_once(APPPATH .  '/widgets/custom/library/'. $htmlFile) :'';
?>
<head>
</head>
<body>

</body>
