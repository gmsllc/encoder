<?php
class Responder {

    public function __construct($config, $db, $log_file=false){
        $this->config = $config;
        $this->sql = $this->config['sql'];
        $this->db = $db;
        $this->log_file = $log_file;
    }

    public function handleResponse($xml){
        $xml = new SimpleXmlElement($xml);
        $stmt = $this->db->prepare($this->sql['update_status']);
        $status = (string)$xml->status;
        $media_id = (int)$xml->mediaId;
        $this->logResponse($status, $media_id);
        $stmt->execute(array(':status' => $status, ':media_id' => $media_id));
    }

    protected function logResponse($status, $media_id){
        if ($this->log_file) {
            $timestamp = date("Y-m-d:h-i-s");
            $message_to_log = "[$timestamp] MediaId: $media_id, Status: $status";
            file_put_contents($this->log_file, $message_to_log, FILE_APPEND);
        }

    }

}

