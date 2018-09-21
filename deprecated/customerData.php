
<?php 
$dispatcher = $GLOBALS['dispatcher'];
$activityID=$_COOKIE[Controlador::ACTIVITY_PARAM];
$activity=$dispatcher->getControlador()->findActivityData($activityID);
if(isset($activity)){
    $customerName=isset($activity->customerName)?$activity->customerName:'';
    
    $customerEmail=isset($activity->customerEmail)?$activity->customerEmail:'';
    
    $streetAddress=isset($activity->streetAddress)?$activity->streetAddress:'';

    $status=isset($activity->XA_CONFIRMACITA)?$activity->XA_CONFIRMACITA:'PENDIENTE';
    
    $stateProvince=isset($activity->stateProvince)?$activity->stateProvince:'';
    
    $city=isset($activity->city)?$activity->city:'';
    
    $customerNumber=isset($activity->customerNumber)?$activity->customerNumber:'';
    
    $customerPhone=isset($activity->customerPhone)?$activity->customerPhone:'';
    
    $customerCell=isset($activity->customerCell)?$activity->customerCell:'';
    
}

?>

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
<!-- 				<li class="ok"><span><i class="demo-icon icon-icon_agenda"></i></span> -->
<!-- 				<p>Validaci&oacute;n de datos</p></li> -->
<!-- 				<li><span><i class="demo-icon icon-icon_calendar"></i></span> -->
<!-- 				<p>Agendamiento</p></li> -->
<!-- 				<li><span><i class="demo-icon icon-icon_ok"></i></span> -->
<!-- 				<p>Confirmaci&oacute;n</p></li> -->
<!-- 			</ul> -->
<!-- 		</div> -->
		<div class="container">
			<h3 class="text_center">Datos personales</h3>
			<form action="" method="post" class="form_cliente">
				<div class="clear-box col_grey">
					<h2 class="top_form">Cliente</h2>
					<div class="col-xs-12 col-sm-6">
						<label>Nombre</label><input class="form-control" type="text" value="<?php  echo $customerName?>">
					</div>
					<div class="col-xs-12 col-sm-6">
						<label>Nombre Departamento</label><input
							class="form-control"
							type="text" value="<?php  echo $stateProvince?>">
					</div>
					<div class="col-xs-12 col-sm-6">
						<label>Nombre Localidad</label><input
							class="form-control"
							type="text" value="<?php  echo $city?>">
					</div>
					<div class="col-xs-12 col-sm-6">
						<label>N&uacute;mero de cliente</label><input
							class="form-control"
							type="text" value="<?php  echo $customerNumber?>">
					</div>
					
					<div class="col-xs-12 col-sm-6">
						<label>Email</label><input
							class="form-control" type="text" value="<?php  echo $customerEmail?>">
					</div>
					<div class="col-xs-12 col-sm-6">
						<label>Direcci&oacute;n Instalacion</label><input 
							class="form-control" type="text"
							value="<?php  echo $streetAddress?>">
					</div>

					<div class="col-xs-12 col-sm-6">
						<label>Tel. Celular</label><input
							class="form-control"
							type="text" value="<?php  echo $customerCell?>">
					</div>
					<div class="col-xs-12 col-sm-6">
						<label>Tel Fijo</label><input
							class="form-control" type="text" value="<?php  echo $customerPhone?>">
					</div>
					<div class="col-xs-12 col-sm-6">
						<label>Estado</label><input class="form-control"
							type="text" value="<?php  echo $status?>">
					</div>
				</div>
				<?php include Controlador::MESSAGES_MODAL_URL; ?>
				<!-- modales -->
                <?php include 'cancelModal.php'?>
                <?php include 'confirmModal.php'?>
				<!-- botones -->
				<div class="row">
					<?php 
					$Controlador = $GLOBALS['dispatcher']->getControlador();
					if($Controlador->showConfirm()){
					    echo '<div class="col-xs-12 col-sm-6">';
					    echo '<button type="button" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::CONFIRM_LABEL . '"';
					    echo 'class="button btn btn-lg btn-primary" onclick="showConfirmModal();">Confirmar</button>';
					    echo '</div>';
					}
					if($Controlador->showCancel()){
					    echo '<div class="col-xs-12 col-sm-6">';
					    echo '<button type="button" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::CANCELAR_LABEL . '"';
					    echo 'class="button btn btn-lg btn-primary" onclick="showCancelModal();">Cancelar</button>';
					    echo '</div>';
					}
					if($Controlador->showSchedule()){
					    echo '<div class="col-xs-12 col-sm-6">';
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::SCHEDULE_DATE_LABEL . '"';
					    echo 'class="button btn btn-lg btn-primary" onclick="onSubmitButton(this);">Reagendar</button>';
					    echo '</div>';
					}
					if($Controlador->showTechnicanLocation()){
					    echo '<div class="col-xs-12 col-sm-6">';
					    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::UBICACION_LABEL . '"';
					    echo 'class="button btn btn-lg btn-primary" onclick="onSubmitButton(this);">Donde esta mi t&eacute;cnico</button>';
					    echo '</div>';
					}
					?>
				</div>
				
			</form>
		</div>
	</div>

</body>
</html>