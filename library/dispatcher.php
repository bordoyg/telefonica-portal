<?php
require_once(APPPATH . 'widgets/custom/library/controlador.php');
use RightNow\Connect\v1_3 as RNCPHP;

class Dispatcher {
    const INDEX_URL='index.php';
    const CONFIRM_OK_URL = 'okConfirm.php' ;
    const CANCEL_OK_URL = 'okCancel.php' ;
    const SCHEDULE_DATE_URL = 'scheduleDate.php';
    const SCHEDULE_OK_URL = 'okSchedule.php';
    const SCHEDULE_ANY_DATE_URL = 'scheduleAnyDate.php';
    const LOCATION_URL = 'location.php';
    const CUSTOMER_DATA_URL = 'customerData.php';
    const MENU_URL = 'menu.php';
    const ERROR_URL = 'error.php';
    const MESSAGES_URL = 'messages.php';
    const ERROR_SCHEDULE_URL = 'errorSchedule.php';
    const CANCEL_MOTIVO_URL='motivoCancelacion.php';
    
    const CONFIRMAR_LABEL = 'confirmar';
    const CONFIRM_CONFIRM_LABEL = 'confirmarConfirm';
    const SCHEDULE_DATE_CONFIRM_LABEL = 'confirmarSchedule';
    const SCHEDULE_MORE_DATES = 'masFechasSchedule';
    const SCHEDULE_NO_MORE_DATES = 'noMasFechasSchedule';
    const CANCELAR_LABEL = 'cancelar';
    const CANCEL_CONFIRM_LABEL = 'cancelConfirm';
    const CANCEL_FROM_CALENDAR_LABEL='cancelFromCalendarAproRec';
    const CANCEL_FROM_CALENDAR_ASEGURAMIENTO_LABEL='cancelFromCalendarAseg';
    const CANCEL_FROM_MENU_ASEGURAMIENTO_LABEL='cancelFromMenuAseg';
    const SCHEDULE_DATE_LABEL = 'reagendar';
    const UBICACION_LABEL = 'map';
    const CANCEL_MOTIVO_LABEL='motivoCancelacion';

    
    const OPTION_PARAM = 'opcion';
    const MOTIVO_CANCELACION_PARAM = 'motivoCancelacionParam';
    
    const NO_VOLVER = 'novolver';

    private $controlador=NULL;
    
    function __construct() {
        try{
            $config=parse_ini_file (APPPATH. '/models/custom/etb/conf/config.ini', false);
            $GLOBALS['config']=$config;
            foreach($GLOBALS['config'] as $key=>$val){
                try{
                    $GLOBALS['config'][$key] = RNCPHP\MessageBase::fetch( $val )->Value;
                }catch(\Exception $e){
                    echo '<div key="logDebug" time="' . time() . '" style="display:none;"><pre>Hubo un error al levantar la configuracion ' . $key . ' </pre></div>';
                }
                
            }
            if(!isset( $GLOBALS['Controlador'])){
                $GLOBALS['Controlador']=new Controlador();
            }
            $this->controlador = $GLOBALS['Controlador'];
        } catch (Exception $e) {
            $this->controlador->addMessageError('Hubo un error inesperado al inicializar la aplicacion');
            Utils::logDebug('Hubo un error inesperado al inicializar la aplicacion', $e);
            return Dispatcher::MENU_URL;
        }
    }
    function resolveAction() {
        try {
            $action = isset($_REQUEST[Dispatcher::OPTION_PARAM]) ? $_REQUEST[Dispatcher::OPTION_PARAM] : null ;
            
            if (strcmp(Dispatcher::CONFIRMAR_LABEL, $action) === 0){
                return $this->controlador->excecuteConfirmConfirm();
            }
            if (strcmp(Dispatcher::SCHEDULE_DATE_LABEL, $action) === 0){
                return $this->controlador->excecuteScheduleCalendar();
            }
            if (strcmp(Dispatcher::SCHEDULE_MORE_DATES, $action) === 0){
                return $this->controlador->excecuteScheduleCalendar();
            }
            if (strcmp(Dispatcher::SCHEDULE_DATE_CONFIRM_LABEL, $action) === 0){
                return $this->controlador->excecuteScheduleConfirm();
            }
            if (strcmp(Dispatcher::CANCELAR_LABEL, $action) === 0){
                return $this->controlador->excecuteCancelConfirm();
            }
            if (strcmp(Dispatcher::CANCEL_FROM_MENU_ASEGURAMIENTO_LABEL, $action) === 0){
                return $this->controlador->excecuteCancelAseguramientoFromMenuConfirm();
            }
            if (strcmp(Dispatcher::CANCEL_FROM_CALENDAR_ASEGURAMIENTO_LABEL, $action) === 0){
                return $this->controlador->excecuteCancelAseguramientoFromCalendarConfirm();
            }
            if (strcmp(Dispatcher::CANCEL_FROM_CALENDAR_LABEL, $action) === 0){
                return $this->controlador->excecuteCancelFromCalendarConfirm();
            }
            if(strcmp(Dispatcher::CANCEL_MOTIVO_LABEL, $action) === 0){
                return Dispatcher::CANCEL_MOTIVO_URL;
            }
            if (strcmp(Dispatcher::UBICACION_LABEL, $action) === 0){
                return $this->controlador->excecuteLocation();
            }else{
                return $this->controlador->excecuteMenu();
            }
        } catch (Exception $e) {
            $this->controlador->addMessageError('Hubo un error inesperado: ' . $e->getMessage());
            Utils::logDebug('Hubo un error inesperado', $e);
            return Dispatcher::MESSAGES_URL;
        }
    }

    function getControlador(){
        return $this->controlador;
    }
}