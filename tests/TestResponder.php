<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
require_once dirname(__FILE__) . "/TestBootstrap.php";
require_once "Zend/Test/PHPUnit/ControllerTestCase.php"; 
require_once dirname(dirname(__FILE__))."/library/Responder.php";
require_once dirname(dirname(__FILE__))."/library/Configuration.php";

class TestResponder extends Zend_Test_PHPUnit_ControllerTestCase {

    public function setUp(){
        $config = new Configuration();
        $this->config = $config->getConfiguration();
        $this->sql = $this->config['sql'];
        $this->db = new PDO('sqlite:test.sqlite');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->responder = new Responder($this->config, $this->db);
        $this->createTableAndMockData();
    }

    protected function createTableAndMockData(){
        $this->db->exec($this->sql['create_table_queue']);
        $stmt = $this->db->prepare($this->sql['add_to_queue']);
        $stmt->execute(array(321, 'src', 'dst', 1, 'queued'));
    }

    public function tearDown(){
        unset($this->responder);
        $this->db->exec($this->sql['clean_out_queue']);
    }

<<<<<<< HEAD
    public function testStoresGoodInfoCorrectly(){
        $xml = $this->config['good_xml_response'];
        list($media_id, $status) = $this->responder->handleResponse($xml);
        $stmt = $this->db->prepare('select media_id, status from queue');
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
=======
    public function testStoresInfoInDatabaseCorrectly(){
        $xml = $this->config['good_xml_response'];
        list($media_id, $status) = $this->responder->handleResponse($xml);
        echo 'MEDIA ID -> ' . $media_id . ' STATUS -> ' . $status . ' ... ';
        $stmt = $this->db->prepare('select media_id, status from queue');
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $result['status'];
>>>>>>> 2d2fb0c4f3940fbaa9f8d0d84fd68cd092702bcf
        $actual = $result['media_id'];
        $expected = 321;
        $this->assertEquals($actual, $expected);
    }

<<<<<<< HEAD
    public function testStoresErrorInfoCorrectly(){
=======
    public function testResponseWhenThereIsAnError(){
>>>>>>> 2d2fb0c4f3940fbaa9f8d0d84fd68cd092702bcf
        $xml = $this->config['bad_xml_response'];
        $this->responder->handleResponse($xml);
        $stmt = $this->db->prepare('select status from queue');
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $actual = $result['status'];
        $expected = 'Error';
        $this->assertEquals($actual, $expected);
    }

}
