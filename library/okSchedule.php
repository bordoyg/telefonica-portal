<!DOCTYPE html>
<html>
<?php require_once(APPPATH . 'widgets/custom/library/header.php'); 
require_once(APPPATH . 'widgets/custom/library/header.php'); 
require_once(APPPATH . 'widgets/custom/library/controlador.php'); 

$dispatcher = $GLOBALS['dispatcher'];
$Controlador = $dispatcher->getControlador();
?>
<body>
    <img src="/euf/assets/others/etb/img/bg1r.jpg" class="rpv bgrpv"/>
    <header>
        <a href="http://www.etb.com.co"><img src="/euf/assets/others/etb/img/etblogo.png"/></a>
    </header>
    <div class="content">
        <div class="cont-left">
            <h1>Cita reagendada</h1>
            <p>La nueva fecha para tu cita es el:</p>
            <?php echo $_REQUEST[Controlador::MESSAGE_PARAM]; ?>

            <p>Recuerda que debe haber alguien disponible<br> para atender la visita</p>
            <h3>¡MUCHAS GRACIAS!</h3>    
                
            <a href="https://etb.com/" class="bigbtn2">
                Finalizar
            </a>    
            
            <img src="/euf/assets/others/etb/img/logofibra.png" class="logofibra"/>
        </div>
        
        <div class="cont-right">
            <img src="/euf/assets/others/etb/img/bg1.jpg" class="bg-right"/>
        </div>
    </div>
    <footer>
        <p>2019 © ETB S.A. ESP. Todos los derechos reservados.</p>
    </footer>
</body>

</html>
