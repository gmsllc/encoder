<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

require_once dirname(__FILE__) . "/testBootstrap.php";
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
        $this->log_file = 'test.log';
        $this->responder = new Responder(
            1, $this->config, $this->db, $this->log_file);

        $this->createTablesAndMockData();
    }

    protected function createTablesAndMockData(){
        $this->createTableQueue();
        $this->createTableMapping();
    }

    protected function createTableQueue(){
        $this->db->exec($this->sql['create_table_queue']);
        $stmt = $this->db->prepare($this->sql['add_to_queue']);
        $stmt->execute(array(321, 'src', 'dst', 1, 'queued'));
    }

    protected function createTableMapping(){
        $this->db->exec($this->sql['create_table_response_mapper']);
        $this->db->exec($this->sql['add_response_maps']);
    }

    public function tearDown(){
        unset($this->responder);
        $this->db->exec($this->sql['clean_out_queue']);
    }

    public function testStoresInfoInDatabaseCorrectly(){
        $xml = $this->config['good_xml_response'];
        list($media_id, $status) = $this->responder->handleResponse($xml);
        $stmt = $this->db->prepare('select media_id, status from queue');
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $actual = $result['media_id'];
        $expected = 321;
        $this->assertEquals($actual, $expected);
    }

    public function testResponseWhenThereIsAnError(){
        $xml = $this->config['bad_xml_response'];
        $this->responder->handleResponse($xml);
        $stmt = $this->db->prepare('select status from queue');
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $actual = $result['status'];
        $expected = 'Error';
        $this->assertEquals($actual, $expected);
    }

    public function testLoggingWorks(){
        $this->cleanTestLogFile();
        $this->testStoresInfoInDatabaseCorrectly();
        $expected = "MediaId: 321, Status: error";
        $actual = $this->getLogLine();
        $this->assertEquals($expected, $actual);  
        $this->cleanTestLogFile();
    }

    protected function cleanTestLogFile(){
        file_put_contents($this->log_file, '');
    }

    protected function getLogLine(){
        $line = file_get_contents($this->log_file);
        $parts = explode(']', $line);
        return trim($parts[1]);
    }
}
