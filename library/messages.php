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

    <div class="content" style="min-height: 561px;">
        <div class="wrap">
            <section class="type1-cont">
            	<p>
                <?php echo $_REQUEST[Controlador::MESSAGE_PARAM]; ?>
                </p>
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

</html>