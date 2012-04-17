<?php
error_reporting(E_ALL);

require_once dirname(__FILE__) . "/TestBootstrap.php";
require_once "Zend/Test/PHPUnit/ControllerTestCase.php"; 
require_once dirname(dirname(__FILE__))."/library/Encoder.php";
require_once dirname(dirname(__FILE__))."/library/Configuration.php";

class TestEncoderApi extends Zend_Test_PHPUnit_ControllerTestCase {
    protected $encoder;
    protected $source = 'fake_source';
    protected $destination = 'fake_destination';

    public function setUp(){
        $this->db = new PDO('sqlite:test.sqlite');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $config_object = new Configuration();
<<<<<<< HEAD
        $config = $config_object->getConfiguration();
        $url = $config['xml_data']['api_url'];
        $http_client = new Zend_Http_Client($url, $settings);
        $this->encoder = new Encoder($http_client, $config_object, $this->db
        );
=======
        $this->encoder = new Encoder($config_object, $this->db);
>>>>>>> 2d2fb0c4f3940fbaa9f8d0d84fd68cd092702bcf
        $this->config = $config_object->getConfiguration();
        $this->sql = $this->config['sql'];
        $this->db->exec($this->sql['create_table_queue']);
    }

    public function tearDown(){
        unset($this->encoder);
    }

    public function testBuildXml(){
        $data = $this->dummyData();
        $xml_real = $this->encoder->buildXml($data, $this->source, $this->destination);
        $xml_fake = $this->dummyXml();
        $this->assertContains($xml_fake, $xml_real);
    }

    public function testMakeApiRequest(){
        $config = $this->encoder->getConfiguration();
        $source_video = $config['xml_data']['source']; 
        $destination  = $config['xml_data']['destination'];
        $priority = 1;
        $expected = trim(self::CORRECT_XML_RESPONSE);
        $response = $this->encoder->requestEncoding($source_video, $destination, $priority);
        $this->assertGreaterThan(1, $response);
    }

    protected function dummyData(){
        return array(
            'foo' => 'A',
            'bar' => 'B',
            'source' => $this->source,
            'destination' => $this->destination,
            'more' => array('fux' => 'C', 'sum' => 'D')
        );
    }

    protected function dummyXml(){
        return "<query><foo>A</foo><bar>B</bar><source>{$this->source}</source><destination>{$this->destination}</destination><more><fux>C</fux><sum>D</sum></more></query>";
    }

    const CORRECT_XML_RESPONSE =<<<CORRECT
    <response><message>Added</message>
CORRECT;

}
