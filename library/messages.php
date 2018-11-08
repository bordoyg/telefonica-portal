<!DOCTYPE html>
<html>
<head></head>
<?php require_once(APPPATH . 'widgets/custom/library/header.php'); ?>
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
				<h2>Estimado cliente</h2>
				<h3>
				<?php 
				echo $_REQUEST[Controlador::MESSAGE_PARAM];
				?>
				</h3>
				<?php 
				if(strcmp(Controlador::ERROR_ORDEN_INEXISTENTE, $_REQUEST[Controlador::MESSAGE_PARAM])!=0
				    && strcmp(Controlador::ERROR_ORDEN_NO_VIGENTE, $_REQUEST[Controlador::MESSAGE_PARAM])!=0){
				    
			        if(!isset($_REQUEST[Dispatcher::NO_VOLVER])){
			            echo '<form action="" method="post">';
			            echo '    <div><button type="submit" class="button btn btn-lg btn-primary" >Volver</button></div>';
			            echo '</form>';
			        }
				}
				?>
			</div>
		</form>
		</div>
	</div>
</body>
</html>