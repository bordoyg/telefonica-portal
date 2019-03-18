<!DOCTYPE html>
<html>
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
            	<p>
                <?php echo $_REQUEST[Controlador::MESSAGE_PARAM]; ?>
                </p>
                <?php 
				if(strcmp(Controlador::ERROR_ORDEN_INEXISTENTE, $_REQUEST[Controlador::MESSAGE_PARAM])!=0
				    && strcmp(Controlador::ERROR_ORDEN_NO_VIGENTE, $_REQUEST[Controlador::MESSAGE_PARAM])!=0){
				    
			        if(!isset($_REQUEST[Dispatcher::NO_VOLVER])){
			            echo '<form action="" method="post">';
			            
			            if(strcmp(Controlador::ERROR_REAGENDAR_MSJ, $_REQUEST[Controlador::MESSAGE_PARAM])!=0
			                && strcmp(Controlador::ERROR_GENERIC_MSJ, $_REQUEST[Controlador::MESSAGE_PARAM])!=0
			                && strcmp(Controlador::ERROR_ORDEN_INEXISTENTE, $_REQUEST[Controlador::MESSAGE_PARAM])!=0
			                && strcmp(Controlador::ERROR_ORDEN_NO_VIGENTE, $_REQUEST[Controlador::MESSAGE_PARAM])!=0){
			                
			                echo '<a class="smallbtnfull" href="https://etb.com/">Finalizar</a>';
			            }else{
			                echo '<button type="submit" class="smallbtn sl" >Reintentar</button>';
			                echo '<a class="smallbtn sl" href="https://etb.com/">Finalizar</a>';
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