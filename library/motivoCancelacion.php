<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>ETB</title>
    
    <link rel="shortcut icon" href="img1/favicon.ico" />

    <link href='css/style.css' rel='stylesheet' type='text/css' />
    <link href='css/slick.css' rel='stylesheet' type='text/css' />

    <script src="js/jquery-3.2.0.min.js" type="text/javascript"></script>
    <script src="js/lib/jquery.svgmagic.js" type="text/javascript"></script>
    <script src="js/slick.js" type="text/javascript"></script>

    <script src="js/site-scripts.js" type="text/javascript"></script>
</head>
<?php require_once(APPPATH . 'widgets/custom/library/header.php'); ?>
<body>
<header>
        <div class="menu-head">
            <div class="logo-head">
                <a href="http://etb.com">
                    <img alt="ETB" src="img/logoetb2.png" /></a>
            </div>
        </div>
    </header>

    <div class="content">
        <div class="wrap">
            <section class="type1-cont">
                <h1>Motivo de Cancelación</h1>
                <p>Tu cita programada para el</p>
                <h2>26-Ago-2018</h2> 
                <p>con el fin de Instalación/reparación ha sido CANCELADA correctamente.</p>
                <form action="" method="post">
                <select class="multicanc">
                    <option>...</option>
                    <option>Servicio restablecido.</option>
                    <option>No puedo atender la visita.</option>
                </select>
                <?php          
                    echo '<button type="submit" onclick="return validateSubmit();" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::CANCELAR_LABEL . '"';
                    echo 'class="bigbtn">Cancelarla</button>';
                    
                ?>
                </form>
                
            </section>
        </div>
    </div>

    <footer>
        <p class="credits">2018 © ETB S.A. ESP. Todos los derechos reservados. Música Autorizada Por Acinpro.</p>
    </footer>
</body>
<script>
    console.log('validation script');
    function validateSubmit(){
        if($('.multicanc')[0].selectedIndex==0){
        console.log('false');
        event.preventDefault();
        return false;
        }
        else{
            console.log('true');
            return true;
        }
    }
</script>
</html>