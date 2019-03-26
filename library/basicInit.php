<?php
require_once(APPPATH . 'widgets/custom/library/utils.php');

$translateMonth=array();
$translateMonth['January']='Enero';
$translateMonth['February']='Febrero';
$translateMonth['March']='Marzo';
$translateMonth['April']='Abril';
$translateMonth['May']='Mayo';
$translateMonth['June']='Junio';
$translateMonth['July']='Julio';
$translateMonth['August']='Agosto';
$translateMonth['September']='Septiembre';
$translateMonth['October']='Octubre';
$translateMonth['November']='Noviembre';
$translateMonth['December']='Diciembre';

$GLOBALS['translateMonth']=$translateMonth;

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

header("X-Content-Type-Options: nosniff");
header("Strict-Transport-Security: max-age=25200");
header("X-XSS-Protection: 1; mode=block");
header("X-Frame-Options: DENY");
?>
<head>
</head>
<body>
</body>
