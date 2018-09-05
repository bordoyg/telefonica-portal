<!DOCTYPE html>
<html>
<?php include 'header.php'?>
<body>
	<div id="cont_agend">
		<div class="banner_top text_center">
			<h1>Telef&oacute;nica</h1>
			<h1>Portal de autogesti&oacute;n</h1>
		</div>
		<div class="container">
		<form action="" method="post">
			<div class="box_cont text_center">
				<span class="icon_big"><i class="demo-icon icon-icon_agenda"></i></span>
				<h2>Estimado cliente</h2>
				<h3>
				<?php 
				    $dispatcher = $GLOBALS['dispatcher'];
				    $Controlador = $dispatcher->getControlador();
					if($Controlador->showConfirm()){
					    $activityID=$_COOKIE[Controlador::ACTIVITY_PARAM];
					    $activity=$dispatcher->getControlador()->findActivityData($activityID);
					    $dateStart = new DateTime($activity->date . ' ' . $activity->serviceWindowStart);
					    $dateEnd = new DateTime($activity->date . ' ' . $activity->serviceWindowEnd);
					    echo 'Tu cita de instalaci&oacute;n est&aacute; programada para el ' . $dateStart->format('jS F Y') . ', en la jornada ' . $activity->timeSlot . '(' . $dateStart->format('g:i A') . ' - ' . $dateEnd->format('g:i A') . ') Conf&iacute;rmanos tu disponibilidad para atender la visita del t&eacute;cnico.';
					    
					    echo '<div>';
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::CONFIRM_LABEL . '"';
					    echo 'class="button btn btn-lg btn-primary" >Confirmar</button>';
					    echo '</div>';
					}
					
				?>
				</h3>
			</div>

			<div class="box_cont text_center">
				<h3>
				<?php 
				    $dispatcher = $GLOBALS['dispatcher'];
				    $Controlador = $dispatcher->getControlador();
				    if($Controlador->showSchedule()){
					    echo 'Si lo deseas puedes modificar tu cita de instalaci&oacute;n para otro d&iacute;a o jornada, sujeto a disponibilidad actual de visitas';
					    
					    echo '<div>';
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::SCHEDULE_DATE_LABEL . '"';
					    echo 'class="button btn btn-lg btn-primary" >Modificar</button>';
					    echo '</div>';
					}
					
				?>
				</h3>
			</div>
			
			
			<div class="box_cont text_center">
				<h3>
				<?php 
				    $dispatcher = $GLOBALS['dispatcher'];
				    $Controlador = $dispatcher->getControlador();
				    if($Controlador->showCancel()){
					    echo 'Puedes cancelar tu cita s&iacute; as&iacute; lo deseas. Podr&aacute;s reprogramarla posteriormente comunic&aacute;ndote a la l&iacute;nea de atenci&oacute;n gratuita 01 80009 969090';
					    
					    echo '<div>';
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::CANCEL_LABEL . '"';
					    echo 'class="button btn btn-lg btn-primary" >Cancelar</button>';
					    echo '</div>';
					}
					
				?>
				</h3>
			</div>
			
			<div class="box_cont text_center">
				<h3>
				<?php 
				    $dispatcher = $GLOBALS['dispatcher'];
				    $Controlador = $dispatcher->getControlador();
				    if($Controlador->showTechnicanLocation()){
					    echo 'Puedes consultar la ubicaci&oacute;n del t&eacute;cnico aca';
					    
					    echo '<div>';
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::LOCATION_LABEL . '"';
					    echo 'class="button btn btn-lg btn-primary" >Ver t&eacute;cnico</button>';
					    echo '</div>';
					}
					
				?>
				</h3>
			</div>
			</form>
		</div>
	</div>
</body>
</html>