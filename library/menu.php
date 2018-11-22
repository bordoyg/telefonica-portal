<!DOCTYPE html>
<html>
<head></head>
<?php 
require_once(APPPATH . 'widgets/custom/library/header.php'); 
$dispatcher = $GLOBALS['dispatcher'];
$Controlador = $dispatcher->getControlador();
?>
<body>
	<div id="cont_agend">
		<div class="banner_top text_center">
			<h1>ETB</h1>
			<h1>Portal de autogesti&oacute;n</h1>
		</div>
		<div class="container">
		
		<form action="" method="post">
			<div class="box_cont text_center">
				<span class="icon_big"><i class="demo-icon icon-icon_agenda"></i></span>
				<h3>Estimado cliente</h3>
				</div>
				<?php 
					if($Controlador->showConfirm()){
					    echo '<h3>';
					    $activityID=$_COOKIE[Controlador::ACTIVITY_PARAM];
					    $activity=$dispatcher->getControlador()->findActivityData($activityID);
					    $dateStart = new DateTime($activity->date . ' ' . $activity->serviceWindowStart);
					    $dateEnd = new DateTime($activity->date . ' ' . $activity->serviceWindowEnd);
					    echo 'Tu cita de instalaci&oacute;n est&aacute; programada para el ' . $dateStart->format('d') . ' de ' . $GLOBALS['translateMonth'][$dateStart->format('F')]  . ' de ' . $dateStart->format('Y') . ', en la jornada ' . $activity->timeSlot . '(' . $dateStart->format('g:i A') . ' - ' . $dateEnd->format('g:i A') . ') Conf&iacute;rmanos tu disponibilidad para atender la visita del t&eacute;cnico.';
					    
					    echo '<div>';
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::CONFIRMAR_LABEL . '"';
					    echo 'class="button btn btn-lg btn-primary" >Confirmar</button>';
					    echo '</div>';
					    echo '</h3>';
					    echo '</div>';
					}
				?>

				<?php 
				    if($Controlador->showSchedule()){
				        echo '<div class="box_cont text_center">';
				        echo '<h3>';
				        echo 'Si lo deseas puedes modificar tu cita de instalaci&oacute;n para otro d&iacute;a o jornada, sujeto a disponibilidad actual de visitas';
					    
					    echo '<div>';
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::SCHEDULE_DATE_LABEL . '"';
					    echo 'class="button btn btn-lg btn-primary" >Modificar</button>';
					    echo '</div>';
					    echo '</h3>';
					    echo '</div>';
					}
				?>

				<?php 
				    if($Controlador->showCancel()){
				        echo '<div class="box_cont text_center">';
				        echo '<h3>';
					    echo 'Puedes cancelar tu cita s&iacute; as&iacute; lo deseas. Podr&aacute;s reprogramarla posteriormente comunic&aacute;ndote a la l&iacute;nea de atenci&oacute;n gratuita 01 80009 969090';
					    
					    echo '<div>';
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::CANCELAR_LABEL . '"';
					    echo 'class="button btn btn-lg btn-primary" >Cancelar</button>';
					    echo '</div>';
					    echo '</h3>';
					    echo '</div>';
					}
					
				?>

				<?php 
				    if($Controlador->showTechnicanLocation()){
				        echo '<div class="box_cont text_center">';
				        echo '<h3>';
					    echo 'Puedes consultar la ubicaci&oacute;n del t&eacute;cnico aca';
					    
					    echo '<div>';
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::UBICACION_LABEL . '"';
					    echo 'class="button btn btn-lg btn-primary" >Ver t&eacute;cnico</button>';
					    echo '</div>';
					    echo '</h3>';
					    echo '</div>';
					}
					
				?>

			</form>
		</div>
	</div>
</body>
</html>