<!DOCTYPE html>
<html>
<head></head>
<?php require_once(APPPATH . 'widgets/custom/library/header.php'); ?>
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
                <?php echo $_REQUEST[Controlador::MESSAGE_PARAM]; ?>
                <?php 
				if(strcmp(Controlador::ERROR_ORDEN_INEXISTENTE, $_REQUEST[Controlador::MESSAGE_PARAM])!=0
				    && strcmp(Controlador::ERROR_ORDEN_NO_VIGENTE, $_REQUEST[Controlador::MESSAGE_PARAM])!=0){
				    
			        if(!isset($_REQUEST[Dispatcher::NO_VOLVER])){
			            echo '<form action="" method="post">';
			            
			            if(strcmp(Controlador::ERROR_REAGENDAR_MSJ, $_REQUEST[Controlador::MESSAGE_PARAM])!=0){
			                echo '    <button type="submit" class="smallbtn sl" >Finalizar</button>';
			                echo '    <button type="submit" class="smallbtn sr"  name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::SCHEDULE_DATE_LABEL . '" >Reintentar</button>';
			            }else{
			                echo '    <button type="submit" class="smallbtnfull" >Finalizar</button>';
			            }
			            
			            echo '</form>';
			        }
				}
				?>
            </section>
        </div>
    </div>

    <footer>
        <p class="credits">2019 © ETB S.A. ESP. Todos los derechos reservados</p>
    </footer>
</body>




















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