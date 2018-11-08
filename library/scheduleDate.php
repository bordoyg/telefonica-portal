
<!DOCTYPE html>
<html>
<head></head>
<?php require_once(APPPATH . 'widgets/custom/library/header.php'); ?>
<body>
	<div id="cont_agend">
		<div class="banner_top text_center">
			<h1>EBT</h1>
			<h1>Portal de autogesti&oacute;n</h1>
		</div>
		<div class="container">
			<ul class="steps_form">
				<li class="ok"><span><i class="demo-icon icon-icon_calendar"></i></span>
				<p>Agendamiento</p></li>
				<li><span><i class="demo-icon icon-icon_ok"></i></span>
				<p>Confirmaci&oacute;n</p></li>
			</ul>
		</div>
		<h2 class="text_center">Elija la fecha y jornada de tu cita.</h2>

		<div class="container">
			<form action="" method="post">
				<div class="wrap_calendar">
					<div class="calendar">
						<header>
							<h2 class="month"><?php echo date("F");?></h2>
						</header>
						<table>
							<thead>
								<td>Sun</td>
								<td>Mon</td>
								<td>Tue</td>
								<td>Wed</td>
								<td>Thu</td>
								<td>Fri</td>
								<td>Sat</td>
							</thead>
							<tbody>
							<?php 
								try{
								    $Controlador = $GLOBALS['Controlador'];
								    $action=$_REQUEST[Dispatcher::OPTION_PARAM];
								    $cantDias=$GLOBALS['config']['days-first-query-capacity'];
								    $cantSemanas=5;
								    if (strcmp(Dispatcher::SCHEDULE_MORE_DATES, $action) === 0){
								        $cantDias=$GLOBALS['config']['days-second-query-capacity'];
								        echo '<input type="hidden" name="' . Dispatcher::SCHEDULE_NO_MORE_DATES . '" value"true"/>';
								    }
								    
								    $endConsultedDay=new DateTime("now");
								    $endConsultedDay=$endConsultedDay->sub(new DateInterval("P1D"));
								    $endConsultedDay=$endConsultedDay->add(new DateInterval("P" . $cantDias . "D"));
								    $month = date('m');
								    $year = date('Y');
								    $day = date("d", mktime(0,0,0, $month+1, 0, $year));
								    $lastDayOfMonth=new DateTime("now");
								    $lastDayOfMonth->setDate($year, $month, $day);
								    if($endConsultedDay->format("Y-m-d") > $lastDayOfMonth->format("Y-m-d")){
								        $cantSemanas=6;
								    }
								    
								    $calendar=$Controlador->createCalendar($cantDias);
								    
								    $currentStrDate=date('Y-m-d');
								    for($i=0;$i<$cantSemanas + 1;$i++){
								        if($i==$cantSemanas){
								            echo '<tr style="display:none;">';
								        }else{
								            echo '<tr>';
								        }
								        
								        for($j=0;$j<7;$j++){
								            $cssDisponible="";
								            $jsScript ="";
								            if($calendar[$i][$j]->dayOfMonth->format("Y-m-d")>=$currentStrDate
								                && $calendar[$i][$j]->dayOfMonth->format("Y-m-d")<=$endConsultedDay->format("Y-m-d")){
								                    
								                    if(isset($calendar[$i][$j]->timeSlots)){
								                        $cssDisponible=" calendar-disponible ";
								                        $jsScript='onclick="dateSelected(event);"';
								                    }else{
								                        if(strcmp($calendar[$i][$j]->dayOfMonth->format("Y-m-d"), $currentStrDate)>0){
								                            $cssDisponible=" calendar-no-disponible ";
								                        }
								                    }
								            }
								            $cssToday="";
								            if($calendar[$i][$j]->dayOfMonth->format("Y-m-d")==$currentStrDate){
								                $cssToday=" today";
								            }
								            echo '<td><a href="javascript:;" class="day' . $cssDisponible . $cssToday . '" ' . $jsScript . ' data-day="' . $calendar[$i][$j]->dayOfMonth->format('Ymd') . '">' . $calendar[$i][$j]->dayOfMonth->format('d') . '</a></td>';
								        }
								        echo '</tr>';
								    }
								    
								} catch (Exception $e) {
								    Utils::logDebug('Hubo un error inesperado', $e);
								}
																
								?>
							</tbody>
						</table>
					</div>
					<?php 
					
					try{
    					for($i=0;$i<6;$i++){
    					    for($j=0;$j<7;$j++){
    					        if(isset($calendar[$i][$j]->timeSlots)){
    					            echo '<div class="day-timeslots is-hidden" id="' . $calendar[$i][$j]->dayOfMonth->format('Ymd') . '">';
    					            for($k=0; $k<count($calendar[$i][$j]->timeSlots); $k++){
    					                $dateTimeConverter=new DateTime();
    					                $dateTimeConverter= $dateTimeConverter->createFromFormat('H:i:s', $calendar[$i][$j]->timeSlots[$k]->timeFrom);
    					                $timeFrom=$dateTimeConverter->format('H:i');
    					                
    					                $dateTimeConverter=new DateTime();
    					                $dateTimeConverter= $dateTimeConverter->createFromFormat('H:i:s', $calendar[$i][$j]->timeSlots[$k]->timeTo);
    					                $timeTo=$dateTimeConverter->format('H:i');
    					                
    					                echo ' <div class="date_checkbox">';
    					                echo '     <label><input type="radio" name="' . Controlador::SCHUEDULE_DATE_PARAM . '"';
    					                echo '         value="' . $calendar[$i][$j]->dayOfMonth->format('Y-m-d') .'|'. $calendar[$i][$j]->timeSlots[$k]->label . '"';
    					                echo '         onclick="timeslotSelected(this);"><span';
    					                echo '         class="checkmark"></span>';
    					                echo '     <p>' . $timeFrom . ' - ' . $timeTo . '</p></label>';
    					                echo ' </div>';
    					            }
    					            echo ' </div>';
    					        }
    					    }
    					}
					
                    } catch (Exception $e) {
                        Utils::logDebug('Hubo un error inesperado', $e);
                    }
					?>
					
					<div class="container">
						<div class="row colors_date">
							<div class="col-xs-12 col-sm-4">
								<span class="box_green"></span>D&iacute;a - Jornada disponible
							</div>
							<div class="col-xs-12 col-sm-4">
								<span class="box_blue"></span>D&iacute;a - Jornada seleccionada
							</div>
							<div class="col-xs-12 col-sm-4">
								<span class="box_red"></span>D&iacute;a - Jornada no disponible
							</div>
						</div>
					</div>
				</div>
                
				<div class="row">
				<?php 
				      $noMoreDates=$_REQUEST[Dispatcher::OPTION_PARAM ];
 					  $noMoreDatesBool=strcmp($noMoreDates, Dispatcher::SCHEDULE_MORE_DATES)==0;
 				 ?>
					<div class="col-xs-12 <?php echo $noMoreDatesBool? '':'col-sm-6'; ?> ">
						<button type="submit" id="confirm" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::SCHEDULE_DATE_CONFIRM_LABEL ?>"
							class="button btn_general" onclick="onSubmitButton(this);" disabled>Agendar mi cita</button>
					</div>
 					<div class="col-xs-12 <?php echo $noMoreDatesBool? '':'col-sm-6'; ?> ">
 						<?php 
 						if($noMoreDatesBool){
 						    echo '<button type="submit" ';
 						    echo 'class="button btn_general col_red" onclick="onSubmitButton(this);">Ninguna fecha y jornada disponible funciona para mi</button>';
 						}else{
 						    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::SCHEDULE_MORE_DATES . '"';
 						    echo 'class="button  btn_general col_blue" onclick="onSubmitButton(this);">M&aacute;s fechas</button>';
 						}
 						?>
 					</div>
				</div>
			</form>
		</div>
	</div>
</body>
</html>