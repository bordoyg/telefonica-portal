<?php
require_once(APPPATH . 'widgets/custom/library/serviceRest.php');
require_once(APPPATH . 'widgets/custom/library/serviceSoap.php');

use RightNow\Connect\v1_3 as RNCPHP;

class Controlador {
    const MESSAGE_PARAM = 'errorMessage';
    const MESSAGES_MODAL_URL = 'messagesModal.php';
	const ACTIVITY_PARAM = 'activity';
	const LOCATION_TECHNICAN_PARAM='locationTechnican';
	const LOCATION_CUSTOMER_PARAM='locationCustomer';
	const LOCATION_TECHNICAN="technicanLocation";
	const SCHUEDULE_DATE_PARAM='timeSlot';
	const STATUS_LOCALIZABLE=array("onTheWay", "started");
	const STATUS_VIGENTE=array("onTheWay", "started", "pending");
	const STATUS_PENDING="pending";
	
	const ERROR_GENERIC_MSJ='<div class="row appointment-info"> <p> <span>No fue posible procesar tu </span></p><p><span>solicitud.</span></p><p><span>Por favor intentalo m&aacute;s</span></p><p><span>tarde.</span></p></div>';
    const ERROR_ORDEN_INEXISTENTE='<div class="row appointment-info"><p><span class="text-bold">La orden no existe </span></p></div>';
    
	const ERROR_ORDEN_NO_VIGENTE_AVERIAS='<div class="row appointment-info"><p><span>Tu cita no puede ser confirmada</span></p><p><span>o modificada debido a que no se</span></p><p><span>encuentra vigente en este</span></p><p><span>momento.</span></p><p><span>Si ten&eacute;s alguna inquietud pod&eacute;s</span></p><p><span>comunicarte al</span></p><p><span class="contact-number text-underline">0800-222-8114</span><span></span></p></div>';
	const ERROR_ORDEN_NO_VIGENTE_PROVISION='<div class="row appointment-info"><p><span>Tu cita no puede ser confirmada</span></p><p><span>o modificada debido a que no se</span></p><p><span>encuentra vigente en este</span></p><p><span>momento.</span></p><p><span>Si ten&eacute;s alguna inquietud pod&eacute;s</span></p><p><span>comunicarte al</span></p><p><span class="contact-number text-underline">0800-222-8112</span><span></span></p></div>';
	
	const MSJ_ORDEN_CONFIRMADA='<div class="row appointment-info"><p><span>Tu cita fue confirmada </span></p><p><span>para el </span></p><p><span class="appointment-date-formatted">@@$dateFormatted@@</span><span> </span></p><p><span>entre las @@$dateStartHours@@ y las @@$dateEndHours@@.</span></p></div><div class="row appointment-remember-ad text-left"><p><span class="text-bold">Record&aacute;:</span><span>&nbsp; tiene que haber alguien mayor de edad en el domicilio y te vamos a avisar por SMS cuando el t&eacute;cnico est&eacute; en camino.</span></p></div>';
	const MSJ_ORDEN_MODIFICADA='<div class="row appointment-info"><p><span>Tu cita fue </span></p><p><span>reagendada para el</span></p><p><span class="appointment-date-formatted">@@$dateFormatted@@</span><span> </span></p><p><span>entre las @@$dateStartHours@@ y las @@$dateEndHours@@.</span></p></div><div class="row appointment-remember-ad text-left"><p><span class="text-bold">Record&aacute;:</span></p><p><span>&#149;Tiene que haber alguien mayor de edad en el domicilio</span></p><p><span>&#149;Te vamos a avisar por SMS cuando el t&eacute;cnico </span></p><p><span>est&eacute; en camino.</span></p></div>';
    const MSJ_ORDEN_CANCELADA_AVERIAS='<div class="row appointment-info"><p><span>Tu cita fue cancelada.</span></p><p><span>Podrás reagendarla</span></p><p><span>llamando al</span></p><p><span class="contact-number text-underline">0800-222-8114</span></p><p><span>&nbsp;de lunes a viernes de 9 a</span></p><p><span>21hs.</span></p></div>';
    const MSJ_ORDEN_CANCELADA_PROVISION='<div class="row appointment-info"><p><span>Tu cita fue cancelada.</span></p><p><span>Podrás reagendarla</span></p><p><span>llamando al</span></p><p><span class="contact-number text-underline">0800-222-8112</span></p><p><span>&nbsp;de lunes a viernes de 9 a</span></p><p><span>21hs.</span></p></div>';
	const MSJ_ORDEN_NO_CANCELADA='<div class="row appointment-info"><p><span>Tu cita no pudo ser cancelada.</span></p><p><span>llamanos al</span></p><p><span class="contact-number text-underline">0800-222-8114 para cancelarla</span></p><p><span>&nbsp;de lunes a viernes de 9 a</span></p><p><span>21hs.</span></p></div>';
	const MSJ_LIMITE_MODIFICACIONES='<div class="row appointment-info"> <p> <span>Tu cita no puede ser modificada</span></p><p><span>Llegaste al m&aacute;ximo de modificaciones permitidas</span></p></div>';
	const MSJ_CALLCENTER_CONTACT='<div class="row appointment-info"> <p> <span>Gracias por tu mensaje</span></p><p><span>Un representante se pondr&aacute; en contacto con vos</span></p></div>';
	
    const SUB_STATUS_SIN_FECHA="SINFECHASELECCIONADA";
    const SUB_STATUS_CANCELA_CLIENTE="CANCELA_CLIENTEWEB";
	const SUB_STATUS_CANCELADA_LABEL="El cliente respondio Cancelada";
	const SUB_STATUS_CONFIRMADA_LABEL="El cliente respondio Confirmada";
	const SUB_STATUS_MODIFICADA_LABEL="El cliente respondio Reagendada";
	const SUB_STATUS_CANCELADA="CANCELADA";
	const SUB_STATUS_CONFIRMADA="CONFIRMADA";
	const SUB_STATUS_MODIFICADA="MODIFICADA";
	const CHANEL_LABEL="WEBREAGENDAMIENTO";
	const CANCEL_REASON="OKCONFIRMACLIENTEWEB";
	const EXTERNAL_ACTION_RESCHEDULE="RESCHEDULE";
	
	const WORKTYPE_AVERIAS=array("REP_ADSL","REP_FTTH","REP_FTTN","REP_NO_IMP","REP_MATERIAL","REP_ADSL_INEST","REP_OTT","REP_IPTV","REP_RISK","REP_STB","REP_STB_2","REP_SUPERVISION");
	const WORKTYPE_PROVISION=array("PRO_CHANGE_EQUIPMENT","PRO_CHANGE_TECHNOLOGY","PRO_MOVE","PRO_CLOSET_FTTN","PRO_CLOSET_FTTN_KIT","PRO_UNINSTALL","PRO_INSTALL","PRO_REVCLOSET_FTTN","PRO_INSTALL_IPTV","PRO_INSTALL_KIT","PRO_INSTALL_2","PRO_SUPERV_FTTH","PRO_FALTA_MAT","PRO_QUALITY_INST");
	const PROVISION_LABEL="provision";
	const AVERIA_LABEL="averia";
	
    private $service=NULL;
    private $serviceSoap=NULL;
    
    function __construct() {
        if(!isset($GLOBALS['serviceRest'])){
            $GLOBALS['serviceRest']=new ServiceRest();
        }
        $this->service = $GLOBALS['serviceRest'];
        
        if(!isset($GLOBALS['serviceSoap'])){
            $GLOBALS['serviceSoap']=new ServiceSoap();
        }
        $this->serviceSoap = $GLOBALS['serviceSoap'];
    }

    function findTechnicanLocation($activity){
        //Obtenemos la posicion del domicilio
        if(isset($activity->latitude) && isset($activity->longitude)){
            $_REQUEST[Controlador::LOCATION_CUSTOMER_PARAM]=$activity->longitude.','.$activity->latitude;
        }
        
        //Obtenemos la posicion del tecnico
        $locationData=$this->service->request('/rest/ofscCore/v1/whereIsMyTech', 'GET', 'activityId=' . $activity->activityId . '&includeAvatarImageData=true');
        $_REQUEST[Controlador::LOCATION_TECHNICAN]=$locationData;
        
        
        if(isset($locationData->coordinates) && isset($locationData->coordinates->latitude) && isset($locationData->coordinates->longitude)){
            $lat=$locationData->coordinates->latitude;
            $lng=$locationData->coordinates->longitude;
            $_REQUEST[Controlador::LOCATION_TECHNICAN_PARAM]=  $lng. ',' . $lat ;
        }else{
            $this->addMessageError("No se puedo establecer la ubicacion del t&eacute;cnico, intenta mas tarde");
        }
        
        return $locationData;
    }
    function findAvailabilitySOAP($daysFrom, $daysTo) {
        $activityID=$this->getActivityIdFromContext();
        $activity=$this->findActivityData($activityID);
        
        //Genero los dias para solicitar disponibilidad
        $fromDateStr=date("Y-m-d") ."+ " . $daysFrom . " days";
        $fromDate = strtotime($fromDateStr);
        $date = date("Y-m-d", $fromDate);
        
        $params=array();
        for($i=0; $i<$daysTo - 1; $i++){
            $newDate = strtotime($date."+ 1 days");
            $date = date("Y-m-d",$newDate);

            array_push($params, array('date'=>$date));
        }
        
        //array_push($params, array('calculate_duration'=>true));
        array_push($params, array('calculate_travel_time'=>true));
        array_push($params, array('calculate_work_skill'=>true));
        array_push($params, array('return_time_slot_info'=>true));
        array_push($params, array('determine_location_by_work_zone'=>true));
        array_push($params, array('dont_aggregate_results'=>true));
        //array_push($params, array('min_time_to_end_of_time_slot'=>0));
        array_push($params, array('activity_field'=>array('name'=>'XA_WORK_ZONE_KEY', 'value'=>$activity->XA_WORK_ZONE_KEY)));
        array_push($params, array('activity_field'=>array('name'=>'XA_QUADRANT', 'value'=>$activity->XA_QUADRANT)));
        array_push($params, array('activity_field'=>array('name'=>'XA_ACCESS_TECHNOLOGY', 'value'=>$activity->XA_ACCESS_TECHNOLOGY)));
        array_push($params, array('activity_field'=>array('name'=>'worktype_label', 'value'=>$activity->activityType)));
        array_push($params, array('activity_field'=>array('name'=>'XA_WORK_TYPE', 'value'=>$activity->XA_WORK_TYPE)));
        array_push($params, array('activity_field'=>array('name'=>'XA_NUMBER_DECODERS', 'value'=>0)));
        array_push($params, array('activity_field'=>array('name'=>'XA_CUSTOMER_SEGMENT', 'value'=>$activity->XA_CUSTOMER_SEGMENT)));
        array_push($params, array('activity_field'=>array('name'=>'XA_CENTRAL', 'value'=>$activity->XA_CENTRAL)));
        array_push($params, array('activity_field'=>array('name'=>'XA_BROADBAND_TECHNOLOGY', 'value'=>$activity->XA_BROADBAND_TECHNOLOGY)));
        array_push($params, array('activity_field'=>array('name'=>'XA_EFFORT_CODE', 'value'=>'WEB_AGENDAMIENTO')));
        
        //Push de datos Pablo
        array_push($params, array('activity_field'=>array('name'=>'ACTIVITY_GROUP', 'value'=>NULL)));
        array_push($params, array('activity_field'=>array('name'=>'PRIORITY', 'value'=>$activity->XA_PRIORITY)));
        array_push($params, array('activity_field'=>array('name'=>'XA_TERMINATION_TYPE', 'value'=>$activity->XA_TERMINATION_TYPE)));
        array_push($params, array('activity_field'=>array('name'=>'XA_CURRENT_DIAGNOSIS', 'value'=>$activity->XA_CURRENT_DIAGNOSIS)));
        array_push($params, array('activity_field'=>array('name'=>'XA_TELEPHONE_TECHNOLOGY', 'value'=>$activity->XA_TELEPHONE_TECHNOLOGY)));
        array_push($params, array('activity_field'=>array('name'=>'XA_CUSTOMER_TYPE', 'value'=>$activity->XA_CUSTOMER_TYPE)));
        array_push($params, array('activity_field'=>array('name'=>'XA_CUSTOMER_SUBTYPE', 'value'=>$activity->XA_CUSTOMER_SUBTYPE)));
        array_push($params, array('activity_field'=>array('name'=>'XA_TV_TECHNOLOGY', 'value'=>$activity->XA_TV_TECHNOLOGY)));
        array_push($params, array('activity_field'=>array('name'=>'XA_COMPANY_NAME', 'value'=>$activity->XA_COMPANY_NAME)));
        if(isset($activity->XA_GUARANTEE)){
            array_push($params, array('activity_field'=>array('name'=>'XA_GUARANTEE', 'value'=>$activity->XA_GUARANTEE)));
        }else{
            array_push($params, array('activity_field'=>array('name'=>'XA_GUARANTEE', 'value'=>0)));
        }
        
        $response=$this->serviceSoap->request("/soap/capacity/", "urn:toa:capacity", "get_capacity", $params);
        
        $response=$response['SOAP-ENV:ENVELOPE']['SOAP-ENV:BODY']['URN:GET_CAPACITY_RESPONSE'];
        
        $timeSlotsMap=array();
        if(isset($response['TIME_SLOT_INFO'])){
            for($i=0; $i<count($response['TIME_SLOT_INFO']); $i++){
                $timeSlot=new stdClass();
                $timeSlot->timeFrom=$response['TIME_SLOT_INFO'][$i]['TIME_FROM'];
                $timeSlot->timeTo=$response['TIME_SLOT_INFO'][$i]['TIME_TO'];
                $timeSlot->label=$response['TIME_SLOT_INFO'][$i]['LABEL'];
                $timeSlot->name=$response['TIME_SLOT_INFO'][$i]['NAME'];
                $timeSlotsMap[$response['TIME_SLOT_INFO'][$i]['LABEL']]= $timeSlot;
                
            }
        }
       
        $dates=array();
        $datesAux=array();
        for($i=0; $i<count($response['CAPACITY']); $i++){
            if(isset($response['CAPACITY'][$i]['TIME_SLOT'])
                && isset($response['CAPACITY'][$i]['AVAILABLE'])){
                    
                $availableQuota=$response['CAPACITY'][$i]['AVAILABLE'];
                $activityDuration=$response['ACTIVITY_DURATION'];
                $travelTime=$response['ACTIVITY_TRAVEL_TIME'];
                $timeNeeded=$activityDuration + $travelTime;
                
                if($availableQuota>$timeNeeded){
                    if(!isset($datesAux[$response['CAPACITY'][$i]['DATE']])){
                        $datesAux[$response['CAPACITY'][$i]['DATE']]=array();
                    }
                    $datesAux[$response['CAPACITY'][$i]['DATE']][$response['CAPACITY'][$i]['TIME_SLOT']]=$timeSlotsMap[$response['CAPACITY'][$i]['TIME_SLOT']];
                }
            }
        }
        
        foreach ($datesAux as $clave => $valor) {
            if(isset($valor)){
                $date=new stdClass();
                $d=new DateTime();
                
                $date->date=$d->createFromFormat("Y-m-d", $clave);
                $timeSlots=array();
                foreach ($valor as $c => $v) {
                    array_push($timeSlots, $v);
                }
                
                //Ordenamiento Timeslots
                $sortedTimeSlots=array();
                $dateTimeConverter=new DateTime();
                $minTimeSlotFrom='99:99:99';
                $minTimeSlot=0;
                for($l=0; $l<count($timeSlots); $l++){
                    for($m=0; $m<count($timeSlots)-$l; $m++){
                        $dateTimeConverter= $dateTimeConverter->createFromFormat('H:i:s', $timeSlots[$m]->timeFrom);
                        $currentTimeSlotFrom=$dateTimeConverter->format('H:i:s');
                        $minTimeSlot=$timeSlots[$m];
                        if(strcmp($currentTimeSlotFrom, $minTimeSlotFrom)<0){
                            $minTimeSlotFrom=$currentTimeSlotFrom;
                            $minTimeSlot=$timeSlots[$m];
                        }
                    }
                    
                    array_push($sortedTimeSlots, $minTimeSlot);
                }
                
                $date->timeSlots=$sortedTimeSlots;
                array_push($dates, $date);
            }
        }

        return $dates;
    }
    //@Deprecated
    function findAvailability($days) {
        $activityID=$this->getActivityIdFromContext();
        $activity=$this->findActivityData($activityID);
        
        //Genero los dias para solicitar disponibilidad
        $queryString='dates=';
        $date = date("Y-m-d");
        $queryString=$queryString . $date;
        
        for($i=0; $i<$days - 1; $i++){
            $newDate = strtotime($date."+ 1 days");
            $date = date("Y-m-d",$newDate);
            
            $queryString=$queryString . ',' . $date;
        }
        
        $queryString=$queryString . '&activityType=' . $activity->activityType;
        $queryString=$queryString . '&XA_WORK_ZONE_KEY=' . $activity->XA_WORK_ZONE_KEY;
        $queryString=$queryString . '&XA_WORK_TYPE=' . $activity->XA_WORK_TYPE;
        $queryString=$queryString . '&XA_ACCESS_TECHNOLOGY=' . $activity->XA_ACCESS_TECHNOLOGY;
        $queryString=$queryString . '&XA_QUADRANT=' . $activity->XA_QUADRANT;
        $queryString=$queryString . '&XA_NUMBER_DECODERS=0';//No viene en la actividad, segun ejemplo va con 0
        $queryString=$queryString . '&determineAreaByWorkZone=true';
        $queryString=$queryString . '&XA_NOT_ACCOMPLISHED=' . $activity->XA_NOT_ACCOMPLISHED;
        $queryString=$queryString . '&XA_CUSTOMER_SEGMENT=' . $activity->XA_CUSTOMER_SEGMENT;
        $queryString=$queryString . '&XA_ESTRATO=' . $activity->XA_ESTRATO;
        $queryString=$queryString . '&XA_VELOCIDAD=' . $activity->XA_VELOCIDAD;
        $queryString=$queryString . '&XA_NOT_ACCOMPLISHED=' . $activity->XA_NOT_ACCOMPLISHED;
        
        Utils::logDebug($queryString);
        $activityBookingOptions=$this->service->request('/rest/ofscCapacity/v1/activityBookingOptions', 'GET', $queryString);
        Utils::logDebug(json_encode($activityBookingOptions));
        $timeSlotsMap=array();
        if(isset($activityBookingOptions->timeSlotsDictionary)){
            for($i=0; $i<count($activityBookingOptions->timeSlotsDictionary); $i++){
                $timeSlotsMap[$activityBookingOptions->timeSlotsDictionary[$i]->label]= $activityBookingOptions->timeSlotsDictionary[$i];
            }
        }

        $dates=array();
        for($i=0; $i<count($activityBookingOptions->dates); $i++){
            $timeSlots=array();
            if(isset($activityBookingOptions->dates[$i]->areas)){
                for($j=0; $j<count($activityBookingOptions->dates[$i]->areas); $j++){
                    if(isset($activityBookingOptions->dates[$i]->areas[$j]->timeSlots)){
                        for($k=0; $k<count($activityBookingOptions->dates[$i]->areas[$j]->timeSlots); $k++){
                            if(isset($activityBookingOptions->dates[$i]->areas[$j]->timeSlots[$k]->remainingQuota)
                                && $activityBookingOptions->dates[$i]->areas[$j]->timeSlots[$k]->remainingQuota>0){
                                    
                                    array_push($timeSlots, $timeSlotsMap[$activityBookingOptions->dates[$i]->areas[$j]->timeSlots[$k]->label]);
                            }
                        }
                    }
                }
            }
            //Ordenamiento Timeslots
            $sortedTimeSlots=array();
            $dateTimeConverter=new DateTime();
            $minTimeSlotFrom='99:99:99';
            $minTimeSlot=0;
            for($l=0; $l<count($timeSlots); $l++){
                for($m=0; $m<count($timeSlots); $m++){
                    $dateTimeConverter= $dateTimeConverter->createFromFormat('H:i:s', $timeSlots[$m]->timeFrom);
                    $currentTimeSlotFrom=$dateTimeConverter->format('H:i:s');
                    $minTimeSlot=$timeSlots[$m];
                    if(strcmp($currentTimeSlotFrom, $minTimeSlotFrom)<0){
                        $minTimeSlotFrom=$currentTimeSlotFrom;
                        $minTimeSlot=$timeSlots[$m];
                    }
                }
                array_push($sortedTimeSlots, $minTimeSlot);
            }

            if(count($sortedTimeSlots)>0){
               $date=new stdClass();
               $d=new DateTime();
               
               $date->date=$d->createFromFormat("Y-m-d", $activityBookingOptions->dates[$i]->date);
               $date->timeSlots=$sortedTimeSlots;
               array_push($dates, $date);
            }
        }

        return $dates;
    }
    function findActivityData($activityID){
        try {
            //Buscar los datos de la actividad
            $out = $this->service->request('/rest/ofscCore/v1/activities/' . $activityID, 'GET');
            if(!isset($out->activityId)){
                return null;
            }
            return $out;
        } catch (Exception $e) {
            Utils::logDebug('Hubo un error al buscar la cita', $e);
            $this->addMessageError(Controlador::ERROR_GENERIC_MSJ);
            return null;
        }
    }
    function createCalendar($daysFrom, $daysTo){
        try{
            $calendar=array();
            $firstDay=new DateTime();
            $firstDay->setDate(date("Y"), date("m"), 1);
            $dayWeekFirstDay=$firstDay->format("w");
            $firstDayCalendar=$firstDay->sub(new DateInterval("P" . $dayWeekFirstDay . "D"));
            try{
                $availability=$this->findAvailabilitySOAP($daysFrom, $daysTo);
            }catch(Exception $e){
                Utils::logDebug('Hubo un error al buscar los dias habilitados', $e);
                try{
                    $activityID=$this->getActivityIdFromContext();
                    $params=array();
                    $params['XA_GETCAPACITYERROR']= "FALLA_CONSULTA_CAPACIDAD";
                    $params=json_encode($params);
                    //Se actualiza el timeslot y el estado XA_REMINDER_REPLY
                    $this->service->request('/rest/ofscCore/v1/activities/' . $activityID, 'PATCH', $params);
                }catch(Exception $e){
                    Utils::logDebug('Hubo un error al guardar el campo XA_GETCAPACITYERROR=FALLA_CONSULTA_CAPACIDAD', $e);
                }
            }
            
            for($i=0;$i<8;$i++){
                $calendar[$i]=array();
                for($j=0;$j<7;$j++){
                    $dayOfMonth=new DateTime();
                    $dayOfMonth->setTimestamp($firstDayCalendar->getTimestamp());
                    $dateItem=new stdClass();
                    $dateItem->dayOfMonth=$dayOfMonth;
                    
                    if(isset($availability)){
                        for($k=0; $k<count($availability); $k++){
                            $availabilityDate=$availability[$k]->date->format("Ymd");
                            $dayOfCalendar=$dateItem->dayOfMonth->format("Ymd");
                            
                            if(strcmp($availabilityDate,$dayOfCalendar)==0){
                                $dateItem->timeSlots=$availability[$k]->timeSlots;
                            }
                        }
                    }
                    
                    $calendar[$i][$j]=$dateItem;
                    
                    $firstDayCalendar->add(new DateInterval("P1D"));
                }
            }
        }catch(Exception $e){
            Utils::logDebug('Hubo un error al crear el calendario', $e);
            $this->addMessageError(Controlador::ERROR_GENERIC_MSJ);
            try{
                $activityID=$this->getActivityIdFromContext();
                $params=array();
                $params['XA_GETCAPACITYERROR']= "FALLA_MOSTRAR_CAPACIDAD";
                $params=json_encode($params);
                //Se actualiza el timeslot y el estado XA_REMINDER_REPLY
                $this->service->request('/rest/ofscCore/v1/activities/' . $activityID, 'PATCH', $params);
            }catch(Exception $e){
                Utils::logDebug('Hubo un error al guardar el campo XA_GETCAPACITYERROR=FALLA_MOSTRAR_CAPACIDAD', $e);
            }
            return Dispatcher::MESSAGES_URL;
        }
        
        
        return $calendar;
    }
    
    function excecuteCancelConfirm(){
        try {
            $activityID=$this->getActivityIdFromContext();
            $activity=$this->findActivityData($activityID);
          
            if(strcmp(Controlador::AVERIA_LABEL, $this->isAveriaOProvision($activity))==0){
                $params=array("setDate"=>array("date"=>NULL));
                $params=json_encode($params);
                //Se actualiza el dia = null
                
                $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId . '/custom-actions/cancel', 'POST', $params);
                
                $currentDateTime=date('Y-m-d H:i:s');
                $histroyReply=$activity->XA_HISTORY_REPLY . " | " . Controlador::SUB_STATUS_CANCELADA_LABEL . ", " . Controlador::CHANEL_LABEL . ", " .  $currentDateTime;
                $params=array();
                
                $params['timeSlot']=NULL;
                $params['XA_REMINDER_REPLY']= Controlador::SUB_STATUS_CANCELADA;
                $params['XA_CONFIRMATIONCHANNEL']= Controlador::CHANEL_LABEL;
                $params['XA_CANCEL_REASON']= Controlador::CANCEL_REASON;
                $params['XA_DATETIME_REPLY']= $currentDateTime;
                $params['XA_HISTORY_REPLY']= $histroyReply;
                
                $params=json_encode($params);

                //Se actualiza el timeslot y el estado XA_REMINDER_REPLY
                $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId, 'PATCH', $params);

                $this->addMessageError(Controlador::MSJ_ORDEN_CANCELADA_AVERIAS);;
            
                return Dispatcher::MESSAGES_URL;
            }
            if(strcmp(Controlador::PROVISION_LABEL, $this->isAveriaOProvision($activity))==0){
                $params=array("setDate"=>array("date"=>NULL));
                $params=json_encode($params);
                $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId . '/custom-actions/move', 'POST',$params);
                
                $currentDateTime=date('Y-m-d H:i:s');
                $histroyReply=$activity->XA_HISTORY_REPLY . " | " . Controlador::SUB_STATUS_CANCELADA_LABEL . ", " . Controlador::CHANEL_LABEL . ", " .  $currentDateTime;
                $params=array();
                
                $params=array("setDate"=>array("date"=>NULL));
                $params['XA_REMINDER_REPLY']= Controlador::SUB_STATUS_CANCELADA;
                $params['XA_CONFIRMATIONCHANNEL']= Controlador::CHANEL_LABEL;
                $params['XA_PENDING_EXTERNAL_ACTION']=Controlador::EXTERNAL_ACTION_RESCHEDULE;
                $params['XA_DATETIME_REPLY']= $currentDateTime;
                $params['XA_HISTORY_REPLY']= $histroyReply;
                
                $params=json_encode($params);
                
                $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId, 'PATCH', $params);
                
                $this->addMessageError(Controlador::MSJ_ORDEN_CANCELADA_PROVISION);
                return Dispatcher::MESSAGES_URL;
            }
            
            $this->addMessageError(Controlador::MSJ_ORDEN_NO_CANCELADA);
            return Dispatcher::MESSAGES_URL;
        } catch (Exception $e) {
            Utils::logDebug('Hubo un error al cancelar la cita', $e);
           $this->addMessageError(Controlador::MSJ_ORDEN_NO_CANCELADA);
            return Dispatcher::MESSAGES_URL;
        }
    }
    function excecuteScheduleCalendar(){
        $activityID=$this->getActivityIdFromContext();
        $activity=$this->findActivityData($activityID);
        $modificacionesHechas = intval($activity->XA_Q_REAGENDAMIENTO);
        $modificacionesPermitidas = intval($GLOBALS['config']['modificaciones-permitidas']);
        
        if( $modificacionesHechas >= $modificacionesPermitidas ){
            $this->addMessageError(Controlador::MSJ_LIMITE_MODIFICACIONES);
            return Dispatcher::MESSAGES_URL;
        }
        try{
            $activityID=$this->getActivityIdFromContext();
            $activity=$this->findActivityData($activityID);
            
            $currentDateTime=date('Y-m-d H:i:s');
            $params=array();
            $params['XA_Q_CAMBIOS']= $activity->XA_Q_CAMBIOS . 'Acceso CALENDARIO ' . $currentDateTime . ' ';
            
            $params=json_encode($params);
            $this->service->request('/rest/ofscCore/v1/activities/' . $activityID, 'PATCH', $params);
            Utils::logDebug('Se registro el acceso al calendario correctamente');
        } catch (Exception $e) {
            Utils::logDebug('Hubo un error al registrar la actividad del usuario', $e);
        }
        return Dispatcher::SCHEDULE_DATE_URL;
    }
    function excecuteCallCenterContact(){
        try{
            $activityID=$this->getActivityIdFromContext();
            $activity=$this->findActivityData($activityID);
            
            $params=array("setDate"=>array("date"=>NULL));
            $params=json_encode($params);
            $currentDateTime=date('Y-m-d H:i:s');
            $histroyReply=$activity->XA_HISTORY_REPLY . " | " . Controlador::SUB_STATUS_SIN_FECHA . ", " . Controlador::CHANEL_LABEL . ", " .  $currentDateTime;
            //Se actualiza el dia = null
            $this->service->request('/rest/ofscCore/v1/activities/' . $activityID . '/custom-actions/move', 'POST', $params);
            
            $params=array();
            $params['XA_REMINDER_REPLY']= Controlador::SUB_STATUS_SIN_FECHA;
            $params['timeSlot']= NULL;
            $params['XA_PENDING_EXTERNAL_ACTION']=Controlador::EXTERNAL_ACTION_RESCHEDULE;
            $params['XA_DATETIME_REPLY']= $currentDateTime;
            $params['XA_HISTORY_REPLY']=$histroyReply;

            $params=json_encode($params);
            //Se actualiza el timeslot y el estado XA_REMINDER_REPLY
            $this->service->request('/rest/ofscCore/v1/activities/' . $activityID, 'PATCH', $params);
            
            $this->addMessageError(Controlador::MSJ_CALLCENTER_CONTACT);
            return Dispatcher::MESSAGES_URL;
        } catch (Exception $e) {
            Utils::logDebug('Hubo un error al contactar al callcenter', $e);
            $this->addMessageError(Controlador::ERROR_GENERIC_MSJ);
            return Dispatcher::MESSAGES_URL;
        }
    }
    
    function excecuteScheduleConfirmSOAP(){
        try{
            $activityID=$this->getActivityIdFromContext();
            $activity=$this->findActivityData($activityID);
            //rawTimeslot Ej: 2018-08-01|AM
            $rawTimeslot=$_REQUEST[Controlador::SCHUEDULE_DATE_PARAM];
            $scheduleDate=substr($rawTimeslot, 0, strrpos($rawTimeslot, '|'));
            $scheduleTimeslot=substr($rawTimeslot, strrpos($rawTimeslot, '|') + 1);
            //Se actualiza el timeslot, date y el estado XA_REMINDER_REPLY
            $currentDateTime=date('Y-m-d H:i:s');
            $params=array();
            array_push($params, array('activity_id'=>$activityID));
            array_push($params, array('position_in_route'=>'unchanged'));

            array_push($params, array('properties'=>array('name'=>'XA_REMINDER_REPLY', 'value'=>Controlador::SUB_STATUS_MODIFICADA)));
            array_push($params, array('properties'=>array('name'=>'XA_CONFIRMATIONCHANNEL', 'value'=>Controlador::CHANEL_LABEL)));
            array_push($params, array('properties'=>array('name'=>'XA_DATETIME_REPLY', 'value'=>$currentDateTime)));
            array_push($params, array('properties'=>array('name'=>'time_slot', 'value'=>$scheduleTimeslot)));
            array_push($params, array('properties'=>array('name'=>'date', 'value'=>$scheduleDate)));
            
            $response=$this->serviceSoap->request('/soap/activity/v3/',"urn:toa:activity", "update_activity", $params);
            $response=$response['SOAP-ENV:ENVELOPE']['SOAP-ENV:BODY']['NS1:UPDATE_ACTIVITY_RESPONSE'];
            if($response['RESULT_CODE']!=0){
                throw new Exception($response['ERROR_MSG']);
            }
            
            $activity=$this->findActivityData($activityID);
            $dateStart = new DateTime($activity->date . ' ' . $activity->serviceWindowStart);
            $dateEnd = new DateTime($activity->date . ' ' . $activity->serviceWindowEnd);
            $diaCita= $dateStart->format('d') . ' de ' . $GLOBALS['translateMonth'][$dateStart->format('F')] . ' de ' .$dateStart->format('Y') . ', Jornada: ' . $activity->timeSlot . '(' . $dateStart->format('g:i A') . ' - ' . $dateEnd->format('g:i A') . ')';
            
            // buscar dateStart y dateEnd de la actividad
            $dateStart = new DateTime($activity->date . ' ' . $activity->serviceWindowStart);
            $dateEnd = new DateTime($activity->date . ' ' . $activity->serviceWindowEnd);

            $vars = array(
              '@@$dateFormatted@@'       => $dateStart->format('d-M-Y'),
              '@@$dateStartHours@@'       => $dateStart->format('H'),
              '@@$dateEndHours@@'       => $dateEnd->format('H\h\s')
            );

            $msj= strtr(Controlador::MSJ_ORDEN_MODIFICADA, $vars);
            $msj=str_replace("@diaCita@", $diaCita, $msj);
            $this->addMessageError($msj);
            return Dispatcher::MESSAGES_URL;
        }catch(Exception $e){
            Utils::logDebug('Hubo un error al reagendar la cita', $e);
            $this->addMessageError(Controlador::ERROR_GENERIC_MSJ);
            return Dispatcher::MESSAGES_URL;
        }
    }
    function excecuteScheduleConfirm(){
        try{
            $activityID=$this->getActivityIdFromContext();
            $activity=$this->findActivityData($activityID);
            //rawTimeslot Ej: 2018-08-01|AM
            $rawTimeslot=$_REQUEST[Controlador::SCHUEDULE_DATE_PARAM];
            $scheduleDate=substr($rawTimeslot, 0, strrpos($rawTimeslot, '|'));
            $params=array("setDate"=>array("date"=>$scheduleDate));
            $params=json_encode($params);
            //Se actualiza el dia
            $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId . '/custom-actions/move', 'POST', $params);

            
            $scheduleTimeslot=substr($rawTimeslot, strrpos($rawTimeslot, '|') + 1);
            $currentDateTime=date('Y-m-d H:i:s');
            $histroyReply=$activity->XA_HISTORY_REPLY . " | " . Controlador::SUB_STATUS_MODIFICADA_LABEL . ", " . Controlador::CHANEL_LABEL . ", " .  $currentDateTime;
            
            $params=array();
            
            $params["timeSlot"]= $scheduleTimeslot;
            $params["XA_REMINDER_REPLY"]= Controlador::SUB_STATUS_MODIFICADA;
            $params["XA_HISTORY_REPLY"]= $histroyReply;
            $params["XA_CONFIRMATIONCHANNEL"]= Controlador::CHANEL_LABEL;
            $params["XA_DATETIME_REPLY"]= $currentDateTime;
            $params["XA_PENDING_EXTERNAL_ACTION"]= Controlador::EXTERNAL_ACTION_RESCHEDULE;
            $modificacionesHechas = intval($activity->XA_Q_REAGENDAMIENTO);
            $modificacionesHechas++;
            $params["XA_Q_REAGENDAMIENTO"] = strval($modificacionesHechas);
            
            $params=json_encode($params);

            //Se actualiza el timeslot y el estado XA_REMINDER_REPLY
            $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId, 'PATCH', $params);

            $activity=$this->findActivityData($activityID);
            $dateStart = new DateTime($activity->date . ' ' . $activity->serviceWindowStart);
            $dateEnd = new DateTime($activity->date . ' ' . $activity->serviceWindowEnd);
            $diaCita= $dateStart->format('d') . ' de ' . $GLOBALS['translateMonth'][$dateStart->format('F')] . ' de ' .$dateStart->format('Y'). ', Jornada: ' . $activity->timeSlot . '(' . $dateStart->format('g:i A') . ' - ' . $dateEnd->format('g:i A') . ')';
            
            // buscar dateStart y dateEnd de la actividad
            $dateStart = new DateTime($activity->date . ' ' . $activity->serviceWindowStart);
            $dateEnd = new DateTime($activity->date . ' ' . $activity->serviceWindowEnd);

            $vars = array(
              '@@$dateFormatted@@'       => $dateStart->format('d-M-Y'),
              '@@$dateStartHours@@'       => $dateStart->format('H'),
              '@@$dateEndHours@@'       => $dateEnd->format('H\h\s')
            );

            $msj= strtr(Controlador::MSJ_ORDEN_MODIFICADA, $vars);
            $msj=str_replace("@diaCita@", $diaCita, $msj);
            $this->addMessageError($msj);
            return Dispatcher::MESSAGES_URL;
        }catch(Exception $e){
            Utils::logDebug('Hubo un error al reagendar la cita', $e);
            $this->addMessageError(Controlador::ERROR_GENERIC_MSJ);
            return Dispatcher::MESSAGES_URL;
        }        
    }

    function excecuteConfirmConfirm(){
        try{
            $activityID=$this->getActivityIdFromContext();
            $activity=$this->findActivityData($activityID);
            $currentDateTime=date('Y-m-d H:i:s');
            $histroyReply=$activity->XA_HISTORY_REPLY . " | " . Controlador::SUB_STATUS_CONFIRMADA_LABEL . ", " . Controlador::CHANEL_LABEL . ", " .  $currentDateTime;
            
            $params=array();

            $params["XA_REMINDER_REPLY"]= Controlador::SUB_STATUS_CONFIRMADA;
            $params["XA_CONFIRMATIONCHANNEL"]= Controlador::CHANEL_LABEL;
            $params["XA_DATETIME_REPLY"]= $currentDateTime;
            $params["XA_HISTORY_REPLY"]= $histroyReply;
            
            $params=json_encode($params);

            //Se actualiza el estado XA_REMINDER_REPLY
            $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId, 'PATCH', $params);
            
            // buscar dateStart y dateEnd de la actividad
            $dateStart = new DateTime($activity->date . ' ' . $activity->serviceWindowStart);
            $dateEnd = new DateTime($activity->date . ' ' . $activity->serviceWindowEnd);

            $vars = array(
              '@@$dateFormatted@@'       => $dateStart->format('d-M-Y'),
              '@@$dateStartHours@@'       => $dateStart->format('H'),
              '@@$dateEndHours@@'       => $dateEnd->format('H\h\s')
            );

            $this->addMessageError(strtr(Controlador::MSJ_ORDEN_CONFIRMADA, $vars));
            return Dispatcher::MESSAGES_URL;
        }catch(Exception $e){
            Utils::logDebug('Hubo un error al confirmar la cita', $e);
            $this->addMessageError(Controlador::ERROR_GENERIC_MSJ);
            return Dispatcher::MESSAGES_URL;
        }

    }
    function excecuteLocation(){
        $activityID=$this->getActivityIdFromContext();
        $activity=$this->findActivityData($activityID);
        $this->findTechnicanLocation($activity);
        return Dispatcher::LOCATION_URL;
    }
    function excecuteMenu(){
        try{
            $activityID=$this->getActivityIdFromContext();
            $activity=$this->findActivityData($activityID);
            
            $currentDateTime=date('Y-m-d H:i:s');
            $params=array();
            $params['XA_Q_CAMBIOS']= $activity->XA_Q_CAMBIOS . 'Acceso MENU ' . $currentDateTime . ' ';
            
            $params=json_encode($params);
            $this->service->request('/rest/ofscCore/v1/activities/' . $activityID, 'PATCH', $params);
            Utils::logDebug('Se registro el acceso al menu correctamente');
        } catch (Exception $e) {
            Utils::logDebug('Hubo un error al registrar la actividad del usuario', $e);
        }
        return Dispatcher::MENU_URL;
    }
    function existActivity(){
        try{
            Utils::logDebug('INICIO existActivity');
            foreach($_GET as $key=>$val) {
                $activityID=$this->desencriptar_AES($key);
                break;
            }
            if (!isset($activityID)){
                $activityID=$this->getActivityIdFromContext();
            }
            if (!isset($activityID)){
                //Expiramos la cookie
                setcookie(Controlador::ACTIVITY_PARAM, $activityID,time()-3600);
                return false;
            }else{
                $activity=$this->findActivityData($activityID);
                if (!isset($activity)){
                    //Expiramos la cookie
                    setcookie(Controlador::ACTIVITY_PARAM, $activityID,time()-3600);
                    return false;
                }
            }
            setcookie(Controlador::ACTIVITY_PARAM, $activityID);
            return true;
        }catch(Exception $e){
            Utils::logDebug('Error en existActivity ', $e);
            return false;
        }
    }

    function isValidActivity(){
        try{
            $activityID=$this->getActivityIdFromContext();
            $activity=$this->findActivityData($activityID);
            if(!isset($activity)
                || !isset($activity->date)
                || !isset($activity->startTime)
                || !isset($activity->status)){
                    return false;
            }

            $isVigente= ($this->showTechnicanLocation() || $this->showCancel()|| $this->showConfirm()|| $this->showSchedule());
            Utils::logDebug('isValid: ' . ($isVigente));
            return $isVigente;
        }catch(Exception $e){
            Utils::logDebug('Error en isValidActivity ', $e);
            return false;
        }
    }
    function showConfirm(){
        $activityID=$this->getActivityIdFromContext();
        $activity=$this->findActivityData($activityID);
        $activityDate = DateTime::createFromFormat('Y-m-d', $activity->date);
        $currentDate=DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
 
        return ($activity->status == Controlador::STATUS_PENDING) && ($activityDate > $currentDate);
    }
    function showCancel(){
        $activityID=$this->getActivityIdFromContext();
        $activity=$this->findActivityData($activityID);
        $activityDate = DateTime::createFromFormat('Y-m-d', $activity->date);
        $currentDate=DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
 
        return ($activity->status == Controlador::STATUS_PENDING) && ($activityDate >= $currentDate);
    }
    function showSchedule(){
        return $this->showConfirm();
    }
    function showTechnicanLocation(){
        $activityID=$this->getActivityIdFromContext();
        $activity=$this->findActivityData($activityID);
        $locationData=$this->findTechnicanLocation($activity);
        $activityDate = DateTime::createFromFormat('Y-m-d', $activity->date);
        $currentDate=DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
        $currentDate=$currentDate->format("Y-m-d");
        $activityDate=$activityDate->format("Y-m-d");
        
        $isValidState=in_array($locationData->status, Controlador::STATUS_LOCALIZABLE);
        $isCurrentDate=$activityDate == $currentDate;
        
        Utils::logDebug("isValidState: " . $isValidState?'true':'false');
        Utils::logDebug("isCurrentDate: " . $isCurrentDate?'true':'false');
        
        return $isValidState && $isCurrentDate;
    }
    function addMessageError($msj){
        $_REQUEST[Controlador::MESSAGE_PARAM]=$msj;
    }
    function getActivityIdFromContext(){
        Utils::logDebug('INICIO getActivityIdFromContext');
        foreach($_GET as $key=>$val) {
            Utils::logDebug('Se va a desencriptar');
            Utils::logDebug($key);

            $activityID=$this->desencriptar_AES($key);

            break;
        }
        if (!isset($activityID)){
            $activityID=$_COOKIE[Controlador::ACTIVITY_PARAM];
        }
        return $activityID;
    }
    function desencriptar_AES($encrypted_data_hex)
    {
        try {
            Utils::logDebug('id actividad encriptado');
            Utils::logDebug($encrypted_data_hex);
            $cipher = new RightNow\Connect\Crypto\v1_3\AES();
            $cipher->Mode->ID =1;
            $cipher->IV->Value = 'p0r7417313f0n1c4';
            $cipher->KeySize->LookupName = "128_bits";
            $cipher->Key = 'p0r7417313f0n1c4';
            $cipher->EncryptedText =$this->base64url_decode($encrypted_data_hex.'==');
            
            $cipher->decrypt();
            $decrypted_text = $cipher->Text;
            Utils::logDebug('id actividad desencriptado: ');
            Utils::logDebug($decrypted_text);

            return $decrypted_text;
        }catch (Exception $err ) {
            Utils::logDebug('Hubo un error al desencriptar la actividad');
            // $log = new RNCPHP\CO\LOG();
            // $log->LOG = json_encode($err);
            // $log->save();
            Utils::logDebug($encrypted_data_hex, $err);
            return null;
        }
    }
    function base64url_encode( $data ){
        return rtrim( strtr( base64_encode( $data ), '+/', ':_'), '=');
    }
    
    function base64url_decode( $data ){
        return base64_decode( strtr( $data, ':_', '+/') . str_repeat('=', 3 - ( 3 + strlen( $data )) % 4 ));
    }
    function isAveriaOProvision($activity){
        if(strpos($activity->activityType, 'PRO')===0){
            return Controlador::PROVISION_LABEL;
        }else if(strpos($activity->activityType, 'REP')===0){
            return Controlador::AVERIA_LABEL;
        }
        return NULL;
    }

    function getActivityType(){
        $activityID=$this->getActivityIdFromContext();
        $activity=$this->findActivityData($activityID);
        return $activity->activityType;
    }
}
?>
<head>
</head>
<body>
</body>