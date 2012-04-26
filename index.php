<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

set_include_path(dirname(__FILE__) . '/library:'. get_include_path());
set_include_path(dirname(__FILE__) . '/tests:'. get_include_path());

require_once('Responder.php');
require_once('Configuration.php');

$config = new Configuration();
$config = $config->getConfiguration();

$db = new PDO('sqlite:test.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = $config['sql'];
$db->exec($sql['create_table_queue']);
$stmt = $db->prepare($sql['add_to_queue']);

$log_file = 'test.log';
$responder = new Responder($config, $db, $log_file);

$xml = $_POST['xml'];

$responder->handleResponse($xml);
$responder->informLloyd();
