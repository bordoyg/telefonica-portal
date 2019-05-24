<!DOCTYPE html>
<html>
<?php 
require_once(APPPATH . 'widgets/custom/library/header.php'); 
require_once(APPPATH . 'widgets/custom/library/controlador.php'); 

$dispatcher = $GLOBALS['dispatcher'];
$Controlador = $dispatcher->getControlador();
?>
<body>
    <img src="/euf/assets/others/etb/img/bg1r.jpg" class="rpv bgrpv"/>
    <header>
        <a href="http://www.etb.com.co"><img src="/euf/assets/others/etb/img/etblogo.png"/></a>
    </header>
    <div class="content">
        <div class="cont-left">
            <form action="" method="post">
            	<?php 
                	$activityID=$Controlador->getActivityIdFromContext();
                	$activity=$Controlador->findActivityData($activityID);
                	$dateStart = new DateTime($activity->date . ' ' . $activity->serviceWindowStart);
                	$dateEnd = new DateTime($activity->date . ' ' . $activity->serviceWindowEnd);
                	
                	echo '<h1>Datos de tu cita</h1>';
                	$detectedActivity=$Controlador->isAprovisionamientoAseguramientoRecupero($activity);
                	if(strcmp(Controlador::ASEGURAMIENTO, $detectedActivity)==0){
                	    echo '<p>Tu cita para Reparación está programada para el:</p>';
                	}
                 	if(strcmp(Controlador::APROVISIONAMIENTO, $detectedActivity)==0){
                	    echo '<p>Tu cita para Instalación está programada para el:</p>';
                	}
                	if(strcmp(Controlador::RECUPERO, $detectedActivity)==0){
                 	    echo '<p>Tu cita para Recupero está programada para el:</p>';
                 	}
                	
                	Utils::logDebug("Fecha de la cita: " . $dateStart->format('d-F-Y g:i A'));
                	echo '<h2>' . $dateStart->format('d') . '-' . $GLOBALS['translateMonth'][$dateStart->format('F')]  . '-' . $dateStart->format('Y') . '</h2>' ;
                	echo '<p>entre las ' . $dateStart->format('g:i A') . ' y las ' . $dateEnd->format('g:i A') . ' hrs.</p>';
                	
					if($Controlador->showConfirm()){
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::CONFIRMAR_LABEL . '"';
					    echo 'class="bigbtn">Confirmar Cita</button>';
					}

				    if($Controlador->showSchedule()){
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::SCHEDULE_DATE_LABEL . '"';
					    if($Controlador->showCancel()){
					        echo 'class="smallbtn sb1" >Reagendar</button>';
					    }else{
					        echo 'class="bigbtn2" >Reagendar</button>';
					    }
					}
					if($Controlador->showCancel()){
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::CANCEL_MOTIVO_LABEL . '"';
					    echo 'class="smallbtn">Cancelarla</button>';
					}
					if($Controlador->showTechnicanLocation()){
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::UBICACION_LABEL . '"';
					    echo 'class="bigbtn" >Ver Ubicaci&oacute;n</button>';
					}
				?>
			</form>
            <img src="/euf/assets/others/etb/img/logofibra.png" class="logofibra"/>
        </div>
        
        <div class="cont-right">
            <img src="/euf/assets/others/etb/img/bg1.jpg" class="bg-right"/>
        </div>
    </div>
    <footer>
        <p>2019 © ETB S.A. ESP. Todos los derechos reservados.</p>
    </footer>
</body>

</html>
