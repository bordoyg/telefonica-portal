<?php
require_once(APPPATH . 'widgets/custom/library/serviceRest.php');
require_once(APPPATH . 'widgets/custom/library/serviceSoap.php');

class Controlador {
    const MESSAGE_PARAM = 'errorMessage';
    const MESSAGES_MODAL_URL = 'messagesModal.php';
    const ACTIVITY_PARAM = 'activity';
    const LOCATION_TECHNICAN_LON_PARAM='locationTechnicanLon';
    const LOCATION_TECHNICAN_LAT_PARAM='locationTechnicanLat';
    const LOCATION_CUSTOMER_LON_PARAM='locationCustomerLon';
    const LOCATION_CUSTOMER_LAT_PARAM='locationCustomerLat';
    const LOCATION_TECHNICAN="technicanLocation";
    const SCHUEDULE_DATE_PARAM='timeSlot';
    const STATUS_LOCALIZABLE=array("onTheWay", "started");
    const STATUS_VIGENTE=array("onTheWay", "started", "pending");
    const STATUS_PENDING="pending";
    
    const ERROR_GENERIC_MSJ="<h1 class=\"resalt\">ERROR</h1> <p>No fue posible procesar tu solicitud, por favor int&eacute;ntalo m&aacute;s tarde.</p> ";
    const ERROR_ORDEN_INEXISTENTE="La orden no existe";
    const ERROR_ORDEN_NO_VIGENTE="La fecha de tu cita con ETB ha expirado. Para volver agendar tu cita comun&iacute;cate al 3777777";
    const ERROR_REAGENDAR_MSJ="<h1 class=\"resalt\">ERROR DE AGENDAMIENTO</h1> <p>No fue posible reagendar tu cita. Por favor inténtelo más tarde.</p>";
    
    const MSJ_ORDEN_CONFIRMADA="<h1>Cita Confirmada</h1><p>Tu cita fue confirmada para el dia </p>@diaCita@<p>¡MUCHAS GRACIAS!</p>";
    const MSJ_ORDEN_MODIFICADA="<h1>Cita Reagendada</h1><p>La nueva fecha para tu cita es</p>@diaCita@<p>¡MUCHAS GRACIAS!</p>";
    const MSJ_ORDEN_CANCELADA="<h1>Cita Cancelada</h1><p>Su cita fue Cancelada</p><h2>@diaCita@</h2><p> s&iacute; requiere agendar una nueva cita por favor comun&iacute;quese a nuestra l&iacute;nea de atenci&oacute;n 3777777</p>";
    const MSJ_CANCELACION_NOPUEDOATENDER="<h1>Cita Cancelada</h1><p>Su cita fue Cancelada</p><h2>@diaCita@</h2><p>Por favor comunicarse con el operador logístico. Línea fija en Bogotá (1)3558170 o al WhatsApp 323-2056558</p>";
    const MSJ_LIMITE_CANCELACIONES="<h1>Cita no Cancelada</h1><p>Su cita fue Cancelada</p><h2>@diaCita@</h2><p>Llegó al límite de cancelaciones, y entonces los equipos serán cobrados</p>";
    const MSJ_LIMITE_MODIFICACIONES="<h1>Cita no Reagendada</h1><p>Su cita no fue Reagendada</p><h2>@diaCita@</h2><p>Llegó al límite de reagendamientos, y entonces los equipos serán cobrados</p>";
    
    
    const SUB_STATUS_CANCELADA="CANCELADA";
    const SUB_STATUS_CONFIRMADA="CONFIRMADA";
    const SUB_STATUS_MODIFICADA="MODIFICADA";
    
    const APROVISIONAMIENTO="APROV";
    const ASEGURAMIENTO="ASEG";
    const RECUPERO="REC";
    
    const APROVISIONAMIENTO_VALUES=array('INS','COB_INS', 'FTTC_INS');
    const ASEGURAMIENTO_VALUES=array('MOD','COB_MOD');
    const RECUPERO_VALUES=array('RET','FTTC_RET');
    
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
            $_REQUEST[Controlador::LOCATION_CUSTOMER_LON_PARAM]=$activity->longitude;
            $_REQUEST[Controlador::LOCATION_CUSTOMER_LAT_PARAM]=$activity->latitude;
        }
        
        //Obtenemos la posicion del tecnico
        $locationData=$this->service->request('/rest/ofscCore/v1/whereIsMyTech', 'GET', 'activityId=' . $activity->activityId . '&includeAvatarImageData=true');
        $_REQUEST[Controlador::LOCATION_TECHNICAN]=$locationData;
        
        
        if(isset($locationData->coordinates) && isset($locationData->coordinates->latitude) && isset($locationData->coordinates->longitude)){
            $_REQUEST[Controlador::LOCATION_TECHNICAN_LON_PARAM]= $locationData->coordinates->longitude;
            $_REQUEST[Controlador::LOCATION_TECHNICAN_LAT_PARAM]= $locationData->coordinates->latitude;
        }else{
            $this->addMessageError("No se puedo establecer la ubicacion del t&eacute;cnico, intenta mas tarde");
        }
        
        return $locationData;
    }
    
    function findTechnicanLocationJson(){
        
        $activityID=$this->getActivityIdFromContext();
        $activity=$this->findActivityData($activityID);
        $coordinatesJson="";
        $locationData=$this->service->request('/rest/ofscCore/v1/whereIsMyTech', 'GET', 'activityId=' . $activity->activityId . '&includeAvatarImageData=true');
        
        /*---DummyData---*/
        $dummy = date('s', time());
        $dummy= $dummy /10;
        
        $dummyX=intval($locationData->coordinates->longitude) +intval($dummy);
        $dummyY=intval($locationData->coordinates->latitude) +intval($dummy);
        
        $dummyX=strval($dummyX);
        $dummyY=strval($dummyY);
        
        
        
        $coordinatesJson="<coordinates><x>".$dummyX."</x><y>".$dummyY."</y></coordinates>";
        /*---End Dummy---*/
        
        // $coordinatesJson='{'.$locationData->coordinates->longitude.','.$locationData->coordinates->latitude.'}';
        
        return $coordinatesJson;
    }
    
    function findAvailabilitySOAP($days) {
        $activityID=$this->getActivityIdFromContext();
        $activity=$this->findActivityData($activityID);
        
        //Genero los dias para solicitar disponibilidad
        $date = date("Y-m-d");
        $params=array();
        for($i=0; $i<$days - 1; $i++){
            $newDate = strtotime($date."+ 1 days");
            $date = date("Y-m-d",$newDate);
            
            array_push($params, array('date'=>$date));
        }
        
        array_push($params, array('calculate_duration'=>false));
        array_push($params, array('calculate_travel_time'=>false));
        array_push($params, array('calculate_work_skill'=>true));
        array_push($params, array('work_skill'=>$activity->XA_ACT_WORKSKILL));
        array_push($params, array('location'=>$activity->XA_Zone));
        array_push($params, array('return_time_slot_info'=>true));
        
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
    
    function excecuteCancelConfirm(){
        try {
            $activityID=$this->getActivityIdFromContext();
            $activity=$this->findActivityData($activityID);
            
            $detectedAdctivityType = $this->isAprovisionamientoAseguramientoRecupero($activity);
            $cancelacionesHechas = intval($activity->XA_NUM_MOD_PORTAL);
            $cancelacionesPermitidas = intval($GLOBALS['config']['cacelacionesPermitidas']);
            if( $detectedAdctivityType != null && strcmp($detectedAdctivityType, Controlador::RECUPERO) ){
                
                if( $cancelacionesHechas >= $cancelacionesPermitidas ){
                    $this->addMessageError(Controlador::MSJ_LIMITE_CANCELACIONES);
                    return Dispatcher::MESSAGES_URL;
                }
            }
            
            $motivoCancelacion = isset($_REQUEST[Dispatcher::MOTIVO_CANCELACION_PARAM]) ? $_REQUEST[Dispatcher::MOTIVO_CANCELACION_PARAM] : null ;
            
            $params=array("setDate"=>array("date"=>NULL));
            $params=json_encode($params);
            
            //Se actualiza el dia = null
            $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId . '/custom-actions/move', 'POST', $params);
            $params=array("timeSlot"=>NULL, "XA_CONFIRMACITA"=>Controlador::SUB_STATUS_CANCELADA, "cancel_reason"=>$motivoCancelacion, "XA_CANCELADA_PORTAL"=>"S");
            
            if( $detectedAdctivityType != null && strcmp($detectedAdctivityType, Controlador::RECUPERO) ){
                $cancelacionesHechas++;
                $params["XA_NUM_MOD_PORTAL"] = strval($cancelacionesHechas);
            }
            $params=json_encode($params);
            //Se actualiza el timeslot y el estado XA_CONFIRMACITA
            $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId, 'PATCH', $params);
            
            $dateStart = new DateTime($activity->date . ' ' . $activity->serviceWindowStart);
            $diaCita= $dateStart->format('d') . ' - ' . $GLOBALS['translateMonth'][$dateStart->format('F')] . ' - ' .$dateStart->format('Y');
            
            $msj= Controlador::MSJ_ORDEN_CANCELADA;
            $msj=str_replace("@diaCita@", $diaCita, $msj);
            $this->addMessageError($msj);
            return Dispatcher::MESSAGES_URL;
            
        } catch (Exception $e) {
            Utils::logDebug('Hubo un error al cancelar la cita', $e);
            $this->addMessageError(Controlador::ERROR_GENERIC_MSJ);
            return Dispatcher::MESSAGES_URL;
        }
    }
    
    function excecuteNoPuedoAtenderConfirm(){
        try {
            $activityID=$this->getActivityIdFromContext();
            $activity=$this->findActivityData($activityID);
            
            $detectedAdctivityType = $this->isAprovisionamientoAseguramientoRecupero($activity);
            $cancelacionesHechas = intval($activity->XA_NUM_MOD_PORTAL);
            $cancelacionesPermitidas = intval($GLOBALS['config']['cacelacionesPermitidas']);
            if( $detectedAdctivityType != null && strcmp($detectedAdctivityType, Controlador::RECUPERO) ){
                
                if( $cancelacionesHechas >= $cancelacionesPermitidas ){
                    $this->addMessageError(Controlador::MSJ_LIMITE_CANCELACIONES);
                    return Dispatcher::MESSAGES_URL;
                }
            }
            
            $params=array("setDate"=>array("date"=>NULL));
            $params=json_encode($params);
            
            //Se actualiza el dia = null
            $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId . '/custom-actions/move', 'POST', $params);
            $params=array("timeSlot"=>NULL, "XA_CONFIRMACITA"=>Controlador::SUB_STATUS_CANCELADA, "XA_DESPR_PORTAL"=>"S");
            
            if( $detectedAdctivityType != null && strcmp($detectedAdctivityType, Controlador::RECUPERO) ){
                $cancelacionesHechas++;
                $params["XA_NUM_MOD_PORTAL"] = strval($cancelacionesHechas);
                
            }
            $params=json_encode($params);
            //Se actualiza el timeslot y el estado XA_CONFIRMACITA, XA_DESPR_PORTAL
            $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId, 'PATCH', $params);
            
            $dateStart = new DateTime($activity->date . ' ' . $activity->serviceWindowStart);
            $diaCita= $dateStart->format('d') . ' - ' . $GLOBALS['translateMonth'][$dateStart->format('F')] . ' - ' .$dateStart->format('Y');
            
            $msj= Controlador::MSJ_CANCELACION_NOPUEDOATENDER;
            $msj=str_replace("@diaCita@", $diaCita, $msj);
            $this->addMessageError($msj);
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
            $activityID=$this->getActivityIdFromContext();
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
            $this->addMessageError(Controlador::ERROR_REAGENDAR_MSJ);
            return Dispatcher::MESSAGES_URL;
        }
    }
    function excecuteScheduleConfirm(){
        try{
            $activityID=$this->getActivityIdFromContext();
            $activity=$this->findActivityData($activityID);
            
            $detectedAdctivityType = $this->isAprovisionamientoAseguramientoRecupero($activity);
            $modificacionesHechas = intval($activity->XA_NUM_MOD_PORTAL);
            $modificacionesPermitidas = intval($GLOBALS['config']['cacelacionesPermitidas']);
            if( $detectedAdctivityType != null && strcmp($detectedAdctivityType, Controlador::RECUPERO) ){
                
                if( $modificacionesHechas >= $modificacionesPermitidas ){
                    $this->addMessageError(Controlador::MSJ_LIMITE_MODIFICACIONES);
                    return Dispatcher::MESSAGES_URL;
                }
            }
            //rawTimeslot Ej: 2018-08-01|AM
            $rawTimeslot=$_REQUEST[Controlador::SCHUEDULE_DATE_PARAM];
            $scheduleDate=substr($rawTimeslot, 0, strrpos($rawTimeslot, '|'));
            $params=array("setDate"=>array("date"=>$scheduleDate));
            $params=json_encode($params);
            //Se actualiza el dia
            $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId . '/custom-actions/move', 'POST', $params);
            
            if( $detectedAdctivityType != null && strcmp($detectedAdctivityType, Controlador::RECUPERO) ){
                $modificacionesHechas++;
                $params["XA_NUM_MOD_PORTAL"] = strval($modificacionesHechas);
                
            }
            
            $scheduleTimeslot=substr($rawTimeslot, strrpos($rawTimeslot, '|') + 1);
            $params=array("timeSlot"=>$scheduleTimeslot, "XA_CONFIRMACITA"=>Controlador::SUB_STATUS_MODIFICADA, "XA_REAGENDA_PORTAL"=>'S');
            $params=json_encode($params);
            //Se actualiza el timeslot y el estado XA_CONFIRMACITA y XA_REAGENDA_PORTAL
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
            $this->addMessageError(Controlador::ERROR_REAGENDAR_MSJ);
            return Dispatcher::MESSAGES_URL;
        }
    }
    
    function excecuteConfirmConfirm(){
        try{
            $activityID=$this->getActivityIdFromContext();
            $activity=$this->findActivityData($activityID);
            $params=array("XA_CONFIRMACITA"=>Controlador::SUB_STATUS_CONFIRMADA);
            $params=json_encode($params);
            //Se actualiza el estado XA_CONFIRMACITA
            $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId, 'PATCH', $params);
            
            $dateStart = new DateTime($activity->date . ' ' . $activity->serviceWindowStart);
            $dateEnd = new DateTime($activity->date . ' ' . $activity->serviceWindowEnd);
            $diaCita= '<h2>' . $dateStart->format('d') . '-' . $GLOBALS['translateMonth'][$dateStart->format('F')] . '-' .$dateStart->format('Y'). '</h2><p>entre las ' . $dateStart->format('g:i A') . ' y las ' . $dateEnd->format('g:i A') . ' hrs.</p>';
            
            $msj= Controlador::MSJ_ORDEN_CONFIRMADA;
            $msj=str_replace("@diaCita@", $diaCita, $msj);
            $this->addMessageError($msj);
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
            $activityID=$this->getActivityIdFromContext();
            
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
            //             $dtCurrent= new DateTime("now");
            //             $dtETA=new DateTime();
            //             $dtETA=$dtETA->createFromFormat("Y-m-d H:i:s", $activity->startTime);
            
            //             Utils::logDebug("Current: " . $dtCurrent->format("Y-m-d H:i:s"));
            //             Utils::logDebug("dtETA: " . $dtETA->format("Y-m-d H:i:s"));
            
            //             $interval=$dtCurrent->diff($dtETA, false);
            //             $intervalInSeconds = (new DateTime())->setTimeStamp(0)->add($interval)->getTimeStamp();
            //             $intervalInMinutes = $intervalInSeconds/60;
            
            //             Utils::logDebug("interval en minutos: " . $intervalInMinutes);
            //             Utils::logDebug('Estado localizable: ' . in_array($activity->status, Controlador::STATUS_VIGENTE));
            //             Utils::logDebug('XA_ROUTE: ' . (strcmp($activity->XA_ROUTE,"1")==0));
            //             Utils::logDebug('interval mayor a 20: ' . ($intervalInMinutes>=20));
            
            $isVigente= ($this->showTechnicanLocation() || $this->showCancel());
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
        if(strcmp($GLOBALS['config']['habilitarCancelacion'],"1")!=0){
            return false;
        }
        $activityID=$this->getActivityIdFromContext();
        $activity=$this->findActivityData($activityID);
        $activityDate = DateTime::createFromFormat('Y-m-d', $activity->date, new DateTimeZone($activity->timeZoneIANA));
        $currentDate=DateTime::createFromFormat('Y-m-d', date('Y-m-d'), new DateTimeZone($activity->timeZoneIANA));
        
        return ($activity->status == Controlador::STATUS_PENDING) && ($activityDate >= $currentDate) && !$this->showTechnicanLocation();
    }
    function showSchedule(){
        if(strcmp($GLOBALS['config']['habilitarModificacion'],"1")!=0){
            return false;
        }
        return $this->showCancel();
    }
    function showTechnicanLocation(){
        if(strcmp($GLOBALS['config']['habilitarTecnicoEnCamino'],"1")!=0){
            return false;
        }
        $activityID=$this->getActivityIdFromContext();
        $activity=$this->findActivityData($activityID);
        $locationData=$this->findTechnicanLocation($activity);
        $activityDate = DateTime::createFromFormat('Y-m-d', $activity->date, new DateTimeZone($activity->timeZoneIANA));
        $currentDate=DateTime::createFromFormat('Y-m-d', date('Y-m-d'), new DateTimeZone($activity->timeZoneIANA));
        $currentDate=$currentDate->format("Y-m-d");
        $activityDate=$activityDate->format("Y-m-d");
        Utils::logDebug("SHOWTECHNICANLOCATION: " . in_array($locationData->status, Controlador::STATUS_LOCALIZABLE) . " - " . $activityDate == $currentDate);
        return in_array($locationData->status, Controlador::STATUS_LOCALIZABLE) && $activityDate == $currentDate;
    }
    function addMessageError($msj){
        $_REQUEST[Controlador::MESSAGE_PARAM]=$msj;
    }
    function getActivityIdFromContext(){
        Utils::logDebug('INICIO getActivityIdFromContext');
        foreach($_GET as $key=>$val) {
            Utils::logDebug('Primer parametro en url: ' . $key);
            if(isset($key)){
                Utils::logDebug('Se va a desencriptar');
                Utils::logDebug($key);
                
                $activityID=$this->desencriptar_AES($key);
            }
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
        }
        catch (Exception $err ) {
            Utils::logDebug('Hubo un error al desencriptar la actividad');
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
    function isAprovisionamientoAseguramientoRecupero($activity){
        if(in_array($activity->activityType, Controlador::ASEGURAMIENTO_VALUES)){
            return Controlador::ASEGURAMIENTO;
        }
        if(in_array($activity->activityType, Controlador::APROVISIONAMIENTO_VALUES)){
            return Controlador::APROVISIONAMIENTO;
        }
        if(in_array($activity->activityType, Controlador::RECUPERO_VALUES)){
            return Controlador::RECUPERO;
        }
        return null;
    }
}
?>
<head>
</head>
<body>
</body>