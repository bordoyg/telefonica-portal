<?php
include('controlador.php');

class Dispatcher {
    const INDEX_URL='index.php';
    const CONFIRM_CONFIRM_URL = 'confirmConfirm.php' ;
    const CANCEL_CONFIRM_URL = 'cancelConfirm.php';
    const SCHEDULE_DATE_URL = 'scheduleDate.php';
    const SCHEDULE_DATE_CONFIRM_URL = 'scheduleDateConfirm.php';
    const SCHEDULE_ANY_DATE_URL = 'scheduleAnyDate.php';
    const LOCATION_URL = 'location.php';
    const CUSTOMER_DATA_URL = 'customerData.php';
    const MENU_URL = 'menu.php';
    const ERROR_URL = 'error.php';
    
    const CONFIRM_LABEL = 'confirmar';
    const CONFIRM_CONFIRM_LABEL = 'confirmarConfirm';
    const SCHEDULE_DATE_CONFIRM_LABEL = 'confirmarSchedule';
    const SCHEDULE_MORE_DATES = 'masFechasSchedule';
    const SCHEDULE_NO_MORE_DATES = 'noMasFechasSchedule';
    const SCHEDULE_ANY_DATES = 'ningunaFechaSchedule';
    const CANCEL_LABEL = 'cancelar';
    const CANCEL_CONFIRM_LABEL = 'cancelConfirm';
    const SCHEDULE_DATE_LABEL = 'reagendar';
    const LOCATION_LABEL = 'map';
    
    const OPTION_PARAM = 'opcion';

    private $controlador=NULL;
    
    function __construct() {
        try{
            if(!isset( $GLOBALS['Controlador'])){
                $GLOBALS['Controlador']=new Controlador();
            }
            $this->controlador = $GLOBALS['Controlador'];
        } catch (Exception $e) {
            $this->controlador->addMessageError('Hubo un error inesperado al inicializar la aplicacion: ' . $e->getMessage());
            return Dispatcher::MENU_URL;
        }
    }
    function resolveAction() {
        $action = isset($_REQUEST[Dispatcher::OPTION_PARAM]) ? $_REQUEST[Dispatcher::OPTION_PARAM] : null ;
        try {
            $activityID=isset($_GET[Controlador::ACTIVITY_PARAM]) ? $_GET[Controlador::ACTIVITY_PARAM] : null ;
            if (!isset($activityID)){
                $activityID=$_COOKIE[Controlador::ACTIVITY_PARAM];
            }
            if (!isset($activityID)){
                $this->controlador->addMessageError('La orden no existe');
                return Dispatcher::ERROR_URL;
            }else{
                $activity=$this->getControlador()->findActivityData($activityID);
                if (isset($activity)){
                    setcookie(Controlador::ACTIVITY_PARAM, $activityID);
                }else{
                    //Expiramos la cookie
                    setcookie(Controlador::ACTIVITY_PARAM, $activityID,time()-3600);
                    $this->controlador->addMessageError('La orden no existe');
                    return Dispatcher::ERROR_URL;
                }
            }
            
                   
            if (strcmp(Dispatcher::CONFIRM_LABEL, $action) === 0){
                return $this->controlador->excecuteConfirmConfirm();
            }
            if (strcmp(Dispatcher::SCHEDULE_DATE_LABEL, $action) === 0){
                return $this->controlador->excecuteScheduleCalendar();
            }
            if (strcmp(Dispatcher::SCHEDULE_MORE_DATES, $action) === 0){
                return $this->controlador->excecuteScheduleCalendar();
            }
            if (strcmp(Dispatcher::SCHEDULE_ANY_DATES, $action) === 0){
                return Dispatcher::MENU_URL;
            }
            if (strcmp(Dispatcher::SCHEDULE_DATE_CONFIRM_LABEL, $action) === 0){
                return $this->controlador->excecuteScheduleConfirm();
            }
            if (strcmp(Dispatcher::CANCEL_LABEL, $action) === 0){
                return $this->controlador->excecuteCancelConfirm();
            }
            if (strcmp(Dispatcher::LOCATION_LABEL, $action) === 0){
                return $this->controlador->excecuteLocation();
            }else{
                return Dispatcher::MENU_URL;
            }
        } catch (Exception $e) {
            $this->controlador->addMessageError('Hubo un error inesperado: ' . $e->getMessage());
            return Dispatcher::ERROR_URL;
        }
    }

    function getControlador(){
        return $this->controlador;
    }
}