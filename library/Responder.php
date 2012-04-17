<?php
class Responder {

    public function __construct($config, $db){
        $this->config = $config;
        $this->sql = $this->config['sql'];
        $this->db = $db;
    }

    public function handleResponse($xml){
        $xml = new SimpleXmlElement($xml);
        $stmt = $this->db->prepare($this->sql['update_status']);
        $status = (string)$xml->status;
        $media_id = (int)$xml->mediaId;
        $stmt->execute(array(':status' => $status, ':media_id' => $media_id));
    }
}

