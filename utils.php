<?php 
class Utils{
    static function logDebug($msj, Exception $e=null){
        if(strcmp($GLOBALS['config']['logDebug'],"1")!=0){
            return;
        }
        if($e!=null){
            $msj=$msj . "\n EXCEPTION\ncause: " . $e->getCode() . " " . $e->getMessage() . "\n";
            $msj=$msj . "in: " . $e->getFile() . ":" . $e->getLine() . "\n" . $e->getTraceAsString();
        }
        
        echo '<div key="logDebug" time="' . time() . '" style="display:none;"><pre>' . $msj . '</pre></div>';
    }
}

?>