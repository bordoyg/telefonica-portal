<!DOCTYPE html>
<html>
<?php include 'header.php'?>
<body>
	<div id="cont_agend">
		<div class="banner_top text_center">
			<h1>Telef&oacute;nica</h1>
			<h1>Portal de autogesti&oacute;n</h1>
		</div>
<!-- 		<div class="container"> -->
<!-- 			<ul class="steps_form"> -->
<!-- 				<li class="ok"><span><i class="demo-icon icon-icon_ok"></i></span> -->
<!-- 				<p>Confirmaci&oacute;n</p></li> -->
<!-- 			</ul> -->
<!-- 		</div> -->
		<div class="container">
			<div class="box_cont_2 col_green box_square">
				<span class="icon_big text_center"><i class="demo-icon icon-icon_ok"></i></span>
				<h2 class="text_center">Confirmaste tu cita, te estaremos visitando el dia:</h2>
				<h3 class="text_center">
					<?php 
					$dispatcher = $GLOBALS['dispatcher'];
					$activityID=$_COOKIE[Controlador::ACTIVITY_PARAM];
					$activity=$dispatcher->getControlador()->findActivityData($activityID);
					$dateStart = new DateTime($activity->date . ' ' . $activity->serviceWindowStart);
					$dateEnd = new DateTime($activity->date . ' ' . $activity->serviceWindowEnd);
					echo $dateStart->format('jS F Y') . '<br>Jornada: ' . $activity->timeSlot . '(' . $dateStart->format('g:i A') . ' - ' . $dateEnd->format('g:i A') . ')';
					?>
				</h3>
				<h4>No olvides:</h4>
				<p>* Contar con los permisos / autorizaciones para el ingreso del
					t&eacute;cnico al predio.</p>
				<p>* Contar con los equipos necesarios para que tu servicio quede
					aprobado y garantizado.</p>
				<p>* La persona que reciba el servicio debe ser mayor de edad.</p>
			</div>
<!-- 			<div class="box_cont_2 text_center box_square"> -->
<!-- 				<h2> -->
<!-- 					Tiene otra orden pendiente para agendar. Pulsa <strong>"Proceder"</strong> -->
<!-- 					para agendarla ahora. -->
<!-- 				</h2> -->
<!-- 				<a href="../mmIv1SISasIvF6sS/" class="button btn_general" style="width: inherit;" onclick="onSubmitButton(this);">Proceder</a>
<!-- 			</div> -->
		</div>
	</div>
</body>
</html>