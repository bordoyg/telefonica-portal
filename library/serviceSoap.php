<?php

use RightNow\Connect\v1_3 as RNCPHP;

class ServiceSoap {
    private $login= 'test.oracle';
    private $passOFSC='ora.2018';
    private $company='telefonica-co2.test';
    private $host='api.etadirect.com';
    private $protocol='https';
    private $port='443';
    private $process=NULL;
    
    function __construct() {
        $parameters=$GLOBALS['config'];
        $this->login=$parameters['user'];
        $this->passOFSC=$parameters['pass'];
        $this->company=$parameters['instanceID'];
        $this->host=$parameters['host'];
        $this->protocol=$parameters['protocol'];
        $this->port=$parameters['port'];   
    }

    
    function request($path, $soapNamespace, $soapMethod, $params='' ){
        $url = $this->protocol . '://'. $this->host . ':' . $this->port . $path . "?wsdl";
        $xmlRequest=$this->generarXMLSoapRequest($soapNamespace, $soapMethod, $params);
        // $log = new RNCPHP\CO\LOG();
        // $log->LOG = $url;
        // $log->save();
        // $log = new RNCPHP\CO\LOG();
        // $log->LOG = $xmlRequest;
        // $log->save();
            
        $this->process = curl_init();
        curl_setopt($this->process, CURLOPT_HEADER, false);
        
        curl_setopt($this->process, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($this->process,CURLOPT_SSL_VERIFYPEER, false);
        
        curl_setopt($this->process, CURLOPT_RETURNTRANSFER, TRUE);
        
        curl_setopt($this->process, CURLOPT_URL, $url);
        curl_setopt($this->process, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($this->process, CURLOPT_HTTPGET, FALSE);
        curl_setopt($this->process, CURLOPT_POSTFIELDS, $xmlRequest);
        
        Utils::logDebug("Se va a invocar al servicio: " . $url);
        Utils::logDebug("XMLRequest: " . $xmlRequest);
        
        $return = curl_exec($this->process);

        $return =str_replace("</urn:get_capacity_response>", "\t<time_slot_info><dummy>dummytxt</dummy></time_slot_info>\n</urn:get_capacity_response>", $return);
        $return =str_replace("</activity_travel_time>", "</activity_travel_time>\n\t<capacity><dummy>dummytxt</dummy></capacity>", $return);
        
        $error = curl_error($this->process);
        // $log = new RNCPHP\CO\LOG();
        //     $log->LOG = $return;
        //     $log->save();
        //     $log = new RNCPHP\CO\LOG();
        //     $log->LOG = $error;
        //     $log->save();
        $httpcode = curl_getinfo($this->process, CURLINFO_HTTP_CODE);
        Utils::logDebug("La respuesta del servicio fue: " . $httpcode);
        Utils::logDebug("XMLResponse: " . $return);
        
        curl_close($this->process);
        $content=$this->xmlToArray($return);
        if($httpcode!="200" && $httpcode!="204"){
            throw new Exception("El servicio SOAP" . $url . " retorno un error: " . $httpcode . " response: " . $return . " request: " . $xmlRequest);
        }
        
        return $content;
    }
    
    function generarXMLSoapRequest($soapNamespace, $soapMethod, $params){
        $currentDate=new DateTime('now');
        $currentDate=$currentDate->format(DateTime::ATOM);
        $authString=md5($currentDate . md5($this->passOFSC));
        
        $xml='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="' . $soapNamespace . '">';
        $xml=$xml . '   <soapenv:Header/><soapenv:Body><urn:' . $soapMethod . '>';
        $xml=$xml . '<user><now>' . $currentDate . '</now>';
        $xml=$xml . '<login>' . $this->login . '</login>';
        $xml=$xml . '<company>' . $this->company . '</company>';
        $xml=$xml . '<auth_string>' . $authString . '</auth_string>';
        $xml=$xml . '</user>';
        foreach ($params as $item) {
            foreach ($item as $c => $v) {
                if(isset($v)){
                    if(is_array($v)){
                        $xml=$xml . '<' . $c .'>';
                        foreach ($v as $clave => $valor) {
                            //Isset valor pablo
                            if(isset($valor)){
                                $xml=$xml . '<' . $clave .'>'. $valor . '</' . $clave . '>';
                            }
                            else{
                                $xml=$xml.'<'. $clave . '/>';
                            }
                        }
                        $xml=$xml . '</' . $c . '>';
                    }else{
                        $xml=$xml . '<' . $c .'>'. $v . '</' . $c . '>';
                    }
                }
            }
        }
        
        $xml=$xml . ' </urn:' . $soapMethod . '></soapenv:Body></soapenv:Envelope>';
        return $xml;
        
    }
    /**
     * Convert XML to an Array
     *
     * @param string  $XML
     * @return array
     */
    function xmlToArray($xmlStr){
        $xml_parser = xml_parser_create();
        xml_parse_into_struct($xml_parser, $xmlStr, $vals);
        xml_parser_free($xml_parser);
        // wyznaczamy tablice z powtarzajacymi sie tagami na tym samym poziomie
        $_tmp='';
        foreach ($vals as $xml_elem) {
            $x_tag=$xml_elem['tag'];
            $x_level=$xml_elem['level'];
            $x_type=$xml_elem['type'];
            if ($x_level!=1 && $x_type == 'close') {
                if (isset($multi_key[$x_tag][$x_level]))
                    $multi_key[$x_tag][$x_level]=1;
                    else
                        $multi_key[$x_tag][$x_level]=0;
            }
            if ($x_level!=1 && $x_type == 'complete') {
                if ($_tmp==$x_tag)
                    $multi_key[$x_tag][$x_level]=1;
                    $_tmp=$x_tag;
            }
        }
        // jedziemy po tablicy
        foreach ($vals as $xml_elem) {
            $x_tag=$xml_elem['tag'];
            $x_level=$xml_elem['level'];
            $x_type=$xml_elem['type'];
            if ($x_type == 'open')
                $level[$x_level] = $x_tag;
                $start_level = 1;
                $php_stmt = '$xml_array';
                if ($x_type=='close' && $x_level!=1)
                    $multi_key[$x_tag][$x_level]++;
                    while ($start_level < $x_level) {
                        $php_stmt .= '[$level['.$start_level.']]';
                        if (isset($multi_key[$level[$start_level]][$start_level]) && $multi_key[$level[$start_level]][$start_level])
                            $php_stmt .= '['.($multi_key[$level[$start_level]][$start_level]-1).']';
                            $start_level++;
                    }
                    $add='';
                    if (isset($multi_key[$x_tag][$x_level]) && $multi_key[$x_tag][$x_level] && ($x_type=='open' || $x_type=='complete')) {
                        if (!isset($multi_key2[$x_tag][$x_level]))
                            $multi_key2[$x_tag][$x_level]=0;
                            else
                                $multi_key2[$x_tag][$x_level]++;
                                $add='['.$multi_key2[$x_tag][$x_level].']';
                    }
                    if (isset($xml_elem['value']) && trim($xml_elem['value'])!='' && !array_key_exists('attributes', $xml_elem)) {
                        if ($x_type == 'open')
                            $php_stmt_main=$php_stmt.'[$x_type]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
                            else
                                $php_stmt_main=$php_stmt.'[$x_tag]'.$add.' = $xml_elem[\'value\'];';
                                eval($php_stmt_main);
                    }
                    if (array_key_exists('attributes', $xml_elem)) {
                        if (isset($xml_elem['value'])) {
                            $php_stmt_main=$php_stmt.'[$x_tag]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
                            eval($php_stmt_main);
                        }
                        foreach ($xml_elem['attributes'] as $key=>$value) {
                            $php_stmt_att=$php_stmt.'[$x_tag]'.$add.'[$key] = $value;';
                            eval($php_stmt_att);
                        }
                    }
        }
        return $xml_array;
    }
}
?>
<head>
</head>
<body>
</body>