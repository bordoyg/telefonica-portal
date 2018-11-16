<?php
require_once(APPPATH . 'widgets/custom/library/serviceRest.php');
require_once(APPPATH . 'widgets/custom/library/serviceSoap.php');

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
	
	const ERROR_GENERIC_MSJ="Debido a un problema t&eacute;cnico no podemos procesar tu solicitud en este momento. Por favor intenta lo nuevamente m&aacute;s tarde. Si tienes alguna inquietud puedes comunicarte a la l&iacute;nea 01 80009 969090.";
	const ERROR_ORDEN_INEXISTENTE="La orden no existe";
	const ERROR_ORDEN_NO_VIGENTE="Tu cita no puede ser confirmada o modificada debido a que no se encuentra vigente en este momento. Si tienes alguna inquietud puedes comunicarte a la l&iacute;nea 01 80009 969090.";
	
	const MSJ_ORDEN_CONFIRMADA="Gracias por confirmar tu cita. No olvides tramitar la autorizaci&oacute;n para el ingreso del t&eacute;cnico a tu domicilio";
	const MSJ_ORDEN_MODIFICADA="Tu cita para la instalaci&oacute;n de los servicios Movistar ha sido modificada. @diaCita@. No olvides tramitar la autorizaci&oacute;n para el ingreso del t&eacute;cnico a tu domicilio";
	const MSJ_ORDEN_CANCELADA="De acuerdo a tu elecci&oacute;n tu cita ha sido cancelada. Podr&aacute;s reprogramarla posteriormente comunic&aacute;ndote a la l&iacute;nea de atenci&oacute;n gratuita 01 80009 969090";
	
	const SUB_STATUS_CANCELADA="CANCELADA";
	const SUB_STATUS_CONFIRMADA="CONFIRMADA";
	const SUB_STATUS_MODIFICADA="MODIFICADA";
	
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
         
         if(in_array($locationData->status, Controlador::STATUS_LOCALIZABLE)){
             if(isset($locationData->coordinates) && isset($locationData->coordinates->latitude) && isset($locationData->coordinates->longitude)){
                 $lat=$locationData->coordinates->latitude;
                 $lng=$locationData->coordinates->longitude;
                 $_REQUEST[Controlador::LOCATION_TECHNICAN_PARAM]=  $lng. ',' . $lat ;
             }else{
                 $this->addMessageError("No se puedo establecer la ubicacion del t&eacute;cnico, intenta mas tarde");
             }
         }else{
             $this->addMessageError("No se puedo establecer la ubicacion del t&eacute;cnico, intenta mas tarde");
         }
         
    }
    function findAvailabilitySOAP($days) {
        $activityID=$_COOKIE[Controlador::ACTIVITY_PARAM];
        $activity=$this->findActivityData($activityID);
        
        //Genero los dias para solicitar disponibilidad
        $date = date("Y-m-d");
        $params=array();
        for($i=0; $i<$days - 1; $i++){
            $newDate = strtotime($date."+ 1 days");
            $date = date("Y-m-d",$newDate);

            array_push($params, array('date'=>$date));
        }
        
        array_push($params, array('calculate_duration'=>true));
        array_push($params, array('calculate_travel_time'=>true));
        array_push($params, array('calculate_work_skill'=>true));
        array_push($params, array('return_time_slot_info'=>true));
        array_push($params, array('determine_location_by_work_zone'=>true));
        array_push($params, array('dont_aggregate_results'=>true));
        array_push($params, array('min_time_to_end_of_time_slot'=>0));
        array_push($params, array('activity_field'=>array('name'=>'XA_WORK_ZONE_KEY', 'value'=>$activity->XA_WORK_ZONE_KEY)));
        array_push($params, array('activity_field'=>array('name'=>'XA_QUADRANT', 'value'=>$activity->XA_QUADRANT)));
        array_push($params, array('activity_field'=>array('name'=>'XA_ACCESS_TECHNOLOGY', 'value'=>$activity->XA_ACCESS_TECHNOLOGY)));
        array_push($params, array('activity_field'=>array('name'=>'worktype_label', 'value'=>$activity->activityType)));
        array_push($params, array('activity_field'=>array('name'=>'XA_WORK_TYPE', 'value'=>$activity->XA_WORK_TYPE)));
        array_push($params, array('activity_field'=>array('name'=>'XA_NUMBER_DECODERS', 'value'=>0)));
        array_push($params, array('activity_field'=>array('name'=>'XA_CUSTOMER_SEGMENT', 'value'=>$activity->XA_CUSTOMER_SEGMENT)));
        array_push($params, array('activity_field'=>array('name'=>'XA_ESTRATO', 'value'=>$activity->XA_ESTRATO)));
        array_push($params, array('activity_field'=>array('name'=>'XA_VELOCIDAD', 'value'=>$activity->XA_VELOCIDAD)));
        array_push($params, array('activity_field'=>array('name'=>'XA_NOT_ACCOMPLISHED', 'value'=>0)));
        
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
                
                if($availableQuota>$activityDuration){
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
    function findAvailability($days) {
        $activityID=$_COOKIE[Controlador::ACTIVITY_PARAM];
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
            $this->addMessageError($e->getMessage());
            return null;
        }
    }
    function createCalendar($days){
        try{
            $calendar=array();
            $firstDay=new DateTime();
            $firstDay->setDate(date("Y"), date("m"), 1);
            $dayWeekFirstDay=$firstDay->format("w");
            $firstDayCalendar=$firstDay->sub(new DateInterval("P" . $dayWeekFirstDay . "D"));
            try{
                $availability=$this->findAvailabilitySOAP($days);
            }catch(Exception $e){
                Utils::logDebug('Hubo un error al buscar los dias habilitados', $e);
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
            return Dispatcher::MESSAGES_URL;
        }
        
        
        return $calendar;
    }
    
    function excecuteCancelConfirm(){
        try {
            $activityID=$_COOKIE[Controlador::ACTIVITY_PARAM];
            $activity=$this->findActivityData($activityID);
          
            $params=array("setDate"=>array("date"=>NULL));
            $params=json_encode($params);
            //Se actualiza el dia = null
            $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId . '/custom-actions/move', 'POST', $params);

            $params=array("timeSlot"=>NULL, "XA_CONFIRMACITA"=>Controlador::SUB_STATUS_CANCELADA);
            $params=json_encode($params);
            //Se actualiza el timeslot y el estado XA_CONFIRMACITA
            $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId, 'PATCH', $params);
            
            $this->addMessageError(Controlador::MSJ_ORDEN_CANCELADA);
            return Dispatcher::MESSAGES_URL;
        } catch (Exception $e) {
            Utils::logDebug('Hubo un error al cancelar la cita', $e);
            $this->addMessageError(Controlador::ERROR_GENERIC_MSJ);
            return Dispatcher::MESSAGES_URL;
        }
    }
    function excecuteScheduleCalendar(){

        return Dispatcher::SCHEDULE_DATE_URL;
    }
    function excecuteScheduleConfirmSOAP(){
        try{
            $activityID=$_COOKIE[Controlador::ACTIVITY_PARAM];
            $activity=$this->findActivityData($activityID);
            //rawTimeslot Ej: 2018-08-01|AM
            $rawTimeslot=$_REQUEST[Controlador::SCHUEDULE_DATE_PARAM];
            $scheduleDate=substr($rawTimeslot, 0, strrpos($rawTimeslot, '|'));
            $scheduleTimeslot=substr($rawTimeslot, strrpos($rawTimeslot, '|') + 1);
            //Se actualiza el timeslot, date y el estado XA_CONFIRMACITA
            
            $params=array();
            array_push($params, array('activity_id'=>$activityID));
            array_push($params, array('position_in_route'=>'unchanged'));
            array_push($params, array('properties'=>array('name'=>'XA_CONFIRMACITA', 'value'=>Controlador::SUB_STATUS_MODIFICADA)));
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
            
            $msj= Controlador::MSJ_ORDEN_MODIFICADA;
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
            $activityID=$_COOKIE[Controlador::ACTIVITY_PARAM];
            $activity=$this->findActivityData($activityID);
            //rawTimeslot Ej: 2018-08-01|AM
            $rawTimeslot=$_REQUEST[Controlador::SCHUEDULE_DATE_PARAM];
            $scheduleDate=substr($rawTimeslot, 0, strrpos($rawTimeslot, '|'));
            $params=array("setDate"=>array("date"=>$scheduleDate));
            $params=json_encode($params);
            //Se actualiza el dia
            $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId . '/custom-actions/move', 'POST', $params);

            
            $scheduleTimeslot=substr($rawTimeslot, strrpos($rawTimeslot, '|') + 1);
            $params=array("timeSlot"=>$scheduleTimeslot, "XA_CONFIRMACITA"=>Controlador::SUB_STATUS_MODIFICADA);
            $params=json_encode($params);
            //Se actualiza el timeslot y el estado XA_CONFIRMACITA
            $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId, 'PATCH', $params);

            $activity=$this->findActivityData($activityID);
            $dateStart = new DateTime($activity->date . ' ' . $activity->serviceWindowStart);
            $dateEnd = new DateTime($activity->date . ' ' . $activity->serviceWindowEnd);
            $diaCita= $dateStart->format('d') . ' de ' . $GLOBALS['translateMonth'][$dateStart->format('F')] . ' de ' .$dateStart->format('Y'). ', Jornada: ' . $activity->timeSlot . '(' . $dateStart->format('g:i A') . ' - ' . $dateEnd->format('g:i A') . ')';
            
            $msj= Controlador::MSJ_ORDEN_MODIFICADA;
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
            $activityID=$_COOKIE[Controlador::ACTIVITY_PARAM];
            $activity=$this->findActivityData($activityID);
            $params=array("XA_CONFIRMACITA"=>Controlador::SUB_STATUS_CONFIRMADA);
            $params=json_encode($params);
            //Se actualiza el estado XA_CONFIRMACITA
            $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId, 'PATCH', $params);
            
            $this->addMessageError(Controlador::MSJ_ORDEN_CONFIRMADA);
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
        return Dispatcher::MENU_URL;
    }
    function existActivity(){
        try{
            $activityID=isset($_GET[Controlador::ACTIVITY_PARAM]) ? $_GET[Controlador::ACTIVITY_PARAM] : null ;
            if (!isset($activityID)){
                $activityID=$_COOKIE[Controlador::ACTIVITY_PARAM];
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
                || !isset($activity->startTime)
                || !isset($activity->status)
                || !isset($activity->XA_ROUTE)){
                    return false;
            }
            $dtCurrent= new DateTime("now");
            $dtETA=new DateTime();
            $dtETA=$dtETA->createFromFormat("Y-m-d H:i:s", $activity->startTime);
            
            Utils::logDebug("Current: " . $dtCurrent->format("Y-m-d H:i:s"));
            Utils::logDebug("dtETA: " . $dtETA->format("Y-m-d H:i:s"));
            
            $interval=$dtCurrent->diff($dtETA, false);
            $intervalInSeconds = (new DateTime())->setTimeStamp(0)->add($interval)->getTimeStamp();
            $intervalInMinutes = $intervalInSeconds/60;
            
            Utils::logDebug("interval en minutos: " . $intervalInMinutes);
            Utils::logDebug('Estado localizable: ' . in_array($activity->status, Controlador::STATUS_VIGENTE));
            Utils::logDebug('XA_ROUTE: ' . (strcmp($activity->XA_ROUTE,"1")==0));
            Utils::logDebug('interval mayor a 20: ' . ($intervalInMinutes>=20));
            
            $isVigente= in_array($activity->status, Controlador::STATUS_VIGENTE) && (strcmp($activity->XA_ROUTE, "1")==0) && $intervalInMinutes>=0;
            Utils::logDebug('isVigente: ' . ($isVigente));
            if($isVigente){
                //Guardamos la url de acceso en el campo XA_PROJECT_CODE
                $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]?". implode($_SERVER['argv']);
                if($actual_link!=$activity->XA_PROJECT_CODE){
                    $params=array("XA_PROJECT_CODE"=>$actual_link);
                    $params=json_encode($params);
                    try{
                        //Se actualiza el estado XA_PROJECT_CODE
                        $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId, 'PATCH', $params);
                    }catch(Exception $e){
                        Utils::logDebug('Hubo un error al guardar el campo XA_PROJECT_CODE con el valor: ' . $actual_link, $e);
                    }
                }
            }
            return $isVigente;
        }catch(Exception $e){
            Utils::logDebug('Error en isValidActivity ', $e);
            return false;
        }
    }
    function showConfirm(){
        return $this->showCancel();
    }
    function showCancel(){
        $activityID=$this->getActivityIdFromContext();
        $activity=$this->findActivityData($activityID);
        $activityDate = DateTime::createFromFormat('Y-m-d', $activity->date, new DateTimeZone($activity->timeZone));
        $currentDate=DateTime::createFromFormat('Y-m-d', date('Y-m-d'), new DateTimeZone($activity->timeZone));
 
        return ($activity->status == Controlador::STATUS_PENDING) && ($activityDate > $currentDate);
    }
    function showSchedule(){
        return $this->showCancel();
    }
    function showTechnicanLocation(){
        $activityID=$this->getActivityIdFromContext();
        $activity=$this->findActivityData($activityID);
        $activityDate = DateTime::createFromFormat('Y-m-d', $activity->date, new DateTimeZone($activity->timeZone));
        $currentDate=DateTime::createFromFormat('Y-m-d', date('Y-m-d'), new DateTimeZone($activity->timeZone));
        $currentDate=$currentDate->format("Y-m-d");
        $activityDate=$activityDate->format("Y-m-d");
        Utils::logDebug("SHOWTECHNICANLOCATION: " . in_array($activity->status, Controlador::STATUS_LOCALIZABLE) . " - " . $activityDate == $currentDate);
        return /*in_array($activity->status, Controlador::STATUS_LOCALIZABLE) &&*/ $activityDate == $currentDate;
    }
    function addMessageError($msj){
        $_REQUEST[Controlador::MESSAGE_PARAM]=$msj;
    }
    function getActivityIdFromContext(){
        $activityID=isset($_GET[Controlador::ACTIVITY_PARAM]) ? $_GET[Controlador::ACTIVITY_PARAM] : null ;
        if (!isset($activityID)){
            $activityID=$_COOKIE[Controlador::ACTIVITY_PARAM];
        }
        return $activityID;
    }
    function desencriptar_AES($encrypted_data_hex, $key)
    {
        try {
            $cipher = new RightNow\Connect\Crypto\v1_3\AES();
            $cipher->Mode->ID =1;
            $cipher->IV->Value = 'p0r7417313f0n1c4';
            $cipher->KeySize->LookupName = "128_bits";
            $cipher->Key = 'p0r7417313f0n1c4';
            $cipher->Text = $encrypted_data_hex;
            
//             echo "Text to be encrypted : " .$cipher->Text . "<br>";
//             $cipher->Padding->Id = 2;
//             $cipher->encrypt();
//             $encrypted_text = $cipher->EncryptedText;
//             echo "Encrypted Text : " .base64_encode($encrypted_text)."<br>";
            
            $cipher->decrypt();
            $decrypted_text = $cipher->Text;
//            echo "Decrypted Text : " .$decrypted_text;
        }
        catch (Exception $err ) {
            Utils::logDebug('Hubo un error al desencriptar la actividad: ' . $encrypted_data_hex, $e);
        }
    }
}
?>
<head>
</head>
<body>
</body>