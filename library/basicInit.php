<?php
try{
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

    Utils::logDebug('INICIO basicInit');
    //validacion de existencia
    if($dispatcher->getControlador()->existActivity()){
        Utils::logDebug('basicInit existe actividad');
        //Validacion vigencia
        if($dispatcher->getControlador()->isValidActivity()){
            Utils::logDebug('basicInit actividad valida');
            $htmlFile=$dispatcher->resolveAction();
        }else{
            Utils::logDebug('basicInit actividad no valida');
            $htmlFile= Dispatcher::MESSAGES_URL;
        }
    }else{
        Utils::logDebug('basicInit no existe actividad');
        $dispatcher->getControlador()->addMessageError(Controlador::ERROR_ORDEN_INEXISTENTE);
        $htmlFile= Dispatcher::MESSAGES_URL;
    }
    Utils::logDebug('basicInit htmlfile: ' . $htmlFile);
    isset($htmlFile) ? require_once(APPPATH .  '/widgets/custom/library/'. $htmlFile) :'';
    
    header("X-Content-Type-Options: nosniff");
    header("Strict-Transport-Security: max-age=25200");
    header("X-XSS-Protection: 1; mode=block");
    //header("X-Frame-Options: DENY");
}catch(Exception $e){
    Utils::logDebug('Hubo un error generico al ingresar a la aplicaicon', e);
}

header("X-Content-Type-Options: nosniff");
header("Strict-Transport-Security: max-age=25200");
header("X-XSS-Protection: 1; mode=block");
header("X-Frame-Options: DENY");

?>
<head>
</head>
<body>
</body>
