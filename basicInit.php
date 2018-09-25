<?php
if(!isset($GLOBALS['dispatcher'])){
    $GLOBALS['dispatcher']=new Dispatcher();
}
$dispatcher = $GLOBALS['dispatcher'];

// //Manejo de errores
// register_shutdown_function(function(){
//     echo ' register_shutdown_function';
//     $error = error_get_last();
//     if( $error !== NULL) {
//         echo 'Fatel Error';
//     }
// });
    
// set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
//     echo ' paso por set_error_handler';
//     //throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
// });

//Validacion vigencia
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
