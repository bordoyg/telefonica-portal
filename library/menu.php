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

    <div class="content">
        <div class="wrap">
            <section class="type1-cont">
            <form action="" method="post">
            	<?php 
                	$activityID=$Controlador->getActivityIdFromContext();
                	$activity=$Controlador->findActivityData($activityID);
                	$dateStart = new DateTime($activity->date . ' ' . $activity->serviceWindowStart);
                	$dateEnd = new DateTime($activity->date . ' ' . $activity->serviceWindowEnd);
                	
                	echo '<h1>Confirmación de Cita</h1>';
                	$activityDate = DateTime::createFromFormat('Y-m-d', $activity->date);
                	$currentDate=DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
                	if($activityDate==$currentDate){
                	    echo '<div class="data-tec">';
                	    if(isset($_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->imageData)){
                	        $mediaType=$_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->mediaType;
                	        $imageData=$_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->imageData;
                	        echo '<img src="data: ' . $mediaType . ';base64,' . $imageData . '" height="120" width="120" />';
                	    }else{
                	        echo '<img class="avatar" src="/euf/assets/others/etb/img/avatar.png" />';
                	    }
                	    echo '<p>';
                	    echo '<span>Técnico:</span>   ' . $_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->name . '<br>';
                	    echo '</p>';
                	    echo '</div>';
                	}
                	
                	echo '<p>Tu cita para Instalación/Reparación esta programada para el:</p>';
                	Utils::logDebug("Fecha de la cita: " . $dateStart->format('d-F-Y g:i A'));
                	echo '<h2>' . $dateStart->format('d') . '-' . $GLOBALS['translateMonth'][$dateStart->format('F')]  . '-' . $dateStart->format('Y') . '</h2>' ;
                	echo '<p>entre las ' . $dateStart->format('g:i A') . ' y las ' . $dateEnd->format('g:i A') . ' hrs.</p>';
                	
					if($Controlador->showConfirm()){
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::CONFIRMAR_LABEL . '"';
					    echo 'class="bigbtn">Confirmar Cita</button>';
					}

				    if($Controlador->showSchedule()){
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::SCHEDULE_DATE_LABEL . '"';
					    echo 'class="smallbtn sl col-md-6" style="margin: auto 10px 20px 3%;" >Reagendar</button>';
					}
					if($Controlador->showCancel()){
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::CANCEL_MOTIVO_LABEL . '"';
					    echo 'class="smallbtn sr col-md-6" style="margin: auto 3% 15px 0px;">Cancelarla</button>';
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