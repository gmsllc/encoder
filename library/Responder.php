<?php
class Responder {

    public function __construct($provider, $config, $db, $log_file=false){
        $this->provider = $provider;
        $this->config = $config;
        $this->sql = $this->config['sql'];
        $this->db = $db;
        $this->log_file = $log_file;
    }

    public function handleResponse($xml){
        $this->logRawXml($xml);

        $status = $this->getMapping($xml, 'status');
        $media_id = $this->getMapping($xml, 'media_id');
        $stmt = $this->db->prepare($this->sql['update_status']);
        $stmt->execute(array(':status' => $status, ':media_id' => $media_id));
        $this->logResponse($status, $media_id);
    }

    protected function logRawXml($xml){
            file_put_contents('raw_xml.log', $xml, FILE_APPEND);
    }

    protected function logResponse($status, $media_id){
        if ($this->log_file) {
            $timestamp = date("Y-m-d:h-i-s");
            $message_to_log = "[$timestamp] MediaId: $media_id, Status: $status";
            file_put_contents($this->log_file, $message_to_log, FILE_APPEND);
        }
    }

    protected function getMapping($xml, $tag){
        $mapping_info = $this->config['get_mapping'][$this->provider][$tag];
        $xml =  $this->xmlToArray($xml);
        eval("\$provider_tag = \$xml->$mapping_info;");
        return $provider_tag;
    }

    public function informLloyd(){
        $to = 'lloyd@dreamstarcash.com';
        $subject = 'staging box encoder test';
        $message = 'I have own your ships now';
        mail($to, $subject, $message);
    }

    protected function xmlToArray($xml){
        return json_decode(json_encode( (array) simplexml_load_string($xml))); 
    }

}

