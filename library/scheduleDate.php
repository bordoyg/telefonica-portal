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
	<script>

		function hasAttr(objRef, attrName) {
			var attr = $(objRef).attr(attrName);
			return attr !== undefined || attr !== false;
		}

		function paintAvaliableDays(){
			var datesToPaint = $("form").children().filter("input").filter( (index) => {
                return hasAttr(this, "data-timefrom") && hasAttr(this, "data-timeto");
            });

            var calendarDates = $(".ui-datepicker-calendar tbody")
                                .children("tr")
                                .find("td")
                                .filter("[data-handler*='selectDay']");
			
            datesToPaint.each( (i, dateToPaint) => {

                var inputYear = parseInt( $(dateToPaint).attr("data-day") );
                var inputMonth = parseInt( $(dateToPaint).attr("data-month") );
                var inputDay = parseInt( $(dateToPaint).attr("data-year") );
			
                calendarDates.each( (j, calendarDate) => {
                
                    var calendarYear = parseInt( $(calendarDate).attr("data-year") );
                    var calendarMonth = parseInt( $(calendarDate).attr("data-month") ) + 1;
                    var calendarDay = parseInt( $(calendarDate).children("a").first().text() );

					if( (inputYear === calendarYear) && (inputMonth === calendarMonth) && (inputDay === calendarDay) )
						$(calendarDate).css("background", "#00c389");
                });  
            });
		}
		
		$(document).ready(() => {
			$("#calendar").datepicker();
			$(".ui-corner-all").click(paintAvaliableDays);
			paintAvaliableDays();
		});
	</script>

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
            	                    echo '<input type="hidden" id="' . $availability[$j]->date->format('Ymd') . '"';
            	                    echo ' data-day="' . $availability[$j]->date->format('d') . '"';
            	                    echo ' data-month="' . $availability[$j]->date->format('m') . '"';
            	                    echo ' data-year="' . $availability[$j]->date->format('Y') . '"';
            	                    echo ' data-name="' . $availability[$j]->timeSlots[$k]->name . '"';
            	                    echo ' data-label="' . $availability[$j]->timeSlots[$k]->label . '"';
            	                    echo ' data-timeFrom="' . $availability[$j]->timeSlots[$k]->timeFrom . '"';
            	                    echo ' data-timeTo="' . $availability[$j]->timeSlots[$k]->timeTo . '"/>';
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
 					      <button type="submit" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::SCHEDULE_DATE_LABEL ?>" class="bigbtn" >Reagendar Cita</button>
 					      <button type="submit" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::CONFIRMAR_LABEL ?>" class="smallbtn sl" >Confirmar<br>Cita Original</button>
 					      <button type="submit" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::CANCELAR_LABEL ?>" class="smallbtn sr" >Cancelar<br>Cita Original</button>
 					      <?php 
 					  }else{
 					      ?>
 					      <button type="submit" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::SCHEDULE_DATE_LABEL ?>" class="smallbtn sl actl" >Reagendar Cita</button>
 					      <button type="submit" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::SCHEDULE_MORE_DATES ?>" class="smallbtn sr" >M&aacute;s Fechas</button>
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