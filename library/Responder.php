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
        $xml = new SimpleXmlElement($xml);

        $status = $this->getMapping('status', (string)$xml->status);
        $media_id = $this->getMapping('media_id', (int)$xml->mediaid);

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

    protected function getMapping($provider_tag, $provider_val){
        return $provider_val;
        $stmt = $this->db->prepare($this->sql['get_mapping']);
        $stmt->execute(array(
            ':provider_id'  => $this->provider,
            ':provider_tag' => $provider_tag,
            ':provider_val' => $provider_val
        ));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $out = $result['mapping_val'];
        return $out ? $out : $provider_val;
    }

    public function informLloyd(){
        $to = 'lloyd@dreamstarcash.com';
        $subject = 'staging box encoder test';
        $message = 'I have own your ships now';
        mail($to, $subject, $message);
    }

}

