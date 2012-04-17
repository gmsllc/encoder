<?php
require_once "spyc.php";

class Configuration {
    private $config;

    public function __construct($file=false){
        if (!$file) {
            $file = dirname(dirname(__FILE__)).'/config/config.yml';
        }
        $this->config = spyc_load_file($file);
    }

    public function getConfiguration(){
        return $this->config;
    }

}

