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
                <p>Por favor, selecciona un motivo para la cancelacion</p>
                
                <form action="" method="post">
                    <select name="<?php echo Dispatcher::MOTIVO_CANCELACION_PARAM?>" class="multicanc">
                        <option value="SREST">Servicio restablecido.</option>
                        <option value="NPAV">No puedo atender la visita.</option>
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