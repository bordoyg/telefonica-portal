<?php

class ServiceSoap {
    private $login= 'test.oracle';
    private $passOFSC='ora.2018';
    private $company='telefonica-co2.test';
    private $host='api.etadirect.com';
    private $protocol='https';
    private $port='443';
    private $process=NULL;
    private $authString=NULL;
    
    function __construct() {
        $parameters=$GLOBALS['config'];
        $this->login=$parameters['user'];
        $this->passOFSC=$parameters['pass'];
        $this->company=$parameters['instanceID'];
        $this->host=$parameters['host'];
        $this->protocol=$parameters['protocol'];
        $this->port=$parameters['port'];   
    }
    
    function request($path, $params='' ){
        $url = $this->protocol . '://'. $this->host . ':' . $this->port . $path . "?wsdl";
        
        $currentDate=new DateTime('now');
        $currentDate=$currentDate->format(DateTime::ATOM);
        $this->authString=md5($currentDate . md5($this->passOFSC));
        
        $this->process = curl_init();
        curl_setopt($this->process, CURLOPT_HEADER, false);
        
        curl_setopt($this->process, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($this->process,CURLOPT_SSL_VERIFYPEER, false);
        
        curl_setopt($this->process, CURLOPT_RETURNTRANSFER, TRUE);
        
        curl_setopt($this->process, CURLOPT_URL, $url);
        curl_setopt($this->process, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($this->process, CURLOPT_HTTPGET, FALSE);
        curl_setopt($this->process, CURLOPT_POSTFIELDS, $params);
        
        
        $return = curl_exec($this->process);
        $httpcode = curl_getinfo($this->process, CURLINFO_HTTP_CODE);
        curl_close($this->process);
        
        $content = json_decode($return);
        
        if($httpcode!="200" && $httpcode!="204"){
            throw new Exception("El servicio SOAP" . $path . " retorno un error: " . (isset($content->detail)? $content->detail : $httpcode), $httpcode . " respuesta: " . $return . " parametros: " . $params);
        }
        
        return $content;
    }
    
    function desencriptar_AES($encrypted_data_hex, $key)
    {
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        $iv_size_hex = mcrypt_enc_get_iv_size($td)*2;
        $iv = pack("H*", substr($encrypted_data_hex, 0, $iv_size_hex));
        $encrypted_data_bin = pack("H*", substr($encrypted_data_hex, $iv_size_hex));
        mcrypt_generic_init($td, $key, $iv);
        $decrypted = mdecrypt_generic($td, $encrypted_data_bin);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $decrypted;
    }
}
?>
<head>
</head>
<body>
</body>