<!DOCTYPE html>
<html>

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
                <form action="" method="post">
            	<?php 
            	$controlador = $GLOBALS['Controlador'];
            	$action=$_REQUEST[Dispatcher::OPTION_PARAM];
            	$cantDias=$GLOBALS['config']['days-first-query-capacity'];
            	if (strcmp(Dispatcher::SCHEDULE_MORE_DATES, $action) === 0){
            	    $cantDias=$GLOBALS['config']['days-second-query-capacity'];
            	    echo '<input type="hidden" name="' . Dispatcher::SCHEDULE_NO_MORE_DATES . '" value"true"/>';
            	}
            	
            	try{
            	    $availability=$controlador->findAvailabilitySOAP($cantDias);
            	    if(isset($availability)){
            	        for($j=0; $j<count($availability); $j++){
            	            if(isset($availability[$j]->timeSlots)){
            	                for($k=0; $k<count($availability[$j]->timeSlots); $k++){
            	                    echo '<input type="hidden" id="' . $availability[$j]->dayOfMonth->format('Ymd') . '"';
            	                    echo 'data-name="' . $availability->timeSlots[$k]->name . '"';
            	                    echo 'data-label="' . $availability->timeSlots[$k]->label . '"';
            	                    echo 'data-timeFrom="' . $availability->timeSlots[$k]->timeFrom . '"';
            	                    echo 'data-timeTo="' . $availability->timeSlots[$k]->timeTo . '"/>';
            	                }
            	            }
            	        }
            	    }
            	    
            	    
            	}catch(Exception $e){
            	    Utils::logDebug('Hubo un error al buscar los dias habilitados', $e);
            	}
            	
            	?>
                <h1>Reagendamiento</h1>
                <p>Selecciona la fecha para agendar tu cita:</p>
                <div id='calendar'></div>
                <p class="tag"><span class="c1"></span> Disponible</p>
                <p class="tag"><span class="c2"></span> Seleccionado</p>
                <p class="tag"><span class="c3"></span> No Disponible</p>
				
				<div class="mostrar">
					<p>Franja Horaria</p>				
					<select class="smallbtn smallbtnselect">
						<option>A.M.</option>
						<option>P.M.</option>
					</select>
				</div>
                
                <p>Seleccionaste:</p>
                <h2 id="selectedDate">31-Ago-2018</h2>
                <h3>Recuerda que el horario es de 9 a 18hr y tiene que haber alguien en el domicilio.</h3>
                <?php 
				      $noMoreDates=$_REQUEST[Dispatcher::OPTION_PARAM ];
 					  $noMoreDatesBool=strcmp($noMoreDates, Dispatcher::SCHEDULE_MORE_DATES)==0;
 					  if($noMoreDatesBool){
 					      ?>
 					      	<a class="bigbtn" href="#">Reagendar Cita</a>
                            <a class="smallbtn sl" href="#">Confirmar<br>Cita Original</a>
                            <a class="smallbtn sr" href="#">Cancelar<br>Cita Original</a>
 					      <?php 
 					  }else{
 					      ?>
 					      	<a class="smallbtn sl actl" href="#">Reagendar Cita</a>
                			<a class="smallbtn sr" href="#">M&aacute;s Fechas</a>
 					      <?php 
 					  }
 				?>
                
                </form>
            </section>
        </div>
    </div>

    <footer>
        <p class="credits">2018 © ETB S.A. ESP. Todos los derechos reservados. Música Autorizada Por Acinpro.</p>
    </footer>
   
</body>
</html>