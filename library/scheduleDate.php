<!DOCTYPE html>
<html>

<?php require_once(APPPATH . 'widgets/custom/library/header.php'); ?>

<body>
	<img src="/euf/assets/others/etb/img/bg6r.jpg" class="rpv bgrpv"/>
    <header>
        <a href="http://www.etb.com.co"><img src="/euf/assets/others/etb/img/etblogo.png" /></a>
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


		
		function selectedDate(mouseEvent){
			next_prev_handler();

			var btnDay=null;
			if(mouseEvent.srcElement){
				btnDay=mouseEvent.srcElement;
			}else{
				btnDay=mouseEvent.target;
			}
			var day=$(btnDay).text();
			if(day.length==1){
				day="0" + day;
			}
			var month=parseInt($(btnDay).parent().attr("data-month"));
			month=month + 1;
			if(month.toString().length==1){
				month="0" + month;
			}
			var year=$(btnDay).parent().attr("data-year");
			var date= year+ "-" + month + "-" + day;
			$("#selectedDateText").text( formatDate(dateToArray(date),"ymd",true) );
			$("#selectedDateHidden").val(date);

			date = date.replace(/-/g,"");
			var datesFiltered = $('[id=' + date + ']');
		
			if( datesFiltered.length > 0 ){
				datesFiltered.each( (i, timeslot) => {
					var from = $(timeslot).attr("data-timeFrom");
					var to = $(timeslot).attr("data-timeTo");
					var name = $(timeslot).attr("data-name");

					var dFrom = new Date("1970-01-01T" + from);
					var dTo = new Date("1970-01-01T" + to);
				
					from = dateFormat(dFrom, "h:MM TT");
					to = dateFormat(dTo, "h:MM TT");
					if( i == 0 ){
						$("#selectTimeslot").empty();
					}
					$("#selectTimeslot").append(`<option value="${name}">De ${from} a ${to}</option>`);
				});
			} else {
				$("#selectTimeslot").empty();
			}
			$("#selectTimeslot").trigger('change');
		}

		function next_prev_handler(){
 			setTimeout(() => {
     			var next=$('.ui-corner-all a')[1];
     			var prev=$('.ui-corner-all a')[0];
    
     			next.addEventListener('click',next_prev_handler);
     			prev.addEventListener('click',next_prev_handler);

     			var btnsDay=$(".ui-state-default");
    			if(btnsDay.length>0){
    				btnsDay.each((i, btn) => {
    					$(btn).on('click', selectedDate);
    					$(btn).parent().on('click', function(){
        					var btn={target:$(this).children()};
        					selectedDate(btn);
        				});
    				});
    			}
    			paintAvaliableDays();
  			}, 0);
		}

		$(document).ready(() => {
			$("#calendar").datepicker({
				dateFormat: "yy-mm-dd",
				onChangeMonthYear: paintAvaliableDays
			});

			$("#selectedDateText").text( formatDate(dateToArray(today),"mdy",true) );
			$("#selectedDateHidden").val(today);
			next_prev_handler();



			$('#btnReagendar1').attr("disabled",true);
	   		$('#btnReagendar2').attr("disabled",true);
	   		
	       	$('#selectTimeslot').change(function(){ 
	       		if($('#selectTimeslot').val()!=null){
	       			$('#btnReagendar1').removeAttr("disabled");
	       			$('#btnReagendar2').removeAttr("disabled");
	            }
	    	});
		});

	</script>


    <div class="content">
        <div class="cont-left">
            <div class="cont-square" style="margin-top: 15%;">
                <form id="scheduleForm" action="" method="post">
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
                <h4>Selecciona la fecha<br>
				para reagendar tu cita:</h4>
                <div id='calendar'></div>
                <p class="tag"><span class="c1"></span> Disponible</p>
                <p class="tag"><span class="c2"></span> Seleccionado</p>
                <p class="tag"><span class="c3"></span> No Disponible</p>
                
				<select id ="selectTimeslot" name="<?php echo Controlador::TIMESLOT_PARAM ?>" class="selector">
                    
                </select>


                <p>Seleccionaste:</p>
                <h2 id="selectedDateText">31-Ago-2018</h2>
                <input  id="selectedDateHidden" type="hidden" name="<?php echo Controlador::SCHUEDULE_DATE_PARAM?>"/>
                <?php 
                        $buttonValue=Dispatcher::CANCEL_FROM_CALENDAR_LABEL;
                        if( $detectedAdctivityType != null && strcmp($detectedAdctivityType, Controlador::ASEGURAMIENTO)==0 ){
                            $buttonValue=Dispatcher::CANCEL_MOTIVO_LABEL;
                        }
                
                        $noMoreDates=$_REQUEST[Dispatcher::OPTION_PARAM ];
                        $noMoreDatesBool=strcmp($noMoreDates, Dispatcher::SCHEDULE_MORE_DATES)==0;
                        if($noMoreDatesBool){
                          ?>
                          <button id="btnReagendar1" type="submit" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::SCHEDULE_DATE_CONFIRM_LABEL ?>" class="bigbtn" >Reagendar Cita</button>
                          <?php 
                            if( $detectedAdctivityType != null && strcmp($detectedAdctivityType, Controlador::ASEGURAMIENTO)==0 ){
                          ?>
                              <button type="submit" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::CONFIRMAR_LABEL ?>" class="smallbtn sb1" >Confirmar<br>Cita</button>
                              <button type="submit" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo $buttonValue ?>" class="smallbtn" style="margin-rigth:20px;">Cancelar<br>Cita</button>
                          <?php 
                            }else{
                          ?>
                              <button type="submit" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::CONFIRMAR_LABEL ?>" class="bigbtn" >Confirmar<br>Cita Original</button>
                          <?php 
                            }
                          ?>
                          <?php 
                        }else{
                          ?>
                          <button id="btnReagendar2" type="submit" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::SCHEDULE_DATE_CONFIRM_LABEL ?>" class="smallbtn sb1" >Reagendar Cita</button>
                          <button type="submit" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::SCHEDULE_MORE_DATES ?>" class="smallbtn" >M&aacute;s Fechas</button>
                          <?php 
                        }
 				?>
                
                </form>
            </div>
        </div>
        <div class="cont-right">
            <img src="/euf/assets/others/etb/img/bg6.jpg" class="bg-right" />
        </div>
    </div>

    <footer>
        <p class="credits">2018 © ETB S.A. ESP. Todos los derechos reservados. Música Autorizada Por Acinpro.</p>
    </footer>
</body>
</html>