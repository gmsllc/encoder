<?php

$library = "/Users/lloyd/zendlibrary";
$tests_folder = dirname(__FILE__);
$current_path = get_include_path();

set_include_path(implode(':', array(
    $library,
    $tests_folder,
    $current_path
)));
