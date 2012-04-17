<?php
$responder = new Responder();
$xml = $_POST['xml'];
$responder->handleResponse($xml);
