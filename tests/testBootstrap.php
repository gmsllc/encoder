<?php

$zend = dirname(__FILE__)."/Zend";
$tests = dirname(__FILE__);
$path = get_include_path();
set_include_path(implode(':', array($zend, $tests, $path)));
