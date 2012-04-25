<?php
set_include_path(dirname(__FILE__) . '/library:'. get_include_path());
$responder = new Responder();
$xml = $_POST['xml'];
$responder->handleResponse($xml);
