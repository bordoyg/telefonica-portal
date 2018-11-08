<!DOCTYPE html>
<html>
<head></head>
<?php require_once(APPPATH . 'widgets/custom/library/header.php'); ?>
<body>
	<div id="appointment-confirmation">
		<div class="container">
			<div class="row">
				<div class="col-xs-offset-0 col-sm-offset-2 col-md-offset-4 col-lg-offset-4 col-xs-12 col-sm-8 col-md-4 col-lg-4 text-center">
					<form action="" method="post">
						<div class="row header-image">
                            <img class="img " src="/euf/assets/others/telefonica/images/logo-movistar.png">
                        </div>
                        
                        <?php echo $_REQUEST[Controlador::MESSAGE_PARAM]; ?>
                        
                        <?php 
							if(strcmp(Controlador::ERROR_ORDEN_INEXISTENTE, $_REQUEST[Controlador::MESSAGE_PARAM])!=0
							    && strcmp(Controlador::ERROR_ORDEN_NO_VIGENTE, $_REQUEST[Controlador::MESSAGE_PARAM])!=0) {
							    
						        if(!isset($_REQUEST[Dispatcher::NO_VOLVER])) { ?>
			                        
			                        <div class="row appointment-thanks-msg">
			                            <p>
			                                <span>¡Muchas gracias!</span>
			                            </p>
								        <div class="row action-back">
				                            <div class="col-xs-offset-1 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 col-xs-10 col-sm-6 col-md-6 col-lg-6">
				                                <button type="button" onclick="window.location.href=window.location.href;" 
				                                        class="btn btn-lg btn-block btn-primary">Volver</button>
				                            </div>
			                        	</div>
                        			</div>
                        <?php 
							        }
								}
							?>
                        <div class="row separator">
                            <img id="u7_img" class="img " src="/euf/assets/others/telefonica/images/separator.png"/>
                        </div>
                        <div class="row footer-image">
                            <img id="u6_img" class="img " src="/euf/assets/others/telefonica/images/footer-movistar.png"/>
                        </div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>