<!DOCTYPE html>
<html>
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
                <h1>Motivo de Cancelación</h1>
                <p>Por favor, selecciona un motivo para la cancelacion</p>
                
                <form id="motivoCancelacionForm" action="" method="post">
                    <select id="motivoCancelacionSelect" name="<?php echo Dispatcher::MOTIVO_CANCELACION_PARAM?>" class="multicanc">
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
                        echo 'class="bigbtn">Cancelarla</button>';
                        
                    ?>
                </form>
                
            </section>
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