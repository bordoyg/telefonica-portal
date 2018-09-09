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
				echo $_REQUEST[Controlador::MESSAGE_PARAM];
				?>
				</h3>
				<?php 
				if(strcmp(Controlador::ERROR_ORDEN_INEXISTENTE, $_REQUEST[Controlador::MESSAGE_PARAM])!=0
				    && strcmp(Controlador::ERROR_ORDEN_NO_VIGENTE, $_REQUEST[Controlador::MESSAGE_PARAM])!=0){
				    echo '<div>';
				    echo '  <button type="submit" class="button btn btn-lg btn-primary" >Volver</button>';
				    echo '</div>';
				}
				?>
			</div>
		</form>
		</div>
	</div>
</body>
</html>