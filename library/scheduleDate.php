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

		var datesToPaint = undefined;
		var calendarDates = undefined;
		var today=new Date().toLocaleDateString();

		function hasAttr(objRef, attrName) {
			var attr = $(objRef).attr(attrName);
			return attr !== undefined || attr !== false;
		}
		
		function dateToArray(date){
			var symbol = undefined;
			for (let i = 0; i < date.length; i++) {
				const char = date[i];
				if ( isNaN(char) ){
					symbol = char;
					break
				} else {
					continue;
				}
			}
			return date.split(symbol);
		}

		function formatDate(dateArray, arrayOrder, monthNames){
			var ret = [];
			ret.push(
				dateArray[ arrayOrder.indexOf("d") ],
				"-",
				monthNames? $.datepicker._defaults.monthNamesShort[ parseInt(dateArray[arrayOrder.indexOf("m")])-1 ]:dateArray[ arrayOrder.indexOf("m") ],
				"-",
				dateArray[ arrayOrder.indexOf("y") ]
			)

			return ret.join("");
		}

		function addDays(date, days) {
			var result = new Date(date);
			result.setDate(result.getDate() + days);
			return result;
		}

		function paintAvaliableDays(){
			
			datesToPaint = $("form").children().filter("input").filter( (index) => {
                return hasAttr(this, "data-day") && hasAttr(this, "data-month") && hasAttr(this, "data-year");
			});
			
			var days_to_add=parseInt($('#days_to_add')[0].value);
			var start_date= new Date().toLocaleDateString().split('/');
			var end_date= new Date(addDays(start_date,days_to_add)).toLocaleDateString().split('/');

			var start_day=start_date[1];
			var start_month=start_date[0];
			var start_year=start_date[2];

			var end_day=end_date[1];
			var end_month=end_date[0];
			var end_year=end_date[2];

            calendarDates = $(".ui-datepicker-calendar tbody")
                                .children("tr")
                                .find("td")
                                .filter("[data-handler*='selectDay']");
			calendarDates.each( (j, calendarDate) => {
					var calendarYear = parseInt( $(calendarDate).attr("data-year") );
                    var calendarMonth = parseInt( $(calendarDate).attr("data-month") ) + 1;
					var calendarDay = parseInt( $(calendarDate).children("a").first().text() );
					
					if( (calendarYear >= parseInt(start_year) && calendarYear <= parseInt(end_year)) && (calendarMonth >= parseInt(start_month) && calendarMonth <= parseInt(end_month)) && (calendarDay >= parseInt(start_day) && calendarDay <= parseInt(end_day))){
						$(calendarDate).css("background", "#455e75");

							
					}
			});

            datesToPaint.each( (i, dateToPaint) => {

                var inputYear = parseInt( $(dateToPaint).attr("data-year") );
                var inputMonth = parseInt( $(dateToPaint).attr("data-month") );
                var inputDay = parseInt( $(dateToPaint).attr("data-day") );
				var hasTimeSlot=  hasAttr(this,"data-timefrom");

				

                calendarDates.each( (j, calendarDate) => {
                
                    var calendarYear = parseInt( $(calendarDate).attr("data-year") );
                    var calendarMonth = parseInt( $(calendarDate).attr("data-month") ) + 1;
					var calendarDay = parseInt( $(calendarDate).children("a").first().text() );

					

					if( (inputYear === calendarYear) && (inputMonth === calendarMonth) && (inputDay === calendarDay) ){
						
						$(calendarDate).css("background", "#00c389");
						
					}
				
						
                });  
            });
		}


		
		function selectedDate(date, calendar){
			
			setTimeout(() => {
				$("#calendar").datepicker({
					dateFormat: "yy-mm-dd",
					onChangeMonthYear: paintAvaliableDays,
					onSelect: selectedDate,
			});
				paintAvaliableDays();
				var next=$('.ui-corner-all a')[0];
        		var prev=$('.ui-corner-all a')[1];

				next.addEventListener('click',next_prev_handler);
				prev.addEventListener('click',next_prev_handler);

				$("#selectedDate").text( formatDate(dateToArray(date),"ymd",true) );
				$("#selectedDateHidden").val(date);

				date = date.replace(/-/g,"");
				var datesFiltered = datesToPaint.filter("[id*='" + date + "']");
				
				if( datesFiltered.length > 0 ){
					datesFiltered.each( (i, timeslot) => {
						var from = $(timeslot).attr("data-timeFrom");
						var to = $(timeslot).attr("data-timeTo");
						var name = $(timeslot).attr("data-name");
						from = from.substring(0, from.lastIndexOf(":"));
						to = to.substring(0, to.lastIndexOf(":"));
						if( i == 0 )
							$(".smallbtnselect").empty();
						$(".smallbtnselect").append(`<option value="${name}">De ${from} a ${to}</option>`);
					});
				} else {
					$(".smallbtnselect").empty();
				}

			}, 0);

		}

		function next_prev_handler(){
			setTimeout(() => {
				$("#calendar").datepicker({
				dateFormat: "yy-mm-dd",
				onChangeMonthYear: paintAvaliableDays,
				onSelect: selectedDate,
			});
			var next=$('.ui-corner-all a')[0];
			var prev=$('.ui-corner-all a')[1];

				next.addEventListener('click',next_prev_handler);
				prev.addEventListener('click',next_prev_handler);
				
			paintAvaliableDays();
			}, 0);
		}

		$(document).ready(() => {
			$("#calendar").datepicker({
				dateFormat: "yy-mm-dd",
				onChangeMonthYear: paintAvaliableDays,
				onSelect: selectedDate,
			});
			paintAvaliableDays();
			$("#selectedDate").text( formatDate(dateToArray(today), "mdy", true) );
			var next=$('.ui-corner-all a')[0];
			var prev=$('.ui-corner-all a')[1];

			next.addEventListener('click', next_prev_handler);
			prev.addEventListener('click', next_prev_handler);
		});

	</script>

    <div class="content">
        <div class="wrap">
            <section class="type1-cont">
                <form action="" method="post">
            	<?php 
            	$controlador = $GLOBALS['Controlador'];
            	$activityID=$controlador->getActivityIdFromContext();
            	$activity=$controlador->findActivityData($activityID);
            	$detectedAdctivityType = $controlador->isAprovisionamientoAseguramientoRecupero($activity);
            	$action=$_REQUEST[Dispatcher::OPTION_PARAM];
            	$cantDias=$GLOBALS['config']['days-first-query-capacity-' . $detectedAdctivityType];
            	
            	if (strcmp(Dispatcher::SCHEDULE_MORE_DATES, $action) === 0){
            	    $cantDias=$GLOBALS['config']['days-second-query-capacity-' . $detectedAdctivityType];
            	    echo '<input type="hidden" name="' . Dispatcher::SCHEDULE_NO_MORE_DATES . '" value"true"/>';
				}
				
				echo '<input type="hidden" id="days_to_add" value="'.$cantDias.'" value"true"/>';

            	
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
				
				<div>
					<p>Franja Horaria</p>
					<select name="<?php echo Controlador::TIMESLOT_PARAM ?>" class="smallbtn smallbtnselect">
						<option></option>
					</select>
				</div>
                
                <p>Seleccionaste:</p>
                <h2 id="selectedDate">31-Ago-2018</h2>
                <input  id="selectedDateHidden" type="hidden" name="<?php echo Controlador::SCHUEDULE_DATE_PARAM?>"/>
                <h3>Recuerda que el horario es de 9 a 18hr y tiene que haber alguien en el domicilio.</h3>
                <?php 
                        $buttonValue=Dispatcher::CANCEL_FROM_CALENDAR_LABEL;
                        if( $detectedAdctivityType != null && strcmp($detectedAdctivityType, Controlador::ASEGURAMIENTO) ){
                            $buttonValue=Dispatcher::CANCEL_FROM_CALENDAR_ASEGURAMIENTO_LABEL;
                        }
                
                        $noMoreDates=$_REQUEST[Dispatcher::OPTION_PARAM ];
                        $noMoreDatesBool=strcmp($noMoreDates, Dispatcher::SCHEDULE_MORE_DATES)==0;
                        if($noMoreDatesBool){
                          ?>
                          <button type="submit" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::SCHEDULE_DATE_CONFIRM_LABEL ?>" class="bigbtn" >Reagendar Cita</button>
                          <button type="submit" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::CONFIRMAR_LABEL ?>" class="smallbtn sl" >Confirmar<br>Cita Original</button>
                          <button type="submit" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo $buttonValue ?>" class="smallbtn sr" style="margin-rigth:20px;">Cancelar<br>Cita Original</button>
                          <?php 
                        }else{
                          ?>
                          <button type="submit" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::SCHEDULE_DATE_CONFIRM_LABEL ?>" class="smallbtn sl actl" >Reagendar Cita</button>
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