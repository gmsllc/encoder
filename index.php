<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

set_include_path(dirname(__FILE__) . '/library:'. get_include_path());
set_include_path(dirname(__FILE__) . '/tests:'. get_include_path());

require_once('Responder.php');
require_once('Configuration.php');

$config = new Configuration();
$config = $config->getConfiguration();

$db_config = $config['db_settings'];
$host = $db_config['host'];
$user = $db_config['username'];
$pass = $db_config['password'];
$db   = $db_config['dbname'];

$db = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$log_file = 'live.log';
$responder = new Responder(1, $config, $db, $log_file);

$xml = $_POST['xml'];

$responder->handleResponse($xml);
$responder->informLloyd();
