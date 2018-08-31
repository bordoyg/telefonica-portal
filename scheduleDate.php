
<!DOCTYPE html>
<html>
<?php include 'header.php'?>
<body>
	<div id="cont_agend">
		<div class="banner_top text_center">
			<h1>Telef&oacute;nica</h1>
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
		<h2 class="text_center">Elije la fecha y jornada de tu cita.</h2>
		<?php include Controlador::MESSAGES_URL; ?>

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
								$Controlador = $GLOBALS['Controlador'];
								$action=$_REQUEST[Dispatcher::OPTION_PARAM];
								if (strcmp(Dispatcher::SCHEDULE_MORE_DATES, $action) === 0){
								    $calendar=$Controlador->createCalendar(15);
								    $cantSemanas=6;
								    echo '<input type="hidden" name="' . Dispatcher::SCHEDULE_NO_MORE_DATES . '" value"true"/>';
								}else{
								    $calendar=$Controlador->createCalendar(7);
								    $cantSemanas=5;
								}
								
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
								        if(isset($calendar[$i][$j]->timeSlots)){
								            $cssDisponible=" calendar-disponible ";
								            $jsScript='onclick="dateSelected(event);"';
								        }else{
								            if(strcmp($calendar[$i][$j]->dayOfMonth->format("Y-m-d"),$currentStrDate)>0){
								                $cssDisponible=" calendar-no-disponible ";
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
								
								?>
							</tbody>
						</table>
					</div>
					<?php 
					
					
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
					                echo '         value="' . $calendar[$i][$j]->dayOfMonth->format('Y-m-d') .'|'. $calendar[$i][$j]->timeSlots[$k]->name . '"';
					                echo '         onclick="timeslotSelected(this);"><span';
					                echo '         class="checkmark"></span>';
					                echo '     <p>' . $timeFrom . ' - ' . $timeTo . '</p></label>';
					                echo ' </div>';
					            }
					            echo ' </div>';
					        }
					    }
					}
					
					

					?>
					<!-- 
					<div class="day-timeslots is-hidden" id="20180726">
						<div class="date_checkbox">
							<label><input type="radio" id="201807260700" name="timeslot"
								value="201807260700|AM|BK_BOG_COMF_CHAPIN|300"
								onclick="timeslotSelected(this);" disabled><span
								class="checkmark"></span>
							<p>07:00 - 12:00</p></label>
						</div>
						<div class="date_checkbox">
							<label><input type="radio" id="201807261200" name="timeslot"
								value="201807261200|PM|BK_BOG_COMF_CHAPIN|420"
								onclick="timeslotSelected(this);"><span class="checkmark"></span>
							<p>12:00 - 19:00</p></label>
						</div>
					</div>
					<div class="day-timeslots is-hidden" id="20180728">
						<div class="date_checkbox">
							<label><input type="radio" id="201807280700" name="timeslot"
								value="201807280700|AM|BK_BOG_COMF_CHAPIN|300"
								onclick="timeslotSelected(this);"><span class="checkmark"></span>
							<p>07:00 - 12:00</p></label>
						</div>
						<div class="date_checkbox">
							<label><input type="radio" id="201807281200" name="timeslot"
								value="201807281200|PM|BK_BOG_COMF_CHAPIN|420"
								onclick="timeslotSelected(this);"><span class="checkmark"></span>
							<p>12:00 - 19:00</p></label>
						</div>
					</div>
					<div class="day-timeslots is-hidden" id="20180730">
						<div class="date_checkbox">
							<label><input type="radio" id="201807300700" name="timeslot"
								value="201807300700|AM|BK_BOG_COMF_CHAPIN|300"
								onclick="timeslotSelected(this);"><span class="checkmark"></span>
							<p>07:00 - 12:00</p></label>
						</div>
						<div class="date_checkbox">
							<label><input type="radio" id="201807301200" name="timeslot"
								value="201807301200|PM|BK_BOG_COMF_CHAPIN|420"
								onclick="timeslotSelected(this);"><span class="checkmark"></span>
							<p>12:00 - 19:00</p></label>
						</div>
					</div>
					<div class="day-timeslots is-hidden" id="20180729">
						<div class="date_checkbox">
							<label><input type="radio" id="201807290700" name="timeslot"
								value="201807290700|AM|BK_BOG_COMF_CHAPIN|300"
								onclick="timeslotSelected(this);"><span class="checkmark"></span>
							<p>07:00 - 12:00</p></label>
						</div>
						<div class="date_checkbox">
							<label><input type="radio" id="201807291200" name="timeslot"
								value="201807291200|PM|BK_BOG_COMF_CHAPIN|420"
								onclick="timeslotSelected(this);"><span class="checkmark"></span>
							<p>12:00 - 19:00</p></label>
						</div>
					</div>
					<div class="day-timeslots is-hidden" id="20180727">
						<div class="date_checkbox">
							<label><input type="radio" id="201807270700" name="timeslot"
								value="201807270700|AM|BK_BOG_COMF_CHAPIN|300"
								onclick="timeslotSelected(this);"><span class="checkmark"></span>
							<p>07:00 - 12:00</p></label>
						</div>
						<div class="date_checkbox">
							<label><input type="radio" id="201807271200" name="timeslot"
								value="201807271200|PM|BK_BOG_COMF_CHAPIN|420"
								onclick="timeslotSelected(this);"><span class="checkmark"></span>
							<p>12:00 - 19:00</p></label>
						</div>
					</div>-->
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
 					  $noMoreDates=$_REQUEST[Dispatcher::SCHEDULE_NO_MORE_DATES];
 					  $noMoreDatesBool=strcmp($noMoreDates, "true")==0;
 				 ?>
					<div class="col-xs-12 <?php echo $noMoreDatesBool? '':'col-sm-6'; ?> ">
						<button type="submit" id="confirm" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::SCHEDULE_DATE_CONFIRM_LABEL ?>"
							class="button btn_general" onclick="onSubmitButton(this);" disabled>Agendar mi cita</button>
					</div>
 					<div class="col-xs-12 <?php echo $noMoreDatesBool? '':'col-sm-6'; ?> ">
 						<?php 
 						if($noMoreDatesBool){
 						    echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::SCHEDULE_ANY_DATES . '" ';
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