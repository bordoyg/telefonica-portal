<?php
include('service.php');

class Controlador {
    const MESSAGE_PARAM = 'errorMessage';
    const MESSAGES_URL = 'messages.php';
	const ACTIVITY_PARAM = 'activity';
	const LOCATION_TECHNICAN_PARAM='locationTechnican';
	const LOCATION_CUSTOMER_PARAM='locationCustomer';
	const LOCATION_TECHNICAN="technicanLocation";
	const SCHUEDULE_DATE_PARAM='timeSlot';
	const STATUS_LOCALIZABLE='onTheWay';
	const STATUS_PENDING="pending";
    private $service=NULL;
 
    function __construct() {
        if(!isset($GLOBALS['service'])){
            $GLOBALS['service']=new Service();
        }
        $this->service = $GLOBALS['service'];
    }

    function findTechnicanLocation($activity){
        //Obtenemos la posicion del domicilio
        if(isset($activity->latitude) && isset($activity->longitude)){
            $_REQUEST[Controlador::LOCATION_CUSTOMER_PARAM]='{lat:'.$activity->latitude.',lng:'.$activity->longitude.'}';
        }
        
        //Obtenemos la posicion del tecnico
         $locationData=$this->service->request('/rest/ofscCore/v1/whereIsMyTech', 'GET', 'activityId=' . $activity->activityId . '&includeAvatarImageData=true');
         $_REQUEST[Controlador::LOCATION_TECHNICAN]=$locationData;
         
         if($locationData->status==Controlador::STATUS_LOCALIZABLE){
             if(isset($locationData->coordinates) && isset($locationData->coordinates->latitude) && isset($locationData->coordinates->longitude)){
                 $lat=$locationData->coordinates->latitude;
                 $lng=$locationData->coordinates->longitude;
                 $_REQUEST[Controlador::LOCATION_TECHNICAN_PARAM]='{lat:' . $lat . ', lng:' . $lng . '}';
             }else{
                 $this->addMessageError("No se puedo establecer la ubicacion del t&eacute;cnico, intenta mas tarde");
             }
         }else{
             $this->addMessageError("No se puedo establecer la ubicacion del t&eacute;cnico, intenta mas tarde");
         }
         
    }
    function findAvailability() {
        $activityID=$_COOKIE[Controlador::ACTIVITY_PARAM];
        $activity=$this->findActivityData($activityID);
        
        //Genero los dias para solicitar disponibilidad
        $queryString='dates=';
        $date = date("Y-m-d");
        $queryString=$queryString . $date;
        
        for($i=0; $i<38; $i++){
            $newDate = strtotime($date."+ 1 days");
            $date = date("Y-m-d",$newDate);
            
            $queryString=$queryString . ',' . $date;
        }
        
        $queryString=$queryString . '&activityType=' . $activity->activityType;
        $queryString=$queryString . '&XA_WORK_ZONE_KEY=' . $activity->XA_WORK_ZONE_KEY;
        $queryString=$queryString . '&XA_WORK_TYPE=' . $activity->XA_WORK_TYPE;
        $queryString=$queryString . '&XA_ACCESS_TECHNOLOGY=' . $activity->XA_ACCESS_TECHNOLOGY;
        $queryString=$queryString . '&XA_QUADRANT=' . $activity->XA_QUADRANT;
        $queryString=$queryString . '&XA_NUMBER_DECODERS=1';//No viene en la actividad, funciona con algun nro natural
        $queryString=$queryString . '&determineAreaByWorkZone=true';
        
        
        $activityBookingOptions=$this->service->request('/rest/ofscCapacity/v1/activityBookingOptions', 'GET', $queryString);
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
            
            if(count($timeSlots)>0){
               $date=new stdClass();
               $d=new DateTime();
               $d->createFromFormat("Y-m-d", $activityBookingOptions->dates[$i]->date);
               $date->date=d;
               $date->timeSlots=$timeSlots;
               array_push($dates, $date);
            }
        }
        
        return $dates;
    }
    function findActivityData($activityID){
        try {
            //Buscar los datos de la actividad
            $out = $this->service->request('/rest/ofscCore/v1/activities/' . $activityID, 'GET');

            return $out;
        } catch (Exception $e) {
            $this->addMessageError($e->getMessage());
            return null;
        }
    }
    function createCalendar(){
        $calendar=array();
        $firstDay=new DateTime();
        $firstDay->setDate(date("Y"), date("m"), 1);
        $dayWeekFirstDay=$firstDay->format("w");
        $firstDayCalendar=$firstDay->sub(new DateInterval("P" . $dayWeekFirstDay . "D"));
        
        $availability=$this->findAvailability();
        
        
        for($i=0;$i<6;$i++){
            $calendar[$i]=array();
            for($j=0;$j<7;$j++){
                $dayOfMonth=new DateTime();
                $dayOfMonth->setTimestamp($firstDayCalendar->getTimestamp());
                $dateItem=new stdClass();
                $dateItem->dayOfMonth=$dayOfMonth;
                
                
                for($k=0; $k<count($availability); $k++){
                    if($availability[$k]->date == $dateItem->$dayOfMonth){
                        $dateItem->timeSlots=$availability[$k]->timeSlots;
                    }
                }
                $calendar[$i][$j]=$dateItem;
                
                $firstDayCalendar->add(new DateInterval("P1D"));
            }
        }
        
        
        
        return $calendar;
    }
    
    function excecuteCancelConfirm(){
        try {
            $activityID=$_COOKIE[Controlador::ACTIVITY_PARAM];
            $activity=$this->findActivityData($activityID);
            $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId . '/custom-actions/cancel', 'POST');
            
            return Dispatcher::CANCEL_CONFIRM_URL;
        } catch (Exception $e) {
            if($e->getCode()=="409"){
                $this->addMessageError('No se pudo cancelar la cita contactese con atenci&oacute;n al cliente');
            }else{
                $this->addMessageError('Hubo un error al cancelar la cita: ' . $e->getMessage());
            }
            return Dispatcher::CUSTOMER_DATA_URL;
        }
    }
    function excecuteScheduleCalendar(){

        return Dispatcher::SCHEDULE_DATE_URL;
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
            $params=array("timeSlot"=>$scheduleTimeslot);
            $params=json_encode($params);
            //Se actualiza el timeslot
            $this->service->request('/rest/ofscCore/v1/activities/' . $activity->activityId, 'PATCH', $params);
            return Dispatcher::SCHEDULE_DATE_CONFIRM_URL;
        }catch(Exception $e){
            $this->addMessageError('Hubo un error al reagendar la cita: ' . $e->getMessage());
            return Dispatcher::SCHEDULE_DATE_URL;
        }        
    }

    function excecuteConfirmConfirm(){
        try{
            //$this->service->request('/rest/ofscCore/v1/activities/ rest method to confirm activity', 'POST');
            return Dispatcher::CONFIRM_CONFIRM_URL;
        }catch(Exception $e){
            $this->addMessageError('Hubo un error al confirmar la cita: ' . $e->getMessage());
            return Dispatcher::CUSTOMER_DATA_URL;
        }

    }
    function excecuteLocation(){
        $activityID=$this->getActivityIdFromContext();
        $activity=$this->findActivityData($activityID);
        $this->findTechnicanLocation($activity);
        return Dispatcher::LOCATION_URL;
    }
    function showConfirm(){
        return true;
        $activityID=$this->getActivityIdFromContext();
        $activity=$this->findActivityData($activityID);
        $activityDate = DateTime::createFromFormat('Y-m-d', $activity->date, new DateTimeZone($activity->timeZone));
        $currentDate=DateTime::createFromFormat('Y-m-d', date('Y-m-d'), new DateTimeZone($activity->timeZone));
        return $activityDate > $currentDate;
    }
    function showCancel(){
        //return true;
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
        return true;
        $activityID=$this->getActivityIdFromContext();
        $activity=$this->findActivityData($activityID);
        $activityDate = DateTime::createFromFormat('Y-m-d', $activity->date, new DateTimeZone($activity->timeZone));
        $currentDate=DateTime::createFromFormat('Y-m-d', date('Y-m-d'), new DateTimeZone($activity->timeZone));
        return $activity->status == Controlador::STATUS_LOCALIZABLE && $activityDate == $currentDate;
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
}
