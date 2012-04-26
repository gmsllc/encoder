<?php
require_once "Zend/Http/Client.php";
require_once "Zend/Db.php";
require_once "dataConverter.php";

class Encoder {

    protected $db;
    protected $config;
    protected $http_client;

    public function __construct($config, $http_client, $db){
        $this->config = $config;
        $this->setupDbConnection($db);
        $this->setUpHttpClient();
    }

    protected function setupDbConnection($db=false){
        if ($db) {
            $this->db = $db;
        } else {
            $params = array(
                'host' => '127.0.0.1',
                'username' => 'root',
                'password' => 'root',
                'dbname' => 'tubeadmin',
            );
            $this->db = Zend_Db::factory('Pdo_Mysql', $params);
        }
    }

    protected function setUpHttpClient(){
        $url = $this->config['xml_data']['api_url'];
        $settings = $this->config['client_settings'];
        $this->http_client = new Zend_Http_Client($url, $settings);
        $this->http_client->setMethod(Zend_HTTP_Client::POST);
    }

    public function getConfiguration(){
        return $this->config;
    }

    public function addToQueue($media_id, $source, $destination, $priority){
        $sql = $this->config['sql']['add_to_queue'];
        $stmt = $this->db->prepare($sql); 
        return $stmt->execute(array($media_id, $source, $destination, $priority, 'pending'));
    }

    public function requestEncoding($source, $destination, $priority){
        $data = $this->config['xml_data'];
        $xml = $this->buildXml($data, $source, $destination);
        $this->http_client->setParameterPost('xml', $xml);
        $out = $this->http_client->request();
        $out_text = $out->getBody();
        return $this->saveRequestInDb($out_text, $source, $destination, $priority);
    }

    protected function saveRequestInDb($response, $source, $destination, $priority){
        $xml = new SimpleXmlElement($response);
        $media_id = (int)$xml->MediaID;
        $this->addToQueue($media_id, $source, $destination, $priority);
        return $media_id;
    }

    public function buildXml($data, $source, $destination){
        $data['source'] = $source;
        $data['format']['destination'] = $destination;
        $query_xml = new SimpleXMLElement("<query/>");
        $xml = DataConverter::arrayToQueryXml($data, $query_xml);
        return $xml->asXml();
    }
}

