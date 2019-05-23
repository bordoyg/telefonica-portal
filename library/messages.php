<!DOCTYPE html>
<html>
<?php require_once(APPPATH . 'widgets/custom/library/header.php'); ?>
<body>
    <img src="/euf/assets/others/etb/img/bg2r.jpg" class="rpv bgrpv"/>
    <header>
        <a href="http://www.etb.com.co"><img src="/euf/assets/others/etb/img/etblogo.png" /></a>
    </header>
    <div class="content">
        <div class="cont-left">

            <div class="cont-square">
                <h2><?php echo $_REQUEST[Controlador::MESSAGE_PARAM]; ?></h2>

                <?php 
				if(strcmp(Controlador::ERROR_ORDEN_INEXISTENTE, $_REQUEST[Controlador::MESSAGE_PARAM])!=0
				    && strcmp(Controlador::ERROR_ORDEN_NO_VIGENTE, $_REQUEST[Controlador::MESSAGE_PARAM])!=0){
				    
			        if(!isset($_REQUEST[Dispatcher::NO_VOLVER])){
			            echo '<form action="" method="post">';
			            
			            if(strcmp(Controlador::ERROR_REAGENDAR_MSJ, $_REQUEST[Controlador::MESSAGE_PARAM])!=0
			                && strcmp(Controlador::ERROR_GENERIC_MSJ, $_REQUEST[Controlador::MESSAGE_PARAM])!=0
			                && strcmp(Controlador::ERROR_ORDEN_INEXISTENTE, $_REQUEST[Controlador::MESSAGE_PARAM])!=0
			                && strcmp(Controlador::ERROR_ORDEN_NO_VIGENTE, $_REQUEST[Controlador::MESSAGE_PARAM])!=0){
			                
			                echo '<a class="bigbtn2" href="https://etb.com/">Finalizar</a>';
			            }else{
			                echo '<button type="submit" class="smallbtn sb1" >Reintentar</button>';
			                echo '<a class="smallbtn" href="https://etb.com/">Finalizar</a>';
			            }
			            
			            echo '</form>';
			        }
				}
				?>
            </div>

            <img src="/euf/assets/others/etb/img/logofibra.png" class="logofibra" />
        </div>

        <div class="cont-right">
            <img src="/euf/assets/others/etb/img/bg2.jpg" class="bg-right" />
        </div>
    </div>
    <footer>
        <p>2019 © ETB S.A. ESP. Todos los derechos reservados.</p>
    </footer>
</body>


</html>