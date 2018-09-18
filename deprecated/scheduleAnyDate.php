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
			<ul class="steps_form">
				<li class="ok"><span><i class="demo-icon icon-icon_calendar"></i></span>
				<p>Agendamiento</p></li>
				<li class="ok"><span><i class="demo-icon icon-icon_ok"></i></span>
				<p>Confirmaci&oacute;n</p></li>
			</ul>
		</div>
		<div class="container">
			<div class="box_cont_2 col_green box_square">
				<h2 class="text_center">Ninguna fecha y jornada disponible funciona para mi </h2>
				
				<h4>Comunicate con el call center para re agendar tu cita:</h4>
				<p>Puedes cancelar tu cita si asi lo deseas. Podr&aacute;s reporgramarla posteriormente comunic&aacute;ndote a la la linea de atenci&oacute;n gratuita 01 80009 969090 </p>
			</div>
			<form action="" method="post">
			    <!-- modales -->
                <?php include 'cancelModal.php'?>
    			<div class="row">
    					<div class="col-xs-12 ">
    						<button type="button" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::CANCELAR_LABEL ?>"
    							class="button btn_general" onclick="showCancelModal();" >Cancelar cita</button>
    					</div>
    			</div>
			</form>
		</div>
	</div>
</body>
</html>