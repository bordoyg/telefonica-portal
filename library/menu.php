<!DOCTYPE html>
<html>
<head></head>
<?php 
require_once(APPPATH . 'widgets/custom/library/header.php'); 
$dispatcher = $GLOBALS['dispatcher'];
$Controlador = $dispatcher->getControlador();
?>

<body>
    <header>
        <div class="menu-head">
            <div class="logo-head">
                <a href="http://etb.com">
                    <img alt="ETB" src="/euf/assets/others/etb/img/logoetb2.png" /></a>
            </div>
        </div>
    </header>

    <div class="content" style="min-height: 561px;">
        <div class="wrap">
            <section class="type1-cont">
            <form action="" method="post">
            	<?php 
					if($Controlador->showConfirm()){
					    $activityID=$Controlador->getActivityIdFromContext();
					    $activity=$Controlador->findActivityData($activityID);
					    $dateStart = new DateTime($activity->date . ' ' . $activity->serviceWindowStart);
					    $dateEnd = new DateTime($activity->date . ' ' . $activity->serviceWindowEnd);
					    
					    echo '<h1>Confirmación de Cita</h1>';
					    echo '<p>Tu cita para Instalación/Reparación esta programada para el:</p>';
					    Utils::logDebug("Fecha de la cita: " . $dateStart->format('d-F-Y g:i A'));
					    echo '<h2>' . $dateStart->format('d') . '-' . $GLOBALS['translateMonth'][$dateStart->format('F')]  . '-' . $dateStart->format('Y') . '</h2>' ;
					    echo '<p>entre las ' . $dateStart->format('g:i A') . ' y las ' . $dateEnd->format('g:i A') . ' hrs.</p>';
					    
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::CONFIRMAR_LABEL . '"';
					    echo 'class="bigbtn">Confirmar Cita</button>';
					}

				    if($Controlador->showSchedule()){
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::SCHEDULE_DATE_LABEL . '"';
					    echo 'class="smallbtn sl" >Reagendar</button>';
					}
					if($Controlador->showCancel()){
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::CANCELAR_LABEL . '"';
					    echo 'class="smallbtn sl" >Cancelarla</button>';
					}
					if($Controlador->showTechnicanLocation()){
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::UBICACION_LABEL . '"';
					    echo 'class="bigbtn" >Ver t&eacute;cnico</button>';
					}
				?>
				</form>
            </section>
        </div>
    </div>

    <footer>
        <p class="credits">2019 © ETB S.A. ESP. Todos los derechos reservados.</p>
    </footer>
</body>

</html>