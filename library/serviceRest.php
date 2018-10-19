<?php

class ServiceRest {
    private $userOFSC= 'test.oracle';
    private $passOFSC='ora.2018';
    private $instanceID='telefonica-co2.test';
    private $host='api.etadirect.com';
    private $protocol='https';
    private $port='443';
    private $process=NULL;
    private $encodeCredential=NULL;
    
    function __construct() {
        $parameters=$GLOBALS['config'];
        $this->userOFSC=$parameters['user'];
        $this->passOFSC=$parameters['pass'];
        $this->instanceID=$parameters['instanceID'];
        $this->host=$parameters['host'];
        $this->protocol=$parameters['protocol'];
        $this->port=$parameters['port'];
        
        $this->encodeCredential=base64_encode($this->userOFSC . '@' . $this->instanceID . ':' . $this->passOFSC);
        load_curl();
        
    }
    
    function request($path, $method, $params='' ){
        $this->process = curl_init();
        curl_setopt($this->process, CURLOPT_HTTPHEADER, array('Accept:application/json','Authorization: Basic ' . $this->encodeCredential));
        curl_setopt($this->process, CURLOPT_HEADER, false);
        
        curl_setopt($this->process, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($this->process,CURLOPT_SSL_VERIFYPEER, false);
        
        curl_setopt($this->process, CURLOPT_RETURNTRANSFER, TRUE);
        $url = $this->protocol . '://'. $this->host . ':' . $this->port . $path . ($method==='GET'? '?' . $params:'');
        
        curl_setopt($this->process, CURLOPT_URL, $url);
        curl_setopt($this->process, CURLOPT_CUSTOMREQUEST, $method);
        if($method==='GET'){
            curl_setopt($this->process, CURLOPT_HTTPGET, TRUE);
        }else{
            curl_setopt($this->process, CURLOPT_HTTPGET, FALSE);
            curl_setopt($this->process, CURLOPT_POSTFIELDS, $params);
        }

        //Utils::logDebug("Se va a invocar al servicio: " . $url);
        //Utils::logDebug("Metodo: " . $method);
        //Utils::logDebug("Parametros: " . $params);
        $return = curl_exec($this->process);
        $httpcode = curl_getinfo($this->process, CURLINFO_HTTP_CODE);
        //Utils::logDebug("La respuesta del servicio fue: " . $httpcode);
        //Utils::logDebug("Response: " . $return);
        curl_close($this->process);

        $content = json_decode($return);
        
        if($httpcode!="200" && $httpcode!="204"){
            throw new Exception("El servicio " . $method . " " . $url . " retorno un error: " . (isset($content->detail)? $content->detail : $httpcode), $httpcode . " respuesta: " . $return . " parametros: " . $params);
        }

        return $content;
    }
}
?>
<head>
</head>
<body>
</body>