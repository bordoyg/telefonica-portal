
<!DOCTYPE html>
<html>
<head></head>
<?php 
	require_once(APPPATH . 'widgets/custom/library/header.php'); 
	$noMoreDates=$_REQUEST[Dispatcher::OPTION_PARAM ];
	$noMoreDatesBool=strcmp($noMoreDates, Dispatcher::SCHEDULE_MORE_DATES)==0;
?>
<body>
	<div id="appointment-confirmation">
		<div class="container">
			<div class="row">
				<div class="col-xs-offset-0 col-sm-offset-2 col-md-offset-4 col-lg-offset-4 col-xs-12 col-sm-8 col-md-4 col-lg-4 text-center">
                    <div class="row header-image">
                        <img class="img " src="/euf/assets/others/telefonica/images/logo-movistar.png">
                    </div>
                    <div class="row appointment-info">
                        <p>
                            <span>Seleccion&aacute; fecha para</span>
                        </p>
                        <p>
                            <span>agendar tu cita.</span>
                        </p>
                    </div>
                    <div id="appointment-calendar" class="container-fluid">
						<form id="myForm" action="" method="post">
							<div class="wrap_calendar">
								<div class="calendar">
									<header>
										<h2 class="month"><?php echo $GLOBALS['translateMonth'][date("F")];?></h2>
									</header>
									<table>
										<thead>
											<td>Dom</td>
											<td>Lun</td>
											<td>Mar</td>
											<td>Mie</td>
											<td>Jue</td>
											<td>Vie</td>
											<td>Sab</td>
										</thead>
										<tbody>
										<?php 
											try{
											    $Controlador = $GLOBALS['Controlador'];
											    $action=$_REQUEST[Dispatcher::OPTION_PARAM];
											    $activityID=$Controlador->getActivityIdFromContext();
											    $activity=$Controlador->findActivityData($activityID);
											    
											    $daysFrom=$GLOBALS['config']['calendar-from'];
											    $daysTo=$GLOBALS['config']['calendar-to'];
											    $daysMore=$GLOBALS['config']['calendar-more'];
											    if(strcmp(Controlador::AVERIA_LABEL, $Controlador->isAveriaOProvision($activity))==0){
											        $tmpDaysFrom=$GLOBALS['config']['calendar-averias-from-' . $activity->XA_CUSTOMER_TYPE];
											        if(isset($tmpDaysFrom)&& $tmpDaysFrom>=0){
											            $daysFrom=$tmpDaysFrom;
											        }
											        $tmpDaysTo=$GLOBALS['config']['calendar-averias-to-' . $activity->XA_CUSTOMER_TYPE];
											        if(isset($tmpDaysTo)&& $tmpDaysTo>0){
											            $daysTo=$tmpDaysTo;
											        }
											        $tmpDaysMore=$GLOBALS['config']['calendar-averias-more-' . $activity->XA_CUSTOMER_TYPE];
											        if(isset($tmpDaysMore)&& $tmpDaysMore>0){
											            $daysMore=$tmpDaysMore;
											        }
											    }
											    if(strcmp(Controlador::PROVISION_LABEL, $Controlador->isAveriaOProvision($activity))==0){
											        $tmpDaysFrom=$GLOBALS['config']['calendar-provision-from'];
											        if(isset($tmpDaysFrom)&& $tmpDaysFrom>=0){
											            $daysFrom=$tmpDaysFrom;
											        }
											        $tmpDaysTo=$GLOBALS['config']['calendar-provision-to'];
											        if(isset($tmpDaysTo)&& $tmpDaysTo>0){
											            $daysTo=$tmpDaysTo;
											        }
											        $tmpDaysMore=$GLOBALS['config']['calendar-provision-more'];
											        if(isset($tmpDaysMore)&& $tmpDaysMore>0){
											            $daysMore=$tmpDaysMore;
											        }
											    }
											    
												$cantSemanas=5;
											    if (strcmp(Dispatcher::SCHEDULE_MORE_DATES, $action) === 0){
											        $daysTo=$daysMore;
											        echo '<input type="hidden" name="' . Dispatcher::SCHEDULE_NO_MORE_DATES . '" value"true"/>';
											    }
											    
											    $endConsultedDay=new DateTime("now");
											    $endConsultedDay=$endConsultedDay->sub(new DateInterval("P1D"));
											    $endConsultedDay=$endConsultedDay->add(new DateInterval("P" . $daysTo . "D"));
											    $month = date('m');
											    $year = date('Y');
											    $day = date("d", mktime(0,0,0, $month+1, 0, $year));
											    $lastDayOfMonth=new DateTime("now");
											    $lastDayOfMonth->setDate($year, $month, $day);
											    if($endConsultedDay->format("Y-m-d") > $lastDayOfMonth->format("Y-m-d")){
											        $cantSemanas=6;
											    }
											    $calendar=$Controlador->createCalendar($daysFrom, $daysTo);
											    
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
								
								<?php if(!$noMoreDatesBool) { ?>
									<div class="container-fluid text-left">
										<div class="row colors_date">
											<div class="col-xs-4 col-sm-4">
												<span class="box_green"></span>Disponible
											</div>
											<div class="col-xs-4 col-sm-4">
												<span class="box_red"></span>No Disponible
											</div>
											<div class="col-xs-4 col-sm-4">
												<span class="box_blue"></span>Seleccionado
											</div>
										</div>
									</div>
								<?php } else { ?>
									<div class="container-fluid text-left">
										<div class="row colors_date">
											<div class="col-xs-4 col-sm-4">
												<span class="box_green"></span>Disponible
											</div>
											<div class="col-xs-4 col-sm-4">
												<span class="box_blue"></span>Seleccionado
											</div>
											<div class="col-xs-4 col-sm-4">
												<span class="box_red"></span>No Disponible
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
			 				
			                <div class="row separator <?php if($noMoreDatesBool) { echo 'hide'; }?>">
	                            <img class="img " src="/euf/assets/others/telefonica/images/separator.png"/>
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
							
			 				<?php if(!$noMoreDatesBool) { ?>
				 				<div class="row separator hide">
			                        <img class="img " src="/euf/assets/others/telefonica/images/separator.png"/>
			                    </div>
								<div class="row appointment-remember-ad">
		                            <p>
		                                <span>Record&aacute; que el horario es de 9 a 18hs y tiene </span>
		                            </p>
		                            <p>
		                            	<span>que haber alguien en el domicilio.</span>
		                            </p>
		                        </div>
								<div class="row action-modify">
									<div class="col-xs-6 col-sm-6 padding-right-8px ">
										<button type="submit" id="confirm" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::SCHEDULE_DATE_CONFIRM_LABEL ?>"
											class="btn btn-lg btn-block btn-primary" onclick="onSubmitButton(this);" disabled>Reagendar Cita</button>
									</div>
				 					<div class="col-xs-6 col-sm-6 padding-left-8px">
				 						<button type="submit" name="<?php echo Dispatcher::OPTION_PARAM; ?>" value="<?php echo Dispatcher::SCHEDULE_MORE_DATES; ?>"';
				 						    class="btn btn-lg btn-block btn-blue" onclick="onSubmitButton(this);">Ver m&aacute;s fechas</button>
				 					</div>
								</div>
			 				<?php } else { 
								 ?>
			 					<div class="row action-modify">
			 						<?php 
			 						if(strcmp(Controlador::PROVISION_LABEL, $Controlador->isAveriaOProvision($activity))==0){
			 						    ?>
			 						    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
										<button type="submit" id="confirm" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::SCHEDULE_DATE_CONFIRM_LABEL ?>"
											class="btn btn-lg btn-block btn-primary" onclick="onSubmitButton(this);" disabled>Reagendar Cita</button>
    									</div>
			 						    <?php
			 						}else{
			 						    ?>
			 						    <div class="col-xs-6 col-sm-6 padding-right-8px">
										<button type="submit" id="confirm" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::SCHEDULE_DATE_CONFIRM_LABEL ?>"
											class="btn btn-lg btn-block btn-primary" onclick="onSubmitButton(this);" disabled>Reagendar Cita</button>
    									</div>
    				 					<div class="col-xs-6 col-sm-6 padding-left-8px">
    										<input type="button"
    				 						 	name="<?php echo Dispatcher::OPTION_PARAM ?>" value="Contactame"
    											 class="btn btn-lg btn-block btn-secondary" 
    											 onclick='
    											 	var event = new CustomEvent("callCallCenterShown", { "detail": "Example of an event" });
    												document.dispatchEvent(event);'
    				 							style="font-size: 14px;padding: 8px 5px;">
    				 						
    				 						<?php //Boton oculto que sirve para disparar el evento click del boton OK del popup?>
    				 						<button type="submit"
    				 						 	name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::SCHEDULE_DATE_CALLCENTER_CONTACT ?>"
    				 							class="btn btn-lg btn-block btn-secondary" onclick="onSubmitButton(this);"
    				 							style="font-size: 14px;padding: 8px 5px;display:none;"></button>
    				 					</div>
			 						    <?php
			 						}
			 						?>
								</div>
			 				<?php } ?>
						</form>
						<div class="row separator">
	                        <img class="img " src="/euf/assets/others/telefonica/images/separator.png"/>
	                    </div>
					</div>
                    <div class="row footer-image">
                        <img class="img " src="/euf/assets/others/telefonica/images/footer-movistar.png"/>
                    </div>
                </div>
			</div>
		</div>
	</div>
</body>
</html>