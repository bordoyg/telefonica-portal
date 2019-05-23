<!DOCTYPE html>
<html>
<?php require_once(APPPATH . 'widgets/custom/library/header.php'); ?>
<body>
    <img src="/euf/assets/others/etb/img/bg4r.jpg" class="rpv bgrpv"/>
    <header>
        <a href="http://www.etb.com.co"><img src="/euf/assets/others/etb/img/etblogo.png"/></a>
    </header>
    <div class="content">
        <div class="cont-left">
            <h1>Cita cancelada</h1>
            <p>Tu cita programada para el:</p>
            
            <?php echo $_REQUEST[Controlador::MESSAGE_PARAM]; ?>

            <h3>¡MUCHAS GRACIAS!</h3>    
                
            <a href="https://etb.com/" class="bigbtn2">
                Finalizar
            </a>    
            
            <img src="/euf/assets/others/etb/img/logofibra.png" class="logofibra"/>
        </div>
        
        <div class="cont-right">
            <img src="/euf/assets/others/etb/img/bg4.jpg" class="bg-right"/>
        </div>
    </div>
    <footer>
        <p>2019 © ETB S.A. ESP. Todos los derechos reservados.</p>
    </footer>
</body>

</html>
