<!DOCTYPE html>
<html>
<?php require_once(APPPATH . 'widgets/custom/library/header.php'); ?>


<body>
    <img src="/euf/assets/others/etb/img/bg4r.jpg" class="rpv bgrpv"/>
    <header>
        <a href="http://www.etb.com.co"><img src="/euf/assets/others/etb/img/etblogo.png"/></a>
    </header>
    <div class="content">
        <div class="cont-left">
 			<h1>Cancelación de cita</h1>
            <p>Cuéntanos el motivo de tu cancelación.</p>
            
             <form id="motivoCancelacionForm" action="" method="post">
                <select id="motivoCancelacionSelect" name="<?php echo Dispatcher::MOTIVO_CANCELACION_PARAM?>" class="selector">
                    <option value="SREST">Servicio restablecido.</option>
                    <option value="NPAV">No puedo atender la visita.</option>
                </select>
                <?php    
                    $dispatcher = $GLOBALS['dispatcher'];
                    $Controlador = $dispatcher->getControlador();
                    $activityID=$Controlador->getActivityIdFromContext();
                    $activity=$Controlador->findActivityData($activityID);
                    $detectedAdctivityType = $Controlador->isAprovisionamientoAseguramientoRecupero($activity);
                    $buttonValue=Dispatcher::CANCELAR_LABEL;
                    if( $detectedAdctivityType != null && strcmp($detectedAdctivityType, Controlador::ASEGURAMIENTO)==0 ){
                        $buttonValue=Dispatcher::CANCEL_FROM_MENU_ASEGURAMIENTO_LABEL;
                    }
                    echo '<button type="submit" onclick="return validateSubmit();" name="' . Dispatcher::OPTION_PARAM . '" value="' . $buttonValue . '"';
                    echo 'class="bigbtn2">Finalizar</button>';
                    
                ?>
            </form>
            <img src="/euf/assets/others/etb/img/logofibra.png" class="logofibra"/>
        </div>
        
        <div class="cont-right">
            <img src="/euf/assets/others/etb/img/bg4.jpg" class="bg-right"/>
        </div>
    </div>

    <footer>
        <p class="credits">2018 © ETB S.A. ESP. Todos los derechos reservados. Música Autorizada Por Acinpro.</p>
    </footer>
</body>
<script>
   	$('#motivoCancelacionForm').submit(function(event) {
        if($('#motivoCancelacionSelect').val()==null){
            event.preventDefault();
            return false;
        }else{
        	return true;
        }
	});
</script>
</html>